<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\Language;
use App\Models\Membership;
use App\Models\OfflineGateway;
use App\Models\Package;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;

class BuyPlanController extends Controller
{
    public function index()
    {
        $user_id = Auth::guard('web')->user()->id;
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['bex'] = $currentLang->basic_extended;
        $data['packages'] = Package::where('status', '1')->get();

        $nextPackageCount = Membership::query()->where([
            ['user_id', $user_id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
        //current package
        $data['current_membership'] = Membership::query()->where([
            ['user_id', $user_id],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
        if ($data['current_membership']) {
            $countCurrMem = Membership::query()->where([
                ['user_id', $user_id],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
            if ($countCurrMem > 1) {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', $user_id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            } else {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', $user_id],
                    ['start_date', '>', $data['current_membership']->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
        }
        $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
        $data['package_count'] = $nextPackageCount;


        return view('user.buy_plan.index', $data);
    }

    public function checkout($package_id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $packageCount = Membership::query()->where([
            ['user_id', $user_id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();

        $hasPendingMemb = UserPermissionHelper::hasPendingMembership($user_id);


        if ($hasPendingMemb) {
            Session::flash('warning', __('You already have a pending membership request'));
            return back();
        }
        if ($packageCount >= 2) {
            Session::flash('warning', __('You have another package to activate after the current package expires. You cannot purchase or extend any package until the next package is activated'));
            return back();
        }

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()
                ->get('lang'))
                ->first();
        } else {
            $currentLang = Language::where('is_default', 1)
                ->first();
        }
        $be = $currentLang->basic_extended;
        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::all();
        $data['offline'] = $offline;
        $data['payment_methods'] = $online->merge($offline);
        $data['checkout_package'] = Package::query()->findOrFail($package_id);
        $data['membership'] = Membership::query()->where([
            ['user_id', $user_id],
            ['expire_date', '>=', \Carbon\Carbon::now()->format('Y-m-d')]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')
            ->latest()
            ->first();
        $data['previousPackage'] = null;
        if (!is_null($data['membership'])) {
            $data['previousPackage'] = Package::query()
                ->where('id', $data['membership']->package_id)
                ->first();
        }
        $data['bex'] = $be;
        return view('user.buy_plan.checkout', $data);
    }
}
