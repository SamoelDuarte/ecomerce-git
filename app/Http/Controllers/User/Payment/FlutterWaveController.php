<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;

class FlutterWaveController extends Controller
{
    public $public_key;
    private $secret_key;

    public function __construct()
    {
        $data = UserPaymentGeteway::where('keyword', 'flutterwave')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        $this->public_key = $paydata['public_key'];
        $this->secret_key = $paydata['secret_key'];
    }

    public function paymentProcess(Request $request, $_amount, $_email, $_item_number, $_successUrl, $_cancelUrl, $currency)
    {
        $cancel_url = $_cancelUrl;
        $notify_url = $_successUrl;
        Session::put('user_request', $request->all());
        Session::put('user_payment_id', $_item_number);

        $curl = curl_init();
        $currency = $currency->name;
        $txref = $_item_number; // ensure you generate unique references per transaction.
        $PBFPubKey = $this->public_key; // get your public key from the dashboard.
        $redirect_url = $notify_url;
        $payment_plan = ""; // this is only required for recurring payments.


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount' => $_amount,
                'customer_email' => $_email,
                'currency' => $currency,
                'txref' => $txref,
                'PBFPubKey' => $PBFPubKey,
                'redirect_url' => $redirect_url,
                'payment_plan' => $payment_plan
            ]),
            CURLOPT_HTTPHEADER => [
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            // there was an error contacting the rave API
            return redirect($cancel_url)->with('error', 'Curl returned error: ' . $err);
        }

        $transaction = json_decode($response);

        if (!$transaction->data && !$transaction->data->link) {
            // there was an error from the API
            return redirect($cancel_url)->with('error', 'API returned error: ' . $transaction->message);
        }

        return redirect()->to($transaction->data->link);
    }

    public function successPayment(Request $request)
    {

        $requestData = Session::get('user_request');
        $user  = getUser();

        $bs = BasicSetting::where('user_id', $user->id)->firstorFail();
        $cancel_url = route('customer.itemcheckout.cancel', getParam());
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('user_payment_id');

        if (isset($request['txref'])) {
            $ref = $payment_id;
            $query = array(
                "SECKEY" => $this->secret_key,
                "txref" => $ref
            );
            $data_string = json_encode($query);
            $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $response = curl_exec($ch);
            curl_close($ch);
            $resp = json_decode($response, true);

            if ($resp['status'] == 'error') {
                return redirect($cancel_url);
            }
            if ($resp['status'] = "success") {
                $paymentStatus = $resp['data']['status'];
                $paymentFor = Session::get('paymentFor');
                if ($resp['status'] = "success") {
                    $txnId = UserPermissionHelper::uniqidReal(8);
                    $chargeId = $request->paymentId;
                    $order = Common::saveOrder($requestData, $txnId, $chargeId, 'Completed',null,$user->id);
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
                }
            }
            return redirect($cancel_url);
        }
        return redirect($cancel_url);
    }
}
