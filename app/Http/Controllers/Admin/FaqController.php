<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Language;
use Validator;
use Session;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $lang_id = $lang->id;
        $data['faqs'] = Faq::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;
        return view('admin.faq.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'question' => 'required|max:255',
            'answer' => 'required',
            'serial_number' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $faq = new Faq;
        $faq->language_id = $request->language_id;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->serial_number = $request->serial_number;
        $faq->save();

        Session::flash('success', __("Created Successfully"));
        return "success";
    }

    public function update(Request $request)
    {
        $rules = [
            'question' => 'required|max:255',
            'answer' => 'required',
            'serial_number' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $faq = Faq::findOrFail($request->faq_id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->serial_number = $request->serial_number;
        $faq->save();

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function delete(Request $request)
    {
        $faq = Faq::findOrFail($request->faq_id);
        $faq->delete();
        Session::flash('success', __('Deleted Successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $faq = Faq::findOrFail($id);
            $faq->delete();
        }

        Session::flash('success', __('Deleted Successfully'));
        return "success";
    }
}
