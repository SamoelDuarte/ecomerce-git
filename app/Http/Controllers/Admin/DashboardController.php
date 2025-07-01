<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserNewsletterSubscriber;
use App\Models\User\UserOrder;
use App\Models\User\UserPage;
use App\Models\User\Blog;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
  public function dashboard()
  {
    $data['incomes'] = Membership::select(DB::raw('MONTH(created_at) month'), DB::raw('sum(price) total'))->where('status', 1)->groupBy('month')->whereYear('created_at', date('Y'))->get();
    $data['users'] = User::join('memberships', 'users.id', '=', 'memberships.user_id')
      ->select(DB::raw('MONTH(users.created_at) month'), DB::raw('count(*) total'))
      ->groupBy('month')
      ->whereYear('users.created_at', date('Y'))
      ->where([
        ['memberships.status', '=', 1],
        ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
        ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
      ])
      ->get();
    $data['defaultLang'] = Language::where('is_default', 1)->first();


    return view('admin.dashboard', $data);
  }

  // public function ecommerce()
  // {
  //   $user = Auth::guard('web')->user();
  //   $data['user'] = $user;
  //   $data['blogs'] = $user->blogs()->count();
  //   $data['memberships'] = Membership::query()->where('user_id', Auth::guard('web')->user()->id)
  //     ->orderBy('id', 'DESC')
  //     ->limit(10)->get();

  //   $data['users'] = [];

  //   $nextPackageCount = Membership::query()->where([
  //     ['user_id', $user->id],
  //     ['expire_date', '>=', Carbon::now()->toDateString()]
  //   ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
  //   //current package
  //   $data['current_membership'] = Membership::query()->where([
  //     ['user_id', $user->id],
  //     ['start_date', '<=', Carbon::now()->toDateString()],
  //     ['expire_date', '>=', Carbon::now()->toDateString()]
  //   ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
  //   if ($data['current_membership']) {
  //     $countCurrMem = Membership::query()->where([
  //       ['user_id', $user->id],
  //       ['start_date', '<=', Carbon::now()->toDateString()],
  //       ['expire_date', '>=', Carbon::now()->toDateString()]
  //     ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
  //     if ($countCurrMem > 1) {
  //       $data['next_membership'] = Membership::query()->where([
  //         ['user_id', $user->id],
  //         ['start_date', '<=', Carbon::now()->toDateString()],
  //         ['expire_date', '>=', Carbon::now()->toDateString()]
  //       ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
  //     } else {
  //       $data['next_membership'] = Membership::query()->where([
  //         ['user_id', $user->id],
  //         ['start_date', '>', $data['current_membership']->expire_date]
  //       ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
  //     }
  //     $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
  //   }
  //   $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
  //   $data['package_count'] = $nextPackageCount;

  //   $user_currency = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
  //   if (empty($user_currency)) {
  //     $user_currency = UserCurrency::where('user_id', $user->id)->first();
  //     if ($user_currency) {
  //       $user_currency->is_default = 1;
  //       $user_currency->save();
  //     }
  //   }

  //   $data['total_items'] = UserItem::where('user_id', $user->id)->count();
  //   $data['total_orders'] = UserOrder::where('user_id', $user->id)->count();
  //   $data['total_customers'] = Customer::where('user_id', $user->id)->count();
  //   $data['total_custom_pages'] = UserPage::where('user_id', $user->id)->count();
  //   $data['total_subscribers'] = UserNewsletterSubscriber::where('user_id', $user->id)->count();

  //   $data['orders'] = UserOrder::where('user_id', $user->id)
  //     ->orderBy('id', 'DESC')->limit(10)->get();
  //   return view('admin.ecommerce.dashboard', $data);
  // }

  public function ecommerce()
  {
    $data = [];

    // Contadores globais
    $data['blogs'] = Blog::count();
    $data['total_items'] = UserItem::count();
    $data['total_orders'] = UserOrder::count();
    $data['total_customers'] = Customer::count();
    $data['total_custom_pages'] = UserPage::count();
    $data['total_subscribers'] = UserNewsletterSubscriber::count();

    // Últimos pedidos de todos os usuários
    $data['orders'] = UserOrder::with('user')
    ->orderBy('id', 'DESC')
    ->limit(10)
    ->get();

    // Memberships recentes (últimos 10)
    $data['memberships'] = Membership::orderBy('id', 'DESC')->limit(10)->get();

    // Pacotes ativos no momento
    $data['current_membership'] = Membership::where('start_date', '<=', now())
      ->where('expire_date', '>=', now())
      ->where('status', 1)
      ->whereYear('start_date', '<>', '9999')
      ->first();

    $data['next_membership'] = Membership::where('start_date', '>', now())
      ->whereYear('start_date', '<>', '9999')
      ->where('status', '<>', 2)
      ->orderBy('start_date')
      ->first();

    $data['current_package'] = $data['current_membership']
      ? Package::find($data['current_membership']->package_id)
      : null;

    $data['next_package'] = $data['next_membership']
      ? Package::find($data['next_membership']->package_id)
      : null;

    $data['package_count'] = Membership::where('expire_date', '>=', now())
      ->whereYear('start_date', '<>', '9999')
      ->where('status', '<>', 2)
      ->count();

    // Lista de usuários (opcional)
    $data['users'] = User::select('id', 'username', 'shop_name')->get();

    return view('admin.ecommerce.dashboard', $data);
  }


  public function changeTheme(Request $request)
  {
    return redirect()->back()->withCookie(cookie()->forever('admin-theme', $request->theme));
  }
}
