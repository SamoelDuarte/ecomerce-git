<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Session;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\Language;
use Auth;

class IyzicoController extends Controller
{
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url)
    {
        Session::put('request', $request->all());
        $paymentMethod = PaymentGateway::where('keyword', 'iyzico')->first();
        $paydata = json_decode($paymentMethod->information, true);

        $paymentFor = Session::get('paymentFor');
        if ($paymentFor == 'membership') {
            $first_name = $request['shop_name'];
            $email = $request['email'];
            $address = $request['address'];
            $city = $request['city'];
            $country = $request['country'];
            $phone = $request['phone'];
        } else {
            $first_name = Auth::guard('web')->user()->shop_name;
            $email = Auth::guard('web')->user()->email;
            $address = Auth::guard('web')->user()->address;
            $city = Auth::guard('web')->user()->city;
            $country = Auth::guard('web')->user()->country;
            $phone = Auth::guard('web')->user()->phone;
        }
        $address = $address ?? $request['address'];
        $city = $city ?? $request['city'];
        $country = $country ?? $request['country'];
        $phone = $phone ?? $request['phone'];

        $zip_code = $request['zip_code'];
        $identity_number = $request['identity_number'];
        $basket_id = 'B' . uniqid(999, 99999);

        $options = new \Iyzipay\Options();
        $options->setApiKey($paydata['api_key']);
        $options->setSecretKey($paydata['secret_key']);
        if ($paydata['sandbox_status'] == 1) {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        } else {
            $options->setBaseUrl("https://api.iyzipay.com"); // production mode
        }

        $conversion_id = uniqid(9999, 999999);
        # create request class
        $iyzipay_request = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
        $iyzipay_request->setLocale(\Iyzipay\Model\Locale::EN);
        $iyzipay_request->setConversationId($conversion_id);
        $iyzipay_request->setPrice($_amount);
        $iyzipay_request->setPaidPrice($_amount);
        $iyzipay_request->setCurrency(\Iyzipay\Model\Currency::TL);
        $iyzipay_request->setBasketId($basket_id);
        $iyzipay_request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $iyzipay_request->setCallbackUrl($_success_url);
        $iyzipay_request->setEnabledInstallments(array(2, 3, 6, 9));

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId(uniqid());
        $buyer->setName($first_name);
        $buyer->setSurname($first_name);
        $buyer->setGsmNumber($phone);
        $buyer->setEmail($email);
        $buyer->setIdentityNumber($identity_number);
        $buyer->setLastLoginDate("");
        $buyer->setRegistrationDate("");
        $buyer->setRegistrationAddress($address);
        $buyer->setIp("");
        $buyer->setCity($city);
        $buyer->setCountry($country);
        $buyer->setZipCode($zip_code);
        $iyzipay_request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($first_name);
        $shippingAddress->setCity($city);
        $shippingAddress->setCountry($country);
        $shippingAddress->setAddress($address);
        $shippingAddress->setZipCode($zip_code);
        $iyzipay_request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($first_name);
        $billingAddress->setCity($city);
        $billingAddress->setCountry($country);
        $billingAddress->setAddress($address);
        $billingAddress->setZipCode($zip_code);
        $iyzipay_request->setBillingAddress($billingAddress);

        $q_id = uniqid(999, 99999);
        $basketItems = array();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId($q_id);
        $firstBasketItem->setName("Purchase Id " . $q_id);
        $firstBasketItem->setCategory1("Purchase or Booking");
        $firstBasketItem->setCategory2("");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
        $firstBasketItem->setPrice($_amount);
        $basketItems[0] = $firstBasketItem;

        $iyzipay_request->setBasketItems($basketItems);

        # make request
        $payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($iyzipay_request, $options);
        $paymentResponse = (array)$payWithIyzicoInitialize;

        foreach ($paymentResponse as $key => $data) {
            $paymentInfo = json_decode($data, true);
            if ($paymentInfo['status'] == 'success') {
                if (!empty($paymentInfo['payWithIyzicoPageUrl'])) {
                    Session::put('conversation_id', $conversion_id);
                    return redirect($paymentInfo['payWithIyzicoPageUrl']);
                }
            }
            return redirect($_cancel_url);
        }
    }


    public function successPayment(Request $request)
    {
        $requestData = Session::get('request');
        $requestData['status'] = 0;
        $requestData['conversation_id'] = Session::get('conversation_id');
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $be = $currentLang->basic_extended;
        /** clear the session payment ID **/
        $cancel_url = route('membership.cancel');

        $paymentFor = Session::get('paymentFor');
        $transaction_id = UserPermissionHelper::uniqidReal(8);
        $transaction_details = null;
        if ($paymentFor == "membership") {
            $amount = $requestData['price'];
            $password = $requestData['password'];
            $checkout = new CheckoutController();
            $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);

            session()->flash('success', __('successful_payment'));
            Session::forget('request');
            Session::forget('paymentFor');
            return redirect()->route('success.page');
        } elseif ($paymentFor == "extend") {
            $amount = $requestData['price'];
            $password = uniqid('qrcode');
            $checkout = new UserCheckoutController();
            $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $be, $password);

            session()->flash('success', __('successful_payment'));
            Session::forget('request');
            Session::forget('paymentFor');
            return redirect()->route('success.page');
        }
        return redirect($cancel_url);
    }
}
