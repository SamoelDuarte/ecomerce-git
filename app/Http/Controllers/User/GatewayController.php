<?php

namespace App\Http\Controllers\User;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\UserOfflineGateway;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Models\User\UserShopSetting;

class GatewayController extends Controller
{
    public function index()
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['paypal'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'paypal']])->first();
        $data['stripe'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'stripe']])->first();
        $data['paystack'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'paystack']])->first();
        $data['paytm'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'paytm']])->first();
        $data['flutterwave'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'flutterwave']])->first();
        $data['instamojo'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'instamojo']])->first();
        $data['mollie'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'mollie']])->first();
        $data['razorpay'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'razorpay']])->first();
        $data['mercadopago'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'mercadopago']])->first();
        $data['anet'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'authorize.net']])->first();

        $data['yoco'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'yoco']])->first();
        $data['xendit'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'xendit']])->first();
        $data['perfect_money'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'perfect_money']])->first();
        $data['myfatoorah'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'myfatoorah']])->first();
        $data['toyyibpay'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'toyyibpay']])->first();
        $data['midtrans'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'midtrans']])->first();
        $data['iyzico'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'iyzico']])->first();
        $data['paytabs'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'paytabs']])->first();
        $data['phonepe'] = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'phonepe']])->first();
        return view('user.gateways.index', $data);
    }

    public function paypalUpdate(Request $request)
    {
        $paypal = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paypal']])->first();
        if (empty($paypal)) {
            $paypal = new UserPaymentGeteway();
            $paypal->name = 'Paypal';
            $paypal->keyword = 'paypal';
            $paypal->type = 'automatic';
            $paypal->user_id = Auth::guard('web')->user()->id;
        }
        $paypal->status = $request->status;
        $information = [];
        $information['client_id'] = $request->client_id;
        $information['client_secret'] = $request->client_secret;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your PayPal account.";
        $paypal->information = json_encode($information);
        $paypal->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function stripeUpdate(Request $request)
    {
        $stripe = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'stripe']])->first();

        if (empty($stripe)) {
            $stripe = new UserPaymentGeteway();
            $stripe->name = 'Stripe';
            $stripe->keyword = 'stripe';
            $stripe->type = 'automatic';
            $stripe->user_id = Auth::guard('web')->user()->id;
        }
        $stripe->status = $request->status;
        $information = [];
        $information['key'] = $request->key;
        $information['secret'] = $request->secret;
        $information['text'] = "Pay via your Credit account.";
        $stripe->information = json_encode($information);
        $stripe->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function paystackUpdate(Request $request)
    {
        $paystack = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paystack']])->first();
        if (empty($paystack)) {
            $paystack = new UserPaymentGeteway();
            $paystack->name = 'Paystack';
            $paystack->keyword = 'paystack';
            $paystack->type = 'automatic';
            $paystack->user_id = Auth::guard('web')->user()->id;
        }
        $paystack->status = $request->status;
        $information = [];
        $information['key'] = $request->key;
        $information['email'] = $request->email;
        $information['text'] = "Pay via your Paystack account.";
        $paystack->information = json_encode($information);
        $paystack->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function paytmUpdate(Request $request)
    {
        $paytm = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paytm']])->first();
        if (empty($paytm)) {
            $paytm = new UserPaymentGeteway();
            $paytm->name = 'Paytm';
            $paytm->keyword = 'paytm';
            $paytm->type = 'automatic';
            $paytm->user_id = Auth::guard('web')->user()->id;
        }
        $paytm->status = $request->status;
        $information = [];
        $information['environment'] = $request->environment;
        $information['merchant'] = $request->merchant;
        $information['secret'] = $request->secret;
        $information['website'] = $request->website;
        $information['industry'] = $request->industry;
        $information['text'] = "Pay via your paytm account.";
        $paytm->information = json_encode($information);
        $paytm->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function flutterwaveUpdate(Request $request)
    {
        $flutterwave = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'flutterwave']])->first();

        if (empty($flutterwave)) {
            $flutterwave = new UserPaymentGeteway();
            $flutterwave->name = 'Flutterwave';
            $flutterwave->keyword = 'flutterwave';
            $flutterwave->type = 'automatic';
            $flutterwave->user_id = Auth::guard('web')->user()->id;
        }
        $flutterwave->status = $request->status;
        $information = [];
        $information['public_key'] = $request->public_key;
        $information['secret_key'] = $request->secret_key;
        $information['text'] = "Pay via your Flutterwave account.";
        $flutterwave->information = json_encode($information);
        $flutterwave->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function instamojoUpdate(Request $request)
    {
        $instamojo = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'instamojo']])->first();

        if (empty($instamojo)) {
            $instamojo = new UserPaymentGeteway();
            $instamojo->name = 'Instamojo';
            $instamojo->keyword = 'instamojo';
            $instamojo->type = 'automatic';
            $instamojo->user_id = Auth::guard('web')->user()->id;
        }
        $instamojo->status = $request->status;
        $information = [];
        $information['key'] = $request->key;
        $information['token'] = $request->token;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your Instamojo account.";
        $instamojo->information = json_encode($information);
        $instamojo->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function mollieUpdate(Request $request)
    {
        $mollie = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mollie']])->first();

        if (empty($mollie)) {
            $mollie = new UserPaymentGeteway();
            $mollie->name = 'Mollie';
            $mollie->keyword = 'mollie';
            $mollie->type = 'automatic';
            $mollie->user_id = Auth::guard('web')->user()->id;
        }
        $mollie->status = $request->status;
        $information = [];
        $information['key'] = $request->key;
        $information['text'] = "Pay via your Mollie Payment account.";
        $mollie->information = json_encode($information);
        $mollie->save();

        $arr = ['MOLLIE_KEY' => $request->key];
        setEnvironmentValue($arr);
        \Artisan::call('config:clear');
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function razorpayUpdate(Request $request)
    {
        $razorpay = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'razorpay']])->first();

        if (empty($razorpay)) {
            $razorpay = new UserPaymentGeteway();
            $razorpay->name = 'Razorpay';
            $razorpay->keyword = 'razorpay';
            $razorpay->type = 'automatic';
            $razorpay->user_id = Auth::guard('web')->user()->id;
        }
        $razorpay->status = $request->status;
        $information = [];
        $information['key'] = $request->key;
        $information['secret'] = $request->secret;
        $information['text'] = "Pay via your Razorpay account.";
        $razorpay->information = json_encode($information);
        $razorpay->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function anetUpdate(Request $request)
    {
        $anet = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'authorize.net']])->first();

        if (empty($anet)) {
            $anet = new UserPaymentGeteway();
            $anet->name = 'Authorize.net';
            $anet->keyword = 'authorize.net';
            $anet->type = 'automatic';
            $anet->user_id = Auth::guard('web')->user()->id;
        }
        $anet->status = $request->status;
        $information = [];
        $information['login_id'] = $request->login_id;
        $information['transaction_key'] = $request->transaction_key;
        $information['public_key'] = $request->public_key;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your Authorize.net account.";
        $anet->information = json_encode($information);
        $anet->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function mercadopagoUpdate(Request $request)
    {
        $mercadopago = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'mercadopago']])->first();

        if (empty($mercadopago)) {
            $mercadopago = new UserPaymentGeteway();
            $mercadopago->name = 'Mercadopago';
            $mercadopago->keyword = 'mercadopago';
            $mercadopago->type = 'automatic';
            $mercadopago->user_id = Auth::guard('web')->user()->id;
        }
        $mercadopago->status = $request->status;
        $information = [];
        $information['token'] = $request->token;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your Mercado Pago account.";
        $mercadopago->information = json_encode($information);
        $mercadopago->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function yocoUpdate(Request $request)
    {
        $yoco = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'yoco']])->first();
        if (empty($yoco)) {
            $yoco = new UserPaymentGeteway();
            $yoco->name = 'Yoco';
            $yoco->keyword = 'yoco';
            $yoco->type = 'automatic';
            $yoco->user_id = Auth::guard('web')->user()->id;
        }
        $yoco->status = $request->status;

        $information = [];
        $information['secret_key'] = $request->secret_key;
        $information['text'] = "Pay via your yoco account.";
        $yoco->information = json_encode($information);
        $yoco->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function xenditUpdate(Request $request)
    {
        $xendit = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'xendit']])->first();
        if (empty($xendit)) {
            $xendit = new UserPaymentGeteway();
            $xendit->name = 'Xendit';
            $xendit->keyword = 'xendit';
            $xendit->type = 'automatic';
            $xendit->user_id = Auth::guard('web')->user()->id;
        }
        $xendit->status = $request->status;
        $information = [];
        $information['secret_key'] = $request->secret_key;
        $information['text'] = "Pay via your xendit account.";
        $xendit->information = json_encode($information);
        $xendit->save();

        Session::flash('success', __('Updated Successfully'));

        return back();
    }

    public function perfectMoneyUpdate(Request $request)
    {
        $data = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'perfect_money']])->first();
        if (empty($data)) {
            $data = new UserPaymentGeteway();
            $data->name = 'Perfect Money';
            $data->keyword = 'perfect_money';
            $data->type = 'automatic';
            $data->user_id = Auth::guard('web')->user()->id;
        }
        $data->status = $request->status;
        $information = [];
        $information['perfect_money_wallet_id'] = $request->perfect_money_wallet_id;
        $information['text'] = "Pay via your Perfect Money account.";
        $data->information = json_encode($information);
        $data->save();
        Session::flash('success', __('Updated Successfully'));

        return back();
    }

    public function myfatoorahUpdate(Request $request)
    {
        $myfatoorah = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'myfatoorah']])->first();
        if (empty($myfatoorah)) {
            $myfatoorah = new UserPaymentGeteway();
            $myfatoorah->name = 'MyFatoorah';
            $myfatoorah->keyword = 'myfatoorah';
            $myfatoorah->type = 'automatic';
            $myfatoorah->user_id = Auth::guard('web')->user()->id;
        }
        $myfatoorah->status = $request->status;
        $information = [];
        $information['token'] = $request->token;
        $information['sandbox_status'] = $request->sandbox_status;
        $information['text'] = "Pay via your myfatoorah account.";
        $myfatoorah->information = json_encode($information);
        $myfatoorah->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function midtransUpdate(Request $request)
    {
        $data = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'midtrans']])->first();
        if (empty($data)) {
            $data = new UserPaymentGeteway();
            $data->name = 'Midtrans';
            $data->keyword = 'midtrans';
            $data->type = 'automatic';
            $data->user_id = Auth::guard('web')->user()->id;
        }
        $information = [
            "is_production" => $request->is_production,
            "server_key" => $request->server_key
        ];
        $data->status = $request->status;
        $data->information = json_encode($information);
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function iyzicoUpdate(Request $request)
    {
        $data = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'iyzico']])->first();
        if (empty($data)) {
            $data = new UserPaymentGeteway();
            $data->name = 'Iyzico';
            $data->keyword = 'iyzico';
            $data->type = 'automatic';
            $data->user_id = Auth::guard('web')->user()->id;
        }
        $information = [
            "sandbox_status" => $request->sandbox_status,
            "api_key" => $request->api_key,
            "secret_key" => $request->secret_key
        ];
        $data->status = $request->status;
        $data->information = json_encode($information);
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function toyyibpayUpdate(Request $request)
    {
        $toyyibpay = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'toyyibpay']])->first();
        if (empty($toyyibpay)) {
            $toyyibpay = new UserPaymentGeteway();
            $toyyibpay->name = 'Toyyibpay';
            $toyyibpay->keyword = 'toyyibpay';
            $toyyibpay->type = 'automatic';
            $toyyibpay->user_id = Auth::guard('web')->user()->id;
        }
        $toyyibpay->status = $request->status;
        $information = [];
        $information['sandbox_status'] = $request->sandbox_status;
        $information['secret_key'] = $request->secret_key;
        $information['category_code'] = $request->category_code;
        $information['text'] = "Pay via your toyyibpay account.";
        $toyyibpay->information = json_encode($information);
        $toyyibpay->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function paytabsUpdate(Request $request)
    {
        $paytabs = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'paytabs']])->first();
        if (empty($paytabs)) {
            $paytabs = new UserPaymentGeteway();
            $paytabs->name = 'Paytabs';
            $paytabs->keyword = 'paytabs';
            $paytabs->type = 'automatic';
            $paytabs->user_id = Auth::guard('web')->user()->id;
        }
        $paytabs->status = $request->status;
        $information = [];
        $information['server_key'] = $request->server_key;
        $information['profile_id'] = $request->profile_id;
        $information['country'] = $request->country;
        $information['api_endpoint'] = $request->api_endpoint;
        $information['text'] = "Pay via your paytabs account.";
        $paytabs->information = json_encode($information);
        $paytabs->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function phonepeUpdate(Request $request)
    {
        $phonepe = UserPaymentGeteway::where([['user_id', Auth::guard('web')->user()->id], ['keyword', 'phonepe']])->first();
        if (empty($phonepe)) {
            $phonepe = new UserPaymentGeteway();
            $phonepe->name = 'PhonePe';
            $phonepe->keyword = 'phonepe';
            $phonepe->type = 'automatic';
            $phonepe->user_id = Auth::guard('web')->user()->id;
        }
        $phonepe->status = $request->status;
        $information = [];
        $information['merchant_id'] = $request->merchant_id;
        $information['salt_key'] = $request->salt_key;
        $information['salt_index'] = $request->salt_index;
        $information['sandbox_check'] = $request->sandbox_check;
        $information['text'] = "Pay via your PhonePe account.";
        $phonepe->information = json_encode($information);
        $phonepe->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function offline(Request $request)
    {
        $data['ogateways'] = UserOfflineGateway::where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'DESC')->get();
        $data['shopsettings'] = UserShopSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        return view('user.gateways.offline.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'short_description' => 'nullable',
            'serial_number' => 'required|integer',
            'is_receipt' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $in = $request->all();
        $in['user_id'] = Auth::guard('web')->user()->id;
        UserOfflineGateway::create($in);
        Session::flash('success', __('Created successfully'));
        return "success";
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'short_description' => 'nullable',
            'serial_number' => 'required|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $in = $request->except('_token', 'ogateway_id');
        UserOfflineGateway::where('id', $request->ogateway_id)->update($in);
        Session::flash('success', __('Updated Successfully'));
        return "success";
    }
    public function status(Request $request)
    {
        $og = UserOfflineGateway::find($request->ogateway_id);
        if (!empty($request->type) && $request->type == 'item') {
            $og->item_checkout_status = $request->item_checkout_status;
        }
        $og->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete(Request $request)
    {
        $ogateway = UserOfflineGateway::findOrFail($request->ogateway_id);
        $ogateway->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }
}
