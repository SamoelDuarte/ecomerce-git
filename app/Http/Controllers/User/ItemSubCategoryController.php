<?php

namespace App\Http\Controllers\User;

use App\Http\Helpers\UserPermissionHelper;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Http\Controllers\Controller;
use App\Models\User\UserItemCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User\UserItemSubCategory;
use Illuminate\Support\Facades\Validator;

class ItemSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $lang_id = $lang->id;

        $data['categories'] = UserItemCategory::where('language_id', $lang_id)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->where('status', 1)
            ->orderBy('name', 'ASC')->get();

        $data['itemsubcategories'] = UserItemSubCategory::where('language_id', $lang_id)->where('user_id', Auth::guard('web')->user()->id)
            ->with('category')
            ->orderBy('created_at', 'DESC')->paginate(10);
        $data['lang_id'] = $lang_id;

        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $data['subcategories_limit'] = $current_package->subcategories_limit;
        $data['total_subcategories'] = UserItemSubCategory::where('language_id', $lang->id)->where('user_id', Auth::guard('web')->user()->id)->count();
        return view('user.item.subcategory.index', $data);
    }


    public function store(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $languages = Language::where('user_id', $user_id)->get();

        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $subcategories_limit = $current_package->subcategories_limit;

        $total_subcategories = UserItemSubCategory::where('language_id', $request->user_language_id)->where('user_id', $user_id)->count();
        $total_subcategories = $total_subcategories + 1;

        $slug = make_slug($request->name);
        $user_subcategories = UserItemSubCategory::where('language_id', $request->user_language_id)->where('user_id', $user_id)->get();
        foreach ($user_subcategories as $user_subcategory) {
            if ($user_subcategory->slug == $slug) {
                Session::flash('warning', __('The same subcategory name already exists'));
                return "success";
            }
        }

        if ($subcategories_limit <= $total_subcategories) {
            Session::flash('warning', __('Subcategory limit exceeded'));
            return "success";
        }

        $rules = [
            'category_id' => 'required',
            'serial_number' => 'required',
            'status' => 'required',
        ];
        $defaulLang = Language::where([['user_id', Auth::guard('web')->user()->id], ['is_default', 1]])->first();
        $rules[$defaulLang->code . '_name'] = 'required|max:255';
        $messages[$defaulLang->code . '_name.required'] = __('The name feild is required for') . ' ' . $defaulLang->name . ' ' . __('language');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $unique_id = uniqid();
        $category = UserItemCategory::where('id', $request->category_id)->first();
        $category_unique_id = $category->unique_id;
        foreach ($languages as $lang) {
            if ($lang->is_default == 1 || $request->filled($lang->code . '_name')) {
                $category = UserItemCategory::where([['unique_id', $category_unique_id], ['language_id', $lang->id]])->first();
                $data = new UserItemSubCategory;
                $data->unique_id = $unique_id;
                $data->user_id =  $user_id;
                $data->language_id  =  $lang->id;
                $data->category_id = $category->id;
                $data->slug = make_slug($request[$lang->code . '_name']);
                $data->name = $request[$lang->code . '_name'];
                $data->status = $request->status;
                $data->serial_number = $request->serial_number;
                $data->save();
            }
        }

        Session::flash('success', __('Created successfully'));
        return "success";
    }


    public function edit(Request $request, $id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['data'] = UserItemSubCategory::findOrFail($id);
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $subcategories_limit = $current_package->subcategories_limit;
        $lang = Language::where('code', request('language'))->where('user_id', $user_id)->first();
        $lang_id = $lang->id;
        $total_subcategories = UserItemSubCategory::where('language_id', $lang_id)->where('user_id', $user_id)->count();
        if ($total_subcategories > $subcategories_limit) {
            Session::flash('warning', __('Delete') . ' ' . $total_subcategories - $subcategories_limit . ' ' . __('Category to Enable Edit'));
            return redirect()->back();
        }
        $data['categories'] = UserItemCategory::where('language_id', $lang_id)->where('user_id', $user_id)->orderBy('name', 'ASC')->get();
        $data['languages'] = Language::where('user_id', $user_id)->get();

        return view('user.item.subcategory.edit', $data);
    }

    public function update(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $languages = Language::where('user_id', $user_id)->get();
        $defaulLang = Language::where([['user_id', Auth::guard('web')->user()->id], ['is_default', 1]])->first();
        $rules = [
            'status' => 'required',
            'category_id' => 'required',
            'serial_number' => 'required',
        ];
        $rules[$defaulLang->code . '_name'] = 'required|max:255';
        $messages[$defaulLang->code . '_name.required'] = __('The subcategory name field is required for') . ' ' . $defaulLang->name . ' ' . __('language');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $data = UserItemSubCategory::findOrFail($request->subcategory_id);
        $unique_id = is_null($data->unique_id) ? uniqid() : $data->unique_id;
        $category = UserItemCategory::where('id', $request->category_id)->first();
        $category_unique_id = $category->unique_id;

        foreach ($languages as $language) {
            $subcategory = UserItemSubCategory::where('id', $request[$language->code . '_id'])->first();

            if (empty($subcategory)) {
                $subcategory = new UserItemSubCategory();
            }

            $category = UserItemCategory::where([['unique_id', $category_unique_id], ['language_id', $language->id]])->first();

            $subcategory->unique_id = $unique_id;
            $subcategory->user_id =  $user_id;
            $subcategory->language_id  =  $language->id;
            $subcategory->category_id = $category->id;
            $subcategory->slug = make_slug($request[$language->code . '_name']);
            $subcategory->name = $request[$language->code . '_name'];
            $subcategory->status = $request->status;
            $subcategory->serial_number = $request->serial_number;
            $subcategory->save();
        }
        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function delete(Request $request)
    {
        $category = UserItemSubCategory::findOrFail($request->subcategory_id);
        if ($category->items()->count() > 0) {
            Session::flash('warning', __('First, delete all the items under the selected categories'));
            return back();
        }
        $category->delete();

        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $pcategory = UserItemSubCategory::findOrFail($id);
            if ($pcategory->items()->count() > 0) {
                Session::flash('warning', __('First, delete all the items under the products of these subcategories'));
                return "success";
            }
        }
        foreach ($ids as $id) {
            $ItemCategory = UserItemSubCategory::findOrFail($id);
            $ItemCategory->delete();
        }

        Session::flash('success', __('Deleted successfully'));
        return "success";
    }
}
