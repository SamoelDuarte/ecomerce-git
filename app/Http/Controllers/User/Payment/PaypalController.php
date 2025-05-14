<?php

namespace App\Http\Controllers\User\Payment;

use Redirect;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use App\Models\User\BasicSetting;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserOrder;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Config;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use PayPal\Exception\PayPalConnectionException;

class PaypalController extends Controller
{
    private $_api_context;

    public function __construct()
    {
        $data = UserPaymentGeteway::where('keyword', 'paypal')->where('user_id', getUser()->id)->first();
        $paydata = $data->convertAutoData();
        $paypal_conf = Config::get('paypal');
        $paypal_conf['client_id'] = $paydata['client_id'];
        $paypal_conf['secret'] = $paydata['client_secret'];
        $paypal_conf['settings']['mode'] = $paydata['sandbox_check'] == 1 ? 'sandbox' : 'live';
        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function paymentProcess(Request $request, $_amount, $_title, $_success_url, $_cancel_url, $currency_code)
    {
        $title = $_title;
        $price = $_amount;
        $price = round($price, 2);
        $cancel_url = $_cancel_url;
        $success_url = $_success_url;

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($title)
            /** item name **/
            ->setCurrency("$currency_code")
            ->setQuantity(1)
            ->setPrice($price);
        /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency("$currency_code")
            ->setTotal($price);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($title . ' Via Paypal');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($success_url)
            /** Specify return URL **/
            ->setCancelUrl($cancel_url);
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
        } catch (PayPalConnectionException $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        Session::put('user_request', $request->all());
        Session::put('user_amount', $_amount);
        Session::put('user_paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        return redirect()->back()->with('error', 'Unknown error occurred');
    }

    public function successPayment(Request $request)
    {

        $requestData = Session::get('user_request');
        $amount = Session::get('user_amount');
        $user = getUser();
        $be = BasicSetting::where('user_id', $user->id)->firstorFail();
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('user_paypal_payment_id');
        /** clear the session payment ID **/
        $cancel_url = route('customer.itemcheckout.cancel', getParam());
        if (empty($request['PayerID']) || empty($request['token'])) {
            return redirect($cancel_url);
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request['PayerID']);
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
            $resp = json_decode($payment, true);
            $txnId = $resp['transactions'][0]['related_resources'][0]['sale']['id'];
            $chargeId = $request->paymentId;
            $order = Common::saveOrder($requestData, $txnId, $chargeId, 'Completed', 'online', $user->id);
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
        return redirect($cancel_url);
    }
}
