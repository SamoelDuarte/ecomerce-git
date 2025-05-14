<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\Language;
use App\Models\Seo;
use App\Models\User;
use DB;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return
     */
    public function showLinkRequestForm()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $bs = $currentLang->basic_setting;

        Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
        Config::set('captcha.secret', $bs->google_recaptcha_secret_key);

        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        return view('front.auth.passwords.email', $data);
    }

    public function forgetPasswordMail(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns'
            ]
        ];
        $messages = [];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user = User::query()->where('email', '=', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'No user found with this email address');
        }

        // store user email in session to use it later
        $request->session()->put('userEmail', $user->email);

        // get the mail template information from db
        $mailData['subject'] = "Recover Password of Your Account";

        // get the website title info from db

        $info = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $name = $user->username;

        // change the password with newly created random password
        $pass = uniqid();
        $admin = User::where('email', $request->email)->first();
        $admin->password = bcrypt($pass);
        $admin->save();

        $msg    = "<h4>Hello $name,</h4><div><p><strong>Your current username:</strong> $name</p><p><strong>Your new password:</strong>$pass</p></div>";


        $mailData['body'] = $msg;

        $mailData['recipient'] = $user->email;

        $mailData['sessionMessage'] = 'A mail has been sent to your email address';

        $mailData['smtp_status'] = $info->is_smtp;
        $mailData['smtp_host'] = $info->smtp_host;
        $mailData['smtp_port'] = $info->smtp_port;
        $mailData['encryption'] = $info->encryption;
        $mailData['smtp_username'] = $info->smtp_username;
        $mailData['smtp_password'] = $info->smtp_password;
        $mailData['from_mail'] = $info->from_mail;
        $mailData['from_name'] = $info->from_name;

        if ($info->is_smtp == 1) {
            BasicMailer::sendMail($mailData);
            Session::flash('success', 'A mail has been sent to your email address');
        } else {
            Session::flash('warning', 'Mail could not be sent');
        }
        
        return redirect()->back();
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('users');
    }
}
