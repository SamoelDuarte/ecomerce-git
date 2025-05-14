<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Language;
use App\Models\User\Tab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TabSectionController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $lang_id = $lang->id;
        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $data['tabs_limit'] = $current_package->tabs_limit;
        $data['total_tabs'] = Tab::where('language_id', $lang->id)->where('user_id', Auth::guard('web')->user()->id)->count();

        $data['tabs'] = Tab::where('language_id', $lang_id)->where('user_id', Auth::guard('web')->user()->id)->orderBy('serial_number', 'ASC')->paginate(10);
        $data['lang_id'] = $lang_id;
        return view('user.home.tab_section.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'user_language_id' => 'required',
            'name' => 'required|max:255',
            'status' => 'required',
            'serial_number' => 'required|numeric',
        ];
        $messages = [];
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
        $data = new Tab;
        $input = $request->all();
        $input['slug'] =  make_slug($request->name);
        $input['user_id'] =  Auth::guard('web')->user()->id;
        $input['language_id'] =  $request->user_language_id;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $dir = public_path('assets/front/img/user/items/tabs/');
            $input['image'] = Uploader::upload_picture($dir, $file);
        }
        $data->create($input);

        Session::flash('success', __('Created successfully'));
        return "success";
    }
    public function update(Request $request)
    {
        $messages = [];
        $rules = [
            'name' => 'required|max:255',
            'status' => 'required',
            'serial_number' => 'required|numeric',
        ];

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
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $data = Tab::findOrFail($request->tab_id);
        $input = $request->all();
        $input['slug'] =  make_slug($request->name);

        $dir = public_path('assets/front/img/user/items/tabs/');
        if ($request->hasFile('image')) {
            @unlink($dir . $data->image);
            $file = $request->file('image');
            $input['image'] = Uploader::upload_picture($dir, $file);
        } else {
            $input['image'] =  $data->image;
        }
        $data->update($input);

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function feature(Request $request)
    {
        $tab = Tab::findOrFail($request->tab_id);
        $tab->is_feature = $request->is_feature;
        $tab->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete(Request $request)
    {
        $tab = Tab::findOrFail($request->tab_id);
        $tab->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $ptab = Tab::findOrFail($id);
            if ($ptab->items()->count() > 0) {
                Session::flash('warning', __('First, delete all the items under the selected tabs'));
                return "success";
            }
        }
        foreach ($ids as $id) {
            $Itemtab = Tab::findOrFail($id);
            @unlink(public_path('assets/front/img/user/items/tabs/') . $Itemtab->image);
            $Itemtab->delete();
            $Itemtab->subtabs()->delete();
        }

        Session::flash('success', __('Deleted successfully'));
        return "success";
    }
    public function products($id, Request $request)
    {
        $id = (int)$id;
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $data['language_id'] = $lang->id;
        $productsdddd = Tab::where('id', $id)->where('language_id', $lang->id)->get();

        $data['items'] = DB::table('user_items')->where('user_items.user_id', Auth::guard('web')->user()->id)
            ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_contents.category_id', '=', 'user_item_categories.id')
            ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.*', 'user_item_categories.name AS category')
            ->orderBy('user_items.id', 'DESC')
            ->where('user_item_contents.language_id', '=', $lang->id)
            ->where('user_item_categories.language_id', '=', $lang->id)
            ->get();

        $products = json_decode($productsdddd[0]->products, true);
        $data['tab_products'] = $products;
        $data['user_id'] = Auth::guard('web')->user()->id;
        $data['tab_id'] = $id;

        return view('user.home.tab_section.products', $data);
    }

    public function productsStore(Request $request)
    {
        $items = json_encode($request->products);
        Tab::where('user_id', Auth::guard('web')->user()->id)->where('id', $request->tab_id)->where('language_id', $request->language_id)->update(['products' => $items]);

        Session::flash('success', __('Updated Successfully'));
        return redirect()->back();
    }
}
