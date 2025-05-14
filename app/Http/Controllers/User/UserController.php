<?php

namespace App\Http\Controllers\User;

use App;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\Admin\UserCategory;
use App\Models\Customer;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Language;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserNewsletterSubscriber;
use App\Models\User\UserOrder;
use App\Models\User\UserPage;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('setlang');
    }

    public function index()
    {
        $user = Auth::guard('web')->user();
        $data['user'] = $user;
        $data['blogs'] = $user->blogs()->count();
        $data['memberships'] = Membership::query()->where('user_id', Auth::guard('web')->user()->id)
            ->orderBy('id', 'DESC')
            ->limit(10)->get();

        $data['users'] = [];

        $nextPackageCount = Membership::query()->where([
            ['user_id', $user->id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
        //current package
        $data['current_membership'] = Membership::query()->where([
            ['user_id', $user->id],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
        if ($data['current_membership']) {
            $countCurrMem = Membership::query()->where([
                ['user_id', $user->id],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
            if ($countCurrMem > 1) {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', $user->id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            } else {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', $user->id],
                    ['start_date', '>', $data['current_membership']->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
        }
        $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
        $data['package_count'] = $nextPackageCount;

        $user_currency = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
        if (empty($user_currency)) {
            $user_currency = UserCurrency::where('user_id', $user->id)->first();
            if ($user_currency) {
                $user_currency->is_default = 1;
                $user_currency->save();
            }
        }

        $data['total_items'] = UserItem::where('user_id', $user->id)->count();
        $data['total_orders'] = UserOrder::where('user_id', $user->id)->count();
        $data['total_customers'] = Customer::where('user_id', $user->id)->count();
        $data['total_custom_pages'] = UserPage::where('user_id', $user->id)->count();
        $data['total_subscribers'] = UserNewsletterSubscriber::where('user_id', $user->id)->count();

        $data['orders'] = UserOrder::where('user_id', $user->id)
            ->orderBy('id', 'DESC')->limit(10)->get();
        return view('user.dashboard', $data);
    }

    public function status(Request $request)
    {
        $user = Auth::user();
        $user->online_status = $request->value;
        $user->save();
        $msg = '';
        if ($request->value == 1) {
            $msg = __("Profile has been made visible");
        } else {
            $msg = __("Profile has been hidden");
        }
        Session::flash('success', $msg);
        return "success";
    }

    public function profile(Request $request)
    {
        $langId = Language::where('code', $request->language)->pluck('id')->first();
        $user = Auth::user();
        $categories =  UserCategory::query()->where('language_id', $langId)->get();

        return view('user.edit-profile', compact('user', 'categories'));
    }

    public function profileupdate(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|dimensions:width=100,height=100',
            'shop_name' => 'required',
            'username' => 'required|unique:users,username,' . Auth::guard('web')->user()->id,
            'phone' => 'required',
            'city' => 'required',
            'country' => 'required',
            'address' => 'required',
        ]);

        $input = $request->all();
        $data = Auth::user();
        if ($request->hasFile('photo')) {
            $directory = public_path('assets/front/img/user/');
            $input['photo'] = Uploader::update_picture($directory, $request->file('photo'), $data->photo);
        }
        $data->update($input);

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function changePass()
    {
        return view('user.changepass');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);
        // if given old password matches with the password of this authenticated user...
        if (Hash::check($request->old_password, Auth::guard('web')->user()->password)) {
            $oldPassMatch = 'matched';
        } else {
            $oldPassMatch = 'not_matched';
        }
        if ($validator->fails() || $oldPassMatch == 'not_matched') {
            if ($oldPassMatch == 'not_matched') {
                $validator->errors()->add('oldPassMatch', true);
            }
            return redirect()->route('user.changePass')
                ->withErrors($validator);
        }

        // updating password in database...
        $user = App\Models\User::findOrFail(Auth::guard('web')->user()->id);
        $user->password = bcrypt($request->password);
        $user->save();

        Session::flash('success', __('The password has been changed successfully'));

        return redirect()->back();
    }

    public function billingupdate(Request $request)
    {
        $request->validate([
            "billing_fname" => 'required',
            "billing_lname" => 'required',
            "billing_email" => 'required',
            "billing_number" => 'required',
            "billing_city" => 'required',
            "billing_state" => 'required',
            "billing_address" => 'required',
            "billing_country" => 'required',
        ]);
        Auth::user()->update($request->all());
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function changeTheme(Request $request)
    {
        return redirect()->back()->withCookie(cookie()->forever('user-theme', $request->theme));
    }
}
