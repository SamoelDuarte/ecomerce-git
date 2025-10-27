<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use Mollie\Laravel\Facades\Mollie;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;

class MollieController extends Controller
{
    public function __construct()
    {
        $data = UserPaymentGeteway::where('keyword', 'mollie')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        Config::set('mollie.key', $paydata['key']);
    }

    public function paymentProcess(Request $request, $_amount, $_success_url, $_title, $currency, $cancel_url)
    {
        $notify_url = $_success_url;
        try {
            $payment = Mollie::api()->payments()->create([
                'amount' => [
                    'currency' => $currency->name,
                    'value' => '' . sprintf('%0.2f', $_amount) . '', // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                'description' => $_title,
                'redirectUrl' => $notify_url,
            ]);
        } catch (\Exception $e) {
            return redirect($cancel_url);
        }



        /** add payment ID to session **/
        Session::put('user_request', $request->all());
        Session::put('user_payment_id', $payment->id);
        Session::put('user_success_url', $_success_url);

        $payment = Mollie::api()->payments()->get($payment->id);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function successPayment(Request $request)
    {

        $requestData = Session::get('user_request');
        $user  = getUser();
        $bs = BasicSetting::where('user_id', $user->id)->firstorFail();
        $requestData['user_id'] = Auth::guard('customer')->user()->id;

        $cancel_url = Session::get('cancel_url');
        $payment_id = Session::get('user_payment_id');

        /** Get the payment ID before session clear **/

        $payment = Mollie::api()->payments()->get($payment_id);

        if ($payment->status == 'paid') {
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
        }
        return redirect($cancel_url);
    }
}
