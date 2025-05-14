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
            if (onlyDigitalItemsInCart() && !Auth::check()) {
                return redirect()->to($prevUrl);
            }
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
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->firstOrFail();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->firstOrFail();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->firstOrFail();
        }

        $cart = Session::get('cart');
        $items = [];
        $qty = [];
        $st_errors = [];
        $variations = [];
        foreach ($cart as $id => $c_item) {
            // check stock quantity without variation
            $item = UserItem::findOrFail($c_item['id']);
            if ($item->type == 'physical') {
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
        $total = Common::orderTotal($request->shipping_charge, $user->id);
        $total = $total - session()->get('user_coupon');

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

        if ($request->payment_method == "Paypal") {
            $available_currency = array('AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'THB', 'USD');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }

            $amount = round($payment_total, 2);
            $paypal = new PaypalController();
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $success_url = route('customer.itemcheckout.paypal.success', getParam());
            return $paypal->paymentProcess($request, $amount, $title, $success_url, $cancel_url, $currency->text);
        } elseif ($request->payment_method == "Stripe") {
            $available_currency = array('USD', 'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BIF', 'BMD', 'BND', 'BOB', 'BRL', 'BSD', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'ISK', 'JMD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KRW', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SEK', 'SGD', 'SHP', 'SLE', 'SOS', 'SRD', 'STD', 'SZL', 'THB', 'TJS', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'UYU', 'UZS', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = round($payment_total, 2);
            $stripe = new StripeController();
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            return $stripe->paymentProcess($request, $amount, $title, NULL, $cancel_url, $currency->text);
        } elseif ($request->payment_method == "Paytm") {
            if ($currency->text != "INR") {
                session()->flash('message', __('only_paytm_INR'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $item_number = uniqid('paytm-') . time();
            $callback_url = route('customer.itemcheckout.paytm.status', getParam());
            $paytm = new PaytmController();
            return $paytm->paymentProcess($request, $amount, $item_number, $callback_url);
        } elseif ($request->payment_method == "Paystack") {
            if ($currency->text != "NGN") {
                session()->flash('message', __('only_paystack_NGN'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total * 100;
            $email = $request->billing_email;
            $success_url = route('customer.itemcheckout.paystack.success', getParam());
            $payStack = new PaystackController();
            return $payStack->paymentProcess($request, $amount, $email, $success_url, $currency);
        } elseif ($request->payment_method == "Razorpay") {
            if ($currency->text != "INR") {
                session()->flash('message', __('only_razorpay_INR'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $item_number = uniqid('razorpay-') . time();
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $success_url = route('customer.itemcheckout.razorpay.success', getParam());

            $razorpay = new RazorpayController();
            return $razorpay->paymentProcess($request, $amount, $item_number, $cancel_url, $success_url, $title, $description, $bs);
        } elseif ($request->payment_method == "Instamojo") {
            if ($currency->text != "INR") {
                session()->flash('message', __('only_instamojo_INR'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            if ($total < 9) {
                session()->flash('message', __('Minimum 10 INR required for this payment gateway'));
                session()->flash('alert-type', 'warning');
                return back()->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.instamojo.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $instaMojo = new InstamojoController();
            return $instaMojo->paymentProcess($request, $amount, $success_url, $cancel_url, $title, $bs);
        } elseif ($request->payment_method == "Mercadopago") {
            if ($currency->text != "BRL") {
                session()->flash('message', __('only_mercadopago_BRL'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $email = $request->email;
            $success_url = route('customer.itemcheckout.mercadopago.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $mercadopagoPayment = new MercadopagoController();
            return $mercadopagoPayment->paymentProcess($request, $amount, $success_url, $cancel_url, $email, $title, $description);
        } elseif ($request->payment_method == "Flutterwave") {
            $available_currency = array('BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $email = $request->billing_email;
            $item_number = uniqid('flutterwave-') . time();
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $success_url = route('customer.itemcheckout.flutterwave.success', getParam());
            $flutterWave = new FlutterWaveController();
            return $flutterWave->paymentProcess($request, $amount, $email, $item_number, $success_url, $cancel_url, $currency);
        } elseif ($request->payment_method == "Authorize.net") {
            $available_currency = array('USD', 'CAD', 'CHF', 'DKK', 'EUR', 'GBP', 'NOK', 'PLN', 'SEK', 'AUD', 'NZD');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $anetPayment = new AuthorizenetController();
            return $anetPayment->paymentProcess($request, $amount, $bs);
        } elseif ($request->payment_method == "Mollie") {
            $available_currency = array('AED', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HRK', 'HUF', 'ILS', 'ISK', 'JPY', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'THB', 'TWD', 'USD', 'ZAR');
            if (!in_array($currency->text, $available_currency)) {
                return redirect()->back()->with('error', __('invalid_currency'))->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.mollie.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $molliePayment = new MollieController();
            return $molliePayment->paymentProcess($request, $amount, $success_url, $title, $currency, $cancel_url);
        } elseif ($request->payment_method == "Yoco") {
            $available_currency = array('ZAR');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.yoco.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $molliePayment = new YocoController();
            return $molliePayment->paymentProcess($request, $amount, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Xendit") {
            $available_currency = array('IDR', 'PHP', 'USD', 'SGD', 'MYR');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.xendit.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $molliePayment = new XenditController();
            return $molliePayment->paymentProcess($request, $amount, $success_url, $cancel_url, $currency);
        } elseif ($request->payment_method == "Perfect Money") {
            $available_currency = array('USD');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.perfect_money.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $molliePayment = new PerfectMoneyController();
            $title = $user->first_name . ' ' . $user->last_name;
            return $molliePayment->paymentProcess($request, $amount, $success_url, $cancel_url, $currency, $title);
        } elseif ($request->payment_method == "Myfatoorah") {
            $available_currency = array('KWD', 'SAR', 'BHD', 'AED', 'QAR', 'OMR', 'JOD');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }

            $amount = $total;
            $success_url = route('customer.itemcheckout.myfatoorah.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            Session::put('myfatoorah_user', $user);
            Session::put('cancel_url', $cancel_url);
            Session::put('getparam', getParam());

            $molliePayment = new MyfatoorahController();
            return $molliePayment->paymentProcess($request, $amount, $cancel_url);
        } elseif ($request->payment_method == "Toyyibpay") {
            $available_currency = array('RM');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.toyyibpay.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $paymentData = new ToyyibpayController();
            return $paymentData->paymentProcess($request, $amount, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Paytabs") {
            $paytabInfo = paytabInfo('user', $user->id);
            if ($currency->text != $paytabInfo['currency']) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.paytabs.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $paymentData = new PaytabsController();
            return $paymentData->paymentProcess($request, $amount, $success_url, $cancel_url);
        } elseif ($request->payment_method == "PhonePe") {
            $available_currency = array('INR');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.paytabs.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $paymentData = new PhonePeController();
            return $paymentData->paymentProcess($request, $amount, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Midtrans") {
            $available_currency = array('IDR');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.midtrans.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $paymentData = new MidtransController();
            $title = $user->first_name . ' ' . $user->last_name;
            return $paymentData->paymentProcess($request, $amount, $title, $success_url, $cancel_url);
        } elseif ($request->payment_method == "Iyzico") {
            $available_currency = array('TRY');
            if (!in_array($currency->text, $available_currency)) {
                session()->flash('message', __('invalid_currency'));
                session()->flash('alert-type', 'error');
                return redirect()->back()->withInput($request->all());
            }
            $amount = $total;
            $success_url = route('customer.itemcheckout.iyzico.success', getParam());
            $cancel_url = route('customer.itemcheckout.cancel', getParam());
            $paymentData = new IyzicoController();
            $title = $user->first_name . ' ' . $user->last_name;
            return $paymentData->paymentProcess($request, $amount, $success_url, $cancel_url);
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
