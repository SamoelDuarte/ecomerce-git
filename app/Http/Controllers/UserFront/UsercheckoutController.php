<?php

namespace App\Http\Controllers\UserFront;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Payment\AuthorizenetController;
use App\Http\Controllers\User\Payment\FlutterWaveController;
use App\Http\Controllers\User\Payment\InstamojoController;
use App\Http\Controllers\User\Payment\IyzicoController;
use App\Http\Controllers\User\Payment\MercadopagoController;
use App\Http\Controllers\User\Payment\MidtransController;
use App\Http\Controllers\User\Payment\MollieController;
use App\Http\Controllers\User\Payment\MyfatoorahController;
use App\Http\Controllers\User\Payment\PagSmileController;
use App\Http\Controllers\User\Payment\PaypalController;
use App\Http\Controllers\User\Payment\PaystackController;
use App\Http\Controllers\User\Payment\PaytabsController;
use App\Http\Controllers\User\Payment\PaytmController;
use App\Http\Controllers\User\Payment\PerfectMoneyController;
use App\Http\Controllers\User\Payment\PhonePeController;
use App\Http\Controllers\User\Payment\RazorpayController;
use App\Http\Controllers\User\Payment\StripeController;
use App\Http\Controllers\User\Payment\ToyyibpayController;
use App\Http\Controllers\User\Payment\XenditController;
use App\Http\Controllers\User\Payment\YocoController;
use App\Http\Helpers\Common;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\BasicSetting;
use App\Models\User\Language;
use App\Models\User\ProductVariantOption;
use App\Models\User\UserItem;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UsercheckoutController extends Controller
{
    public function checkout($domain, Request $request)
    {


        $prevUrl = session()->get('prevUrl', []);
        if (!empty($prevUrl) && is_string($prevUrl)) {
            // if (onlyDigitalItemsInCart() && !Auth::check()) {
            //     return redirect()->to($prevUrl);
            // }
        }

        $user = getUser();
        $user_id = $user->id;
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $order_limit = $current_package->order_limit;
        $total_order = UserOrder::where('user_id', $user_id)->count();
        $total_order = $total_order + 1;

        if ($order_limit <= $total_order) {
            return back()->with([
                'alert-type' => 'warning',
                'message' => __('Order Limit Exceeded')
            ]);
        }

        $type = request()->input('type');
        if ($type == 'guest') {
            $cart = Session::get('cart');
        }

        // store
        if (!Session::has('cart')) {
            return view('errors.404');
        }


        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                if ($userCurrentLang) {
                    session()->put('user_lang', $userCurrentLang->code);
                }
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        
        // Se não encontrou idioma, criar um padrão
        if (!$userCurrentLang) {
            $userCurrentLang = Language::create([
                'name' => 'Português',
                'code' => 'pt',
                'is_default' => 1,
                'rtl' => 0,
                'type' => 'admin',
                'user_id' => $user->id,
                'keywords' => json_encode([])
            ]);
        }
        $cart = Session::get('cart');
        $items = [];
        $qty = [];
        $st_errors = [];
        $variations = [];
        foreach ($cart as $id => $c_item) {
            // check stock quantity without variation
            $item = UserItem::findOrFail($c_item['id']);
            if ($item->type == 'fisico') {
                if ($c_item["variations"] == null) {

                    if ($item->stock < $c_item['qty']) {
                        $st_errors[] = __("Stock not available for") . " " . $c_item["name"];
                    }
                } else {
                    $orderderd_variations = $c_item["variations"];

                    foreach ($orderderd_variations as $vkey => $value) {
                        $db_variations = ProductVariantOption::where('id', $value['option_id'])->first();
                        if ($db_variations) {
                            $db_stock = $db_variations->stock;
                            if ($db_stock < $c_item['qty']) {
                                $st_errors[] = __("Stock not available for selected") . " " . $vkey . " of " . $c_item["name"];
                            }
                        } else {
                            $st_errors[] = __('Something went wrong..!');
                        }
                    }
                }
            }
        }


        if (count($st_errors)) {
            return redirect()->back()->with('st_errors', $st_errors);
        }

        // Calcula total sem frete (usar 0 para ignorar shipping_charge antigo)
        $total = Common::orderTotal(0, $user->id);

        // Adiciona o valor do frete do Frenet
        $frete = floatval($request->input('shipping_service_price', 0));
        $total = $total - session()->get('user_coupon') + $frete;

        $offline_payment_gateways = UserOfflineGateway::where('user_id', $user->id)->get()->pluck('name')->toArray();
        if (in_array(@$request->payment_method, $offline_payment_gateways)) {
            $mode = 'offline';
        } else {
            $mode = 'online';
        }
        if (Common::orderValidation($request, $mode, $user->id)) {
            return Common::orderValidation($request, $mode, $user->id);
        }
        $bs = BasicSetting::where('user_id', $user->id)->firstorFail();
        $input = $request->all();
        $request['status'] = 1;
        $title = 'Item Checkout';
        $description = 'Item Checkout description';
        Session::put('user_paymentFor', 'user_item_order');

        $currency  = Common::getUserCurrentCurrency($user->id);
        $payment_total = $total;

        if ($request->payment_method == "Pagsmile") {
            if ($currency->text != "BRL") {
                session()->flash('message', __('only_pagSmile_BRL'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }

            $amount         = $total;
            $email          = $request->billing_email ?? 'cliente@email.com';
            $success_url    = route('customer.itemcheckout.pagSmile.success', getParam());
            $cancel_url     = route('customer.itemcheckout.pagSmile.cancel', getParam());
            $title          = 'Pagamento de pedido';
            $description    = 'Pedido de compra realizado pelo cliente';

            $pagSmile = new PagSmileController();
            return $pagSmile->paymentProcess($request, $amount, $email, $success_url, $cancel_url, $title, $description);
        } elseif (in_array($request->payment_method, $offline_payment_gateways)) {
            $request['mode'] = 'offline';
            $request['status'] = 0;
            $request['receipt_name'] = null;
            $amount = $total;
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $transaction_details = "offline";

            $chargeId = $request->paymentId;
            $order = Common::saveOrder($request, $transaction_id, $chargeId, 'Pending', 'offline', $user->id);
            $order_id = $order->id;
            Common::saveOrderedItems($order_id);
            Common::sendMails($order);
            session()->flash('success', __('successful_payment'));
            Session::forget('user_request');
            Session::forget('user_amount');
            Session::forget('user_paypal_payment_id');
            return redirect()->route('customer.itemcheckout.offline.success', getParam());
        }
    }

    public function paymentInstruction(Request $request)
    {
        $user = getUser();
        $offline = UserOfflineGateway::where([['user_id', $user->id], ['name', $request->name]])
            ->select('short_description', 'instructions', 'is_receipt')
            ->first();
        return response()->json([
            'description' => $offline->short_description,
            'instructions' => $offline->instructions,
            'is_receipt' => $offline->is_receipt
        ]);
    }

    public function offlineSuccess()
    {
        return view('user-front.offline-success');
    }

    public function cancelPayment()
    {
        session()->flash('warning', __('cancel_payment'));
        return redirect()->route('front.user.checkout', getParam());
    }
}
