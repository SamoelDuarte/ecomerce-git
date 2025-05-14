<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Request;
use Session;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if (Request::is('admin') || Request::is('admin/*')) {
                return route('admin.login');
            } elseif (Request::route()->getPrefix() == '{username}/customer' || (Request::route()->getPrefix() == '/customer' && Request::getHost() != env('WEBSITE_HOST'))) {
                Session::forget('user_coupon');
                if (Request::input('type') == 'guest') {
                    return route('front.user.checkout.guest', getParam());
                } else {
                    if (request()->routeIs('front.user.checkout')) {
                        return route('customer.login', [getParam(), 'redirected' => 'checkout']);
                    } else {
                        return route('customer.login', getParam());
                    }
                }
            } else {
                return route('user.login');
            }
        }
    }
}
