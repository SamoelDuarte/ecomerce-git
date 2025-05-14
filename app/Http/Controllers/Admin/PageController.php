<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Language;
use Purifier;
use Session;
use Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $lang_id = $lang->id;
        $data['apages'] = Page::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;
        return view('admin.page.index', $data);
    }

    public function create()
    {
        $data['tpages'] = Page::where('language_id', 0)->get();
        return view('admin.page.create', $data);
    }

    public function store(Request $request)
    {
        $slug = make_slug($request->title);
        $rules = [
            'language_id' => 'required',
            'title' => 'required',
            'body' => 'required',
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $page = new Page;
        $page->language_id = $request->language_id;
        $page->title = $request->title;
        $page->slug = $slug;
        $page->body = Purifier::clean($request->body);
        $page->status = $request->status;
        $page->save();

        Session::flash('success', __('Created Successfully'));
        return "success";
    }

    public function edit($pageID)
    {
        $data['page'] = Page::findOrFail($pageID);
        return view('admin.page.edit', $data);
    }

    public function update(Request $request)
    {
        $slug = make_slug($request->title);

        $rules = [
            'title' => 'required',
            'body' => 'required',
            'status' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $pageID = $request->pageid;

        $page = Page::findOrFail($pageID);
        $page->title = $request->title;
        $page->slug = $slug;
        $page->body = Purifier::clean($request->body);
        $page->status = $request->status;
        $page->save();

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function delete(Request $request)
    {
        $pageID = $request->pageid;
        $page = Page::findOrFail($pageID);
        $page->delete();
        Session::flash('success', __('Deleted Successfully'));
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $page = Page::findOrFail($id);
            $page->delete();
        }
        Session::flash('success', __('Deleted Successfully'));
        return "success";
    }
}
