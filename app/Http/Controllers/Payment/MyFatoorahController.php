<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Helpers\Common;
use App\Http\Helpers\UserPermissionHelper;
use Illuminate\Support\Facades\Config;
use App\Http\Helpers\MegaMailer;
use Basel\MyFatoorah\MyFatoorah;
use App\Models\Package;
use App\Models\Language;
use Carbon\Carbon;
use Session;
use Auth;

class MyFatoorahController extends Controller
{
    public $myfatoorah;
    public function __construct()
    {
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $be = $currentLang->basic_extended;

        $paymentMethod = PaymentGateway::where('keyword', 'myfatoorah')->first();
        $paydata = $paymentMethod->convertAutoData();
        Config::set('myfatorah.token', $paydata['token']);
        Config::set('myfatorah.DisplayCurrencyIso', $be->base_currency_text);
        Config::set('myfatorah.CallBackUrl', route('myfatoorah.success'));
        Config::set('myfatorah.ErrorUrl', route('myfatoorah.cancel'));
        if ($paydata['sandbox_status'] == 1) {
            $this->myfatoorah = MyFatoorah::getInstance(true);
        } else {
            $this->myfatoorah = MyFatoorah::getInstance(false);
        }
    }

    public function paymentProcess(Request $request, $_amount, $_cancel_url)
    {
        Session::put('request', $request->all());

        /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        ~~~~~~~~~~~ Payment Gateway Info ~~~~~~~~~~~
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
        $paymentMethod = PaymentGateway::where('keyword', 'myfatoorah')->first();
        $paydata = $paymentMethod->convertAutoData();
        $random_1 = rand(999, 9999);
        $random_2 = rand(9999, 99999);
        $paymentFor = Session::get('paymentFor');
        $name = $paymentFor == 'membership' ? $request->first_name . ' ' . $request->last_name : Auth::guard('web')->user()->first_name . ' ' . Auth::guard('web')->user()->first_name;

        $phone = $paymentFor == 'membership' ? $request->phone : Auth::guard('web')->user()->phone;
        $result = $this->myfatoorah->sendPayment(
            $name,
            $_amount,
            [
                'CustomerMobile' => $paydata['sandbox_status'] == 1 ? '56562123544' : $phone,
                'CustomerReference' => "$random_1",  //orderID
                'UserDefinedField' => "$random_2", //clientID
                "InvoiceItems" => [
                    [
                        "ItemName" => "Package Purchase",
                        "Quantity" => 1,
                        "UnitPrice" => $_amount
                    ]
                ]
            ]
        );
        if ($result && $result['IsSuccess'] == true) {
            $request->session()->put('myfatoorah_payment_type', 'buy_plan');
            return redirect($result['Data']['InvoiceURL']);
        } else {
            return redirect($_cancel_url);
        }
    }


    public function successPayment(Request $request)
    {
        $requestData = Session::get('request');
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $be = $currentLang->basic_extended;
        $bs = $currentLang->basic_setting;
        /** clear the session payment ID **/

        if (!empty($request->paymentId)) {
            $paymentFor = Session::get('paymentFor');
            $package = Package::find($requestData['package_id']);
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = null;
            if ($paymentFor == "membership") {
                $amount = $requestData['price'];
                $password = $requestData['password'];
                $checkout = new CheckoutController();
                $requestData['status'] = 1;
                $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);

                $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                $activation = Carbon::parse($lastMemb->start_date);
                $expire = Carbon::parse($lastMemb->expire_date);
                $file_name = Common::makeInvoice($requestData, "membership", $user, $password, $amount, "Yoco", $requestData['phone'], $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title, 1);

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
                    'templateType' => 'registration_with_premium_package',
                    'type' => 'registrationWithPremiumPackage'
                ];
                $mailer->mailFromAdmin($data);

                session()->flash('success', __('successful_payment'));
                Session::forget('request');
                Session::forget('paymentFor');
                return [
                    'status' => 'success'
                ];
            } elseif ($paymentFor == "extend") {
                $amount = $requestData['price'];
                $password = uniqid('qrcode');
                $checkout = new UserCheckoutController();
                $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);

                $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                $activation = Carbon::parse($lastMemb->start_date);
                $expire = Carbon::parse($lastMemb->expire_date);
                $file_name = Common::makeInvoice($requestData, "extend", $user, $password, $amount, $requestData["payment_method"], $user->phone_number, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title, 1);

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
                    'templateType' => 'membership_extend',
                    'type' => 'membershipExtend'
                ];
                $mailer->mailFromAdmin($data);


                session()->flash('success', __('successful_payment'));
                Session::forget('request');
                Session::forget('paymentFor');
                return [
                    'status' => 'success'
                ];
            } else {
                return [
                    'status' => 'fail'
                ];
            }
        } else {
            return [
                'status' => 'fail'
            ];
        }
    }
}
