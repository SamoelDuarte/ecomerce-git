<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\BlogCategory;
use App\Models\User\BlogContent;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Validator;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index(Request $request)
    {
        $user_id =  Auth::guard('web')->user()->id;
        if ($request->has('language')) {
            $lang = Language::where([
                ['code', $request->language],
                ['user_id', $user_id]
            ])->first();
            Session::put('currentLangCode', $request->language);
        } else {
            $lang = Language::where([
                ['is_default', 1],
                ['user_id', $user_id]
            ])
                ->first();
            Session::put('currentLangCode', $lang->code);
        }
        $data['bcategorys'] = BlogCategory::where([
            ['language_id', '=', $lang->id],
            ['user_id', '=', $user_id],
        ])
            ->orderBy('serial_number', 'ASC')
            ->get();

        return view('user.blog.bcategory.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'status' => 'required',
            'serial_number' => 'required|integer|min:0',
        ];

        $messages = [];
        $languages = Language::where('user_id', Auth::guard('web')->user()->id)->get();
        $defaulLang = Language::where([['user_id', Auth::guard('web')->user()->id], ['is_default', 1]])->first();
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
                $bcategory = new BlogCategory();
                $bcategory->unique_id = $index_id;
                $bcategory->language_id = $language->id;
                $bcategory->name = $request[$language->code . '_name'];
                $bcategory->status = $request->status;
                $bcategory->user_id = Auth::guard('web')->user()->id;
                $bcategory->serial_number = $request->serial_number;
                $bcategory->save();
            }
        }

        Session::flash('success', __('Created successfully'));
        return "success";
    }

    public function edit($id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['languages'] = Language::where('user_id', $user_id)->get();
        $data['data'] = BlogCategory::findOrFail($id);

        return view('user.blog.bcategory.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'status' => 'required',
            'serial_number' => 'required|integer',
        ];

        $messages = [
            'status' => __('The status field is required'),
            'serial_number' => __('The serial number field is required'),
        ];
        $defaulLang = Language::where([['user_id', Auth::guard('web')->user()->id], ['is_default', 1]])->first();
        $rules[$defaulLang->code . '_name'] = 'required|max:255';
        $messages[$defaulLang->code . '_name.required'] = __('The category name field is required for') . ' ' . $defaulLang->name . ' ' . __('language');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user_id = Auth::guard('web')->user()->id;
        $languages = Language::where('user_id', $user_id)->get();
        $bcategory = BlogCategory::findOrFail($request->category_id);
        $unique_id = is_null($bcategory->unique_id) ? uniqid() : $bcategory->unique_id;

        foreach ($languages as $language) {
            if ($request->filled($language->code . '_name')) {
                $bcategory = BlogCategory::where('id', $request[$language->code . '_id'])->first();

                if (empty($bcategory)) {
                    $bcategory = new BlogCategory();
                }
                $bcategory->unique_id = $unique_id;
                $bcategory->user_id = $user_id;
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
        $bcategory = BlogCategory::findOrFail($request->bcategory_id);
        $blog_contents = BlogContent::where('category_id', $request->bcategory_id)->get();
        if (count($blog_contents) > 0) {
            Session::flash('warning', __('First, delete all the blogs under this category'));
            return back();
        }
        $bcategory->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $bcategory = BlogCategory::findOrFail($id);
            $blog_contents = BlogContent::where('category_id', $id)->get();
            if (count($blog_contents) > 0) {
                Session::flash('warning', __('First, delete all the blogs under this category'));
                return "success";
            }
        }

        foreach ($ids as $id) {
            $bcategory = BlogCategory::findOrFail($id);
            $bcategory->delete();
        }

        Session::flash('success', __('Deleted successfully'));
        return "success";
    }
}
