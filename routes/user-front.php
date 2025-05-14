<?php

$domain = env('WEBSITE_HOST');

if (!app()->runningInConsole()) {
    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
        $domain = 'www.' . env('WEBSITE_HOST');
    }
}

$parsedUrl = parse_url(url()->current());

$host = str_replace("www.", "", $parsedUrl['host']);
if (array_key_exists('host', $parsedUrl)) {
    // if it is a path based URL
    if ($host == env('WEBSITE_HOST')) {
        $domain = $domain;
        $prefix = '/{username}';
    }
    // if it is a subdomain / custom domain
    else {
        if (!app()->runningInConsole()) {
            if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
                $domain = 'www.{domain}';
            } else {
                $domain = '{domain}';
            }
        }
        $prefix = '';
    }
}


Route::group(['domain' => $domain, 'prefix' => $prefix, 'middleware' => ['userVisibilityCheck', 'userLanguage', 'userMaintenance']], function () {
    Route::get('/', 'UserFront\HomeController@userDetailView')
        ->name('front.user.detail.view');
    Route::get('/invoice', 'UserFront\HomeController@invoice')
        ->name('front.user.detail.invoice');

    Route::get('/changelanguage/{code}', 'UserFront\HomeController@changeUserLanguage')->name('front.user.changeUserLanguage');
    Route::get('/changecurrency/{id}', 'UserFront\HomeController@changeUserCurrency')->name('front.user.changeUserCurrency');
    Route::get('apply/{token}', 'UserFront\HomeController@removeMaintenance')->name('front.user.remove')->withoutMiddleware('userMaintenance');

    Route::get('/about', 'UserFront\HomeController@userAbout')->name('front.user.about');

    Route::get('/page/{slug}', 'Front\FrontendController@customPage')->name('front.user.custom.page');
    Route::post('/subscribe', 'User\SubscriberController@Usersubscribe')->name('front.user.subscribe');

    Route::get('/shop', 'UserFront\ShopController@Shop')->name('front.user.shop');
    Route::get('/shop-search', 'UserFront\ShopController@ShopSearch')->name('front.user.shop.search');
    Route::get('/shop-type', 'UserFront\ShopController@shop_type')->name('front.user.shop.shop_type');
    Route::get('/get-variation', 'UserFront\ShopController@get_variation')->name('front.user.shop.get_variation');
    Route::get('/product/{slug}', 'UserFront\ShopController@productDetails')->name('front.user.productDetails');
    Route::post('/product/quick-view/{slug}', 'UserFront\ShopController@productDetailsQuickview')->name('front.user.productDetails.quickview');

    Route::get('product-info/variation/', 'UserFront\ShopController@get_productVariation')->name('front.user.get_variation');
    Route::get('/cart/dropdown', 'UserFront\ItemController@cartDropdown')->name('front.user.cart.dropdown');
    Route::post('/cart/dropdown/count', 'UserFront\ItemController@cartDropdownCount')->name('front.user.cart.dropdownCount');
    Route::post('/compare/count', 'UserFront\ItemController@compareCount')->name('front.user.compare.Count');
    Route::post('/wishlist/count', 'UserFront\ItemController@wishlistCount')->name('front.user.compare.wishlist');

    Route::get('/cart', 'UserFront\ItemController@cart')->name('front.user.cart');
    Route::get('/add-to-cart/{id}', 'UserFront\ItemController@addToCart')->name('front.user.add.cart');
    Route::get('/add-to-wishlist/{id}', 'UserFront\ItemController@addToWishlist')->name('front.user.add.wishlist');
    Route::get('/remove-wishlist/{id}', 'UserFront\ItemController@removeToWishlist')->name('front.user.remove.wishlist');
    Route::get('/add-to-compare/{id}', 'UserFront\ItemController@addToCompare')->name('front.user.add.compare');
    Route::get('/cart/item/remove/{uid}', 'UserFront\ItemController@cartitemremove')->name('front.cart.item.remove');
    Route::get('/compare/item/remove/{uid}', 'UserFront\ItemController@compareitemremove')->name('front.compare.item.remove');
    Route::post('/cart/update', 'UserFront\ItemController@updatecart')->name('front.user.cart.update');
    Route::post('product/review/submit', 'UserFront\ReviewController@reviewsubmit')->name('item.review.submit');
    Route::post('/coupon', 'UserFront\ItemController@coupon')->name('front.coupon');

    Route::get('/contact', 'UserFront\HomeController@contactView')->name('front.user.contact');
    Route::post('/contact-message', 'UserFront\HomeController@contactMessage')->name('front.user.contact.send_message')->middleware('Demo');
    Route::get('/faqs', 'UserFront\HomeController@faqs')->name('front.user.faq');

    Route::prefix('blog')->group(function () {
        Route::get('/', 'UserFront\HomeController@userBlogs')->name('front.user.blogs');
        Route::get('/{slug}', 'UserFront\HomeController@userBlogDetail')->name('user-front.blog_details');
    });

    Route::get('item-variation-converter/{value}/{id}', function ($domain, $value, $id) {
        return currency_converter($value, $id);
    })->name('front.item.variation.currency.convert');


    Route::get('/customer-success', 'UserFront\CustomerController@onlineSuccess')->name('customer.success.page');

    Route::get('/compare', 'UserFront\ItemController@compare')->name('front.user.compare');

    Route::get('/login/google', 'UserFront\CustomerController@redirectToGoogle')->name('customer.google.login');
    Route::get('/login/google/callback', 'UserFront\CustomerController@handleGoogleCallback')->name('customer.google.callback');

    Route::prefix('/customer')->middleware(['guest:customer'])->group(function () {
        // user redirect to login page route
        Route::get('/login',  'UserFront\CustomerController@login')->name('customer.login');
        // user login submit route
        Route::post('/login-submit', 'UserFront\CustomerController@loginSubmit')->name('customer.login_submit');
        // user forget password route
        Route::get('/forgot-password', 'UserFront\CustomerController@forgetPassword')->name('customer.forget_password');
        // send mail to user for forget password route
        Route::post('/send-forget-password-mail', 'UserFront\CustomerController@sendMail')->name('customer.send_forget_password_mail')->middleware('Demo');
        // reset password route
        Route::get('/reset-password', 'UserFront\CustomerController@resetPassword')->name('customer.reset_password');
        // user reset password submit route
        Route::post('/reset-password-submit', 'UserFront\CustomerController@resetPasswordSubmit')->name('customer.reset_password_submit')->middleware('Demo');
        // user redirect to signup page route
        Route::get('/signup', 'UserFront\CustomerController@signup')->name('customer.signup');
        // user signup submit route
        Route::post('/signup-submit', 'UserFront\CustomerController@signupSubmit')->name('customer.signup.submit')->middleware('Demo');
        // signup verify route
        Route::get('/signup-verify/{token}', 'UserFront\CustomerController@signupVerify')->name('customer.signup.verify');
    });


    Route::prefix('/customer')->middleware(['auth:customer', 'accountStatus', 'checkWebsiteOwner', 'Demo'])->group(function () {
        // user redirect to dashboard route
        //user order
        Route::get('/dashboard', 'UserFront\CustomerController@redirectToDashboard')->name('customer.dashboard');
        Route::get('/shipping/details', 'UserFront\CustomerController@shippingdetails')->name('customer.shpping-details');
        Route::post('/shipping/details/update', 'UserFront\CustomerController@shippingupdate')->name('customer.shipping-update');
        Route::get('/billing/details', 'UserFront\CustomerController@billingdetails')->name('customer.billing-details');
        Route::post('/billing/details/update', 'UserFront\CustomerController@billingupdate')->name('customer.billing-update');
        // edit profile route
        Route::get('/edit-profile', 'UserFront\CustomerController@editProfile')->name('customer.edit_profile');
        // update profile route
        Route::post('/update-profile', 'UserFront\CustomerController@updateProfile')->name('customer.update_profile');
        // all ads route
        Route::get('/order/{id}', 'UserFront\CustomerController@orderdetails')->name('customer.orders-details');
        Route::get('/orders', 'UserFront\CustomerController@customerOrders')->name('customer.orders');
        Route::get('/wishlist', 'UserFront\CustomerController@customerWishlist')->name('customer.wishlist');
        Route::get('/remove-from-wishlist/{id}', 'UserFront\CustomerController@removefromWish')->name('customer.removefromWish');

        Route::get('/checkout/process', 'UserFront\ItemController@checkout_process')->name('front.user.checkout');
        Route::get('/checkout', 'UserFront\ItemController@checkout')->name('front.user.checkout.final_step');

        Route::get('/change-password',  'UserFront\CustomerController@changePassword')->name('customer.change_password');
        // update password route
        Route::post('/update-password',  'UserFront\CustomerController@updatePassword')->name('customer.update_password');
        // user logout attempt route
        Route::get('/logout',  'UserFront\CustomerController@logoutSubmit')->name('customer.logout');
    });

    Route::post('/coupon', 'UserFront\ItemController@coupon')->name('front.coupon');

    Route::get('/checkout/guest', 'UserFront\ItemController@checkoutGuest')->name('front.user.checkout.guest');

    Route::post('/item/payment/submit', 'UserFront\UsercheckoutController@checkout')->name('item.payment.submit')->middleware('Demo');



    Route::group(['middleware' => ['routeAccess:Testimonial']], function () {
        Route::get('/testimonial', 'Front\FrontendController@userTestimonial')->name('front.user.testimonial');
    });

    Route::group(['middleware' => ['routeAccess:Contact']], function () {
        Route::post('/contact/message', 'Front\FrontendController@contactMessage')->name('front.contact.message')->middleware('Demo');
    });
    Route::get('/user/changelanguage', 'Front\FrontendController@changeUserLanguage')->name('changeUserLanguage');

    Route::post('/product/payment/instruction', 'UserFront\UsercheckoutController@paymentInstruction')->name('product.payment.paymentInstruction');

    Route::prefix('order')->group(function () {
        Route::get('paypal/success', "User\Payment\PaypalController@successPayment")->name('customer.itemcheckout.paypal.success');
        Route::any('/cancel', "UserFront\UsercheckoutController@cancelPayment")->name('customer.itemcheckout.cancel');

        Route::get('paystack/success', 'User\Payment\PaystackController@successPayment')->name('customer.itemcheckout.paystack.success');

        Route::get('mercadopago/success', 'User\Payment\MercadopagoController@successPayment')->name('customer.itemcheckout.mercadopago.success');

        Route::post('razorpay/success', 'User\Payment\RazorpayController@successPayment')->name('customer.itemcheckout.razorpay.success');

        Route::get('instamojo/success', 'User\Payment\InstamojoController@successPayment')->name('customer.itemcheckout.instamojo.success');

        Route::post('flutterwave/success', 'User\Payment\FlutterWaveController@successPayment')->name('customer.itemcheckout.flutterwave.success');

        Route::get('/mollie/success', 'User\Payment\MollieController@successPayment')->name('customer.itemcheckout.mollie.success');

        Route::get('/yoco/success', 'User\Payment\YocoController@successPayment')->name('customer.itemcheckout.yoco.success');

        Route::get('/xendit/success', 'User\Payment\YocoController@successPayment')->name('customer.itemcheckout.xendit.success');
        Route::get('/perfect-money/success', 'User\Payment\PerfectMoneyController@successPayment')->name('customer.itemcheckout.perfect_money.success');

        Route::get('/myfatoorah/success', 'User\Payment\MyfatoorahController@successPayment')->name('customer.itemcheckout.myfatoorah.success');
        Route::get('/toyyibpay/success', 'User\Payment\ToyyibpayController@successPayment')->name('customer.itemcheckout.toyyibpay.success');
        Route::post('/paytabs/success', 'User\Payment\PaytabsController@successPayment')->name('customer.itemcheckout.paytabs.success');
        Route::post('/phonepe/success', 'User\Payment\PhonePeController@successPayment')->name('customer.itemcheckout.phonepe.success');
        Route::get('/midtrans/success', 'User\Payment\MidtransController@successPayment')->name('customer.itemcheckout.midtrans.success');
        Route::post('/iyzico/success', 'User\Payment\IyzicoController@successPayment')->name('customer.itemcheckout.iyzico.success');

        Route::get('/offline/success', 'UserFront\UsercheckoutController@offlineSuccess')->name('customer.itemcheckout.offline.success');

        Route::post('paytm/payment-status', "User\Payment\PaytmController@paymentStatus")->name('customer.itemcheckout.paytm.status');
    });
});
