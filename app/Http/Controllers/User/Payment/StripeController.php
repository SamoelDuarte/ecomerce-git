<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use Illuminate\Support\Facades\Config;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class StripeController extends Controller
{
    public function __construct()
    {
        //Set Spripe Keys
        $stripe = UserPaymentGeteway::where([['keyword', 'stripe'], ['user_id', getUser()->id]])->first();
        $stripeConf = json_decode($stripe->information, true);
        Config::set('services.stripe.key', $stripeConf["key"]);
        Config::set('services.stripe.secret', $stripeConf["secret"]);
    }

    public function paymentProcess(Request $request, $_amount, $_title, $_success_url, $_cancel_url, $currency_code)
    {
        $title = $_title;
        $price = $_amount;
        $price = round($price, 2);
        $cancel_url = $_cancel_url;
        Session::put('user_request', $request->all());
        $stripe = Stripe::make(Config::get('services.stripe.secret'));

        if (!isset($request->stripeToken)) {
            return back()->with('error', 'Token Problem With Your Token.');
        }
        $charge = $stripe->charges()->create([
            'card' => $request->stripeToken,
            'currency' =>  $currency_code,
            'amount' => $price,
            'description' => $title,
        ]);
        if ($charge['status'] == 'succeeded') {
            $user = getUser();
            $txnId = UserPermissionHelper::uniqidReal(8);
            $chargeId = $request->paymentId;
            $order = Common::saveOrder($request, $txnId, $chargeId, 'Completed', 'online', $user->id);
            $order_id = $order->id;

            Common::saveOrderedItems($order_id);
            Common::generateInvoice($order, $user);
            $order = UserOrder::where('id', $order_id)->first();
            Common::OrderCompletedMail($order, $user);
            session()->flash('success', __('successful_payment'));
            Session::forget('user_request');
            Session::forget('cart');
            Session::forget('user_amount');
            Session::forget('user_paypal_payment_id');
            return redirect()->route('customer.success.page', getParam());
        }
        return redirect($cancel_url)->with('error', __('Please Enter Valid Credit Card Informations.'));
    }
}
