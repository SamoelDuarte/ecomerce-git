<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Http\Helpers\MegaMailer;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $data['memberships'] = Membership::query()->when($search, function ($query, $search) {
            return $query->where('transaction_id', 'like', '%' . $search . '%');
        })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('admin.payment_log.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     */
    public function update(Request $request)
    {

        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $be = $currentLang->basic_extended;
        $bs = $currentLang->basic_setting;
        $membership = Membership::query()->findOrFail($request->id);
        $user = User::query()->findOrFail($membership->user_id);
        $package = Package::query()->findOrFail($membership->package_id);
        $count_membership = Membership::query()->where('user_id', $membership->user_id)->count();
        if ($request->status === "1") {
            $member['first_name'] = $user->first_name;
            $member['last_name'] = $user->last_name;
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
            $filename = Common::makeInvoice($data, "membership", $member, $user->password, $membership->price, "offline", $user->phone, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $membership->transaction_id, $package->title,1);

            $mailer = new MegaMailer();
            $data = [
                'toMail' => $user->email,
                'toName' => $user->fname,
                'username' => $user->username,
                'package_title' => $package->title,
                'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
                'activation_date' => $data['start_date'],
                'expire_date' => $package->term == "lifetime" ? 'Lifetime' : $data['expire_date'],
                'membership_invoice' => $filename,
                'website_title' => $bs->website_title,
                'templateType' => $mailTemplate,
                'type' => $mailType
            ];
            $mailer->mailFromAdmin($data);
        } elseif ($request->status == 2) {
            if ($count_membership > 1) {
                $mailTemplate = 'payment_rejected_for_membership_extension_offline_gateway';
                $mailType = 'paymentRejectedForMembershipExtensionOfflineGateway';
            } else {
                $mailTemplate = 'payment_rejected_for_registration_offline_gateway';
                $mailType = 'paymentRejectedForRegistrationOfflineGateway';
            }

            $mailer = new MegaMailer();
            $data = [
                'toMail' => $user->email,
                'toName' => $user->fname,
                'username' => $user->username,
                'package_title' => $package->title,
                'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
                'website_title' => $bs->website_title,
                'templateType' => $mailTemplate,
                'type' => $mailType
            ];
            $mailer->mailFromAdmin($data);
        }

        $membership->update(['status' => $request->status]);
        session()->flash('success', __('Updated Successfully'));
        return back();
    }
}
