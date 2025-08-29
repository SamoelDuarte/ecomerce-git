<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Common;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\UserPermissionHelper;
use App\Jobs\SubscriptionExpiredMail;
use App\Jobs\SubscriptionReminderMail;
use App\Models\BasicExtended;
use App\Models\BasicSetting;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\User\UserOrder;
use App\Models\User\UserPaymentGeteway;
use Carbon\Carbon;

class CronJobController extends Controller
{
    public function expired()
    {
        $bs = BasicSetting::first();
        $be = BasicExtended::first();


        $exMembers = Membership::whereDate('expire_date', Carbon::now()->subDays(1))->where('modified', '<>', 1)->get();
        foreach ($exMembers as $key => $exMember) {
            if (!empty($exMember->user)) {
                $user = $exMember->user;
                $currPackage = UserPermissionHelper::userPackage($user->id);

                if (is_null($currPackage)) {
                    SubscriptionExpiredMail::dispatch($user, $bs, $be);
                }
            }
        }


        $rmdMembers = Membership::whereDate('expire_date', Carbon::now()->addDays($be->expiration_reminder))->get();
        foreach ($rmdMembers as $key => $rmdMember) {
            if (!empty($rmdMember->user)) {
                $user = $rmdMember->user;
                $nextPackageCount = Membership::query()->where([
                    ['user_id', $user->id],
                    ['start_date', '>', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->count();

                if ($nextPackageCount == 0) {
                    SubscriptionReminderMail::dispatch($user, $bs, $be, $rmdMember->expire_date);
                }
            }
        }

        \Artisan::call("queue:work --stop-when-empty");
    }

    public function check_payment()
    {
        //check iyzico pending payments
        $iyzico_pending_memberships = Membership::where([['status', 0], ['payment_method', 'Iyzico']])->get();
        foreach ($iyzico_pending_memberships as $iyzico_pending_membership) {
            if (!is_null($iyzico_pending_membership->conversation_id)) {
                $result = $this->IyzicoPaymentStatus('admin', null, $iyzico_pending_membership->conversation_id);
                if ($result == 'success') {
                    $this->updateIyzicoPendingMemership($iyzico_pending_membership->id, 1);
                } else {
                    $this->updateIyzicoPendingMemership($iyzico_pending_membership->id, 2);
                }
            }
        }

        //check iyzico pending payments
        $iyzico_pending_orders = UserOrder::where([['payment_status', 'Pending'], ['method', 'Iyzico']])->get();
        foreach ($iyzico_pending_orders as $iyzico_pending_order) {
            if (!is_null($iyzico_pending_order->conversation_id)) {
                $result = $this->IyzicoPaymentStatus('user', $iyzico_pending_order->user_id, $iyzico_pending_order->conversation_id);
                if ($result == 'success') {
                    $this->updateIyzicoPendingOrder($iyzico_pending_order->id, $iyzico_pending_order->user_id, 'Completed');
                }
            }
        }
    }



    /*******************************************************************
     *********** Get iyzico payment status from iyzico server **********
     *******************************************************************/
    private function IyzicoPaymentStatus($type, $user_id, $conversation_id)
    {
        if ($type == 'admin') {
            $paymentMethod = PaymentGateway::where('keyword', 'iyzico')->first();
            $paydata = $paymentMethod->convertAutoData();
        } else {
            $paymentMethod = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'iyzico']])->first();
            $paydata = json_decode($paymentMethod->information, true);
        }

        $options = new \Iyzipay\Options();
        $options->setApiKey($paydata['api_key']);
        $options->setSecretKey($paydata['secret_key']);
        if ($paydata['sandbox_status'] == 1) {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        } else {
            $options->setBaseUrl("https://api.iyzipay.com"); // production mode
        }

        $request = new \Iyzipay\Request\ReportingPaymentDetailRequest();
        $request->setPaymentConversationId($conversation_id);

        $paymentResponse = \Iyzipay\Model\ReportingPaymentDetail::create($request, $options);
        $result = (array) $paymentResponse;

        foreach ($result as $key => $data) {
            $data = json_decode($data, true);
            if ($data['status'] == 'success' && !empty($data['payments'])) {
                if (is_array($data['payments'])) {
                    if ($data['payments'][0]['paymentStatus'] == 1) {
                        return 'success';
                    } else {
                        return 'not found';
                    }
                } else {
                    return 'not found';
                }
            } else {
                return 'not found';
            }
        }
        return 'not found';
    }


