<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getUserPageHeading($language)
    {
        if (URL::current() == Route::is('front.user.shop')) {
            $pageHeading = $language->pageName()->select('shop_page')->first();
        } elseif (URL::current() == Route::is('front.user.blogs')) {
            $pageHeading = $language->pageName()->select('blog_page')->first();
        } elseif (URL::current() == Route::is('front.user.contact')) {
            $pageHeading = $language->pageName()->select('contact_page')->first();
        } elseif (URL::current() == Route::is('front.user.about')) {
            $pageHeading = $language->pageName()->select('about_page')->first();
        } elseif (URL::current() == Route::is('customer.wishlist')) {
            $pageHeading = $language->pageName()->select('wishlist_page')->first();
        } elseif (URL::current() == Route::is('front.user.cart')) {
            $pageHeading = $language->pageName()->select('cart_page')->first();
        } elseif (URL::current() == Route::is('front.user.compare')) {
            $pageHeading = $language->pageName()->select('compare_page')->first();
        } elseif (URL::current() == Route::is('customer.login')) {
            $pageHeading = $language->pageName()->select('login_page')->first();
        } elseif (URL::current() == Route::is('customer.signup')) {
            $pageHeading = $language->pageName()->select('signup_page')->first();
        } elseif (URL::current() == Route::is('customer.forget_password')) {
            $pageHeading = $language->pageName()->select('forget_password_page')->first();
        } elseif (URL::current() == Route::is('customer.dashboard')) {
            $pageHeading = $language->pageName()->select('dashboard_page')->first();
        } elseif (URL::current() == Route::is('customer.orders')) {
            $pageHeading = $language->pageName()->select('orders_page')->first();
        } elseif (URL::current() == Route::is('customer.edit_profile')) {
            $pageHeading = $language->pageName()->select('edit_profile_page')->first();
        } elseif (URL::current() == Route::is('customer.billing-details')) {
            $pageHeading = $language->pageName()->select('billing_details_page')->first();
        } elseif (URL::current() == Route::is('customer.shpping-details')) {
            $pageHeading = $language->pageName()->select('shipping_details_page')->first();
        } elseif (URL::current() == Route::is('customer.change_password')) {
            $pageHeading = $language->pageName()->select('change_password_page')->first();
        } elseif (URL::current() == Route::is('front.user.checkout.final_step')) {
            $pageHeading = $language->pageName()->select('checkout_page')->first();
        } elseif (URL::current() == Route::is('front.user.faq')) {
            $pageHeading = $language->pageName()->select('faq_page')->first();
        } else {
            $pageHeading = null;
        }
        return $pageHeading;
    }


    public function getPageHeading($language)
    {
        if (URL::current() == Route::is('front.templates.view')) {
            $pageHeading = $language->pageName()->pluck('template_title')->first();
        } elseif (URL::current() == Route::is('front.pricing')) {
            $pageHeading = $language->pageName()->pluck('pricing_title')->first();
        } elseif (URL::current() == Route::is('front.user.view')) {
            $pageHeading = $language->pageName()->pluck('shop_title')->first();
        } elseif (URL::current() == Route::is('front.faq.view')) {
            $pageHeading = $language->pageName()->pluck('faq_title')->first();
        } elseif (URL::current() == Route::is('front.contact')) {
            $pageHeading = $language->pageName()->pluck('contact_title')->first();
        } elseif (URL::current() == Route::is('front.blogs')) {
            $pageHeading = $language->pageName()->pluck('blog_title')->first();
        } elseif (URL::current() == Route::is('user.login')) {
            $pageHeading = $language->pageName()->pluck('login_title')->first();
        } elseif (URL::current() == Route::is('front.register.view')) {
            $pageHeading = $language->pageName()->pluck('signup_title')->first();
        } elseif (URL::current() == Route::is('front.registration.step2')) {
            $pageHeading = $language->pageName()->pluck('checkout_title')->first();
        } elseif (URL::current() == Route::is('user.forgot.password.form')) {
            $pageHeading = $language->pageName()->pluck('reset_password_title')->first();
        } else {
            $pageHeading = null;
        }
        return $pageHeading;
    }
}
