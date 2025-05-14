<?php

namespace App\Providers;

use App\Http\Helpers\UserPermissionHelper;
use App\Models\BasicExtended;
use App\Models\CustomerWishList;
use App\Models\User\UserContact;
use App\Models\User\UserCurrency;
use App\Models\User\UserFooter;
use App\Models\User\UserHeader;
use App\Models\User\UserItemCategory;
use App\Models\User\UserMenu;
use App\Models\User\UserUlink;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\Social;
use App\Models\Language;
use App\Models\User\Language as UserLanguage;
use App\Models\Menu;
use App\Models\User\BasicSetting;
use App\Models\User\BasicExtende;
use App\Models\User\SEO;
use App\Models\User\UserPermission;
use App\Models\User\UserShopSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('user', function () {
            return getUser();
        });

        //user-dashboard current langauge
        $this->app->singleton('userCurrentLang', function () {
            $user = app('user');
            if (session()->has('user_lang')) {
                $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
                if (empty($userCurrentLang)) {
                    $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                    session()->put('user_lang', $userCurrentLang->code);
                }
            } else {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
            }
            return $userCurrentLang;
        });

        //user basic-settings
        $this->app->singleton('userBs', function () {
            $user = app('user');
            $userBs = BasicSetting::where('user_id', $user->id)->first();
            return $userBs;
        });

        //user basic-extend
        $this->app->singleton('userBe', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            $userBe = BasicExtende::where([
                ['user_id', $user->id],
                ['language_id', $userCurrentLang->id]
            ])->first();

            return $userBe;
        });

        //user item-categories
        $this->app->singleton('categories', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            $categories = UserItemCategory::with([
                'subcategories' => function ($query) {
                    return $query->where('status', 1);
                }
            ])->where('language_id', $userCurrentLang->id)
                ->where([['user_id', $user->id], ['status', 1]])
                ->orderBy('serial_number', 'ASC')
                ->get();

            return $categories;
        });
        //user header-content
        $this->app->singleton('header', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            $header = UserHeader::where('language_id', $userCurrentLang->id)
                ->where('user_id', $user->id)
                ->first();
            return $header;
        });
        //user usefull links
        $this->app->singleton('ulinks', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            $ulinks = UserUlink::where('language_id', $userCurrentLang->id)
                ->where('user_id', $user->id)
                ->get();
            return $ulinks;
        });
        //user footer
        $this->app->singleton('footer', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            $footer = UserFooter::where('language_id', $userCurrentLang->id)
                ->where('user_id', $user->id)
                ->first();
            return $footer;
        });
        //user currency
        $this->app->singleton('userCurrency', function () {
            $user = app('user');
            $userCurrency = UserCurrency::where('user_id', $user->id)->get();
            return $userCurrency;
        });
        //user languages
        $this->app->singleton('userLangs', function () {
            $user = app('user');
            $userLangs = UserLanguage::where('user_id', $user->id)->get();
            return $userLangs;
        });
        //user Contact info
        $this->app->singleton('userContact', function () {
            $user = app('user');
            $userCurrentLang = app('userCurrentLang');
            $userContact = UserContact::where([
                ['user_id', $user->id],
                ['language_id', $userCurrentLang->id]
            ])->first();
            return $userContact;
        });
        //user shoping settings
        $this->app->singleton('shop_settings', function () {
            $user = app('user');
            $shop_settings = UserShopSetting::where('user_id', $user->id)->first();
            return $shop_settings;
        });
        //user social_medias
        $this->app->singleton('social_medias', function () {
            $user = app('user');
            $social_medias = $user->social_media()->get() ?? collect([]);
            return $social_medias;
        });

        //admin all languages
        $this->app->singleton('langs', function () {
            return Language::all();
        });
        //admin front current language
        $this->app->singleton('currentLang', function () {
            if (session()->has('lang')) {
                $currentLang = Language::where('code', session()->get('lang'))->first();
            } else {
                $currentLang = Language::where('is_default', 1)->first();
            }
            return $currentLang;
        });
        //selected currency for currency converter helper
        $this->app->singleton('userCurrentCurr', function () {
            if (Session::has('myfatoorah_user')) {
                $user = Session::get('myfatoorah_user');
            } else {
                $user = app('user');
            }

            if (session()->has('user_curr')) {
                $userCurrentCurr = UserCurrency::where('id', session()->get('user_curr'))->select('id', 'value')->first();

                if (empty($userCurrentCurr)) {
                    $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->select('id', 'value')->first();
                    session()->put('user_curr', $userCurrentCurr->id);
                }
            } else {
                $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->select('value', 'id')->first();
            }
            return $userCurrentCurr;
        });
        //selected currency for currency converter helper
        $this->app->singleton('userDefaultCurrency', function () {
            if (Session::has('myfatoorah_user')) {
                $user = Session::get('myfatoorah_user');
            } else {
                $user = app('user');
            }
            $userDefaultCurrency = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->select('id', 'value')->first();
            return $userDefaultCurrency;
        });
    }

    public function changePreferences($userId)
    {
        $currentPackage = UserPermissionHelper::currentPackagePermission($userId);

        $preference = UserPermission::where([
            ['user_id', $userId]
        ])->first();

        // if current package does not match with 'package_id' of 'user_permissions' table, then change 'package_id' in 'user_permissions'
        if (!empty($currentPackage) && ($currentPackage->id != $preference->package_id)) {
            $preference->package_id = $currentPackage->id;

            $features = !empty($currentPackage->features) ? json_decode($currentPackage->features, true) : [];
            $features[] = "Contact";
            $features[] = "Footer Mail";
            $features[] = "Profile Listing";
            $preference->permissions = json_encode($features);
            $preference->package_id = $currentPackage->id;
            $preference->save();
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Paginator::useBootstrap();

        if (!app()->runningInConsole()) {
            $socials = Social::orderBy('serial_number', 'ASC')->get();
            $langs = app('langs');

            View::composer('*', function ($view) {
                $currentLang = app('currentLang');
                $bs = $currentLang->basic_setting;
                $be = $currentLang->basic_extended;

                if (Cookie::has('userDashboardLang')) {
                    $userDashboardLang = Language::where('code', Cookie::get('userDashboardLang'))->first();
                } else {
                    $userDashboardLang = Language::where('is_default', 1)->first();
                    Cookie::queue('userDashboardLang', $userDashboardLang->code, 60 * 24 * 30);
                }

                $view->with('bs', $bs);
                $view->with('be', $be);
                $view->with('currentLang', $currentLang);
            });

            View::composer(['front.*'], function ($view) {
                $currentLang = app('currentLang');
                if (Menu::where('language_id', $currentLang->id)->count() > 0) {
                    $menus = Menu::where('language_id', $currentLang->id)->first()->menus;
                } else {
                    $menus = json_encode([]);
                }

                if ($currentLang->rtl == 1) {
                    $rtl = 1;
                } else {
                    $rtl = 0;
                }

                $view->with('menus', $menus);
                $view->with('rtl', $rtl);
            });

            View::composer(['user.*'], function ($view) {
                if (Auth::check()) {
                    $userId = Auth::user()->id;
                    // change package_id in 'user_permissions'
                    $this->changePreferences($userId);
                    $userBs = DB::table('user_basic_settings')->where('user_id', $userId)->first();

                    $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($userId);
                    if (!empty($package)) {
                        $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($userId);
                        $permissions = json_decode($permissions, true);
                        $view->with(['permissions' => $permissions]);
                    }

                    //for translate tenant dashboard start
                    if (Cookie::has('userDashboardLang')) {
                        $userDashboardLang = Language::where('code', Cookie::get('userDashboardLang'))->first();
                    } else {
                        $userDashboardLang = Language::where('is_default', 1)->first();
                        Cookie::queue('userDashboardLang', $userDashboardLang->code, 60 * 24 * 30);
                    }

                    Session::put('user_lang', 'user_' . $userDashboardLang->code);
                    app()->setLocale('user_' . $userDashboardLang->code);

                    $shopSetting = UserShopSetting::where('user_id', $userId)->select('time_format')->first();

                    $be = BasicExtended::where('language_id', $userDashboardLang->id)->select('package_features', 'cname_record_section_text', 'cname_record_section_title')->first();

                    $view->with([
                        'userBs' => $userBs,
                        'dashboard_language' => $userDashboardLang,
                        'defaultLang' => $userDashboardLang->code,
                        'shopSetting' => $shopSetting,
                        'package_features' => $be->package_features,
                        'package' => $package,
                        'cname_record_section_text' => $be->cname_record_section_text,
                        'cname_record_section_title' => $be->cname_record_section_title
                    ]);
                }
            });
            View::composer(['admin.*'], function ($view) {
                if (session()->has('admin_lang')) {
                    $lang_code = str_replace('admin_', '', session()->get('admin_lang'));
                    $language = Language::where('code', $lang_code)->first();
                    if (empty($language)) {
                        $language = Language::where('is_default', 1)->first();
                    }
                } else {
                    $language = Language::where('is_default', 1)->first();
                }
                View::share('default', $language);
            });

            View::composer(['user-front.*'], function ($view) {
                $user = app('user');
                // change package_id in 'user_permissions'
                $this->changePreferences($user->id);

                $userCurrentLang = app('userCurrentLang');
                $keywords = json_decode($userCurrentLang->keywords, true);

                if (UserMenu::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->count() > 0) {
                    $userMenus = UserMenu::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->first()->menus;
                } else {
                    $userMenus = json_encode([]);
                }
                $userBs = app('userBs');
                $userBe = app('userBe');
                $userContact = app('userContact');
                $userCurrency = app('userCurrency');
                $userLangs = app('userLangs');
                $userLangs = app('userLangs');
                $social_medias = app('social_medias');

                if (session()->has('user_curr')) {
                    session()->put('user_curr', session()->get('user_curr'));
                } else {
                    $userCurrentCurr = UserCurrency::where('user_id', $user->id)->where('is_default', 1)->first();
                    session()->put('user_curr', $userCurrentCurr->id);
                    session()->put('user_curr_sign', $userCurrentCurr->symbol);
                }

                $ulinks =  app('ulinks');
                $header =  app('header');
                $footer =  app('footer');
                $categories =  app('categories');
                $shop_settings =  app('shop_settings');

                $packagePermissions = UserPermissionHelper::packagePermission($user->id);
                $packagePermissions = json_decode($packagePermissions, true);
                $ubs = app('userBs');

                if ($userCurrentLang->rtl == 1) {
                    $rtl = 1;
                } else {
                    $rtl = 0;
                }

                $user_id = $user->id;
                $compareCount = 0;
                if (Session::get('compare')) {
                    $compare = Session::get('compare');
                    if (!is_null($compare) && is_array($compare)) {
                        $compare = array_filter($compare, function ($item) use ($user_id) {
                            return $item['user_id'] == $user_id;
                        });
                    }
                    $compareCount = count($compare);
                }

                if (!empty($user)) {
                    if (Auth::guard('customer')->check()) {
                        $wishListCount = CustomerWishList::where([['customer_id', Auth::guard('customer')->user()->id], ['user_id', $user->id]])
                            ->count();
                    } else {
                        $wishListCount = 0;
                    }
                } else {
                    $wishListCount = 0;
                }

                $cart = Session::get('cart');
                $cartCount = 0;
                if ($cart) {
                    if (!is_null($cart) && is_array($cart)) {
                        $cart = array_filter($cart, function ($item) use ($user_id) {
                            return $item['user_id'] == $user_id;
                        });
                    }
                    $cartCount = count($cart);
                }

                $view->with('wishListCount', $wishListCount);
                $view->with('cartCount', $cartCount);
                $view->with('compareCount', $compareCount);
                $view->with('rtl', $rtl);
                $view->with('user', $user);
                $view->with('userBs', $userBs);
                $view->with('userBe', $userBe);
                $view->with('userContact', $userContact);
                $view->with('footer', $footer);
                $view->with('header', $header);
                $view->with('categories', $categories);
                $view->with('ulinks', $ulinks);
                $view->with('userMenus', $userMenus);
                $view->with('userCurrency', $userCurrency);
                $view->with('social_medias', $social_medias);
                $view->with('userCurrentLang', $userCurrentLang);
                $view->with('userLangs', $userLangs);
                $view->with('keywords', $keywords);
                $view->with('packagePermissions', $packagePermissions);
                $view->with('ubs', $ubs);
                $view->with('shop_settings', $shop_settings);
            });

            View::share('langs', $langs);
            View::share('socials', $socials);
        }
    }
}
