<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\AuthorizenetController;
use App\Http\Controllers\Payment\FlutterWaveController;
use App\Http\Controllers\Payment\InstamojoController;
use App\Http\Controllers\Payment\IyzicoController;
use App\Http\Controllers\Payment\MercadopagoController;
use App\Http\Controllers\Payment\MidtransController;
use App\Http\Controllers\Payment\MollieController;
use App\Http\Controllers\Payment\MyFatoorahController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\PaystackController;
use App\Http\Controllers\Payment\PaytabsController;
use App\Http\Controllers\Payment\PaytmController;
use App\Http\Controllers\Payment\PerfectMoneyController;
use App\Http\Controllers\Payment\PhonePeController;
use App\Http\Controllers\Payment\RazorpayController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\Payment\ToyyibpayController;
use App\Http\Controllers\Payment\XenditController;
use App\Http\Controllers\Payment\YocoController;
use App\Http\Helpers\Common;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Requests\Checkout\CheckoutRequest;
use App\Models\Language;
use App\Models\Membership;
use App\Models\OfflineGateway;
use App\Models\Package;
use App\Models\User;
use App\Models\User\BasicSetting;
use App\Models\User\UserCurrency;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserFooter;
use App\Models\User\UserMenu;
use App\Models\User\UserPaymentGeteway;
use App\Models\User\UserPermission;
use App\Models\User\UserShopSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function checkout(CheckoutRequest $request)
    {
        $offline_payment_gateways = OfflineGateway::all()->pluck('name')->toArray();
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $bs = $currentLang->basic_setting;
        $be = $currentLang->basic_extended;
        $request['status'] = 1;
        $request['mode'] = 'online';
        $request['receipt_name'] = null;
        Session::put('paymentFor', 'membership');
        $title = "You are purchasing a membership";
        $description = "Congratulation you are going to join our membership.Please make a payment for confirming your membership now!";
        if ($request->package_type == "trial") {
            $package = Package::find($request['package_id']);
            $request['price'] = 0.00;
            $request['payment_method'] = "-";
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = "Trial";
            $user = $this->store($request->all(), $transaction_id, $transaction_details, $request->price, $be, $request->password);

            $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
            $activation = Carbon::parse($lastMemb->start_date);
            $expire = Carbon::parse($lastMemb->expire_date);
            $file_name = Common::makeInvoice($request->all(), "membership", $user, $request->password, $request['price'], "Trial", $request['phone'], $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title,1);

            $mailer = new MegaMailer();
            $data = [
                'toMail' => $user->email,
                'toName' => $user->fname,
                'username' => $user->username,
                'package_title' => $package->title,
                'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
                'activation_date' => $activation->toFormattedDateString(),
                'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                'membership_invoice' => $file_name,
                'website_title' => $bs->website_title,
                'templateType' => 'registration_with_trial_package',
                'type' => 'registrationWithTrialPackage'
            ];
            $mailer->mailFromAdmin($data);

            session()->flash('success', __('successful_payment'));
            return redirect()->route('membership.trial.success');
        } elseif ($request->price == 0) {
            $package = Package::find($request['package_id']);
            $request['price'] = 0.00;
            $request['payment_method'] = "-";
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = "Free";
            $user = $this->store($request->all(), $transaction_id, $transaction_details, $request->price, $be, $request->password);


            $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
            $activation = Carbon::parse($lastMemb->start_date);
            $expire = Carbon::parse($lastMemb->expire_date);
            $file_name = Common::makeInvoice($request->all(), "membership", $user, $request->password, $request['price'], "Free", $request['phone'], $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title,1);

            $mailer = new MegaMailer();
            $data = [
                'toMail' => $user->email,
                'toName' => $user->fname,
                'username' => $user->username,
                'package_title' => $package->title,
                'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
                'activation_date' => $activation->toFormattedDateString(),
                'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                'membership_invoice' => $file_name,
                'website_title' => $bs->website_title,
                'templateType' => 'registration_with_free_package',
                'type' => 'registrationWithFreePackage'
            ];
            $mailer->mailFromAdmin($data);


            session()->flash('success', __('successful_payment'));
            return redirect()->route('success.page');
        } elseif ($request->payment_method == "Paypal") {
            $amount = round($request->price, 2);
            $paypal = new PaypalController();
            $cancel_url = route('membership.paypal.cancel');
            $success_url = route('membership.paypal.success');
            return $paypal->paymentProcess($request, $amount, $title, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Stripe") {
            $amount = round($request->price, 2);
            $stripe = new StripeController();
            $cancel_url = route('membership.stripe.cancel');
            return $stripe->paymentProcess($request, $amount, $title, NULL, $cancel_url);
        } elseif ($request->payment_method == "Paytm") {
            if ($be->base_currency_text != "INR") {
                return redirect()->back()->with('error', __('only_paytm_INR'))->withInput($request->all());
            }
            $amount = $request->price;
            $item_number = uniqid('paytm-') . time();
            $callback_url = route('membership.paytm.status');
            $paytm = new PaytmController();
            return $paytm->paymentProcess($request, $amount, $item_number, $callback_url);
        } elseif ($request->payment_method == "Paystack") {
            if ($be->base_currency_text != "NGN") {
                return redirect()->back()->with('error', __('only_paystack_NGN'))->withInput($request->all());
            }
            $amount = $request->price * 100;
            $email = $request->email;
            $success_url = route('membership.paystack.success');
            $payStack = new PaystackController();
            return $payStack->paymentProcess($request, $amount, $email, $success_url, $be);
        } elseif ($request->payment_method == "Razorpay") {
            if ($be->base_currency_text != "INR") {
                return redirect()->back()->with('error', __('only_razorpay_INR'))->withInput($request->all());
            }
            $amount = $request->price;
            $item_number = uniqid('razorpay-') . time();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.razorpay.success');
            $razorpay = new RazorpayController();
            return $razorpay->paymentProcess($request, $amount, $item_number, $cancel_url, $success_url, $title, $description, $bs, $be);
        } elseif ($request->payment_method == "Instamojo") {
            if ($be->base_currency_text != "INR") {
                return redirect()->back()->with('error', __('only_instamojo_INR'))->withInput($request->all());
            }
            if ($request->price < 9) {
                session()->flash('warning', __('Minimum 10 INR required for this payment gateway'));
                return back()->withInput($request->all());
            }
            $amount = $request->price;
            $success_url = route('membership.instamojo.success');
            $cancel_url = route('membership.instamojo.cancel');
            $instaMojo = new InstamojoController();
            return $instaMojo->paymentProcess($request, $amount, $success_url, $cancel_url, $title, $be);
        } elseif ($request->payment_method == "Mercado Pago") {
            if ($be->base_currency_text != "BRL" && $be->base_currency_text != "ARS") {
                return redirect()->back()->with('error', __('only_mercadopago_BRL'))->withInput($request->all());
            }
            $amount = $request->price;
            $email = $request->email;
            $success_url = route('membership.mercadopago.success');
            $cancel_url = route('membership.mercadopago.cancel');
            $mercadopagoPayment = new MercadopagoController();
            return $mercadopagoPayment->paymentProcess($request, $amount, $success_url, $cancel_url, $email, $title, $description, $be);
        } elseif ($request->payment_method == "Flutterwave") {
            $available_currency = array(
                'BIF',
                'CAD',
                'CDF',
                'CVE',
                'EUR',
                'GBP',
                'GHS',
                'GMD',
                'GNF',
                'KES',
                'LRD',
                'MWK',
                'NGN',
                'RWF',
                'SLL',
                'STD',
                'TZS',
                'UGX',
                'USD',
                'XAF',
                'XOF',
                'ZMK',
                'ZMW',
                'ZWD'
            );
            if (!in_array($be->base_currency_text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = $request->price;
            $email = $request->email;
            $item_number = uniqid('flutterwave-') . time();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.flutterwave.success');
            $flutterWave = new FlutterWaveController();
            return $flutterWave->paymentProcess($request, $amount, $email, $item_number, $success_url, $cancel_url, $be);
        } elseif ($request->payment_method == "Authorize.net") {
            $available_currency = array('USD', 'CAD', 'CHF', 'DKK', 'EUR', 'GBP', 'NOK', 'PLN', 'SEK', 'AUD', 'NZD');
            if (!in_array($be->base_currency_text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = $request->price;
            $cancel_url = route('membership.anet.cancel');
            $anetPayment = new AuthorizenetController();
            return $anetPayment->paymentProcess($request, $amount, $cancel_url, $title, $be);
        } elseif ($request->payment_method == "Mollie Payment") {
            $available_currency = array('AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR');
            if (!in_array($be->base_currency_text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = round(($request->price / $be->base_currency_rate), 2);
            $success_url = route('membership.mollie.success');
            $cancel_url = route('membership.mollie.cancel');
            $molliePayment = new MollieController();
            return $molliePayment->paymentProcess($request, $amount, $success_url, $cancel_url, $title, $be);
        } elseif ($request->payment_method == "Yoco") {
            if ($be->base_currency_text != "ZAR") {
                return redirect()->back()->with('error', __('Invalid Currency for Yoco Payment'))->withInput($request->all());
            }
            $amount = round($request->price, 2);
            $data = new YocoController();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.yoco.success');
            return $data->paymentProcess($request, $amount, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Xendit") {
            $allowed_currency = array('IDR', 'PHP', 'USD', 'SGD', 'MYR');
            if (!in_array($be->base_currency_text, $allowed_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }

            $amount = round($request->price, 2);
            $data = new XenditController();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.xendit.success');
            return $data->paymentProcess($request, $amount, $success_url, $cancel_url, $be, $bs);
        } elseif ($request->payment_method == "Perfect money") {
            $allowed_currency = array('USD');
            if (!in_array($be->base_currency_text, $allowed_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }

            $amount = round($request->price, 2);
            $data = new PerfectMoneyController();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.perfect_money.success');
            $website_title =  $bs->website_title;
            return $data->paymentProcess($request, $amount, $success_url, $cancel_url, $be, $website_title);
        } elseif ($request->payment_method == "Midtrans") {
            $allowed_currency = array('IDR');
            if (!in_array($be->base_currency_text, $allowed_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }

            $amount = round($request->price, 2);
            $data = new MidtransController();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.midtrans.success');
            $website_title =  $bs->website_title;
            return $data->paymentProcess($request, $amount, $success_url, $cancel_url, $be, $website_title);
        } elseif ($request->payment_method == "Iyzico") {
            $allowed_currency = array('TRY');
            if (!in_array($be->base_currency_text, $allowed_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }

            $amount = round($request->price, 2);
            $data = new IyzicoController();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.iyzico.success');
            return $data->paymentProcess($request, $amount, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Toyyibpay") {
            $allowed_currency = array('RM');
            if (!in_array($be->base_currency_text, $allowed_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }

            $amount = round($request->price, 2);
            $data = new ToyyibpayController();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.toyyibpay.success');
            return $data->paymentProcess($request, $amount, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Paytabs") {
            $paytabInfo = paytabInfo('admin', null);
            if ($be->base_currency_text != $paytabInfo['currency']) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }

            $amount = round($request->price, 2);
            $data = new PaytabsController();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.paytabs.success');
            return $data->paymentProcess($request, $amount, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Phonepe") {
            $allowed_currency = array('INR');
            if (!in_array($be->base_currency_text, $allowed_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }

            $amount = round($request->price, 2);
            $data = new PhonePeController();
            $cancel_url = route('membership.cancel');
            $success_url = route('membership.phonepe.success');
            return $data->paymentProcess($request, $amount, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Myfatoorah") {
            $allowed_currency = array('KWD', 'SAR', 'BHD', 'AED', 'QAR', 'OMR', 'JOD');
            if (!in_array($be->base_currency_text, $allowed_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }

            $amount = round($request->price, 2);
            $data = new MyFatoorahController();
            $cancel_url = route('membership.cancel');
            return $data->paymentProcess($request, $amount, $cancel_url);
        } elseif (in_array($request->payment_method, $offline_payment_gateways)) {
            $request['mode'] = 'offline';
            $request['status'] = 0;
            $request['receipt_name'] = null;
            if ($request->has('receipt')) {
                $filename = time() . '.' . $request->file('receipt')->getClientOriginalExtension();
                $directory = "./assets/front/img/membership/receipt";
                if (!file_exists($directory)) mkdir($directory, 0775, true);
                $request->file('receipt')->move($directory, $filename);
                $request['receipt_name'] = $filename;
            }
            $amount = $request->price;
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = "offline";
            $password = $request->password;
            $this->store($request, $transaction_id, json_encode($transaction_details), $amount, $be, $password);
            return redirect()->route('membership.offline.success');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($request, $transaction_id, $transaction_details, $amount, $be, $password)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $bs = $currentLang->basic_setting;
        $token = md5(time() . $request['username'] . $request['email']);
        $verification_link = "<a href='" . url('register/mode/' . $request['mode'] . '/verify/' . $token) . "'>" . "<button type=\"button\" class=\"btn btn-primary\">Click Here</button>" . "</a>";

        $user = User::where('username', $request['username']);
        if ($user->count() == 0) {
            $user = User::create([
                'shop_name' => $request['shop_name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'username' => $request['username'],
                'password' => bcrypt($password),
                'status' => $request["status"],
                'address' => $request["address"] ? $request["address"] : null,
                'city' => $request["city"] ? $request["city"] : null,
                'state' => $request["district"] ? $request["district"] : null,
                'country' => $request["country"] ? $request["country"] : null,
                'verification_link' => $token,
                'category_id' => $request['category'],
            ]);

            //customize
            $langCount = User\Language::where('user_id', $user->id)->count();
            $adminLangs = Language::get();
            if ($langCount == 0) {
                //create language for admin
                foreach ($adminLangs as $lang) {
                    $language = User\Language::create([
                        'name' => $lang->name,
                        'code' => $lang->code,
                        'is_default' => $lang->is_default,
                        'dashboard_default' => $lang->dashboard_default,
                        'rtl' => $lang->rtl,
                        'type' => 'admin',
                        'user_id' => $user->id,
                        'keywords' => $lang->customer_keywords
                    ]);

                    $menus = array(
                        array("text" => "Home", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "home"),
                        array("text" => "Shop", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "shop"),
                        array("text" => "Blog", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "blog"),
                        array("text" => "FAQ", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "faq"),
                        array("text" => "Contact", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "contact"),
                        array("text" => "About Us", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "about")
                    );

                    //create user default menus
                    UserMenu::create([
                        'user_id' => $user->id,
                        'language_id' => $language->id,
                        'menus' => json_encode($menus, true),
                    ]);
                }
            }

            //create user default currency usd
            $currCount = UserCurrency::where('user_id', $user->id)->where('is_default', 1)->count();
            if ($currCount == 0) {
                UserCurrency::create([
                    'text' => 'USD',
                    'symbol' => '$',
                    'value' => '1',
                    'is_default' => 1,
                    'text_position' => 'left',
                    'symbol_position' => 'left',
                    'user_id' => $user->id,
                ]);
            }

            if (is_array($request)) {
                if (array_key_exists('conversation_id', $request)) {
                    $conversation_id = $request['conversation_id'];
                } else {
                    $conversation_id = null;
                }
            } else {
                $conversation_id = null;
            }

            if (!isset($request["status"])) {
                $status = 0;
            } else {
                $status = $request["status"];
            }

            //create memership
            Membership::create([
                'price' => $amount,
                'currency' => $be->base_currency_text ? $be->base_currency_text : "USD",
                'currency_symbol' => $be->base_currency_symbol ? $be->base_currency_symbol : $be->base_currency_text,
                'payment_method' => $request["payment_method"],
                'transaction_id' => $transaction_id ? $transaction_id : 0,
                'status' => $status,
                'is_trial' => $request["package_type"] == "regular" ? 0 : 1,
                'trial_days' => $request["package_type"] == "regular" ? 0 : $request["trial_days"],
                'receipt' => $request["receipt_name"] ? $request["receipt_name"] : null,
                'transaction_details' => $transaction_details ? $transaction_details : null,
                'settings' => json_encode($be),
                'package_id' => $request['package_id'],
                'user_id' => $user->id,
                'start_date' => Carbon::parse($request['start_date']),
                'expire_date' => Carbon::parse($request['expire_date']),
                'conversation_id' => $conversation_id
            ]);

            // create user permission form package
            $package = Package::findOrFail($request['package_id']);
            $features = json_decode($package->features, true);
            $features[] = "Contact";
            $features[] = "Footer Mail";
            $features[] = "Profile Listing";
            UserPermission::create([
                'package_id' => $request['package_id'],
                'user_id' => $user->id,
                'permissions' => json_encode($features)
            ]);

            //create user basic settings table
            BasicSetting::create([
                'user_id' => $user->id,
                'theme' => 'vegetables',
            ]);

            // create payment gateways
            $payment_keywords = ['flutterwave', 'razorpay', 'paytm', 'paystack', 'instamojo', 'stripe', 'paypal', 'mollie', 'mercadopago', 'authorize.net', 'midtrans', 'iyzico', 'toyyibpay', 'phonepe', 'yoco', 'xendit', 'myfatoorah', 'paytabs', 'perfect_money'];
            foreach ($payment_keywords as $key => $value) {
                UserPaymentGeteway::create([
                    'title' => null,
                    'user_id' => $user->id,
                    'details' => null,
                    'keyword' => $value,
                    'subtitle' => null,
                    'name' => ucfirst($value),
                    'type' => 'automatic',
                    'information' => null
                ]);
            }

            // create email template
            $this->storeEmailTemplate($user->id);

            //create user shop settings
            $shop_settings = new UserShopSetting();
            $shop_settings->user_id = $user->id;
            $shop_settings->catalog_mode = 0;
            $shop_settings->item_rating_system = 1;
            $shop_settings->top_rated_count = 5;
            $shop_settings->top_selling_count = 5;
            $shop_settings->save();

            $footer = new UserFooter();
            $footer->footer_text = 'lorem ispum dummy text.';
            $footer->user_id = $user->id;
            $footer->language_id = $language->id;
            $footer->useful_links_title = 'Useful Links';
            $footer->copyright_text = null;
            $footer->footer_logo =  null;
            $footer->background_image =  null;
            $footer->save();

            //send verification email to user
            $mailer = new MegaMailer();
            $data = [
                'toMail' => $user->email,
                'toName' => $user->first_name,
                'customer_name' => $user->first_name,
                'verification_link' => $verification_link,
                'website_title' => $bs->website_title,
                'templateType' => 'email_verification',
                'type' => 'emailVerification'
            ];
            $mailer->mailFromAdmin($data);
        } else {
            $user = $user->first();
        }
        return $user;
    }

    private function storeEmailTemplate($user_id)
    {
        $templates = [
            'email_verification' => [
                'email_subject' => 'Please Verify Your Email Address',
                'email_body' => '<p>Dear {customer_name},</p>
                <p>Thank you for signing up with {website_title}! To complete your registration and activate your account, please verify your email address by clicking the link below:</p>
                <p>{verification_link}</p>
                <p>If you didn’t sign up for an account with {website_title}, please ignore this message.</p>
                <p>If you have any questions or need assistance, feel free to contact us.</p>
                <p>Best regards,</p>
                <p><strong>{website_title}</strong></p>'
            ],
            'product_order' => [
                'email_subject' => 'Product Order Confirmation',
                'email_body' => "<p>Dear {customer_name},</p>
                <p>Thank you for your order with {website_title}! We're excited to let you know that we’ve received your order. Below, you’ll find the details of your purchase.</p>
                <h3><strong>Order Summary</strong></h3>
                <ul>
                <li><strong>Order Number:</strong> {order_number}</li>
                <li><strong>Order Details:</strong> {order_link}</li>
                </ul>
                <h3><strong>Shipping Information</strong></h3>
                <ul>
                <li><strong>Name:</strong> {shipping_fname} {shipping_lname}</li>
                <li><strong>Address:</strong> {shipping_address}, {shipping_city}, {shipping_country}</li>
                <li><strong>Phone Number:</strong> {shipping_number}</li>
                </ul>
                <h3><strong>Billing Information</strong></h3>
                <ul>
                <li><strong>Name:</strong> {billing_fname} {billing_lname}</li>
                <li><strong>Address:</strong> {billing_address}, {billing_city}, {billing_country}</li>
                <li><strong>Phone Number:</strong> {billing_number}</li>
                </ul>
                <p>We will process and ship your order shortly, and you’ll receive an email notification once your items are on the way.</p>
                <p>If you have any questions or need assistance, please feel free to contact our customer support team.</p>
                <p>Thank you for choosing {website_title}!</p>
                <p>Best regards,<br><strong>{website_title}</strong></p>"
            ],
            'reset_password' => [
                'email_subject' => 'Reset Your Password',
                'email_body' => "<p>Dear {customer_name},</p>
                <p>We received a request to reset your password for your account on {website_title}. To proceed, please click the link below to reset your password:</p>
                <p>{password_reset_link}</p>
                <p>If you didn’t request a password reset, please ignore this email. Your account remains secure.</p>
                <p>If you need further assistance, feel free to reach out to our support team.</p>
                <p>Best regards,<br><strong>{website_title}</strong></p>"
            ],
            'product_order_status' => [
                'email_subject' => 'Product Order Status',
                'email_body' => "<p>Dear {customer_name},</p>
                <p>We wanted to provide you with an update on the status of your order with {website_title}.</p>
                <h3><strong>Order Status: {order_status}</strong></h3>
                <p>We are working hard to ensure your order is processed and shipped as quickly as possible. You can always log in to your account for the latest updates on your order.</p>
                <p>If you have any questions or need assistance, don’t hesitate to contact our customer support team. We’re here to help!</p>
                <p>Thank you for shopping with {website_title}.</p>
                <p>Best regards,<br><strong>{website_title}</strong></p>"
            ]
        ];

        foreach ($templates as $key => $val) {
            UserEmailTemplate::create([
                'user_id' => $user_id,
                'email_type' => $key,
                'email_subject' => $val['email_subject'],
                'email_body' => $val['email_body'],
            ]);
        }
    }

    public function offlineSuccess()
    {
        return view('front.offline-success');
    }

    public function trialSuccess()
    {
        return view('front.trial-success');
    }

    public function cancelPayment()
    {
        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', __('cancel_payment'));
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('user.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }
}
