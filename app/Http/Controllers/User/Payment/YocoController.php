<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;
use Illuminate\Support\Facades\Http;

class YocoController extends Controller
{
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url)
    {
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'yoco'], ['user_id', getUser()->id]])->first();
        $paydata = json_decode($paymentMethod->information, true);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $paydata['secret_key'],
        ])->post('https://payments.yoco.com/api/checkouts', [
            'amount' => $_amount * 100,
            'currency' => 'ZAR',
            'successUrl' => $_success_url,
            'cancelUrl' => $_cancel_url
        ]);
        $responseData = $response->json();

        Session::put('user_request', $request->all());
        if (array_key_exists('redirectUrl', $responseData)) {
            Session::put('yoco_id', $responseData['id']);
            Session::put('s_key', $paydata['secret_key']);
            Session::put('amount', $_amount);
            //redirect for received payment from user
            return redirect($responseData["redirectUrl"]);
        } else {
            return redirect($_cancel_url);
        }
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user = getUser();
        $id = Session::get('yoco_id');
        $s_key = Session::get('s_key');
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'yoco'], ['user_id', getUser()->id]])->first();
        $paydata = $paymentMethod->convertAutoData();
        if ($id && $paydata['secret_key'] == $s_key) {
            $txnId = UserPermissionHelper::uniqidReal(8);
            $chargeId = $request->paymentId;
            $order = Common::saveOrder($requestData, $txnId, $chargeId,'Completed', 'online', $user->id);
            $order_id = $order->id;

            Common::saveOrderedItems($order_id);
            Common::generateInvoice($order, $user);
            $order = UserOrder::where('id', $order_id)->first();
            Common::OrderCompletedMail($order, $user);
            session()->flash('success', __('successful_payment'));
            Session::forget('user_request');
            Session::forget('user_amount');
            Session::forget('cart');
            Session::forget('user_paypal_payment_id');
            return redirect()->route('customer.success.page', getParam());
        } else {
            session()->flash('message', __('cancel_payment'));
            session()->flash('alert-type', 'warning');
            return redirect()->route('customer.itemcheckout.cancel', getParam());
        }
    }
}
