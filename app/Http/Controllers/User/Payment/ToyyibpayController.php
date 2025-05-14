<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;

class ToyyibpayController extends Controller
{
    /**
     * Redirect the User to Paystack Payment Page
     * @return
     */
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url)
    {
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'toyyibpay'], ['user_id', getUser()->id]])->first();
        $paydata = json_decode($paymentMethod->information, true);
        $first_name = $request->billing_fname;
        $last_name = $request->billing_lname;
        $email = $request->billing_email;
        $phone = $request->billing_number;

        $ref = uniqid();
        session()->put('toyyibpay_ref_id', $ref);
        $bill_description = 'Product Purchase via toyyibpay';

        $some_data = array(
            'userSecretKey' => $paydata['secret_key'],
            'categoryCode' => $paydata['category_code'],
            'billName' => 'Product Purchase',
            'billDescription' => $bill_description,
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => $_amount * 100,
            'billReturnUrl' => $_success_url,
            'billExternalReferenceNo' => $ref,
            'billTo' => $first_name . ' ' . $last_name,
            'billEmail' => $email,
            'billPhone' => $phone,
        );

        if ($paydata['sandbox_status'] == 1) {
            $host = 'https://dev.toyyibpay.com/'; // for development environment
        } else {
            $host = 'https://toyyibpay.com/'; // for production environment
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $host . 'index.php/api/createBill');  // sandbox will be dev.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $response = json_decode($result, true);

        if (!empty($response[0])) {
            Session::put('user_request', $request->all());
            return redirect($host . $response[0]["BillCode"]);
        } else {
            if(array_key_exists('msg',$response)){
            session()->flash('error',$response['msg']);
            }
            return redirect($_cancel_url);
        }
    }

    public function successPayment(Request $request)
    {

        $requestData = Session::get('user_request');
        $user = getUser();
        $ref = session()->get('toyyibpay_ref_id');
        if ($request['status_id'] == 1 && $request['order_id'] == $ref) {
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
