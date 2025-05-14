<?php

$domain = env('WEBSITE_HOST');

if (!app()->runningInConsole()) {
    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
        $domain = 'www.' . env('WEBSITE_HOST');
    }
}

Route::domain($domain)->group(function () {
    Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'checkstatus', 'adminLanguage', 'Demo']], function () {
        // RTL check
        Route::get('/rtlcheck/{langid}', 'Admin\LanguageController@rtlcheck')->name('admin.rtlcheck');

        // admin redirect to dashboard route
        Route::get('/change-theme', 'Admin\DashboardController@changeTheme')->name('admin.theme.change');

        // Admin logout Route
        Route::get('/logout', 'Admin\LoginController@logout')->name('admin.logout');

        Route::group(['middleware' => 'checkpermission:Dashboard'], function () {
            // Admin Dashboard Routes
            Route::get('/dashboard', 'Admin\DashboardController@dashboard')->name('admin.dashboard');
        });


        // Admin Profile Routes
        Route::get('/changePassword', 'Admin\ProfileController@changePass')->name('admin.changePass');
        Route::post('/profile/updatePassword', 'Admin\ProfileController@updatePassword')->name('admin.updatePassword');
        Route::get('/profile/edit', 'Admin\ProfileController@editProfile')->name('admin.editProfile');
        Route::post('/profile/update', 'Admin\ProfileController@updateProfile')->name('admin.updateProfile');


        Route::group(['middleware' => 'checkpermission:Settings'], function () {
            // Admin Basic Information Routes
            Route::get('/general-settings', 'Admin\BasicController@generalSetting')->name('admin.general-settings');
            Route::post('/general-settings/post', 'Admin\BasicController@updateGeneralSetting')->name('admin.general-settings.update');


            // Admin Email Settings Routes
            Route::get('/mail-from-admin', 'Admin\EmailController@mailFromAdmin')->name('admin.mailFromAdmin');
            Route::post('/mail-from-admin/update', 'Admin\EmailController@updateMailFromAdmin')->name('admin.mailfromadmin.update');
            Route::get('/mail-to-admin', 'Admin\EmailController@mailToAdmin')->name('admin.mailToAdmin');
            Route::post('/mail-to-admin/update', 'Admin\EmailController@updateMailToAdmin')->name('admin.mailtoadmin.update');
            Route::get('/mail_templates', 'Admin\MailTemplateController@mailTemplates')->name('admin.mail_templates');
            Route::get('/edit_mail_template/{id}', 'Admin\MailTemplateController@editMailTemplate')->name('admin.edit_mail_template');
            Route::post('/update_mail_template/{id}', 'Admin\MailTemplateController@updateMailTemplate')->name('admin.update_mail_template');


            // Admin Scripts Routes
            Route::get('/plugins', 'Admin\BasicController@script')->name('admin.script');
            Route::post('/plugin/update', 'Admin\BasicController@updatescript')->name('admin.script.update');


            // Admin Social Routes
            Route::get('/social', 'Admin\SocialController@index')->name('admin.social.index');
            Route::post('/social/store', 'Admin\SocialController@store')->name('admin.social.store');
            Route::get('/social/{id}/edit', 'Admin\SocialController@edit')->name('admin.social.edit');
            Route::post('/social/update', 'Admin\SocialController@update')->name('admin.social.update');
            Route::post('/social/delete', 'Admin\SocialController@delete')->name('admin.social.delete');


            // Admin Maintanance Mode Routes
            Route::get('/maintainance', 'Admin\BasicController@maintainance')->name('admin.maintainance');
            Route::post('/maintainance/update', 'Admin\BasicController@updatemaintainance')->name('admin.maintainance.update');

            // Admin Cookie Alert Routes
            Route::get('/cookie-alert', 'Admin\BasicController@cookiealert')->name('admin.cookie.alert');
            Route::post('/cookie-alert/{langid}/update', 'Admin\BasicController@updatecookie')->name('admin.cookie.update');
        });



        Route::group(['middleware' => 'checkpermission:Push Notification'], function () {
            // Admin Push Notification Routes
            Route::get('/pushnotification/settings', 'Admin\PushController@settings')->name('admin.pushnotification.settings');
            Route::post('/pushnotification/update/settings', 'Admin\PushController@updateSettings')->name('admin.pushnotification.updateSettings');
            Route::get('/pushnotification/send', 'Admin\PushController@send')->name('admin.pushnotification.send');
            Route::post('/push', 'Admin\PushController@push')->name('admin.pushnotification.push');
        });


        Route::group(['middleware' => 'checkpermission:Menu Builder'], function () {
            Route::get('/menu-builder', 'Admin\MenuBuilderController@index')->name('admin.menu_builder.index');
            Route::post('/menu-builder/update', 'Admin\MenuBuilderController@update')->name('admin.menu_builder.update');
        });


        Route::group(['middleware' => 'checkpermission:Pages'], function () {

            // Admin Hero Section Image & Text Routes
            Route::get('/pages/home-page/image-&-texts', 'Admin\HerosectionController@imgtext')->name('admin.herosection.imgtext');
            Route::post('/herosection/{langid}/update', 'Admin\HerosectionController@update')->name('admin.herosection.update');

            // Admin Feature Routes
            Route::get('/features', 'Admin\FeatureController@index')->name('admin.feature.index');
            Route::post('/feature/store', 'Admin\FeatureController@store')->name('admin.feature.store');
            Route::get('/feature/{id}/edit', 'Admin\FeatureController@edit')->name('admin.feature.edit');
            Route::post('/feature/update', 'Admin\FeatureController@update')->name('admin.feature.update');
            Route::post('/feature/delete', 'Admin\FeatureController@delete')->name('admin.feature.delete');


            // Admin Work Process Routes
            Route::get('/process', 'Admin\ProcessController@index')->name('admin.process.index');
            Route::post('/process/store', 'Admin\ProcessController@store')->name('admin.process.store');
            Route::get('/process/{id}/edit', 'Admin\ProcessController@edit')->name('admin.process.edit');
            Route::post('/process/update', 'Admin\ProcessController@update')->name('admin.process.update');
            Route::post('/process/delete', 'Admin\ProcessController@delete')->name('admin.process.delete');

            // Admin Testimonial Routes
            Route::get('/testimonials', 'Admin\TestimonialController@index')->name('admin.testimonial.index');
            Route::get('/testimonial/create', 'Admin\TestimonialController@create')->name('admin.testimonial.create');
            Route::post('/testimonial/store', 'Admin\TestimonialController@store')->name('admin.testimonial.store');
            Route::post('/testimonial/sideImageStore', 'Admin\TestimonialController@sideImageStore')->name('admin.testimonial.sideImageStore');
            Route::get('/testimonial/{id}/edit', 'Admin\TestimonialController@edit')->name('admin.testimonial.edit');
            Route::post('/testimonial/update', 'Admin\TestimonialController@update')->name('admin.testimonial.update');
            Route::post('/testimonial/delete', 'Admin\TestimonialController@delete')->name('admin.testimonial.delete');
            Route::post('/testimonialtext/{langid}/update', 'Admin\TestimonialController@textupdate')->name('admin.testimonialtext.update');

            Route::prefix('additional-sections')->group(function () {
                Route::get('sections', 'Admin\AdditionalSectionController@index')->name('admin.additional_sections');
                Route::get('add-section', 'Admin\AdditionalSectionController@create')->name('admin.additional_section.create');
                Route::post('store-section', 'Admin\AdditionalSectionController@store')->name('admin.additional_section.store');
                Route::get('edit-section/{id}', 'Admin\AdditionalSectionController@edit')->name('admin.additional_section.edit');
                Route::post('update/{id}', 'Admin\AdditionalSectionController@update')->name('admin.additional_section.update');
                Route::post('delete/{id}', 'Admin\AdditionalSectionController@delete')->name('admin.additional_section.delete');
                Route::post('bulkdelete', 'Admin\AdditionalSectionController@bulkdelete')->name('admin.additional_section.bulkdelete');
            });

            // Admin Section Customization Routes
            Route::get('/sections', 'Admin\BasicController@sections')->name('admin.sections.index');
            Route::post('/sections/update', 'Admin\BasicController@updatesections')->name('admin.sections.update');

            // counter section
            Route::get('/counter-section', 'Admin\CounterInformationController@index')->name('admin.home_page.counter-section');

            Route::post('/update-counter-section-info', 'Admin\CounterInformationController@updateInfo')->name('admin.home_page.update_counter_section_info');

            Route::prefix('about-us')->group(function () {
                Route::get('/update-section-status', 'Admin\BasicController@aboutSectionInfo')->name('admin.abouts.section.hide_show');
                Route::post('/update-section-status/update', 'Admin\BasicController@aboutSectionInfoUpdate')->name('admin.abouts.section.hide_show.update');

                Route::prefix('additional-sections')->group(function () {
                    Route::get('sections', 'Admin\AboutAdditionSectionController@index')->name('admin.about_us.additional_sections');
                    Route::get('add-section', 'Admin\AboutAdditionSectionController@create')->name('admin.about_us.additional_section.create');
                    Route::post('store-section', 'Admin\AboutAdditionSectionController@store')->name('admin.about_us.additional_section.store');
                    Route::get('edit-section/{id}', 'Admin\AboutAdditionSectionController@edit')->name('admin.about_us.additional_section.edit');
                    Route::post('update/{id}', 'Admin\AboutAdditionSectionController@update')->name('admin.about_us.additional_section.update');
                    Route::post('delete/{id}', 'Admin\AboutAdditionSectionController@delete')->name('admin.about_us.additional_section.delete');
                    Route::post('bulkdelete', 'Admin\AboutAdditionSectionController@bulkdelete')->name('admin.about_us.additional_section.bulkdelete');
                });
            });

            Route::prefix('/counter')->group(function () {
                Route::post('/store', 'Admin\CounterInformationController@storeCounter')->name('admin.home_page.store_counter');

                Route::post('/update', 'Admin\CounterInformationController@updateCounter')->name('admin.home_page.update_counter');

                Route::post('{id}/delete', 'Admin\CounterInformationController@destroyCounter')->name('admin.home_page.delete_counter');

                Route::post('/bulk-delete', 'Admin\CounterInformationController@bulkDestroyCounter')->name('admin.home_page.bulk_delete_counter');
            });

            // Admin Partner Routes
            Route::get('/partners', 'Admin\PartnerController@index')->name('admin.partner.index');
            Route::post('/partner/store', 'Admin\PartnerController@store')->name('admin.partner.store');
            Route::post('/partner/upload', 'Admin\PartnerController@upload')->name('admin.partner.upload');
            Route::get('/partner/{id}/edit', 'Admin\PartnerController@edit')->name('admin.partner.edit');
            Route::post('/partner/update', 'Admin\PartnerController@update')->name('admin.partner.update');
            Route::post('/partner/{id}/uploadUpdate', 'Admin\PartnerController@uploadUpdate')->name('admin.partner.uploadUpdate');
            Route::post('/partner/delete', 'Admin\PartnerController@delete')->name('admin.partner.delete');

            // Admin Breadcrumb Routes
            Route::get('/breadcrumb', 'Admin\BasicController@breadcrumb')->name('admin.breadcrumb');
            Route::post('/breadcrumb/update', 'Admin\BasicController@updatebreadcrumb')->name('admin.breadcrumb.update');

            Route::get('headings', 'Admin\BasicController@heading')->name('admin.breadcrumb.heading');
            Route::post('headings/update', 'Admin\BasicController@update_heading')->name('admin.breadcrumb.heading_update');

            // basic settings seo route
            Route::get('/seo', 'Admin\BasicController@seo')->name('admin.seo');
            Route::post('/seo/update', 'Admin\BasicController@updateSEO')->name('admin.seo.update');
        });


        // additional page routes
        Route::group(['middleware' => 'checkpermission:Pages'], function () {
            Route::prefix('pages/additional-pages')->group(function () {
                Route::get('/all-pages', 'Admin\PageController@index')->name('admin.page.index');
                Route::get('/add-page', 'Admin\PageController@create')->name('admin.page.create');
                Route::post('/page/store', 'Admin\PageController@store')->name('admin.page.store');
                Route::get('/page/{menuID}/edit', 'Admin\PageController@edit')->name('admin.page.edit');
                Route::post('/page/update', 'Admin\PageController@update')->name('admin.page.update');
                Route::post('/page/delete', 'Admin\PageController@delete')->name('admin.page.delete');
                Route::post('/page/bulk-delete', 'Admin\PageController@bulkDelete')->name('admin.page.bulk.delete');
            });
        });


        Route::group(['middleware' => 'checkpermission:Pages'], function () {
            // Admin Footer Logo Text Routes
            Route::get('/footers', 'Admin\FooterController@index')->name('admin.footer.index');
            Route::post('/footer/{langid}/update', 'Admin\FooterController@update')->name('admin.footer.update');
            Route::post('/footer/remove/image', 'Admin\FooterController@removeImage')->name('admin.footer.rmvimg');


            // Admin Ulink Routes
            Route::get('/ulinks', 'Admin\UlinkController@index')->name('admin.ulink.index');
            Route::get('/ulink/create', 'Admin\UlinkController@create')->name('admin.ulink.create');
            Route::post('/ulink/store', 'Admin\UlinkController@store')->name('admin.ulink.store');
            Route::post('/ulink/update', 'Admin\UlinkController@update')->name('admin.ulink.update');
            Route::post('/ulink/delete', 'Admin\UlinkController@delete')->name('admin.ulink.delete');
        });


        // Announcement Popup Routes
        Route::group(['middleware' => 'checkpermission:Announcement Popup'], function () {
            Route::get('popups', 'Admin\PopupController@index')->name('admin.popup.index');
            Route::get('popup/types', 'Admin\PopupController@types')->name('admin.popup.types');
            Route::get('popup/{id}/edit', 'Admin\PopupController@edit')->name('admin.popup.edit');
            Route::get('popup/create', 'Admin\PopupController@create')->name('admin.popup.create');
            Route::post('popup/store', 'Admin\PopupController@store')->name('admin.popup.store');;
            Route::post('popup/delete', 'Admin\PopupController@delete')->name('admin.popup.delete');
            Route::post('popup/bulk-delete', 'Admin\PopupController@bulkDelete')->name('admin.popup.bulk.delete');
            Route::post('popup/status', 'Admin\PopupController@status')->name('admin.popup.status');
            Route::post('popup/update', 'Admin\PopupController@update')->name('admin.popup.update');;
        });


        //Users Management
        Route::prefix('users-management')->middleware('checkpermission:Users Management')->group(function () {
            // Register User start
            Route::prefix('register-users')->group(function () {
                Route::get('/', 'Admin\RegisterUserController@index')->name('admin.register.user');
                Route::get('/details/{id}', 'Admin\RegisterUserController@view')->name('register.user.view');
                Route::get('change-passwords/{id}', 'Admin\RegisterUserController@changePass')->name('register.user.changePass');
                Route::get('categories', 'Admin\RegisterUserController@category')->name('register.user.category');
                Route::post('categories/store', 'Admin\RegisterUserController@categoryStore')->name('register.user.category_store');
                Route::get('categories/edit/{id}', 'Admin\RegisterUserController@categoryEdit')->name('register.user.category_edit');
                Route::post('categories/update', 'Admin\RegisterUserController@categoryUpdate')->name('register.user.category_update');
                Route::post('categories/delete', 'Admin\RegisterUserController@categoryDelete')->name('register.user.category_delete');
            });
            Route::post('register/user/store', 'Admin\RegisterUserController@store')->name('register.user.store');
            Route::post('register/users/ban', 'Admin\RegisterUserController@userban')->name('register.user.ban');
            Route::post('register/users/featured', 'Admin\RegisterUserController@userFeatured')->name('register.user.featured');
            Route::post('register/users/template', 'Admin\RegisterUserController@userTemplate')->name('register.user.template');
            Route::post('register/users/template/update', 'Admin\RegisterUserController@userUpdateTemplate')->name('register.user.updateTemplate');
            Route::post('register/users/email', 'Admin\RegisterUserController@emailStatus')->name('register.user.email');

            Route::post('/user/current-package/remove', 'Admin\RegisterUserController@removeCurrPackage')->name('user.currPackage.remove');
            Route::post('/user/current-package/change', 'Admin\RegisterUserController@changeCurrPackage')->name('user.currPackage.change');
            Route::post('/user/current-package/add', 'Admin\RegisterUserController@addCurrPackage')->name('user.currPackage.add');
            Route::post('/user/next-package/remove', 'Admin\RegisterUserController@removeNextPackage')->name('user.nextPackage.remove');
            Route::post('/user/next-package/change', 'Admin\RegisterUserController@changeNextPackage')->name('user.nextPackage.change');
            Route::post('/user/next-package/add', 'Admin\RegisterUserController@addNextPackage')->name('user.nextPackage.add');
            Route::post('register/user/delete', 'Admin\RegisterUserController@delete')->name('register.user.delete');
            Route::post('register/user/bulk-delete', 'Admin\RegisterUserController@bulkDelete')->name('register.user.bulk.delete');
            Route::post('register/user/updatePassword', 'Admin\RegisterUserController@updatePassword')->name('register.user.updatePassword');
            Route::get('register/users/secret-login/{id}', 'Admin\RegisterUserController@secret_login')->name('register.user.secret_login');



            // Admin Subscriber Routes
            Route::get('/subscribers', 'Admin\SubscriberController@index')->name('admin.subscriber.index');
            Route::get('/mailsubscriber', 'Admin\SubscriberController@mailsubscriber')->name('admin.mailsubscriber');
            Route::post('/subscribers/sendmail', 'Admin\SubscriberController@subscsendmail')->name('admin.subscribers.sendmail');
            Route::post('/subscriber/delete', 'Admin\SubscriberController@delete')->name('admin.subscriber.delete');
            Route::post('/subscriber/bulk-delete', 'Admin\SubscriberController@bulkDelete')->name('admin.subscriber.bulk.delete');
        });

        // Package Management
        Route::prefix('package-management')->middleware('checkpermission:Package Management')->group(function () {
            // Package Settings routes
            Route::get('/settings', 'Admin\PackageController@settings')->name('admin.package.settings');
            Route::post('/settings', 'Admin\PackageController@updateSettings')->name('admin.package.settings');
            // Package Settings routes
            Route::get('/package-features', 'Admin\PackageController@features')->name('admin.package.features');
            Route::post('/package/features/update', 'Admin\PackageController@updateFeatures')->name('admin.package.features_update');
            // Package routes
            Route::get('packages', 'Admin\PackageController@index')->name('admin.package.index');
            Route::post('package/upload', 'Admin\PackageController@upload')->name('admin.package.upload');
            Route::post('package/store', 'Admin\PackageController@store')->name('admin.package.store');
            Route::get('package/{id}/edit', 'Admin\PackageController@edit')->name('admin.package.edit');
            Route::post('package/update', 'Admin\PackageController@update')->name('admin.package.update');
            Route::post('package/{id}/uploadUpdate', 'Admin\PackageController@uploadUpdate')->name('admin.package.uploadUpdate');
            Route::post('package/delete', 'Admin\PackageController@delete')->name('admin.package.delete');
            Route::post('package/bulk-delete', 'Admin\PackageController@bulkDelete')->name('admin.package.bulk.delete');
        });

        // Payment Log
        Route::group(['middleware' => 'checkpermission:Payment Logs'], function () {
            Route::get('/payment-log', 'Admin\PaymentLogController@index')->name('admin.payment-log.index');
            Route::post('/payment-log/update', 'Admin\PaymentLogController@update')->name('admin.payment-log.update');
        });


        Route::group(['middleware' => 'checkpermission:Pages'], function () {
            // Admin FAQ Routes
            Route::get('/faqs', 'Admin\FaqController@index')->name('admin.faq.index');
            Route::get('/faq/create', 'Admin\FaqController@create')->name('admin.faq.create');
            Route::post('/faq/store', 'Admin\FaqController@store')->name('admin.faq.store');
            Route::post('/faq/update', 'Admin\FaqController@update')->name('admin.faq.update');
            Route::post('/faq/delete', 'Admin\FaqController@delete')->name('admin.faq.delete');
            Route::post('/faq/bulk-delete', 'Admin\FaqController@bulkDelete')->name('admin.faq.bulk.delete');
        });


        Route::group(['middleware' => 'checkpermission:Pages'], function () {
            // Admin Blog Category Routes
            Route::get('/bcategorys', 'Admin\BcategoryController@index')->name('admin.bcategory.index');
            Route::post('/bcategory/store', 'Admin\BcategoryController@store')->name('admin.bcategory.store');
            Route::get('/bcategory/edit/{id}', 'Admin\BcategoryController@edit')->name('admin.bcategory.edit');
            Route::post('/bcategory/update', 'Admin\BcategoryController@update')->name('admin.bcategory.update');
            Route::post('/bcategory/delete', 'Admin\BcategoryController@delete')->name('admin.bcategory.delete');
            Route::post('/bcategory/bulk-delete', 'Admin\BcategoryController@bulkDelete')->name('admin.bcategory.bulk.delete');


            // Admin Blog Routes
            Route::get('/blog', 'Admin\BlogController@index')->name('admin.blog.index');
            Route::post('/blog/upload', 'Admin\BlogController@upload')->name('admin.blog.upload');
            Route::post('/blog/store', 'Admin\BlogController@store')->name('admin.blog.store');
            Route::get('/blog/{id}/edit', 'Admin\BlogController@edit')->name('admin.blog.edit');
            Route::post('/blog/update', 'Admin\BlogController@update')->name('admin.blog.update');
            Route::post('/blog/{id}/uploadUpdate', 'Admin\BlogController@uploadUpdate')->name('admin.blog.uploadUpdate');
            Route::post('/blog/delete', 'Admin\BlogController@delete')->name('admin.blog.delete');
            Route::post('/blog/bulk-delete', 'Admin\BlogController@bulkDelete')->name('admin.blog.bulk.delete');
            Route::get('/blog/{langid}/getcats', 'Admin\BlogController@getcats')->name('admin.blog.getcats');
        });


        Route::group(['middleware' => 'checkpermission:Sitemaps'], function () {
            Route::get('/sitemap', 'Admin\SitemapController@index')->name('admin.sitemap.index');
            Route::post('/sitemap/store', 'Admin\SitemapController@store')->name('admin.sitemap.store');
            Route::get('/sitemap/{id}/update', 'Admin\SitemapController@update')->name('admin.sitemap.update');
            Route::post('/sitemap/{id}/delete', 'Admin\SitemapController@delete')->name('admin.sitemap.delete');
            Route::post('/sitemap/download', 'Admin\SitemapController@download')->name('admin.sitemap.download');
        });


        Route::group(['middleware' => 'checkpermission:Pages'], function () {
            // Admin Contact Routes
            Route::get('/contact', 'Admin\ContactController@index')->name('admin.contact.index');
            Route::post('/contact/{langid}/post', 'Admin\ContactController@update')->name('admin.contact.update');
        });


        Route::group(['middleware' => 'checkpermission:Settings'], function () {
            // Admin Online Gateways Routes
            Route::get('/gateways', 'Admin\GatewayController@index')->name('admin.gateway.index');
            Route::post('/stripe/update', 'Admin\GatewayController@stripeUpdate')->name('admin.stripe.update');
            Route::post('/anet/update', 'Admin\GatewayController@anetUpdate')->name('admin.anet.update');
            Route::post('/paypal/update', 'Admin\GatewayController@paypalUpdate')->name('admin.paypal.update');
            Route::post('/paystack/update', 'Admin\GatewayController@paystackUpdate')->name('admin.paystack.update');
            Route::post('/paytm/update', 'Admin\GatewayController@paytmUpdate')->name('admin.paytm.update');
            Route::post('/flutterwave/update', 'Admin\GatewayController@flutterwaveUpdate')->name('admin.flutterwave.update');
            Route::post('/instamojo/update', 'Admin\GatewayController@instamojoUpdate')->name('admin.instamojo.update');
            Route::post('/mollie/update', 'Admin\GatewayController@mollieUpdate')->name('admin.mollie.update');
            Route::post('/razorpay/update', 'Admin\GatewayController@razorpayUpdate')->name('admin.razorpay.update');
            Route::post('/mercadopago/update', 'Admin\GatewayController@mercadopagoUpdate')->name('admin.mercadopago.update');
            Route::post('/yoco/update', 'Admin\GatewayController@yocoUpdate')->name('admin.yoco.update');
            Route::post('/xendit/update', 'Admin\GatewayController@xenditUpdate')->name('admin.xendit.update');
            Route::post('/perfect_money/update', 'Admin\GatewayController@perfect_moneyUpdate')->name('admin.perfect_money.update');
            Route::post('/myfatoorah/update', 'Admin\GatewayController@myfatoorahUpdate')->name('admin.myfatoorah.update');
            Route::post('/toyyibpay/update', 'Admin\GatewayController@toyyibpayUpdate')->name('admin.toyyibpay.update');
            Route::post('/midtrans/update', 'Admin\GatewayController@midtransUpdate')->name('admin.midtrans.update');
            Route::post('/iyzico/update', 'Admin\GatewayController@iyzicoUpdate')->name('admin.iyzico.update');
            Route::post('/paytabs/update', 'Admin\GatewayController@paytabsUpdate')->name('admin.paytabs.update');
            Route::post('/phonepe/update', 'Admin\GatewayController@phonepeUpdate')->name('admin.phonepe.update');

            // Admin Offline Gateway Routes
            Route::get('/offline/gateways', 'Admin\GatewayController@offline')->name('admin.gateway.offline');
            Route::post('/offline/gateway/store', 'Admin\GatewayController@store')->name('admin.gateway.offline.store');
            Route::post('/offline/gateway/update', 'Admin\GatewayController@update')->name('admin.gateway.offline.update');
            Route::post('/offline/status', 'Admin\GatewayController@status')->name('admin.offline.status');
            Route::post('/offline/gateway/delete', 'Admin\GatewayController@delete')->name('admin.offline.gateway.delete');
        });


        Route::group(['middleware' => 'checkpermission:Admins Management'], function () {
            // Admin Roles Routes
            Route::get('/roles', 'Admin\RoleController@index')->name('admin.role.index');
            Route::post('/role/store', 'Admin\RoleController@store')->name('admin.role.store');
            Route::post('/role/update', 'Admin\RoleController@update')->name('admin.role.update');
            Route::post('/role/delete', 'Admin\RoleController@delete')->name('admin.role.delete');
            Route::get('role/{id}/permissions/manage', 'Admin\RoleController@managePermissions')->name('admin.role.permissions.manage');
            Route::post('role/permissions/update', 'Admin\RoleController@updatePermissions')->name('admin.role.permissions.update');
        });


        Route::group(['middleware' => 'checkpermission:Admins Management'], function () {
            // Admin Users Routes
            Route::get('/users', 'Admin\UserController@index')->name('admin.user.index');
            Route::post('/user/upload', 'Admin\UserController@upload')->name('admin.user.upload');
            Route::post('/user/store', 'Admin\UserController@store')->name('admin.user.store');
            Route::get('/user/{id}/edit', 'Admin\UserController@edit')->name('admin.user.edit');
            Route::post('/user/update', 'Admin\UserController@update')->name('admin.user.update');
            Route::post('/user/{id}/uploadUpdate', 'Admin\UserController@uploadUpdate')->name('admin.user.uploadUpdate');
            Route::post('/user/delete', 'Admin\UserController@delete')->name('admin.user.delete');
        });


        Route::group(['middleware' => 'checkpermission:Settings'], function () {
            // Admin Language Routes
            Route::get('/languages', 'Admin\LanguageController@index')->name('admin.language.index');
            Route::get('/language/{id}/edit', 'Admin\LanguageController@edit')->name('admin.language.edit');
            Route::get('/language/{id}/edit/keyword', 'Admin\LanguageController@editKeyword')->name('admin.language.editKeyword');
            Route::post('/language/store', 'Admin\LanguageController@store')->name('admin.language.store');
            Route::post('/language/{id}/uploadUpdate', 'Admin\LanguageController@uploadUpdate')->name('admin.language.uploadUpdate');
            Route::post('/language/{id}/default', 'Admin\LanguageController@default')->name('admin.language.default');
            Route::post('/language/{id}/dashboard-default', 'Admin\LanguageController@dashboardDefault')->name('admin.language.dashboardDefault');
            Route::post('/language/{id}/delete', 'Admin\LanguageController@delete')->name('admin.language.delete');
            Route::post('/language/update', 'Admin\LanguageController@update')->name('admin.language.update');
            Route::post('/language/{id}/update/keyword', 'Admin\LanguageController@updateKeyword')->name('admin.language.updateKeyword');
            Route::post('/language/add-keyword', 'Admin\LanguageController@addKeyword')->name('admin.language.add_keyword');
            Route::post('/language/add-keyword/admin', 'Admin\LanguageController@addKeywordForAdmin')->name('admin.language.add_keyword.admin.dashboard');

            Route::get('/language/{id}/edit/admin-dashboard/keyword', 'Admin\LanguageController@editAdminKeyword')->name('admin.language.admin_dashboard.editKeyword');
            Route::post('/language/{id}/update/admin-dashboard-keyword', 'Admin\LanguageController@updateAdminKeyword')->name('admin.language.admin_dashboard.updateKeyword');

            Route::get('/language/{id}/edit/user-dashboard/keyword', 'Admin\LanguageController@editUserKeyword')->name('admin.language.user_dashboard.editKeyword');
            Route::post('/language/{id}/update/user-dashboard-keyword', 'Admin\LanguageController@updateUserDashboardKeyword')->name('admin.language.user_dashboard.updateKeyword');
            Route::get('/language/{id}/edit/user-frontend/keyword', 'Admin\LanguageController@editUserFrontendKeyword')->name('admin.language.user_frontend.editKeyword');
            Route::post('/language/{id}/update/user-frontend-keyword', 'Admin\LanguageController@updateCustomerKeyword')->name('admin.language.user_frontend.updateKeyword');
        });


        // Admin Cache Clear Routes
        Route::get('/cache-clear', 'Admin\CacheController@clear')->name('admin.cache.clear');

        // Custom Domains
        Route::group(['middleware' => 'checkpermission:Custom Domains'], function () {
            Route::get('/domains', 'Admin\CustomDomainController@index')->name('admin.custom-domain.index');
            Route::get('/domain/texts', 'Admin\CustomDomainController@texts')->name('admin.custom-domain.texts');
            Route::post('/domain/texts', 'Admin\CustomDomainController@updateTexts')->name('admin.custom-domain.texts');
            Route::post('/domain/status', 'Admin\CustomDomainController@status')->name('admin.custom-domain.status');
            Route::post('/domain/mail', 'Admin\CustomDomainController@mail')->name('admin.custom-domain.mail');
            Route::post('/domain/delete', 'Admin\CustomDomainController@delete')->name('admin.custom-domain.delete');
            Route::post('/domain/bulk-delete', 'Admin\CustomDomainController@bulkDelete')->name('admin.custom-domain.bulk.delete');
        });

        // Subdomains
        Route::group(['middleware' => 'checkpermission:Subdomains'], function () {
            Route::get('/subdomains', 'Admin\SubdomainController@index')->name('admin.subdomain.index');
            Route::post('/subdomain/status', 'Admin\SubdomainController@status')->name('admin.subdomain.status');
            Route::post('/subdomain/mail', 'Admin\SubdomainController@mail')->name('admin.subdomain.mail');
        });
    });
});
