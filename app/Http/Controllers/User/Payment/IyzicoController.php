<?php

namespace App\Http\Controllers\User\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\UserPermissionHelper;

class IyzicoController extends Controller
{
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url)
    {
        $paymentMethod = UserPaymentGeteway::where([['keyword', 'iyzico'], ['user_id', getUser()->id]])->first();
        $paydata = json_decode($paymentMethod->information, true);

        $first_name = $request['billing_fname'];
        $last_name = $request['billing_lname'];
        $email = $request['billing_email'];
        $address = $request['billing_address'];
        $city = $request['billing_city'];
        $country = $request['billing_country'];
        $phone = $request['billing_number'];
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
        $buyer->setSurname($last_name);
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
        $firstBasketItem->setCategory1("Product Purchase");
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
                    $in = $request->all();
                    $in['conversation_id'] = $conversion_id;
                    Session::put('user_request', $in);
                    return redirect($paymentInfo['payWithIyzicoPageUrl']);
                }
            }
            return redirect($_cancel_url);
        }
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('user_request');
        $user = getUser();

        $txnId = UserPermissionHelper::uniqidReal(8);
        $chargeId = $request->paymentId;
        $order = Common::saveOrder($requestData, $txnId, $chargeId, 'Pending', 'online',$user->id);
        $order_id = $order->id;

        Common::saveOrderedItems($order_id);
        session()->flash('success', __('successful_payment'));
        Session::forget('user_request');
        Session::forget('user_amount');
        Session::forget('cart');
        return redirect()->route('customer.success.page', getParam());
    }
}
