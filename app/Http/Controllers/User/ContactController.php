<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Language;
use App\Models\User\UserContact;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        // then, get the service section heading info of that language from db
        $information['data'] = UserContact::where('language_id', $language->id)->where('user_id', Auth::guard('web')->user()->id)->first();
        // get all the languages from db
        return view('user.contact', $information);
    }

    public function update(Request $request, $language)
    {
        $lang = Language::where('code', $language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $data = UserContact::where([
            ['user_id', Auth::guard('web')->user()->id],
            ['language_id', $lang->id]
        ])->first();
        if (is_null($data)) {
            $data = new UserContact;
        }

        $rules = [
            'contact_form_title' => 'nullable|max:255',
            'contact_form_subtitle' => 'nullable|max:255',
            'contact_addresses' => 'nullable',
            'contact_numbers' => 'nullable',
            'contact_mails' => 'nullable|max:255',
            'latitude' => 'nullable|max:255',
            'longitude' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $data->contact_form_title = $request->contact_form_title;
        $data->contact_form_subtitle = $request->contact_form_subtitle;
        $data->contact_addresses = clean($request->contact_addresses);
        $data->contact_numbers = $request->contact_numbers;
        $data->contact_mails = $request->contact_mails;
        $data->latitude = $request->latitude;
        $data->longitude = $request->longitude;
        $data->language_id = $lang->id;
        $data->user_id = Auth::guard('web')->user()->id;
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
}
