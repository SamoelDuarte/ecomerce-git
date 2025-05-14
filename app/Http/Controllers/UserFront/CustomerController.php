<?php

namespace App\Http\Controllers\UserFront;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\Common;
use App\Http\Helpers\Uploader;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\Customer;
use App\Models\CustomerWishList;
use App\Models\User\BasicSetting;
use App\Models\User\Language;
use App\Models\User\SEO;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserOrder;
use App\Models\User\UserShopSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;

class CustomerController extends Controller
{
    public function __construct()
    {
        $user = getUser();
        $basic_settings = BasicSetting::where('user_id', $user->id)->first();
        Config::set('services.google.client_id', $basic_settings->google_client_id);
        Config::set('services.google.client_secret', $basic_settings->google_client_secret);
        Config::set('services.google.redirect', route('customer.google.callback', $user->username));
        if ($basic_settings->is_recaptcha == 1) {
            Config::set('captcha.sitekey', $basic_settings->google_recaptcha_site_key);
            Config::set('captcha.secret', $basic_settings->google_recaptcha_secret_key);
        }
    }
    public function login(Request $request, $domain)
    {
        $user = getUser();

        if ($user->id == 10) {
            $data['demoEmail'] = 'user@example.com';
        } elseif ($user->id == 11) {
            $data['demoEmail'] = 'james@example.com';
        } elseif ($user->id == 12) {
            $data['demoEmail'] = 'john@example.com';
        } elseif ($user->id == 13) {
            $data['demoEmail'] = 'liamjohnson@example.com';
        } elseif ($user->id == 14) {
            $data['demoEmail'] = 'kids@example.com';
        } else {
            $data['demoEmail'] = 'mantiuser@example.com';
        }

        $current_package = UserPermissionHelper::currentPackagePermission($user->id);
        if ($current_package) {
            $features = json_decode($current_package->features, true);
        } else {
            $features = [];
        }
        $data['features'] = $features;
        $data['currentLanguage'] = Common::getUserCurrentLanguage(getUser()->id);
        $data['pageHeading'] = $this->getUserPageHeading($data['currentLanguage']);
        // when user have to redirect to check out page after login.
        $data['seo'] = SEO::where('language_id', $data['currentLanguage']->id)->where('user_id', $user->id)->first();
        return view('user-front.customer.login', $data);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        return $this->authUserViaProvider('google');
    }

    public function authUserViaProvider($provider)
    {
        $user_id = getUser()->id;
        $keywords = Common::get_keywords($user_id);
        if (Session::has('redirectTo')) {
            $redirectUrl = Session::get('redirectTo');
            Session::forget('redirectTo');
        } else {
            return redirect()->route('customer.dashboard', getParam());
        }

        $user = Socialite::driver($provider)->user();
        if ($provider == 'facebook') {
            $user = json_decode(json_encode($user), true);
        } elseif ($provider == 'google') {
            $user = json_decode(json_encode($user), true)['user'];
        }


        if ($provider == 'facebook') {
            $fname = $user['name'];
        } elseif ($provider == 'google') {
            $fname = $user['given_name'];
            $lname = $user['family_name'];
        }
        $email = $user['email'];
        $provider_id = $user['id'];


        // retrieve user via the email
        $customer = Customer::where([['email', $email], ['provider_id', $provider_id]])->first();

        // if doesn't exist, store the new user's info (email, name, avatar, provider_name, provider_id)
        if (empty($customer)) {
            $customer = new Customer();
            $customer->email = $email;
            $customer->first_name = $fname;
            if ($provider == 'google') {
                $customer->last_name = @$lname;
            }
            $customer->username = $provider_id;
            $customer->provider_id = $provider_id;
            $customer->provider = $provider;
            $customer->status = 1;
            $customer->email_verified =  1;
            $customer->email_verified_at =  Carbon::now();
            $customer->save();
        }

        // authenticate the user
        Auth::guard('customer')->login($customer);

        // if user is banned
        if ($customer->status == 0) {
            Auth::guard('customer')->logout();
            Session::flash('error', $keywords['Your account has been banned'] ?? __('Your account has been banned'));
            return redirect()->route('customer.login', getParam());
        }

        // if logged in successfully
        return redirect($redirectUrl);
    }

