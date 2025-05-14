<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\PaymentGateway;
use App\Models\User\UserOrder;
use Illuminate\Support\Facades\Http;
use Str;

class XenditController extends Controller
{
    /**
     * Redirect the User to Paystack Payment Page
     * @return
     */
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $currency)
    {
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'xendit'], ['user_id', getUser()->id]])->first();
        $paydata = json_decode($paymentMethod->information, true);

        try {
            $external_id = Str::random(10);
            $secret_key = 'Basic ' . base64_encode($paydata['secret_key'] . ':');
            $data_request = Http::withHeaders([
                'Authorization' => $secret_key
            ])->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $external_id,
                'amount' => $_amount,
                'currency' => $currency->name,
                'success_redirect_url' => $_success_url
            ]);
            $response = $data_request->object();
            $response = json_decode(json_encode($response), true);
            Session::put('user_request', $request->all());

            Session::put('cancel_url', $_cancel_url);
            Session::put('xendit_id', $response['id']);
            Session::put('secret_key', $secret_key);
            return redirect($response['invoice_url']);
        } catch (\Exception $e) {
            session()->flash('message', __('cancel_payment'));
            session()->flash('alert-type', 'warning');
            return redirect($_cancel_url);
        }
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user = getUser();
        $xendit_id = Session::get('xendit_id');
        $secret_key = Session::get('secret_key');
        $paymentMethod = PaymentGateway::where('keyword', 'xendit')->first();
        $paydata = json_decode($paymentMethod->information, true);
        $p_secret_key = 'Basic ' . base64_encode($paydata['secret_key'] . ':');
        if (!is_null($xendit_id) && $secret_key == $p_secret_key) {
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
            return redirect()->route('customer.success.page', getParam());
        } else {
            session()->flash('message', __('cancel_payment'));
            session()->flash('alert-type', 'warning');
            return redirect()->route('customer.itemcheckout.cancel', getParam());
        }
    }
}