    /****************************************************************************
     *********** Update pending membership if payment is successfull ***********
     ****************************************************************************/
    private function updateIyzicoPendingMemership($id, $status)
    {
        $currentLang = Language::where('is_default', 1)->first();
        $be = $currentLang->basic_extended;
        $bs = $currentLang->basic_setting;
        $membership = Membership::query()->findOrFail($id);
        $user = User::query()->findOrFail($membership->user_id);
        $package = Package::query()->findOrFail($membership->package_id);
        $count_membership = Membership::query()->where('user_id', $membership->user_id)->count();

        $member['shop_name'] = $user->shop_name;
        $member['username'] = $user->username;
        $member['email'] = $user->email;
        $data['payment_method'] = $membership->payment_method;

        //comparison date
        $date1 = Carbon::createFromFormat('m/d/Y', \Carbon\Carbon::parse($membership->start_date)->format('m/d/Y'));
        $date2 = Carbon::createFromFormat('m/d/Y', \Carbon\Carbon::now()->format('m/d/Y'));
        $result = $date1->gte($date2);
        if ($result) {
            $data['start_date'] = $membership->start_date;
            $data['expire_date'] = $membership->expire_date;
        } else {
            $data['start_date'] = Carbon::today()->format('d-m-Y');
            if ($package->term === "daily") {
                $data['expire_date'] = Carbon::today()->addDay()->format('d-m-Y');
            } elseif ($package->term === "weekly") {
                $data['expire_date'] = Carbon::today()->addWeek()->format('d-m-Y');
            } elseif ($package->term === "monthly") {
                $data['expire_date'] = Carbon::today()->addMonth()->format('d-m-Y');
            } elseif ($package->term === "lifetime") {
                $data['expire_date'] = Carbon::maxValue()->format('d-m-Y');
            } else {
                $data['expire_date'] = Carbon::today()->addYear()->format('d-m-Y');
            }
            $membership->update(['start_date' =>  Carbon::parse($data['start_date'])]);
            $membership->update(['expire_date' =>  Carbon::parse($data['expire_date'])]);
        }

        // if previous membership package is lifetime, then exipre that membership
        $previousMembership = Membership::query()
            ->where([
                ['user_id', $user->id],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])
            ->where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->first();
        if (!is_null($previousMembership)) {
            $previousPackage = Package::query()
                ->select('term')
                ->where('id', $previousMembership->package_id)
                ->first();
            if ($previousPackage->term === 'lifetime' || $previousMembership->is_trial == 1) {
                $yesterday = Carbon::yesterday()->format('d-m-Y');
                $previousMembership->expire_date = Carbon::parse($yesterday);
                $previousMembership->save();
            }
        }


        if ($count_membership > 1) {

            $mailTemplate = 'payment_accepted_for_membership_extension_offline_gateway';
            $mailType = 'paymentAcceptedForMembershipExtensionOfflineGateway';
        } else {

            $mailTemplate = 'payment_accepted_for_registration_offline_gateway';
            $mailType = 'paymentAcceptedForRegistrationOfflineGateway';

            $user->update([
                'status' => 1
            ]);
        }
        if ($status == 2) {
            $mailTemplate = 'payment_rejected_for_registration_offline_gateway';
        }

        $filename = Common::makeInvoice($data, "membership", $member, $user->password, $membership->price, "offline", $user->phone, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $membership->transaction_id, $package->title, $status);

        $mailer = new MegaMailer();
        $data = [
            'toMail' => $user->email,
            'toName' => $user->fname,
            'username' => $user->username,
            'package_title' => $package->title,
            'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
            'discount' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $membership->discount . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
            'total' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $membership->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
            'activation_date' => $data['start_date'],
            'expire_date' => $package->term == "lifetime" ? 'Lifetime' : $data['expire_date'],
            'membership_invoice' => $filename,
            'website_title' => $bs->website_title,
            'templateType' => $mailTemplate,
            'type' => $mailType
        ];
        $mailer->mailFromAdmin($data);
        $membership->update(['status' => $status]);
    }

    private function updateIyzicoPendingOrder($order_id, $user_id, $status)
    {
        try {
            $order = UserOrder::where('id', $order_id)->first();
            if ($order) {
                $user  = User::where('id', $user_id)->first();
                if ($user) {
                    $order->payment_status = $status;
                    $order->save();
                    Common::generateInvoice($order, $user);
                    Common::OrderCompletedMail($order, $user);
                }
            }
        } catch (\Exception $th) {
        }
    }
}
