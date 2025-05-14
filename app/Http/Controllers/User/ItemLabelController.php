<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Label;
use App\Models\User\Language;
use Auth;
use Illuminate\Http\Request;
use Session;
use Validator;

class ItemLabelController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $language = Language::where([['user_id', $user_id], ['code', $request->language]])->firstOrFail();
        $data['userLanguages'] = Language::where('user_id', $user_id)->get();
        $data['labels'] = Label::where([['user_id', $user_id], ['language_id', $language->id]])->get();
        $data['user_id'] = $user_id;
        return view('user.item.label.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'user_language_id' => 'required',
            'name' => 'required',
            'color' => 'required',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $in = $request->all();
        $in['language_id'] = $request->user_language_id;
        $in['user_id'] = Auth::guard('web')->user()->id;
        Label::create($in);
        Session::flash('success', __('Created successfully'));
        return 'success';
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'color' => 'required',
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $data = Label::findOrFail($request->id);
        $input = $request->all();
        $data->update($input);

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function delete(Request $request)
    {
        $label = Label::findOrFail($request->label_id);
        $label->delete();

        Session::flash('success', __('Deleted successfully'));
        return back();
    }
}
