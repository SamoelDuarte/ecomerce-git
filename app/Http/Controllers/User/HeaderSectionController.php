<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Language;
use App\Models\User\UserHeader;
use Illuminate\Http\Request;
use Auth;
use Session;
use Validator;

class HeaderSectionController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $header = UserHeader::where('language_id', $lang->id)->where('user_id', Auth::guard('web')->user()->id)->first();

        if ($header) {
            $data['header'] = $header;
        } else {
            $data['header'] = NULL;
        }

        return view('user.header.index', $data);
    }

    public function update(Request $request, $langid)
    {
        $rules = [
            'header_text' => 'nullable|max:255',
            'header_middle_text' => 'nullable|max:255',
            'icon' => 'nullable',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        // dd($request->icon);
        $bs = UserHeader::where('language_id', $langid)->where('user_id', Auth::guard('web')->user()->id)->first();
        if ($bs == NULL) {
            $bs = new UserHeader();
            $bs->language_id = $langid;
            $bs->user_id = Auth::guard('web')->user()->id;
            $bs = $bs->save();
        }
        $bs->header_text = $request->header_text;
        $bs->header_middle_text = $request->header_middle_text;
        $bs->header_logo = $request->icon;
        $bs->save();
        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }
}
