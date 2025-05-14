<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\Language;
use App\Models\Package;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Helpers\Common;
use Carbon\Carbon;
use App\Http\Helpers\MegaMailer;
use App\Traits\HelperTrait;

class MidtransBankNotifyController extends Controller
{
    public function bank_notify(Request $request)
    {
        $midtrans_payment_type = Session::get('midtrans_payment_type');
        if ($midtrans_payment_type == 'membership') {
            $requestData = Session::get('request');
            $currentLang = session()->has('lang') ?
                (Language::where('code', session()->get('lang'))->first())
                : (Language::where('is_default', 1)->first());
            $be = $currentLang->basic_extended;
            $bs = $currentLang->basic_setting;
            /** clear the session payment ID **/
            $cancel_url = route('membership.cancel');

            $token = Session::get('token');
            if ($request->status_code == 200 && $token == $request->order_id) {
                $paymentFor = Session::get('paymentFor');
                $package = Package::find($requestData['package_id']);
                $transaction_id = UserPermissionHelper::uniqidReal(8);
                $transaction_details = null;
                if ($paymentFor == "membership") {
                    $amount = $requestData['price'];
                    $password = $requestData['password'];
                    $checkout = new CheckoutController();
                    $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);

                    $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                    $activation = Carbon::parse($lastMemb->start_date);
                    $expire = Carbon::parse($lastMemb->expire_date);
                    $file_name = Common::makeInvoice($requestData, "membership", $user, $password, $amount, "Yoco", $requestData['phone'], $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title,1);

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
                    return redirect()->route('success.page');
                } elseif ($paymentFor == "extend") {
                    $amount = $requestData['price'];
                    $password = uniqid('qrcode');
                    $checkout = new UserCheckoutController();
                    $user = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);

                    $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                    $activation = Carbon::parse($lastMemb->start_date);
                    $expire = Carbon::parse($lastMemb->expire_date);
                    $file_name = Common::makeInvoice($requestData, "extend", $user, $password, $amount, $requestData["payment_method"], $user->phone_number, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title,1);

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
                    return redirect()->route('success.page');
                }
            }
            return redirect($cancel_url);
        }
    }
}
