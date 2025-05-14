<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\Language;
use Session;

class ForgetController extends Controller
{
    public function mailForm()
    {
        return view('admin.forget');
    }

    public function sendmail(Request $request)
    {
        // check whether the mail exists in database
        $request->validate([
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    $count = Admin::where('email', $value)->count();
                    if ($count == 0) {
                        $fail(__("The email address doesn't exist"));
                    }
                }
            ]
        ]);

        // change the password with newly created random password
        $pass = uniqid();
        $admin = Admin::where('email', $request->email)->first();
        $admin->password = bcrypt($pass);
        $admin->save();

        // send the random (newly created) & username to the mail
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $be = $currentLang->basic_extended;
        $from = $be->from_mail;
        $to = $request->email;
        $subject = "Restore Password & Username";
        $username = $admin->username;

        $msg    = "<h4>Hello $username,</h4><div><p><strong>Your current username:</strong> $username</p><p><strong>Your new password:</strong>$pass</p></div>";

        /******** Send mail to user ********/
        $data = [];
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_port'] = $be->smtp_port;
        $data['encryption'] = $be->encryption;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;

        //mail info in array
        $data['from_mail'] = $from;
        $data['recipient'] = $to;
        $data['subject'] = $subject;
        $data['body'] = $msg;
        BasicMailer::sendMail($data);

        Session::flash('success', __('New password and current username have been sent successfully via email'));
        return back();
    }
}