    public function loginSubmit(Request $request, $domain)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $basic_settings = BasicSetting::where('user_id', $user->id)->select('is_recaptcha')->first();

        // at first, get the url from session which will be redirected after login
        if (Session::has('redirectTo')) {
            $redirectURL = Session::get('redirectTo');
        } else {
            $redirectURL = null;
        }
        $current_package = UserPermissionHelper::currentPackagePermission($user->id);
        if ($current_package) {
            $features = json_decode($current_package->features, true);
        } else {
            $features = [];
        }

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => in_array('Google Recaptcha', $features) && $basic_settings->is_recaptcha == 1 ? 'required|captcha' : '',
        ];
        $messages = [
            'g-recaptcha-response.required' => $keywords['Please verify that you are not a robot'] ?? __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => $keywords['Captcha error! try again later or contact site admin'] ?? __('Captcha error! try again later or contact site admin'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // get the email and password which has provided by the user
        $credentials = $request->only('email', 'password', 'user_id');
        // login attempt

        if (Auth::guard('customer')->attempt($credentials)) {
            $authUser = Auth::guard('customer')->user();
            // first, check whether the user's email address verified or not

            if ($authUser->email_verified_at == null) {
                Session::flash('error', $keywords['Please, verify your email address'] ?? __('Please, verify your email address'));
                // logout auth user as condition not satisfied
                Auth::guard('customer')->logout();
                return redirect()->back();
            }

            // second, check whether the user's account is active or not
            if ($authUser->status == 0) {
                Session::flash('error', $keywords['Sorry, your account has been deactivated'] ?? __('Sorry, your account has been deactivated'));
                // logout auth user as condition not satisfied
                Auth::guard('customer')->logout();
                return redirect()->back();
            }

            // otherwise, redirect auth user to next url
            if ($redirectURL == null) {
                return redirect()->route('customer.dashboard', getParam());
            } else {
                // before, redirect to next url forget the session value
                Session::forget('redirectTo');
                return redirect($redirectURL);
            }
        } else {
            Session::flash('error', $keywords['The provided credentials do not match our records'] ?? __('The provided credentials do not match our records'));
            return redirect()->back();
        }
    }
    public function forgetPassword($domain)
    {
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);
        $data['seo'] = SEO::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)
            ->select('forget_password_meta_keywords', 'forget_password_meta_description')
            ->first();
        return view('user-front.customer.forget-password', $data);
    }
    public function sendMail(Request $request)
    {
        $rootUser = getUser();
        $keywords = Common::get_keywords($rootUser->id);
        $basic_settings = BasicSetting::where('user_id', $rootUser->id)->select('is_recaptcha')->first();
        if (is_null($rootUser->first_name && $rootUser->last_name)) {
            $website_title = $rootUser->username;
        } else {
            $website_title =  $rootUser->first_name . ' ' . $rootUser->last_name;
        }
        $current_package = UserPermissionHelper::currentPackagePermission($rootUser->id);
        if ($current_package) {
            $features = json_decode($current_package->features, true);
        } else {
            $features = [];
        }

        $rules = [
            'email' => [
                'required',
                'email:rfc,dns',
            ],
            'g-recaptcha-response' => in_array('Google Recaptcha', $features) && $basic_settings->is_recaptcha == 1 ? 'required|captcha' : '',
        ];
        $messages = [
            'g-recaptcha-response.required' => $keywords['Please verify that you are not a robot'] ?? __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => $keywords['Captcha error! try again later or contact site admin'] ?? __('Captcha error! try again later or contact site admin'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = Customer::where([['email', $request->email], ['user_id', $rootUser->id]])->first();
        if (empty($user)) {
            Session::flash("error", $keywords['Sorry, no account found with this email address'] ?? __('Sorry, no account found with this email address'));
            return back()->withInput();
        }

        // first, get the mail template information from db
        $mailTemplate = UserEmailTemplate::where([['email_type', 'reset_password'], ['user_id', $rootUser->id]])->first();
        $mailSubject = $mailTemplate->email_subject;
        $mailBody = $mailTemplate->email_body;

        // second, send a password reset link to user via email
        $info = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $name = $user->first_name . ' ' . $user->last_name;
        $token = uniqid();
        $link = '<a href=' . route('customer.reset_password', ['token' => $token, getParam()]) . '>Click Here</a>';
        $mailBody = str_replace('{customer_name}', $name, $mailBody);
        $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $website_title, $mailBody);

        $data = [];
        $data['smtp_status'] = $info->is_smtp;
        $data['smtp_host'] = $info->smtp_host;
        $data['smtp_port'] = $info->smtp_port;
        $data['encryption'] = $info->encryption;
        $data['smtp_username'] = $info->smtp_username;
        $data['smtp_password'] = $info->smtp_password;

        //mail info in array
        $data['from_mail'] = $info->from_mail;
        $data['recipient'] = $request->email;
        $data['subject'] = $mailSubject;
        $data['body'] = $mailBody;
        // Send Mail
        if ($info->is_smtp == 1) {
            BasicMailer::sendMail($data);
            // store user email in session to use it later
            $user->verification_token = $token;
            $user->save();
            Session::flash('success', $keywords['We have sent a password reset link into your email address'] ?? __('We have sent a password reset link into your email address'));
        } else {
            Session::flash('success', $keywords['Mail could not sent'] ?? __('Mail could not sent'));
        }
        return redirect()->back();
    }


    public function resetPassword(Request $request, $domain)
    {
        if (empty($request->token)) {
            return redirect()->route('front.index');
        }
        $user = getUser();
        return view('user-front.customer.reset-password');
    }

    public function resetPasswordSubmit(Request $request, $domain)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $rules = [
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Customer::where('verification_token', $request->token)->where('user_id', $user->id)->first();
        if (empty($user)) {
            Session::flash('error', $keywords['Something went wrong'] ?? __('Something went wrong'));
            return back();
        }
        $user->update([
            'password' => Hash::make($request->new_password),
            'verification_token' => null,
        ]);
        Session::flash('success', $keywords['Updated Successfully'] ?? __('Updated Successfully'));
        return redirect()->route('customer.login', getParam());
    }

    public function signup($domain)
    {
        $user = getUser();
        $data['currentLanguage'] = Common::getUserCurrentLanguage(getUser()->id);
        $data['pageHeading'] = $this->getUserPageHeading($data['currentLanguage']);
        // when user have to redirect to check out page after login.
        $data['seo'] = SEO::where('language_id', $data['currentLanguage']->id)->where('user_id', $user->id)->first();
        $basic_settings = BasicSetting::where('user_id', $user->id)->select('is_recaptcha', 'google_recaptcha_site_key', 'google_recaptcha_secret_key')->first();
        if ($basic_settings->is_recaptcha == 1) {
            Config::set('captcha.sitekey', $basic_settings->google_recaptcha_site_key);
            Config::set('captcha.secret', $basic_settings->google_recaptcha_secret_key);
        }

        return view('user-front.customer.signup', $data);
    }

    public function signupSubmit(Request $request, $domain)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $basic_settings = BasicSetting::where('user_id', $user->id)->select('is_recaptcha')->first();
        $current_package = UserPermissionHelper::currentPackagePermission($user->id);
        if ($current_package) {
            $features = json_decode($current_package->features, true);
        } else {
            $features = [];
        }
        $rules = [
            'username' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($user) {
                    if (Customer::where('username', $value)->where('user_id', $user->id)->count() > 0) {
                        $fail($keywords['Username has already been taken'] ?? __('Username has already been taken'));
                    }
                }
            ],
            'email' => ['required', 'email', 'max:255', function ($attribute, $value, $fail) use ($user) {
                if (Customer::where('email', $value)->where('user_id', $user->id)->count() > 0) {
                    $fail($keywords['Email has already been taken'] ?? __('Email has already been taken'));
                }
            }],
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'g-recaptcha-response' => in_array('Google Recaptcha', $features) && $basic_settings->is_recaptcha == 1 ? 'required|captcha' : '',
        ];
        $messages = [
            'g-recaptcha-response.required' => $keywords['Please verify that you are not a robot'] ?? __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => $keywords['Captcha error! try again later or contact site admin'] ?? __('Captcha error! try again later or contact site admin'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $customer = new Customer;
        $customer->username = $request->username;
        $customer->email = $request->email;
        $customer->user_id = $user->id;
        $customer->password = Hash::make($request->password);
        // first, generate a random string
        $randStr = Str::random(20);
        // second, generate a token
        $token = md5($randStr . $request->username . $request->email);

        $customer->verification_token = $token;
        $customer->save();

        // send a mail to user for verify his/her email address
        if (is_null($user->first_name && $user->last_name)) {
            $website_title = $user->username;
        } else {
            $website_title =  $user->first_name . ' ' . $user->last_name;
        }

        $this->sendVerificationMail($request, $token, $user->id, $website_title);

        return redirect()
            ->back()
            ->with('sendmail', $keywords['We need to verify your email address. We have sent an email to'] ?? __('We need to verify your email address. We have sent an email to') . ' ' . $request->email . ' ' . $keywords['to verify your email address. Please click link in that email to continue'] ?? __('to verify your email address. Please click link in that email to continue'));
    }

    public function sendVerificationMail(Request $request, $token, $user_id, $website_title)
    {
        // first get the mail template information from db
        $mailTemplate = UserEmailTemplate::where([['email_type', 'email_verification'], ['user_id', $user_id]])->first();

        $mailSubject = !is_null($mailTemplate->email_subject) ? $mailTemplate->email_subject : "Verify your email address.";
        $mailBody = $mailTemplate->email_body;
        // second get the website title & mail's smtp information from db
        $info = DB::table('basic_extendeds')
            ->select('is_smtp', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $link = '<a href=' . route('customer.signup.verify', ['token' => $token, getParam()]) . '>Click Here</a>';
        if (!is_null($mailBody)) {
            // replace template's curly-brace string with actual data
            $mailBody = str_replace('{customer_name}', $request->username, $mailBody);
            $mailBody = str_replace('{verification_link}', $link, $mailBody);
            $mailBody = str_replace('{website_title}', $website_title, $mailBody);
        } else {
            $mailBody = "<p>Dear $request->username ,</p>
            <p>Please verify your email address before access your dashboard</p>
            $link
            <p>Thanks, $website_title</p>
            ";
        }

        /******** Send mail  ********/
        $data = [];
        $data['smtp_status'] = $info->is_smtp;
        $data['smtp_host'] = $info->smtp_host;
        $data['smtp_port'] = $info->smtp_port;
        $data['encryption'] = $info->encryption;
        $data['smtp_username'] = $info->smtp_username;
        $data['smtp_password'] = $info->smtp_password;

        //mail info in array
        $data['from_mail'] = $info->from_mail;
        $data['recipient'] = $request->email;
        $data['subject'] = $mailSubject;
        $data['body'] = $mailBody;
        BasicMailer::sendMail($data);
        return;
    }
    public function signupVerify(Request $request, $domain, $token)
    {
        $user_id = getUser()->id;
        $keywords = Common::get_keywords($user_id);
        try {
            $user = Customer::where('verification_token', $token)->firstOrFail();
            // after verify user email, put "null" in the "verification token"
            $user->update([
                'email_verified_at' => date('Y-m-d H:i:s'),
                'status' => 1,
                'verification_token' => null
            ]);
            Session::flash('success', $keywords['Your email has verified'] ?? __('Your email has verified'));
            // after email verification, authenticate this user
            Auth::guard('customer')->login($user);
            return redirect()->route('customer.dashboard', getParam());
        } catch (ModelNotFoundException $e) {
            Session::flash('error', $keywords['Could not verify your email'] ?? __('Could not verify your email'));
            return redirect()->route('customer.signup', getParam());
        }
    }

    public function redirectToDashboard($domain)
    {
        $data['author'] = getUser();
        $data['language'] = Common::getUserCurrentLanguage($data['author']->id);
        $data['pageHeading'] = $this->getUserPageHeading($data['language']);
        $customer = Auth::guard('customer')->user();
        $data['authUser'] = $customer;
        $customer_id = $customer->id;
        $data['orders'] = UserOrder::where('customer_id', $customer_id)->orderBy('created_at', 'desc')->limit(5)->get();
        $data['pending_orders'] = UserOrder::where([['customer_id', $customer_id], ['order_status', 'pending']])->count();
        $data['processing_orders'] = UserOrder::where([['customer_id', $customer_id], ['order_status', 'processing']])->count();
        $data['completed_orders'] = UserOrder::where([['customer_id', $customer_id], ['order_status', 'completed']])->count();

        return view('user-front.customer.dashboard', $data);
    }

    public function editProfile()
    {
        $user = getUser();
        $currentLanguage = Common::getUserCurrentLanguage($user->id);
        $queryResult['pageHeading'] = $this->getUserPageHeading($currentLanguage);
        $queryResult['authUser'] = Auth::guard('customer')->user();
        return view('user-front.customer.edit-profile', $queryResult);
    }

    public function updateProfile(Request $request)
    {
        $user_id = getUser()->id;
        $keywords = Common::get_keywords($user_id);
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'image' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail($keywords['Only png, jpg, jpeg image is allowed'] ?? __("Only png, jpg, jpeg image is allowed"));
                        }
                    }
                },
            ],
        ];

        $request->validate($rules);
        $authUser = Auth::guard('customer')->user();
        $directory = public_path('assets/user-front/images/users/');
        if ($request->hasFile('image')) {
            @unlink($directory . $authUser->image);
            $proPic = $request->file('image');
            $picName = Uploader::upload_picture($directory, $proPic);
        }
        $authUser->update($request->except('image') + [
            'image' => $request->exists('image') ? $picName : $authUser->image
        ]);
        Session::flash('success', $keywords['Updated successfully'] ?? __('Updated successfully'));
        return redirect()->back();
    }

    public function slider($domain, Request $request)
    {
        $filename = null;
        $request->validate([
            'file' => 'mimes:jpg,jpeg,png|required',
        ]);
        if ($request->hasFile('file')) {
            $filename = Uploader::upload_picture('assets/user/img/ads/slider-images', $request->file('file'));
        }
        return response()->json(['status' => 'success', 'file_id' => $filename]);
    }

    public function changePassword()
    {
        $user = getUser();
        $data['authUser'] = Auth::guard('customer')->user();
        $currentLanguage = Common::getUserCurrentLanguage($user->id);
        $data['pageHeading'] = $this->getUserPageHeading($currentLanguage);
        return view('user-front.customer.change-password', $data);
    }

    public function updatePassword(Request $request)
    {

        $user_id = getUser()->id;
        $keywords = Common::get_keywords($user_id);
        $rules = [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::guard('customer')->user()->password)) {
                        $fail($keywords['Your password was not updated, since the provided current password does not match'] ?? __('Your password was not updated, since the provided current password does not match'));
                    }
                }
            ],
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $user = Auth::guard('customer')->user();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        Session::flash('success', $keywords['Updated successfully'] ?? __('Updated successfully'));
        return redirect()->back();
    }
    public function logoutSubmit(Request $request, $domain)
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login', getParam());
    }

    public function shippingdetails($domain)
    {
        $user = getUser();
        $currentLanguage = Common::getUserCurrentLanguage($user->id);
        $pageHeading = $this->getUserPageHeading($currentLanguage);
        $bex = UserShopSetting::where('user_id', $user->id)->first();
        $user = Auth::guard('customer')->user();
        return view('user-front.customer.shipping_details', compact('user', 'pageHeading'));
    }
    public function shippingupdate(Request $request)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $request->validate([
            "shipping_fname" => 'required',
            "shipping_lname" => 'required',
            "shipping_email" => 'required',
            "shipping_number" => 'required',
            "shipping_city" => 'required',
            "shipping_address" => 'required',
            "shipping_country" => 'required',
        ]);
        Auth::guard('customer')->user()->update($request->all());
        Session::flash('success', $keywords['Updated successfully'] ?? __('Updated successfully'));
        return back();
    }
    public function billingdetails()
    {
        $queryResult['user'] = getUser();
        $currentLanguage = Common::getUserCurrentLanguage($queryResult['user']->id);
        $queryResult['pageHeading'] = $this->getUserPageHeading($currentLanguage);
        $bex = UserShopSetting::where('user_id', $queryResult['user']->id)->first();
        Auth::guard('customer')->user();
        return view('user-front.customer.billing_details', $queryResult);
    }
    public function billingupdate(Request $request)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $request->validate([
            "billing_fname" => 'required',
            "billing_lname" => 'required',
            "billing_email" => 'required',
            "billing_number" => 'required',
            "billing_city" => 'required',
            "billing_address" => 'required',
            "billing_country" => 'required',
        ]);
        Auth::guard('customer')->user()->update($request->all());
        Session::flash('success', $keywords['Updated successfully'] ?? __('Updated successfully'));
        return back();
    }

    public function customerOrders($domain)
    {
        $user = getUser();
        $data['author'] = getUser();
        $userCurrentLang = Common::getUserCurrentLanguage($user->id);
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);

        $bex = UserShopSetting::where('user_id', $user->id)->first();

        $data['orders'] = UserOrder::where('customer_id', Auth::guard('customer')->user()->id)->orderBy('id', 'DESC')->get();
        return view('user-front.customer.order', $data);
    }

    public function customerWishlist($domain)
    {
        $user = getUser();
        if (session()->has('user_lang') && !empty($user)) {
            $data['language'] = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($data['language'])) {
                $data['language'] = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $data['language']->code);
            }
        } else {
            $data['language'] = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $data['pageHeading'] = $this->getUserPageHeading($data['language']);

        $data['wishlist'] = CustomerWishList::where([['customer_id', Auth::guard('customer')->user()->id], ['user_id', $user->id]])
            ->with('item.itemContents')
            ->orderBy('id', 'DESC')->get();
        $data['user_id'] = $user->id;
        return view('user-front.customer.wishlist', $data);
    }

    public function removefromWish($domain, $id)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        if (env('DEMO_MODE') == 'active') {
            return response()->json(['message' => 'This is Demo version. You can not change anything.']);
        }
        $data['wishlist'] = CustomerWishList::findOrFail($id)->delete();
        Session::flash('error', $keywords['Item removed successfully'] ?? __('Item removed successfully'));
        return response()->json(['message' => 'remove_from_wishlist']);
    }

    public function orderdetails($domain, $id)
    {
        $user = getUser();
        $data['currentLanguage'] = Common::getUserCurrentLanguage(getUser()->id);
        $data['currentCurrency'] = Common::getUserCurrentCurrency(getUser()->id);

        $data['data'] = UserOrder::findOrFail($id);
        return view('user-front.customer.order_details', $data);
    }

    public function onlineSuccess()
    {
        Session::forget('user_coupon');
        Session::forget('coupon_amount');
        return view('user-front.success');
    }
}
