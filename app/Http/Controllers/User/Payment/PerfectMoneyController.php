<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;

class PerfectMoneyController extends Controller
{
    /**
     * Redirect the User to Paystack Payment Page
     * @return
     */
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $currency, $title)
    {
        $amount = $_amount;
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'perfect_money'], ['user_id', getUser()->id]])->first();
        $paydata = json_decode($paymentMethod->information, true);

        Session::put('user_request', $request->all());
        $notify_url = $_success_url;
        $randomNo = substr(uniqid(), 0, 8);

        $val['PAYEE_ACCOUNT'] = $paydata['perfect_money_wallet_id'];;
        $val['PAYEE_NAME'] = $title;
        $val['PAYMENT_ID'] = "$randomNo"; //random id
        $val['PAYMENT_AMOUNT'] = $amount;
        $val['PAYMENT_UNITS'] = "$currency->name";

        $val['STATUS_URL'] = $_success_url;
        $val['PAYMENT_URL'] = $_success_url;
        $val['PAYMENT_URL_METHOD'] = 'GET';
        $val['NOPAYMENT_URL'] = $_cancel_url;
        $val['NOPAYMENT_URL_METHOD'] = 'GET';
        $val['SUGGESTED_MEMO'] = "$request->billing_email";
        $val['BAGGAGE_FIELDS'] = 'IDENT';

        $data['val'] = $val;
        $data['method'] = 'post';
        $data['url'] = 'https://perfectmoney.com/api/step1.asp';

        Session::put('payment_id', $randomNo);
        Session::put('amount', $amount);
        return view('payments.perfect-money', compact('data'));
    }

    public function successPayment(Request $request)
    {

        $requestData = Session::get('user_request');
        $user = getUser();
        $amo = $request['PAYMENT_AMOUNT'];
        $track = $request['PAYMENT_ID'];
        $id = Session::get('payment_id');
        $final_amount = Session::get('amount');
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'perfect_money'], ['user_id', getUser()->id]])->first();
        $perfectMoneyInfo = json_decode($paymentMethod->information, true);

        if ($request->PAYEE_ACCOUNT == $perfectMoneyInfo['perfect_money_wallet_id']  && $track == $id && $amo == round($final_amount, 2)) {
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
            session()->flash('alert-type', 'error');
            return redirect()->route('customer.itemcheckout.cancel', getParam());
        }
    }
}
