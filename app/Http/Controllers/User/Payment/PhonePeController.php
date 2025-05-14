<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;
use App\Models\User\UserPaymentGeteway;
use Ixudra\Curl\Facades\Curl;

class PhonePeController extends Controller
{
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url)
    {
        $user = getUser();
        Session::put('user_request', $request->all());
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'phonepe'], ['user_id', $user->id]])->first();
        $paydata = json_decode($paymentMethod->information, true);
        $notify_url = $_success_url;
        $random_id = rand(111, 999);

        $data = array(
            'merchantId' => $paydata['merchant_id'], // sandbox merchant id
            'merchantTransactionId' => uniqid(),
            'merchantUserId' => 'MUID' . $random_id, // it will be the ID of tenants / vendors from database
            'amount' => intval($_amount * 100),
            'redirectUrl' => $notify_url,
            'redirectMode' => 'POST',
            'callbackUrl' => $notify_url,
            'mobileNumber' => $request->billing_phone,
            'paymentInstrument' =>
            array(
                'type' => 'PAY_PAGE',
            ),
        );

        $encode = base64_encode(json_encode($data));
        $saltKey = $paydata['salt_key'];
        $saltIndex = $paydata['salt_index'];

        $string = $encode . '/pg/v1/pay' . $saltKey;
        $sha256 = hash('sha256', $string);

        $finalXHeader = $sha256 . '###' . $saltIndex;

        if ($paydata['sandbox_check'] == 1) {
            $url = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay"; // sandbox payment URL
        } else {
            $url = "https://api.phonepe.com/apis/hermes/pg/v1/pay"; // prod payment URL
        }

        $response = Curl::to($url)
            ->withHeader('Content-Type:application/json')
            ->withHeader('X-VERIFY:' . $finalXHeader)
            ->withData(json_encode(['request' => $encode]))
            ->post();

        $rData = json_decode($response);
        if (empty(@$rData->data->instrumentResponse->redirectInfo->url)) {
            session()->flash('message', __('cancel_payment'));
            session()->flash('alert-type', 'warning');
            return redirect($_cancel_url);
        }
        return redirect()->to($rData->data->instrumentResponse->redirectInfo->url);
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user = getUser();
        if ($request->code == 'PAYMENT_SUCCESS') {
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
