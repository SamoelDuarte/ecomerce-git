<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bcategory;
use App\Models\Language;
use Validator;
use Session;

class BcategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();

        $lang_id = $lang->id;
        $data['bcategorys'] = Bcategory::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;

        return view('admin.blog.bcategory.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $data['data'] = Bcategory::findOrFail($id);
        $data['languages'] = Language::get();
        return view('admin.blog.bcategory.edit', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'status' => 'required',
            'serial_number' => 'required|integer',
        ];
        $messages = [];
        $languages = Language::get();
        $defaulLang = Language::query()->where('is_default', 1)->first();

        $rules[$defaulLang->code . '_name'] = 'required|max:255';
        $messages[$defaulLang->code . '_name.required'] = __('The category name field is required for') . ' ' . $defaulLang->name . ' ' . __('language');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $index_id = uniqid();
        foreach ($languages as $language) {
            if ($language->is_default == 1 || $request->filled($language->code . '_name')) {
                $bcategory = new Bcategory;
                $bcategory->unique_id = $index_id;
                $bcategory->language_id = $language->id;
                $bcategory->name = $request[$language->code . '_name'];
                $bcategory->status = $request->status;
                $bcategory->serial_number = $request->serial_number;
                $bcategory->save();
            }
        }

        Session::flash('success', __('Created Successfully'));
        return "success";
    }


    public function update(Request $request)
    {
        $rules = [
            'status' => 'required',
            'serial_number' => 'required|integer',
        ];

        $defaulLang = Language::query()->where('is_default', 1)->first();

        $rules[$defaulLang->code . '_name'] = 'required|max:255';
        $messages[$defaulLang->code . '_name.required'] = __('The category name field is required for') . ' ' . $defaulLang->name . ' ' . __('language');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $languages = Language::get();
        $bcategory = Bcategory::findOrFail($request->category_id);
        $unique_id = is_null($bcategory->unique_id) ? uniqid() : $bcategory->unique_id;

        foreach ($languages as $language) {
            if ($request->filled($language->code . '_name')) {
                $bcategory = Bcategory::where('id', $request[$language->code . '_id'])->first();

                if (empty($bcategory)) {
                    $bcategory = new Bcategory();
                }
                $bcategory->unique_id = $unique_id;
                $bcategory->language_id = $language->id;
                $bcategory->name = $request[$language->code . '_name'];
                $bcategory->status = $request->status;
                $bcategory->serial_number = $request->serial_number;
                $bcategory->save();
            }
        }

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function delete(Request $request)
    {
        $bcategory = Bcategory::where('id', $request->bcategory_id)->firstOrFail();
        if ($bcategory->blogs()->count() > 0) {
            Session::flash('warning', __('First, delete all the blogs in this category'));
            return back();
        }
        $bcategory->delete();

        Session::flash('success', __('Deleted Successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $bcategory = Bcategory::where('id', $id)->firstOrFail();
            if ($bcategory->blogs()->count() > 0) {
                Session::flash('warning', __('First, delete all the blogs in the selected categories'));
                return "success";
            }
        }
        foreach ($ids as $id) {
            $bcategory = Bcategory::findOrFail($id);
            $bcategory->delete();
        }

        Session::flash('success', __('Deleted Successfully'));
        return "success";
    }
}
