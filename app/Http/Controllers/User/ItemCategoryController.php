<?php

namespace App\Http\Controllers\User;

use App\Http\Helpers\UserPermissionHelper;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\UserItemCategory;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ItemCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $lang_id = $lang->id;
        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $data['categories_limit'] = $current_package->categories_limit;
        $data['total_categories'] = UserItemCategory::where('language_id', $lang->id)->where('user_id', Auth::guard('web')->user()->id)->count();

        $data['itemcategories'] = UserItemCategory::where('language_id', $lang_id)->where('user_id', Auth::guard('web')->user()->id)->orderBy('serial_number', 'ASC')->paginate(10);

        $data['lang_id'] = $lang_id;
        return view('user.item.category.index', $data);
    }

    public function store(Request $request)
    {
        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $categories_limit = $current_package->categories_limit;

        $total_categories = UserItemCategory::where('language_id', $request->user_language_id)->where('user_id', Auth::guard('web')->user()->id)->count();
        $total_categories = $total_categories + 1;

        $slug = make_slug($request->name);
        $user_categories = UserItemCategory::where('language_id', $request->user_language_id)->where('user_id', Auth::guard('web')->user()->id)->get();
        foreach ($user_categories as $user_category) {
            if ($user_category->slug == $slug) {
                Session::flash('warning', __('The category already exists'));
                return "success";
            }
        }

        if ($categories_limit < $total_categories) {
            Session::flash('warning', __('Category limit exceeded'));
            return "success";
        }
        $messages = [];
        $rules = [
            'status' => 'required',
            'serial_number' => 'required',
            'image' => 'required'
        ];
        $languages = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $defaulLang = Language::where([['user_id', Auth::guard('web')->user()->id], ['is_default', 1]])->first();
        $rules[$defaulLang->code . '_name'] = 'required|max:255';
        $messages[$defaulLang->code . '_name.required'] = __('The category name field is required for') . ' ' . $defaulLang->name . ' ' . __('language');



        if ($request->hasFile('image')) {
            $rules['image'] = 'mimes:jpeg,png,svg,jpg';
            $messages['image.mimes'] = __('Only jpeg,png,svg,jpg files are allowed');
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $index_id = uniqid();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $directory = public_path('assets/front/img/user/items/categories/');
            $image_name = Uploader::upload_picture($directory, $file);
        } else {
            $image_name = null;
        }
        foreach ($languages as $language) {
            if ($language->is_default == 1 || $request->filled($language->code . '_name')) {
                $category = new UserItemCategory();
                $category->unique_id = $index_id;
                $category->user_id = Auth::guard('web')->user()->id;
                $category->language_id = $language->id;
                $category->name = $request[$language->code . '_name'];
                $category->slug = make_slug($request[$language->code . '_name']);
                $category->color = $request->color;
                $category->image = $image_name;
                $category->status = $request->status;
                $category->serial_number = $request->serial_number;
                $category->save();
            }
        }

        Session::flash('success', __('Created successfully'));
        return "success";
    }

    public function edit(Request $request, $id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['languages'] = Language::where('user_id', $user_id)->get();
        $data['data'] = UserItemCategory::findOrFail($id);

        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $categories_limit = $current_package->categories_limit;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $lang_id = $lang->id;
        $total_categories = UserItemCategory::where('language_id', $lang_id)->where('user_id', $user_id)->count();
        if ($total_categories > $categories_limit) {
            Session::flash('warning', __('Delete category to enable editing'));
            return redirect()->back();
        }
        return view('user.item.category.edit', $data);
    }

    public function update(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $languages = Language::where('user_id', $user_id)->get();
        $messages = [];
        $rules = [
            'status' => 'required',
            'serial_number' => 'required',
        ];

        $defaulLang = Language::where([['user_id', Auth::guard('web')->user()->id], ['is_default', 1]])->first();
        $rules[$defaulLang->code . '_name'] = 'required|max:255';
        $messages[$defaulLang->code . '_name.required'] = __('The category name field is required for') . ' ' . $defaulLang->name . ' ' . __('language');

        if ($request->hasFile('image')) {
            $rules['image'] = 'mimes:jpeg,png,svg,jpg';
            $messages = [
                'image.mimes' => __('Only jpeg,png,svg,jpg files are allowed')
            ];
        }

        if ($request->hasFile('image')) {
            $rules['image'] = 'mimes:jpeg,png,svg,jpg';
            $messages = [
                'image.mimes' => __('Only jpeg,png,svg,jpg files are allowed')
            ];
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $data = UserItemCategory::findOrFail($request->category_id);

        if ($request->hasFile('image')) {
            $directory = public_path('assets/front/img/user/items/categories/');
            @unlink($directory . $data->image);
            $file = $request->file('image');
            $image_name = Uploader::upload_picture($directory, $file);
        } else {
            $image_name =  $data->image;
        }

        $unique_id = is_null($data->unique_id) ? uniqid() : $data->unique_id;
        foreach ($languages as $language) {
            if ($request->filled($language->code . '_name')) {
                $category = UserItemCategory::where('id', $request[$language->code . '_id'])->first();

                if (empty($category)) {
                    $category = new UserItemCategory();
                }
                $category->unique_id = $unique_id;
                $category->user_id = $user_id;
                $category->language_id = $language->id;
                $category->name = $request[$language->code . '_name'];
                $category->slug = make_slug($request[$language->code . '_name']);
                $category->color = $request->color;
                $category->image = $image_name;
                $category->status = $request->status;
                $category->serial_number = $request->serial_number;
                $category->save();
            }
        }

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function feature(Request $request)
    {
        $category = UserItemCategory::findOrFail($request->category_id);
        $category->is_feature = $request->is_feature;
        $category->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete(Request $request)
    {
        $category = UserItemCategory::findOrFail($request->category_id);
        if ($category->items()->count() > 0) {
            Session::flash('warning', __('First, delete all the items under the selected categories'));
            return back();
        }

        //delete language wise category
        $categories = UserItemCategory::where('unique_id', $category->unique_id)->get();
        foreach ($categories as $d_category) {
            @unlink(public_path('assets/front/img/user/items/categories/') . $d_category->image);
            $d_category->delete();
            $d_category->subcategories()->delete();
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $pcategory = UserItemCategory::findOrFail($id);
            if ($pcategory->items()->count() > 0) {
                Session::flash('warning', __('First, delete all the items under the selected categories'));
                return "success";
            }
        }
        foreach ($ids as $id) {
            $ItemCategory = UserItemCategory::findOrFail($id);

            //delete language wise category
            $categories = UserItemCategory::where('unique_id', $ItemCategory->unique_id)->get();
            foreach ($categories as $d_category) {
                @unlink(public_path('assets/front/img/user/items/categories/') . $d_category->image);
                $d_category->delete();
                $d_category->subcategories()->delete();
            }
        }

        Session::flash('success', __('Deleted successfully'));
        return "success";
    }
}
