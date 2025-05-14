<?php

namespace App\Http\Controllers\User;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserEmailTemplate;
use Purifier;
use Session;

class MailTemplateController extends Controller
{
    public function mailTemplates()
    {
        $user_id = Auth::guard('web')->user()->id;
        $default_templates = [
            'email_verification',
            'product_order',
            'reset_password',
            'product_order_status'
        ];

        foreach ($default_templates as $key => $val) {
            $template = UserEmailTemplate::where([['user_id', $user_id], ['email_type', $val]])->first();
            if (!$template) {
                UserEmailTemplate::create([
                    'user_id' => $user_id,
                    'email_type' => $val,
                    'email_subject' => null,
                    'email_body' => null,
                ]);
            }
        }
        $data['templates'] = UserEmailTemplate::where('user_id', $user_id)->get();

        return view('user.settings.email.templates', $data);
    }

    public function editMailTemplate($id)
    {
        $templateInfo = UserEmailTemplate::findOrFail($id);
        return view('user.settings.email.edit-template', compact('templateInfo'));
    }

    public function updateMailTemplate(Request $request, $id)
    {
        $rules = [
            'email_subject' => 'required',
            'email_body' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        UserEmailTemplate::findOrFail($id)->update([
            'email_body' => Purifier::clean($request->email_body, 'youtube'),
            'email_subject' => $request->email_subject,
        ]);
        Session::flash('success', __('Updated Successfully'));
        return redirect()->back();
    }
}
