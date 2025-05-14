<?php

namespace App\Http\Controllers\User\Payment;

use Instamojo\Instamojo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;
use Exception;

class InstamojoController extends Controller
{
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title)
    {
        $data = UserPaymentGeteway::where('keyword', 'instamojo')->where('user_id', getUser()->id)->first();

        $paydata = $data->convertAutoData();
        $cancel_url = $_cancel_url;
        $notify_url = $_success_url;

        if ($paydata['sandbox_check'] == 1) {
            $api = new Instamojo($paydata['key'], $paydata['token'], 'https://test.instamojo.com/api/1.1/');
        } else {
            $api = new Instamojo($paydata['key'], $paydata['token']);
        }

        try {
            $response = $api->paymentRequestCreate(array(
                "purpose" => $_title,
                "amount" => $_amount,
                "send_email" => false,
                "email" => null,
                "redirect_url" => $notify_url
            ));
            $redirect_url = $response['longurl'];
            Session::put("user_request", $request->all());
            Session::put('user_payment_id', $response['id']);
            Session::put('user_success_url', $notify_url);
            Session::put('user_cancel_url', $cancel_url);

            return redirect($redirect_url);
        } catch (Exception $e) {
            return redirect($cancel_url)->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function successPayment(Request $request)
    {

        $requestData = Session::get('user_request');
        $user = getUser();
        $cancel_url = Session::get('user_cancel_url');
        /** Get the payment ID before session clear **/
        $txnId = UserPermissionHelper::uniqidReal(8);
        $chargeId = $request->paymentId;
        $order = Common::saveOrder($requestData, $txnId, $chargeId, 'Completed','online',$user->id);
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
}
