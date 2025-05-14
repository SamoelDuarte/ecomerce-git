<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserUlink;
use Auth;
use Illuminate\Http\Request;
use Session;
use Validator;

class UlinkSectionController extends Controller
{
    public function index(Request $request)
    {
        $lang = \App\Models\User\Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $lang_id = $lang->id;
        $data['aulinks'] = UserUlink::where('language_id', $lang_id)->where('user_id', Auth::guard('web')->user()->id)->get();
        $data['lang_id'] = $lang_id;
        return view('user.footer.Ulink.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'user_language_id' => 'required',
            'name' => 'required|max:255',
            'url' => 'required|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $UserUlink = new UserUlink;
        $UserUlink->language_id = $request->user_language_id;
        $UserUlink->user_id =  Auth::guard('web')->user()->id;
        $UserUlink->name = $request->name;
        $UserUlink->url = $request->url;
        $UserUlink->save();

        Session::flash('success', __('Created successfully'));
        return 'success';
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'url' => 'required|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $UserUlink = UserUlink::findOrFail($request->ulink_id);
        $UserUlink->name = $request->name;
        $UserUlink->url = $request->url;
        $UserUlink->save();

        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }

    public function delete(Request $request)
    {

        $UserUlink = UserUlink::findOrFail($request->ulink_id);
        $UserUlink->delete();

        Session::flash('success', __('Deleted successfully'));
        return back();
    }
}
