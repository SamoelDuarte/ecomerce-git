<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;
use Illuminate\Support\Facades\Http;

class PaytabsController extends Controller
{
    /**
     * Redirect the User to Paystack Payment Page
     * @return
     */
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url)
    {
        $user = getUser();
        Session::put('user_request', $request->all());
        $paytabInfo = paytabInfo('user', $user->id);
        $description = 'Product Purchase via paytabs';
        try {
            $response = Http::withHeaders([
                'Authorization' => $paytabInfo['server_key'], // Server Key
                'Content-Type' => 'application/json',
            ])->post($paytabInfo['url'], [
                'profile_id' => $paytabInfo['profile_id'], // Profile ID
                'tran_type' => 'sale',
                'tran_class' => 'ecom',
                'cart_id' => uniqid(),
                'cart_description' => $description,
                'cart_currency' => $paytabInfo['currency'], // set currency by region
                'cart_amount' => round($_amount, 2),
                'return' => $_success_url,
            ]);

            $responseData = $response->json();
            // put some data in session before redirect to paytm url
            return redirect()->to($responseData['redirect_url']);
        } catch (\Exception $e) {
            return redirect($_cancel_url);
        }
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user = getUser();
        $resp = $request->all();
        if ($resp['respStatus'] == "A" && $resp['respMessage'] == 'Authorised') {
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
