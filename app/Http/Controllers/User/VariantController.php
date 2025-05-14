<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Language;
use App\Models\User\UserItemCategory;
use App\Models\User\UserItemSubCategory;
use App\Models\Variant;
use App\Models\VariantContent;
use App\Models\VariantOption;
use App\Models\VariantOptionContent;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Session;

class VariantController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $language = Language::where([['code', $request->language], ['user_id', $user_id]])->first();
        $data['languages'] = Language::where('user_id', $user_id)->get();
        $data['variants'] = VariantContent::where([['user_id', $user_id], ['language_id', $language->id]])->orderBy('created_at', 'DESC')->get();

        return view('user.item.variant.index', $data);
    }

    public function create()
    {
        return view('user.item.variant.create');
    }

    public function get_subcategory(Request $request)
    {
        $subcategories = UserItemSubCategory::where('category_id', $request->category_id)->get();
        return $subcategories;
    }

    public function store(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $user_languages = Language::where('user_id', $user_id)->get();
        $rules = [
            'category_id' => 'required',
        ];

        foreach ($user_languages as $user_language) {
            $code = $user_language->code;
            $rules["variant_names.{$code}.*"] = 'required';
            $messages["variant_names.{$code}.*.required"] = __('The variant name is required for') . " {$user_language->name} " . __('language');

            $rules["option_names.{$code}.*.*"] = 'required';
            $messages["option_names.{$code}.*.*.required"] = __('The option name is required for') . " {$user_language->name} " . __('language');
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        if (is_null($request->option_names)) {
            session()->flash('warning', 'You need to add at least one option.');
            return "success";
        }

        // Step 2: Save the Variant
        $variant = new Variant();
        $variant->user_id = $user_id;
        $variant->save();

        // Step 3: Save Variant Contents
        $d_category = UserItemCategory::where('id', $request->category_id)->first();
        $d_subcategory = UserItemSubCategory::where('id', $request->sub_category_id)->first();
        $category_unique_id = $d_category->unique_id;
        $subcategory_unique_id = $d_subcategory ? $d_subcategory->unique_id : null;


        foreach ($request->input('variant_names') as $languageCode => $variantNames) {
            foreach ($variantNames as $index => $variantName) {
                $language_id = Language::where([['code', $languageCode], ['user_id', $user_id]])->first()->id;


                $category = UserItemCategory::where([['unique_id', $category_unique_id], ['language_id', $language_id]])->first();
                $subcategory = UserItemSubCategory::where([['unique_id', $subcategory_unique_id], ['language_id', $language_id]])->first();
                $category_id = intval(@$category->id) ?? null;
                $sub_category_id = intval(@$subcategory->id) ?? null;

                $variantContent = new VariantContent();
                $variantContent->category_id = $category_id;
                $variantContent->sub_category_id = $sub_category_id;
                $variantContent->language_id = $language_id;
                $variantContent->user_id = $user_id;
                $variantContent->name = $variantName;
                $variantContent->variant_id = $variant->id;
                $variantContent->save();
            }

            // If options are more than 0
            if (isset($request->option_names[$languageCode]) && count($request->option_names[$languageCode]) > 0) {
                $variantOption = new VariantOption();
                $variantOption->user_id = $user_id;
                $variantOption->variant_id = $variant->id;
                $variantOption->save();

                foreach ($request->option_names[$languageCode] as $key => $optionName) {
                    // Assuming $optionName is a string, if not, loop over sub-arrays if needed
                    if (is_array($optionName)) {
                        foreach ($optionName as $singleOptionName) {
                            $this->saveVariantOptionContent($variant->id, $variantOption->id, $user_id, $languageCode, $singleOptionName, $key);
                        }
                    } else {
                        $this->saveVariantOptionContent($variant->id, $variantOption->id, $user_id, $languageCode, $optionName, $key);
                    }
                }
            }
        }

        Session::flash('success', __('Created successfully'));
        return "success";
    }

    public function edit($id)
    {
        $data['variant'] = Variant::where('id', $id)->firstOrFail();
        return view('user.item.variant.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $user_languages = Language::where('user_id', $user_id)->get();
        $rules = [
            'category_id' => 'required'
        ];
        $messages = [
            'category_id.required' => __('The category is required')
        ];

        foreach ($user_languages as $user_language) {
            $code = $user_language->code;
            $rules["variant_names.{$code}.*"] = 'required';
            $messages["variant_names.{$code}.*.required"] = __('The variant name is required for') . " {$user_language->name} " . __('language');

            $rules["option_names.{$code}.*.*"] = 'required';
            $messages["option_names.{$code}.*.*.required"] = __('The option name is required for') . " {$user_language->name} " . __('language');
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        if (is_null($request->option_names)) {
            session()->flash('warning', 'You need to add at least one option.');
            return "success";
        }
        // Step 1: Update the Variant
        $variant = Variant::findOrFail($id);
        $variant->user_id = $user_id;
        $variant->save();

        $d_category = UserItemCategory::where('id', $request->category_id)->first();
        $d_subcategory = UserItemSubCategory::where('id', $request->sub_category_id)->first();
        $category_unique_id = $d_category->unique_id;
        $subcategory_unique_id = @$d_subcategory->unique_id;

        // Step 2: Update or Create Variant Contents
        foreach ($request->input('variant_names') as $languageCode => $variantNames) {
            foreach ($variantNames as $index => $variantName) {
                $language_id = Language::where([['code', $languageCode], ['user_id', $user_id]])->first()->id;

                $category = UserItemCategory::where([['unique_id', $category_unique_id], ['language_id', $language_id]])->first();
                $subcategory = UserItemSubCategory::where([['unique_id', $subcategory_unique_id], ['language_id', $language_id]])->first();
                $category_id = intval(@$category->id) ?? null;
                $sub_category_id = intval(@$subcategory->id) ?? null;

                $variantContent = VariantContent::firstOrNew([
                    'variant_id' => $variant->id,
                    'language_id' => \App\Models\User\Language::where([['code', $languageCode], ['user_id', $user_id]])->first()->id,
                    'user_id' => $user_id
                ]);
                $variantContent->category_id = $category_id;
                $variantContent->sub_category_id = $sub_category_id;
                $variantContent->name = $variantName;
                $variantContent->save();
            }
        }

        // Step 3: Update or Create Variant Options and Contents
        $variantOptions = $request->input('option_names');
        foreach ($variantOptions as $languageCode => $optionNames) {
            foreach ($optionNames as $key => $names) {
                // Ensure $names is an array (it may have been serialized)
                if (!is_array($names)) {
                    $names = [$names];
                }

                // Get or create the VariantOption model
                $variantOption = VariantOption::firstOrNew([
                    'variant_id' => $variant->id,
                    'user_id' => $user_id,
                ]);
                $variantOption->save();

                foreach ($names as $name) {
                    $this->saveVariantOptionContent($variant->id, null, $user_id, $languageCode, $name, $key);
                }
            }
        }

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    private function saveVariantOptionContent($variant_id, $variantOptionId = null, $userId, $languageCode, $optionName, $key)
    {
        $conditions = [
            'variant_id' => $variant_id,
            'language_id' => \App\Models\User\Language::where([['code', $languageCode], ['user_id', $userId]])->first()->id,
            'index_key' => $key,
            'user_id' => $userId
        ];
        // Add the variant_option_id only if it's not null
        if ($variantOptionId != null) {
            $conditions['variant_option_id'] = $variantOptionId;
        }
        $variantOptionContent = VariantOptionContent::firstOrNew($conditions);
        $variantOptionContent->option_name = $optionName;
        $variantOptionContent->save();
    }

    public function delete($id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $variant = Variant::where([['id', $id], ['user_id', $user_id]])->firstOrFail();

        //delete variant content
        $variant_contents = VariantContent::where('variant_id', $variant->id)->get();
        foreach ($variant_contents as $variant_content) {
            $variant_content->delete();
        }

        //delete variant option
        $variant_options = VariantOption::where([['variant_id', $id], ['user_id', $user_id]])->get();
        foreach ($variant_options as $variant_option) {
            $variant_option->delete();
        }
        //delete variant option contents
        $variation_option_contents = VariantOptionContent::where('variant_id', $id)->get();
        foreach ($variation_option_contents as $variation_option_content) {
            $variation_option_content->delete();
        }

        $variant->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }
    public function bulk_delete(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $ids = $request->ids;

        foreach ($ids as $id) {
            $variant = Variant::where([['id', $id], ['user_id', $user_id]])->firstOrFail();

            //delete variant content
            $variant_contents = VariantContent::where('variant_id', $variant->id)->get();
            foreach ($variant_contents as $variant_content) {
                $variant_content->delete();
            }

            //delete variant option
            $variant_options = VariantOption::where([['variant_id', $id], ['user_id', $user_id]])->get();
            foreach ($variant_options as $variant_option) {
                $variant_option->delete();
            }
            //delete variant option contents
            $variation_option_contents = VariantOptionContent::where('variant_id', $id)->get();
            foreach ($variation_option_contents as $variation_option_content) {
                $variation_option_content->delete();
            }

            $variant->delete();
            Session::flash('success', __('Deleted successfully'));
        }
        return 'success';
    }

    public function delete_option(Request $request)
    {
        $options = VariantOptionContent::where('index_key', $request->index)->get();
        foreach ($options as $option) {
            $option->delete();
        }
        return 'success';
    }
}
