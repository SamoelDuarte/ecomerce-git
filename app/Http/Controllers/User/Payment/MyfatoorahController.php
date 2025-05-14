<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\UserOrder;
use Basel\MyFatoorah\MyFatoorah;
use Illuminate\Support\Facades\Config;

class MyfatoorahController extends Controller
{
    public $myfatoorah;
    public function __construct()
    {
        $myfatoorah_user = Session::get('myfatoorah_user');
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'myfatoorah'], ['user_id', $myfatoorah_user->id]])->first();
        $paydata = json_decode($paymentMethod->information, true);

        $currency  = Common::getUserCurrentCurrency($myfatoorah_user->id);

        Config::set('myfatorah.token', $paydata['token']);
        Config::set('myfatorah.DisplayCurrencyIso', $currency->name);
        Config::set('myfatorah.CallBackUrl', route('myfatoorah.success'));
        Config::set('myfatorah.ErrorUrl', route('myfatoorah.cancel'));
        if ($paydata['sandbox_status'] == 1) {
            $this->myfatoorah = MyFatoorah::getInstance(true);
        } else {
            $this->myfatoorah = MyFatoorah::getInstance(false);
        }
    }

    public function paymentProcess(Request $request, $_amount, $_cancel_url)
    {
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'myfatoorah'], ['user_id', getUser()->id]])->first();
        $paydata = json_decode($paymentMethod->information, true);

        $random_1 = rand(999, 9999);
        $random_2 = rand(9999, 99999);
        $name = $request->billing_fname . ' ' . $request->billing_lname;
        Session::put('user_request', $request->all());

        $phone = $request->billing_number;
        $result = $this->myfatoorah->sendPayment(
            $name,
            $_amount,
            [
                'CustomerMobile' => $paydata['sandbox_status'] == 1 ? '56562123544' : $phone,
                'CustomerReference' => "$random_1",  //orderID
                'UserDefinedField' => "$random_2", //clientID
                "InvoiceItems" => [
                    [
                        "ItemName" => "Product Purchase",
                        "Quantity" => 1,
                        "UnitPrice" => $_amount
                    ]
                ]
            ]
        );
        if ($result && $result['IsSuccess'] == true) {
            $request->session()->put('myfatoorah_payment_type', 'product_purchase');
            return redirect($result['Data']['InvoiceURL']);
        } else {
            return redirect($_cancel_url);
        }
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user = Session::get('myfatoorah_user');

        if (!empty($request->paymentId)) {
            $txnId = UserPermissionHelper::uniqidReal(8);
            $chargeId = $request->paymentId;
            $order = Common::saveOrder($requestData, $txnId, $chargeId, 'Completed', 'online', $user->id);
            $order_id = $order->id;

            Common::saveOrderedItems($order_id);
            Common::generateInvoice($order, $user);
            $order = UserOrder::where('id', $order_id)->first();
            Common::OrderCompletedMail($order, $user);
            session()->flash('success', __('successful_payment'));
            Session::forget('user_request');
            Session::forget('user_amount');
            Session::forget('cart');
            return [
                'status' => 'success'
            ];
        } else {
            return [
                'status' => 'fail'
            ];
        }
    }
}
