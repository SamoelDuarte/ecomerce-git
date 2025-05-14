<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;
use Midtrans\Snap;
use Midtrans\Config as MidtransConfig;

class MidtransController extends Controller
{
    public function paymentProcess(Request $request, $_amount, $title, $_success_url, $_cancel_url)
    {
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'midtrans'], ['user_id', getUser()->id]])->first();
        $paydata = json_decode($paymentMethod->information, true);

        $name = $request->billing_fname . ' ' . $request->billing_lname;
        $email = $request->billing_email;
        $phone = $request->billing_number;

        Session::put('user_request', $request->all());
        // will come from database
        MidtransConfig::$serverKey = $paydata['server_key'];
        MidtransConfig::$isProduction = $paydata['is_production'] == 0 ? true : false;
        MidtransConfig::$isSanitized = true;
        MidtransConfig::$is3ds = true;
        $token = uniqid();
        Session::put('token', $token);
        $params = [
            'transaction_details' => [
                'order_id' => $token,
                'gross_amount' => $_amount * 1000, // will be multiplied by 1000
            ],
            'customer_details' => [
                'first_name' => $name,
                'email' => $email,
                'phone' => $phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        // put some data in session before redirect to midtrans url
        if (
            $paydata['is_production'] == 1
        ) {
            $is_production = $paydata['is_production'];
        }

        $data['snapToken'] = $snapToken;
        $data['is_production'] = $is_production;
        $data['success_url'] = $_success_url;
        $data['_cancel_url'] = $_cancel_url;
        $data['client_key'] = $paydata['server_key'];
        $data['title'] = $title;
        Session::put('midtrans_payment_type', 'product_purchase');
        Session::put('getParam', getParam());
        return view('payments.midtrans-membership', $data);
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user = getUser();
        $token = Session::get('token');
        if ($request->status_code == 200 && $token == $request->order_id) {
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
            session()->flash('warning', __('cancel_payment'));
            return redirect()->route('customer.itemcheckout.cancel', getParam());
        }
    }
}
