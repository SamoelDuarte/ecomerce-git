<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OfflineGateway;
use App\Models\PaymentGateway;
use Artisan;
use Illuminate\Support\Facades\Session;
use Validator;

class GatewayController extends Controller
{
    public function index()
    {
        $data['paypal'] = PaymentGateway::where('keyword', 'paypal')->first();
        $data['stripe'] = PaymentGateway::where('keyword', 'stripe')->first();
        $data['paystack'] = PaymentGateway::where('keyword', 'paystack')->first();
        $data['paytm'] = PaymentGateway::where('keyword', 'paytm')->first();
        $data['flutterwave'] = PaymentGateway::where('keyword', 'flutterwave')->first();
        $data['instamojo'] = PaymentGateway::where('keyword', 'instamojo')->first();
        $data['mollie'] = PaymentGateway::where('keyword', 'mollie')->first();
        $data['razorpay'] = PaymentGateway::where('keyword', 'razorpay')->first();
        $data['mercadopago'] = PaymentGateway::where('keyword', 'mercadopago')->first();
        $data['anet'] = PaymentGateway::where('keyword', 'authorize.net')->first();
        $data['midtrans'] = PaymentGateway::where('keyword', 'midtrans')->first();
        $data['iyzico'] = PaymentGateway::where('keyword', 'iyzico')->first();
        $data['toyyibpay'] = PaymentGateway::where('keyword', 'toyyibpay')->first();
        $data['phonepe'] = PaymentGateway::where('keyword', 'phonepe')->first();
        $data['yoco'] = PaymentGateway::where('keyword', 'yoco')->first();
        $data['xendit'] = PaymentGateway::where('keyword', 'xendit')->first();
        $data['myfatoorah'] = PaymentGateway::where('keyword', 'myfatoorah')->first();
        $data['paytabs'] = PaymentGateway::where('keyword', 'paytabs')->first();
        $data['perfect_money'] = PaymentGateway::where('keyword', 'perfect_money')->first();
        return view('admin.gateways.index', $data);
    }

    public function paypalUpdate(Request $request)
    {
        $paypal = PaymentGateway::find(15);
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
        $stripe = PaymentGateway::find(14);
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
        $paystack = PaymentGateway::find(12);
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
        $paytm = PaymentGateway::find(11);
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
        $flutterwave = PaymentGateway::find(6);
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
        $instamojo = PaymentGateway::find(13);
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
        $mollie = PaymentGateway::find(17);
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
        $razorpay = PaymentGateway::find(9);
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
        $anet = PaymentGateway::find(20);
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
        $mercadopago = PaymentGateway::find(19);
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
        $data = PaymentGateway::where('keyword', 'yoco')->first();
        $information = [
            "secret_key" => $request->secret_key
        ];
        $data->status = $request->status;
        $data->information = json_encode($information);
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
    public function xenditUpdate(Request $request)
    {
        $data = PaymentGateway::where('keyword', 'xendit')->first();
        $information = [
            "secret_key" => $request->secret_key
        ];
        $data->status = $request->status;
        $data->information = json_encode($information);
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
    public function perfect_moneyUpdate(Request $request)
    {
        $data = PaymentGateway::where('keyword', 'perfect_money')->first();
        $information = [
            "perfect_money_wallet_id" => $request->perfect_money_wallet_id
        ];
        $data->status = $request->status;
        $data->information = json_encode($information);
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function myfatoorahUpdate(Request $request)
    {
        $data = PaymentGateway::where('keyword', 'myfatoorah')->first();
        $information = [
            "token" => $request->token,
            "sandbox_status" => $request->sandbox_status,
        ];
        $data->status = $request->status;
        $data->information = json_encode($information);
        $data->save();
        $array = [
            'MYFATOORAH_TOKEN' => $request->token,
            'MYFATOORAH_CALLBACK_URL' => route('myfatoorah.success'),
            'MYFATOORAH_ERROR_URL' => route('myfatoorah.cancel'),
        ];
        setEnvironmentValue($array);
        Artisan::call('config:clear');
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function midtransUpdate(Request $request)
    {
        $data = PaymentGateway::where('keyword', 'midtrans')->first();
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
    public function toyyibpayUpdate(Request $request)
    {
        $data = PaymentGateway::where('keyword', 'toyyibpay')->first();
        $information = [
            "sandbox_status" => $request->sandbox_status,
            "secret_key" => $request->secret_key,
            "category_code" => $request->category_code
        ];
        $data->status = $request->status;
        $data->information = json_encode($information);
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function iyzicoUpdate(Request $request)
    {
        $data = PaymentGateway::where('keyword', 'iyzico')->first();
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

    public function paytabsUpdate(Request $request)
    {
        $data = PaymentGateway::where('keyword', 'paytabs')->first();
        $information = [
            "country" => $request->country,
            "server_key" => $request->server_key,
            "profile_id" => $request->profile_id,
            "api_endpoint" => $request->api_endpoint
        ];
        $data->status = $request->status;
        $data->information = json_encode($information);
        $data->save();
        Session::flash('success',  __('Updated Successfully'));
        return back();
    }
    public function phonepeUpdate(Request $request)
    {
        $data = PaymentGateway::where('keyword', 'phonepe')->first();
        $information = [
            "sandbox_status" => $request->sandbox_status,
            "merchant_id" => $request->merchant_id,
            "salt_key" => $request->salt_key,
            "salt_index" => $request->salt_index
        ];
        $data->status = $request->status;
        $data->information = json_encode($information);
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function offline(Request $request)
    {
        $data['ogateways'] = OfflineGateway::orderBy('id', 'DESC')->get();
        return view('admin.gateways.offline.index', $data);
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
        OfflineGateway::create($in);
        Session::flash('success', __('Created Successfully'));
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
        OfflineGateway::where('id', $request->ogateway_id)->update($in);
        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function status(Request $request)
    {
        $og = OfflineGateway::find($request->ogateway_id);
        $og->status = $request->status;
        $og->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete(Request $request)
    {
        $ogateway = OfflineGateway::findOrFail($request->ogateway_id);
        $ogateway->delete();
        Session::flash('success', __('Deleted Successfully'));
        return back();
    }
}
