<?php
/*=======================================================
******************** User Dashboard Routes **********************
=======================================================*/

Route::group(['prefix' => 'user', 'middleware' => ['auth', 'userstatus', 'Demo', 'userLanguage']], function () {
    // user theme change
    Route::get('/change-theme', 'User\UserController@changeTheme')->name('user.theme.change');
    // RTL check
    Route::get('/rtlcheck/{langid}', 'User\LanguageController@rtlcheck')->name('user.rtlcheck');
    Route::get('/rtlcheck2/{langid}', 'User\LanguageController@rtlcheck2')->name('user.rtlcheck2');

    Route::get('/dashboard', 'User\UserController@index')->name('user-dashboard');
    Route::get('/profile', 'User\UserController@profile')->name('user-profile');
    Route::post('/profile', 'User\UserController@profileupdate')->name('user-profile-update')->middleware('limitCheck:items,update');
    Route::get('/logout', 'User\Auth\LoginController@logout')->name('user-logout');
    Route::post('/change-status', 'User\UserController@status')->name('user-status');


    // User Hero Section Image & Text Routes
    // home page hero-section slider-version route start
    Route::prefix('pages')->group(function () {
        Route::get('/menu-builder', 'User\MenuBuilderController@index')->name('user.menu_builder.index');
        Route::post('/menu-builder/update', 'User\MenuBuilderController@update')->name('user.menu_builder.update')->middleware('limitCheck:items,update');

        //breadcrumb and page heading routes
        Route::prefix('breadcrumbs')->group(function () {
            Route::get('/image', 'User\BasicController@breadcrumb')->name('user.breadcrumb');
            Route::post('/image/update', 'User\BasicController@updatebreadcrumb')->name('user.breadcrumb.update')->middleware('limitCheck:items,update,without_ajax');

            Route::get('headings', 'User\HeadingController@index')->name('user.breadcrumb.heading');
            Route::post('headings/update', 'User\HeadingController@update')->name('user.breadcrumb.heading_update')->middleware('limitCheck:items,update');
        });
        //home page route
        Route::prefix('home-page')->group(function () {
            Route::prefix('hero-slider')->group(function () {
                Route::get('/', 'User\HeroSliderController@sliderVersion')->name('user.home_page.hero.slider_version');
                Route::get('/create_slider', 'User\HeroSliderController@createSlider')->name('user.home_page.hero.create_slider');
                Route::post('/store_slider_info', 'User\HeroSliderController@storeSliderInfo')->name('user.home_page.hero.store_slider_info')->middleware('limitCheck:items,update,without_ajax');
                Route::get('/edit_slider/{id}', 'User\HeroSliderController@editSlider')->name('user.home_page.hero.edit_slider');
                Route::post('/update_slider_info/{id}', 'User\HeroSliderController@updateSliderInfo')->name('user.home_page.hero.update_slider_info')->middleware('limitCheck:items,update,without_ajax');
                Route::post('/delete_slider', 'User\HeroSliderController@deleteSlider')->name('user.home_page.hero.delete_slider');
                Route::post('/static/{language}', 'User\HeroSliderController@updateStaticSlider')->name('user.home_page.heroStatic.update_slider_info')->middleware('limitCheck:items,update,without_ajax');

                Route::get('hero-section-background-image/', 'User\HeroSliderController@HeroSecBgImg')->name('user.home_page.herosec.bacground_img');
                Route::post('/update/hero-section-background-image', 'User\HeroSliderController@updateHeroSecBgImg')->name('user.home_page.heroSec.update_bacground_img');

                Route::get('/hero-section-product-sliders', 'User\HeroSliderController@productSlider')->name('user.home_page.heroSec.product_slider');
                Route::post('/hero-section-product-sliders/update', 'User\HeroSliderController@updateProductSlider')->name('user.home_page.heroSec.product_slider.update');
            });

            Route::get('/cta-section', 'User\CtaSectionController@index')->name('user.cta_section.index');
            Route::post('/cta-section/update', 'User\CtaSectionController@update')->name('user.cta_section.update');


            // home page banner-section route start
            Route::prefix('banner_section')->group(function () {
                Route::get('/', 'User\BannerSectionController@bannerSection')->name('user.home_page.banner_section');
                Route::post('/store_banner', 'User\BannerSectionController@storebanner')->name('user.home_page.banner_section.store_banner')->middleware('limitCheck:items,update');
                Route::post('/update_banner', 'User\BannerSectionController@updatebanner')->name('user.home_page.banner_section.update_banner')->middleware('limitCheck:items,update');
                Route::post('/delete_banner', 'User\BannerSectionController@deletebanner')->name('user.home_page.banner_section.delete_banner');
            });

            Route::prefix('features')->group(function () {
                Route::get('/', 'User\HowItWorkController@index')->name('user.home_page.heroSec.how_it_work');
                Route::post('/store', 'User\HowItWorkController@store')->name('user.home_page.heroSec.how_it_work.store')->middleware('limitCheck:items,update');
                Route::post('/update', 'User\HowItWorkController@update')->name('user.home_page.heroSec.how_it_work.update')->middleware('limitCheck:items,update');
                Route::post('/delete', 'User\HowItWorkController@delete')->name('user.home_page.heroSec.how_it_work.delete');
            });

            // home page Tab Image route start
            Route::prefix('tabs')->group(function () {
                Route::get('', 'User\TabSectionController@index')->name('user.tab.index');
                Route::post('/store', 'User\TabSectionController@store')->name('user.tab.store')->middleware('limitCheck:items,update');
                Route::post('/update', 'User\TabSectionController@update')->name('user.tab.update')->middleware('limitCheck:items,update');
                Route::post('/feature', 'User\TabSectionController@feature')->name('user.tab.feature');
                Route::post('/delete', 'User\TabSectionController@delete')->name('user.tab.delete');
                Route::post('/bulk-delete', 'User\TabSectionController@bulkDelete')->name('user.tab.bulk.delete');
                Route::get('/tab/{id}', 'User\TabSectionController@products')->name('user.tab.products');
                Route::post('/products/store', 'User\TabSectionController@productsStore')->name('user.tab.products.store');
            });

            //additinal section
            Route::prefix('additional-sections')->group(function () {
                Route::get('sections', 'User\AdditionalSectionController@index')->name('user.additional_sections');
                Route::get('add-section', 'User\AdditionalSectionController@create')->name('user.additional_section.create');
                Route::post('store-section', 'User\AdditionalSectionController@store')->name('user.additional_section.store')->middleware('limitCheck:items,update');
                Route::get('edit-section/{id}', 'User\AdditionalSectionController@edit')->name('user.additional_section.edit');
                Route::post('update/{id}', 'User\AdditionalSectionController@update')->name('user.additional_section.update')->middleware('limitCheck:items,update');
                Route::post('delete/{id}', 'User\AdditionalSectionController@delete')->name('user.additional_section.delete');
                Route::post('bulkdelete', 'User\AdditionalSectionController@bulkdelete')->name('user.additional_section.bulkdelete');
            });

            // user home page text routes
            Route::get('/home-page-text-section', 'User\HomePageTextController@index')->name('user.home.section.index');
            Route::post('/home-page-text-section/{langid}/update', 'User\HomePageTextController@update')->name('user.home.section.update')->middleware('limitCheck:items,update');
        });

        Route::prefix('about-us/')->group(function () {

            Route::get('/', 'User\AboutUsController@about')->name('user.pages.aboutus.about');
            Route::post('about/update', 'User\AboutUsController@updaetAbout')->name('user.pages.about_us.update')->middleware('limitCheck:items,update,without_ajax');

            Route::prefix('features')->group(function () {
                Route::get('/', 'User\AboutUsController@features')->name('user.pages.about_us.features.index');
                Route::post('/store', 'User\AboutUsController@feature_store')->name('user.pages.about_us.features.store')->middleware('limitCheck:items,update');
                Route::get('/edit/{id}', 'User\AboutUsController@feature_edit')->name('user.pages.about_us.features.edit');
                Route::post('/update/{id}', 'User\AboutUsController@feature_update')->name('user.pages.about_us.features.update')->middleware('limitCheck:items,update');
                Route::post('/delete', 'User\AboutUsController@delete_features')->name('user.pages.about_us.delete_features');
                Route::post('/bulk-delete', 'User\AboutUsController@bulk_delete_features')->name('user.pages.about_us.bulk_delete_features');
            });

            //counter section
            Route::prefix('pages/counter-section/')->group(function () {
                Route::get('/', 'User\CounterInformationController@counter')->name('user.pages.counter_section.index');
                Route::post('/update', 'User\CounterInformationController@updateInfo')->name('user.pages.counter_section.update')->middleware('limitCheck:items,update,without_ajax');

                Route::prefix('counter')->group(function () {
                    Route::post('/store', 'User\CounterInformationController@storeCounter')->name('user.pages.counter_section.counter.store')->middleware('limitCheck:items,update');
                    Route::get('/edit/{id}', 'User\CounterInformationController@counter_edit')->name('user.pages.counter_section.counter.edit');
                    Route::post('/update/{id}', 'User\CounterInformationController@counter_update')->name('user.pages.counter_section.counter.update')->middleware('limitCheck:items,update');
                    Route::post('/delete/{id}', 'User\CounterInformationController@delete_counter')->name('user.pages.counter_section.delete_counter');
                    Route::post('/bulk-delete', 'User\CounterInformationController@bulk_delete_counter')->name('user.pages.counter_section.bulk_delete_counter');
                });
            });

            //testimonial
            Route::prefix('testimonials')->group(function () {
                Route::get('/', 'User\AboutTestimonialController@index')->name('user.about_us.testimonial.index');
                Route::post('/section/update', 'User\AboutTestimonialController@updateInfo')->name('user.about_us.testimonials.section_info.update')->middleware('limitCheck:items,update,without_ajax');
                Route::post('/store', 'User\AboutTestimonialController@store')->name('user.about_us.testimonial.store')->middleware('limitCheck:items,update');
                Route::get('/edit/{id}', 'User\AboutTestimonialController@edit')->name('user.about_us.testimonial.edit');
                Route::post('/update/{id}', 'User\AboutTestimonialController@update')->name('user.about_us.testimonialUpdate')->middleware('limitCheck:items,update');
                Route::post('/delete', 'User\AboutTestimonialController@delete')->name('user.about_us.testimonial.delete');
                Route::post('/bulk/delete', 'User\AboutTestimonialController@bulk_delete')->name('user.about_us.testimonial.bulk.delete');
            });

            Route::prefix('additional-sections')->group(function () {
                Route::get('sections', 'User\AboutAdditionalSectionController@index')->name('user.about.additional_sections');
                Route::get('add-section', 'User\AboutAdditionalSectionController@create')->name('user.about.additional_section.create');
                Route::post('store-section', 'User\AboutAdditionalSectionController@store')->name('user.about.additional_section.store')->middleware('limitCheck:items,update');
                Route::get('edit-section/{id}', 'User\AboutAdditionalSectionController@edit')->name('user.about.additional_section.edit');
                Route::post('update/{id}', 'User\AboutAdditionalSectionController@update')->name('user.about.additional_section.update')->middleware('limitCheck:items,update');
                Route::post('delete/{id}', 'User\AboutAdditionalSectionController@delete')->name('user.about.additional_section.delete');
                Route::post('bulkdelete', 'User\AboutAdditionalSectionController@bulkdelete')->name('user.about.additional_section.bulkdelete');
            });

            Route::get('/sections', 'User\AboutUsController@sections')->name('user.about.sections.index');
            Route::post('/sections/update', 'User\AboutUsController@updatesections')->name('user.about.sections.update');
        });
    });

    Route::get('register/users', 'User\RegisterCustomerController@index')->name('user.register.user');
    Route::post('register/user/store', 'User\RegisterCustomerController@store')->name('user.register.user.store')->middleware('limitCheck:items,update');
    Route::post('register/users/ban', 'User\RegisterCustomerController@userban')->name('user.register.user.ban')->middleware('limitCheck:items,update,without_ajax');
    Route::post('register/users/featured', 'User\RegisterCustomerController@userFeatured')->name('user.register.user.featured');
    Route::post('register/users/template', 'User\RegisterCustomerController@userTemplate')->name('user.register.user.template');
    Route::post('register/users/template/update', 'User\RegisterCustomerController@userUpdateTemplate')->name('user.register.user.updateTemplate');
    Route::post('register/users/email', 'User\RegisterCustomerController@emailStatus')->name('user.register.user.email')->middleware('limitCheck:items,update,without_ajax');
    Route::get('register/user/details/{id}', 'User\RegisterCustomerController@view')->name('user.register.user.view');
    Route::post('register/user/delete', 'User\RegisterCustomerController@delete')->name('user.register.user.delete');
    Route::post('register/user/bulk-delete', 'User\RegisterCustomerController@bulkDelete')->name('user.register.user.bulk.delete');
    Route::get('register/user/{id}/changePassword', 'User\RegisterCustomerController@changePass')->name('user.register.user.changePass');
    Route::post('register/user/updatePassword', 'User\RegisterCustomerController@updatePassword')->name('user.register.user.updatePassword')->middleware('limitCheck:items,update,without_ajax');
    Route::get('register/user/secret-login/{id}', 'User\RegisterCustomerController@secret_login')->name('user.register.user.secret_login');

    // home page banner-section route start

    Route::get('/users/footers', 'User\FooterSectionController@index')->name('user.footer.index');
    Route::post('/users/footer/{langid}/update', 'User\FooterSectionController@update')->name('user.footer.update')->middleware('limitCheck:items,update');
    Route::post('/users/footer/remove/image', 'User\FooterSectionController@removeImage')->name('user.footer.rmvimg');

    Route::get('/users/headers', 'User\HeaderSectionController@index')->name('user.header.index');
    Route::post('/users/header/{langid}/update', 'User\HeaderSectionController@update')->name('user.header.update')->middleware('limitCheck:items,update');
    Route::post('/users/header/remove/image', 'User\HeaderSectionController@removeImage')->name('user.header.rmvimg');

    Route::post('currency/status/{id1}/{id2}', 'User\CurrencyController@status')->name('user-currency-status');


    // User Ulink Routes
    Route::get('/users/ulinks', 'User\UlinkSectionController@index')->name('user.ulink.index');
    Route::get('/users/ulink/create', 'User\UlinkSectionController@create')->name('user.ulink.create');
    Route::post('/users/ulink/store', 'User\UlinkSectionController@store')->name('user.ulink.store')->middleware('limitCheck:items,update');
    Route::get('/users/ulink/{id}/edit', 'User\UlinkSectionController@edit')->name('user.ulink.edit');
    Route::post('/users/ulink/update', 'User\UlinkSectionController@update')->name('user.ulink.update')->middleware('limitCheck:items,update');
    Route::post('/users/ulink/delete', 'User\UlinkSectionController@delete')->name('user.ulink.delete');

    // user QR Builder
    Route::group(['middleware' => 'checkUserPermission:QR Builder'], function () {
        Route::get('/saved/qrs', 'User\QrController@index')->name('user.qrcode.index');
        Route::post('/saved/qr/delete', 'User\QrController@delete')->name('user.qrcode.delete')->withoutMiddleware('Demo');
        Route::post('/saved/qr/bulk-delete', 'User\QrController@bulkDelete')->name('user.qrcode.bulk.delete')->withoutMiddleware('Demo');
        Route::get('/qr-code', 'User\QrController@qrCode')->name('user.qrcode');
        Route::post('/qr-code/generate', 'User\QrController@generate')->name('user.qrcode.generate')->withoutMiddleware('Demo');
        Route::get('/qr-code/clear', 'User\QrController@clear')->name('user.qrcode.clear');
        Route::post('/qr-code/save', 'User\QrController@save')->name('user.qrcode.save')->withoutMiddleware('Demo');
    });

    Route::get('/change-password', 'User\UserController@changePass')->name('user.changePass');
    Route::post('/profile/updatePassword', 'User\UserController@updatePassword')->name('user.updatePassword')->middleware('limitCheck:items,update,without_ajax');
    // user start register-user, ban user, details, reports
    Route::post('user/customer/ban', 'User\UserController@userban')->name('user.customer.ban');
    Route::get('register/customer/details/{id}', 'User\UserController@view')->name('register.customer.view');
    Route::post('register/customer/email', 'User\UserController@emailStatus')->name('register.customer.email');
    Route::get('/ads-reports', 'User\PostController@viewReports')->name('user.ads-report');
    Route::get('/register-user', 'User\UserController@registerUsers')->name('user.register-user');
    Route::get('register/customer/{id}/changePassword', 'User\UserController@changePassCstmr')->name('register.customer.changePass');
    Route::post('register/customer/updatePassword', 'User\UserController@updatePasswordCstmr')->name('register.customer.updatePassword');
    Route::post('register/customer/delete', 'User\UserController@delete')->name('register.customer.delete');
    Route::post('/digital/download', 'User\OrderController@digitalDownload')->name('user-digital-download');
    // user End register-user, ban user, details, reports

    Route::prefix('site-settings')->middleware('checkpermission:Site Settings')->group(function () {
        // general sttings route
        Route::get('/general-settings', 'User\BasicController@generalSettings')->name('user.basic_settings.general-settings');
        Route::post('general-settings/updateinfo', 'User\BasicController@updateInfo')->name('user.general_settings.update_info')->middleware('limitCheck:items,update');

        // themes route
        Route::get('/themes', 'User\BasicController@themeVersion')->name('user.theme.version');
        Route::post('/theme/update_version', 'User\BasicController@updateThemeVersion')->name('user.theme.update')->middleware('limitCheck:items,update');

        // plugins route
        Route::get('/plugins', 'User\PluginController@plugins')->name('user.plugins');
        Route::post('/googlelogin/update', 'User\PluginController@updategooglelogin')->name('user.googlelogin.update')->middleware('limitCheck:items,update,without_ajax');
        Route::post('/whatsapp/update', 'User\PluginController@updateWhatsapp')->name('user.whatsapp.update')->middleware('limitCheck:items,update,without_ajax');
        Route::post('/tawk/update', 'User\PluginController@updateTawkTo')->name('user.tawk.update')->middleware('limitCheck:items,update,without_ajax');
        Route::post('/disqus/update', 'User\PluginController@updateDisqus')->name('user.disqus.update')->middleware('limitCheck:items,update,without_ajax');
        Route::post('/analytics/update', 'User\PluginController@updateGoogleAnalytics')->name('user.google.analytics.update')->middleware('limitCheck:items,update,without_ajax');
        Route::post('/recaptcha/update', 'User\PluginController@updateRecaptcha')->name('user.recaptcha.update')->middleware('limitCheck:items,update,without_ajax');
        Route::post('/pixel/update', 'User\PluginController@updatePixel')->name('user.pixel.update')->middleware('limitCheck:items,update,without_ajax');

        // maintenance-mode route
        Route::get('/maintenance-mode', 'User\BasicController@maintenance')->name('user.maintenance_mode');
        Route::post('/update-maintenance-mode', 'User\BasicController@updateMaintenance')->name('user.update_maintenance_mode')->middleware('limitCheck:items,update');

        // user Social routes
        Route::prefix('social-links')->group(function () {
            Route::get('', 'User\SocialController@index')->name('user.social.index');
            Route::post('/store', 'User\SocialController@store')->name('user.social.store')->middleware('limitCheck:items,update,without_ajax');
            Route::get('/{id}/edit', 'User\SocialController@edit')->name('user.social.edit');
            Route::post('/update', 'User\SocialController@update')->name('user.social.update')->middleware('limitCheck:items,update');;
            Route::post('/delete', 'User\SocialController@delete')->name('user.social.delete');
        });

        //cookie-alert routes
        Route::get('/cookie-alert', 'User\BasicController@cookieAlert')->name('user.cookie.alert');
        Route::post('/cookie-alert/{langid}/update', 'User\BasicController@updateCookie')->name('user.cookie.update')->middleware('limitCheck:items,update,without_ajax');

        //  seo informations route
        Route::get('/seo-informations', 'User\BasicController@seo')->name('user.basic_settings.seo');
        Route::post('/update_seo_informations', 'User\BasicController@updateSEO')->name('user.basic_settings.update_seo_informations')->middleware('limitCheck:items,update,without_ajax');

        //email settings
        Route::prefix('email-settings')->group(function () {
            Route::get('/mail-templates', 'User\MailTemplateController@mailTemplates')->name('user.basic_settings.mail_templates');
            Route::get('/edit-mail-template/{id}', 'User\MailTemplateController@editMailTemplate')->name('user.basic_settings.edit_mail_template');
            Route::post('/update_mail_template/{id}', 'User\MailTemplateController@updateMailTemplate')->name('user.basic_settings.update_mail_template')->middleware('limitCheck:items,update,without_ajax');

            Route::get('/mail-information', 'User\SubscriberController@getMailInformation')->name('user.mail.information');
            Route::post('/mail-information/update', 'User\SubscriberController@storeMailInformation')->name('user.mail.subscriber')->middleware('limitCheck:items,update,without_ajax');
        });

        //user language
        Route::get('/languages', 'User\LanguageController@index')->name('user.language.index');
        Route::get('/language/{id}/edit', 'User\LanguageController@edit')->name('user.language.edit');
        Route::get('/language/{id}/edit/keyword', 'User\LanguageController@editKeyword')->name('user.language.editKeyword');
        Route::post('/language/{id}/update/keyword', 'User\LanguageController@updateKeyword')->name('user.language.updateKeyword')->middleware('limitCheck:items,update,without_ajax');
        Route::post('/language/store', 'User\LanguageController@store')->name('user.language.store')->middleware('limitCheck:languages,store');
        Route::post('/language/upload', 'User\LanguageController@upload')->name('user.language.upload');
        Route::post('/language/{id}/uploadUpdate', 'User\LanguageController@uploadUpdate')->name('user.language.uploadUpdate')->middleware('limitCheck:languages,update');
        Route::post('/language/{id}/default', 'User\LanguageController@default')->name('user.language.default');
        Route::post('/language/{id}/dashboard-default', 'User\LanguageController@dashboardDefault')->name('user.language.dashboardDefault');
        Route::post('/language/{id}/delete', 'User\LanguageController@delete')->name('user.language.delete');
        Route::post('/language/update', 'User\LanguageController@update')->name('user.language.update')->middleware('limitCheck:languages,update');
        Route::post('/language/add-keyword', 'User\LanguageController@addKeyword')->name('user.language.add_keyword')->middleware('limitCheck:languages,update');

        // User Online Gateways Routes
        Route::get('/gateways', 'User\GatewayController@index')->name('user.gateway.index');
        Route::middleware('limitCheck:items,update,without_ajax')->group(function () {
            Route::post('/stripe/update', 'User\GatewayController@stripeUpdate')->name('user.stripe.update');
            Route::post('/anet/update', 'User\GatewayController@anetUpdate')->name('user.anet.update');
            Route::post('/paypal/update', 'User\GatewayController@paypalUpdate')->name('user.paypal.update');
            Route::post('/paystack/update', 'User\GatewayController@paystackUpdate')->name('user.paystack.update');
            Route::post('/paytm/update', 'User\GatewayController@paytmUpdate')->name('user.paytm.update');
            Route::post('/flutterwave/update', 'User\GatewayController@flutterwaveUpdate')->name('user.flutterwave.update');
            Route::post('/instamojo/update', 'User\GatewayController@instamojoUpdate')->name('user.instamojo.update');
            Route::post('/mollie/update', 'User\GatewayController@mollieUpdate')->name('user.mollie.update');
            Route::post('/razorpay/update', 'User\GatewayController@razorpayUpdate')->name('user.razorpay.update');
            Route::post('/mercadopago/update', 'User\GatewayController@mercadopagoUpdate')->name('user.mercadopago.update');
            Route::post('/yoco/update', 'User\GatewayController@yocoUpdate')->name('user.yoco.update');
            Route::post('/xendit/update', 'User\GatewayController@xenditUpdate')->name('user.xendit.update');
            Route::post('/perfect_money/update', 'User\GatewayController@perfectMoneyUpdate')->name('user.perfect_money.update');
            Route::post(
                '/myfatoorah/update',
                'User\GatewayController@myfatoorahUpdate'
            )->name('user.myfatoorah.update');
            Route::post('/toyyibpay/update', 'User\GatewayController@toyyibpayUpdate')->name('user.toyyibpay.update');
            Route::post('/paytabs/update', 'User\GatewayController@paytabsUpdate')->name('user.paytabs.update');
            Route::post('/phonepe/update', 'User\GatewayController@phonepeUpdate')->name('user.phonepe.update');
            Route::post('/midtrans/update', 'User\GatewayController@midtransUpdate')->name('user.midtrans.update');
            Route::post('/iyzico/update', 'User\GatewayController@iyzicoUpdate')->name('user.iyzico.update');
        });

        // User Offline Gateway Routes
        Route::get('/offline/gateways', 'User\GatewayController@offline')->name('user.gateway.offline');
        Route::post(
            '/offline/gateway/store',
            'User\GatewayController@store'
        )->name('user.gateway.offline.store')->middleware('limitCheck:items,update');
        Route::post('/offline/gateway/update', 'User\GatewayController@update')->name('user.gateway.offline.update')->middleware('limitCheck:items,update');
        Route::post('/offline/status', 'User\GatewayController@status')->name('user.offline.status')->middleware('limitCheck:items,update,without_ajax');
        Route::post('/offline/gateway/delete', 'User\GatewayController@delete')->name('user.offline.gateway.delete');


        Route::prefix('domains-&-urls')->group(function () {
            // User Domains & URLs
            Route::group(['middleware' => 'checkUserPermission:Custom Domain'], function () {
                Route::get('/domains', 'User\DomainController@domains')->name('user-domains');
                Route::post('/request/domain', 'User\DomainController@domainrequest')->name('user-domain-request')->middleware('limitCheck:items,update,without_ajax');
            });
            // User Subdomains & URLs
            Route::get('/subdomain', 'User\SubdomainController@subdomain')->name('user-subdomain');
        });
    });



    //user contact page route
    Route::get('/contact-page', 'User\ContactController@index')->name('user.contact');
    Route::post('/contact-page/update/{language}', 'User\ContactController@update')->name('user.contact.update')->middleware('limitCheck:items,update,without_ajax');

    //user 404 page route
    Route::get('/404-page', 'User\BasicController@userNotFoundPage')->name('user.not_found_page');
    Route::post('/404-page/update/{language}', 'User\BasicController@updateUserNotFoundPage')->name('user.not_found_page.update')->middleware('limitCheck:items,update');

    // user preloader routes
    Route::get('/preloader', 'User\BasicController@preloader')->name('user.preloader');
    Route::post('/preloader/post', 'User\BasicController@updatepreloader')->name('user.preloader.update');

    Route::prefix('faq')->group(function () {
        Route::get('/', 'User\BasicController@faqindex')->name('user.faq.index');
        Route::get('/create', 'User\BasicController@faqcreate')->name('user.faq.create');
        Route::post('/store', 'User\BasicController@faqstore')->name('user.faq.store')->middleware('limitCheck:items,update');
        Route::post('/update', 'User\BasicController@faqupdate')->name('user.faq.update')->middleware('limitCheck:items,update');
        Route::post('/delete', 'User\BasicController@faqdelete')->name('user.faq.delete');
        Route::post('/bulk-delete', 'User\BasicController@faqbulkDelete')->name('user.faq.bulk.delete');
    });

    Route::prefix('currency')->group(function () {
        Route::get('/', 'User\CurrencyController@index')->name('user-currency-index');
        Route::post('/create', 'User\CurrencyController@store')->name('user-currency-store')->middleware('limitCheck:items,update');
        Route::post('/delete', 'User\CurrencyController@delete')->name('user-currency-delete');

        Route::get('/{id}/edit', 'User\CurrencyController@edit')->name('user-currency-edit');
        Route::post('/update', 'User\CurrencyController@update')->name('user-currency-update')->middleware('limitCheck:items,update');
    });


    //user subscriber routes
    Route::get('/subscribers', 'User\SubscriberController@index')->name('user.subscriber.index');
    Route::get('/mailsubscriber', 'User\SubscriberController@mailsubscriber')->name('user.mailsubscriber');
    Route::post('/subscribers/sendmail', 'User\SubscriberController@subscsendmail')->name('user.subscribers.sendmail')->middleware('limitCheck:items,update');
    Route::post('/subscriber/delete', 'User\SubscriberController@delete')->name('user.subscriber.delete');
    Route::post('/subscriber/bulk-delete', 'User\SubscriberController@bulkDelete')->name('user.subscriber.bulk.delete');


    Route::prefix('pages/blog')->middleware('checkUserPermission:Blog')->group(function () {
        //user blog categories
        Route::get('/categories', 'User\BlogCategoryController@index')->name('user.blog.category.index');
        Route::post('/category/store', 'User\BlogCategoryController@store')->name('user.blog.category.store');
        Route::get('/category/edit/{id}', 'User\BlogCategoryController@edit')->name('user.blog.category.edit');
        Route::post('/category/update', 'User\BlogCategoryController@update')->name('user.blog.category.update');
        Route::post('/category/delete', 'User\BlogCategoryController@delete')->name('user.blog.category.delete');
        Route::post('/category/bulk-delete', 'User\BlogCategoryController@bulkDelete')->name('user.blog.category.bulk.delete');

        //user blogs
        Route::prefix('posts')->group(function () {
            Route::get('/', 'User\BlogController@index')->name('user.blog.index');
            Route::post('/upload', 'User\BlogController@upload')->name('user.blog.upload');
            Route::get('/create', 'User\BlogController@create')->name('user.blog.create');
            Route::post('/store', 'User\BlogController@store')->name('user.blog.store')->middleware('limitCheck:blogs,store');
            Route::get('/{id}/edit', 'User\BlogController@edit')->name('user.blog.edit');
            Route::post('/update', 'User\BlogController@update')->name('user.blog.update')->middleware('limitCheck:blogs,update');
            Route::post('/{id}/uploadUpdate', 'User\BlogController@uploadUpdate')->name('user.blog.uploadUpdate');
            Route::post('/delete', 'User\BlogController@delete')->name('user.blog.delete');
            Route::post('/bulk-delete', 'User\BlogController@bulkDelete')->name('user.blog.bulk.delete');
            Route::get('/getcats', 'User\BlogController@getcats')->name('user.blog.getcats');
            Route::post('/status/update', 'User\BlogController@update_status')->name('user.blog.status.update');
        });
    });


    //user achievements
    Route::group(['middleware' => 'checkUserPermission:Achievements'], function () {
        Route::get('/achievements', 'User\AchievementController@index')->name('user.achievement.index');
        Route::post('/achievement/store', 'User\AchievementController@store')->name('user.achievement.store');
        Route::get('/achievement/{id}/edit', 'User\AchievementController@edit')->name('user.achievement.edit');
        Route::post('/achievement/update', 'User\AchievementController@update')->name('user.achievement.update');
        Route::post('/achievement/delete', 'User\AchievementController@delete')->name('user.achievement.delete');
        Route::post('/achievement/bulk-delete', 'User\AchievementController@bulkDelete')->name('user.achievement.bulk.delete');
    });


    Route::prefix('membership')->middleware('checkpermission:Membership')->group(function () {
        // Payment Log
        Route::get('/logs', 'User\PaymentLogController@index')->name('user.payment-log.index');
        //user package extend route
        Route::get('/extend-membership', 'User\BuyPlanController@index')->name('user.plan.extend.index');
        Route::get('/extend-membership/package/checkout/{package_id}', 'User\BuyPlanController@checkout')->name('user.plan.extend.checkout');
        Route::post('/package/checkout', 'User\UserCheckoutController@checkout')->name('user.plan.checkout');
    });

    Route::prefix('shipping-charges')->middleware('checkpermission:Shipping Charges')->group(function () {
        Route::get('', 'User\ShopSettingController@index')->name('user.shipping.index');
        Route::post('/store', 'User\ShopSettingController@store')->name('user.shipping.store')->middleware('limitCheck:items,update');
        Route::post('/update', 'User\ShopSettingController@update')->name('user.shipping.update')->middleware('limitCheck:items,update');
        Route::post('/delete', 'User\ShopSettingController@delete')->name('user.shipping.delete');
    });


    // User Coupon Routes
    Route::prefix('coupon')->middleware('checkpermission:Coupon')->group(function () {
        Route::get('', 'User\CouponController@index')->name('user.coupon.index');
        Route::post('/store', 'User\CouponController@store')->name('user.coupon.store')->middleware('limitCheck:items,update');
        Route::post('/update', 'User\CouponController@update')->name('user.coupon.update')->middleware('limitCheck:items,update');
        Route::post('/delete', 'User\CouponController@delete')->name('user.coupon.delete');
    });

    //START SHOP SETINGS
    Route::prefix('shop-settings')->middleware('checkpermission:Shop Settings')->group(function () {
        Route::get('/', 'User\ItemController@settings')->name('user.item.settings');
        Route::post('/update', 'User\ItemController@updateSettings')->name('user.item.settings_update')->middleware('limitCheck:items,update,without_ajax');
    });

    //START SHOP MANAGEMENT
    Route::prefix('shop-management/products')->middleware('checkpermission:Shop Management')->group(function () {
        // Category
        Route::get('/categories', 'User\ItemCategoryController@index')->name('user.itemcategory.index');
        Route::prefix('category')->group(function () {
            Route::post('/store', 'User\ItemCategoryController@store')->name('user.itemcategory.store')->middleware('limitCheck:categories,store');
            Route::get('/{id}/edit', 'User\ItemCategoryController@edit')->name('user.itemcategory.edit');
            Route::post('/update', 'User\ItemCategoryController@update')->name('user.itemcategory.update')->middleware('limitCheck:categories,update');
            Route::post('/feature', 'User\ItemCategoryController@feature')->name('user.itemcategory.feature');
            Route::post('/delete', 'User\ItemCategoryController@delete')->name('user.itemcategory.delete');
            Route::post('/bulk-delete', 'User\ItemCategoryController@bulkDelete')->name('user.itemcategory.bulk.delete');
        });

        // SUb Category
        Route::get('/subcategories', 'User\ItemSubCategoryController@index')->name('user.itemsubcategory.index');
        Route::prefix('subcategory')->group(function () {
            Route::post('/store', 'User\ItemSubCategoryController@store')->name('user.itemsubcategory.store')->middleware('limitCheck:subcategories,store');
            Route::get('/{id}/edit', 'User\ItemSubCategoryController@edit')->name('user.itemsubcategory.edit');
            Route::post('/update', 'User\ItemSubCategoryController@update')->name('user.itemsubcategory.update')->middleware('limitCheck:subcategories,update');
            Route::post('/feature', 'User\ItemSubCategoryController@feature')->name('user.itemsubcategory.feature');
            Route::post('/delete', 'User\ItemSubCategoryController@delete')->name('user.itemsubcategory.delete');
            Route::post('/bulk-delete', 'User\ItemSubCategoryController@bulkDelete')->name('user.itemsubcategory.bulk.delete');
        });

        Route::prefix('label')->group(function () {
            Route::get('/', 'User\ItemLabelController@index')->name('user.product.label.index');
            Route::post('/store', 'User\ItemLabelController@store')->name('user.product.label.store')->middleware('limitCheck:items,update');
            Route::post('/update', 'User\ItemLabelController@update')->name('user.product.label.update')->middleware('limitCheck:items,update');
            Route::post('/delete', 'User\ItemLabelController@delete')->name('user.product.label.delete');
        });

        //variant route
        Route::prefix('variants')->group(function () {
            Route::get('/variations', 'User\VariantController@index')->name('user.variant.index');
            Route::get('/add-variant', 'User\VariantController@create')->name('user.variant.create');
            Route::post('/store', 'User\VariantController@store')->name('user.variant.store')->middleware('limitCheck:items,update');
            Route::get('/edit/{id}', 'User\VariantController@edit')->name('user.variant.edit');
            Route::post('/update/{id}', 'User\VariantController@update')->name('user.variant.update')->middleware('limitCheck:items,update');
            Route::post('/delete/{id}', 'User\VariantController@delete')->name('user.variant.delete');
            Route::post('/bulk-delete', 'User\VariantController@bulk_delete')->name('user.variant.bulk_delete');
            Route::get('/get-subcategory', 'User\VariantController@get_subcategory')->name('user.variant.get-subcategory');
            Route::get('/delete-option', 'User\VariantController@delete_option')->name('user.variant.delete-option');
        });

        Route::prefix('items')->group(function () {
            Route::get('/', 'User\ItemController@index')->name('user.item.index');
            Route::get('/type', 'User\ItemController@type')->name('user.item.type');
            Route::get('/create', 'User\ItemController@create')->name('user.item.create');
            Route::post('/store', 'User\ItemController@store')->name('user.item.store')->middleware('limitCheck:items,store');
            Route::get('/{id}/edit', 'User\ItemController@edit')->name('user.item.edit');
            Route::post('/update', 'User\ItemController@update')->name('user.item.update')->middleware('limitCheck:items,update');
            Route::post('/feature', 'User\ItemController@feature')->name('user.item.feature');
            Route::post('/special-offer', 'User\ItemController@specialOffer')->name('user.item.specialOffer');
            Route::post('/delete', 'User\ItemController@delete')->name('user.item.delete');
            Route::get('/{id}/variations', 'User\ItemController@variations')->name('user.item.variations');
            Route::get('/variations/get', 'User\ItemController@getVariation')->name('user.item.variations.get_variation');
            Route::post('/variation/store', 'User\ItemController@variationStore')->name('user.item.variation.store')->middleware('limitCheck:items,update');
            Route::get('/variation/delete/{id}', 'User\ItemController@variationDelete')->name('user.item.variation.delete');
            Route::get('/variation/option/delete', 'User\ItemController@variationOptionDelete')->name('user.item.variation.option.delete');
            Route::post('/paymentStatus', 'User\ItemController@paymentStatus')->name('user.item.paymentStatus');



            Route::post('/setFlashSale/{id}', 'User\ItemController@setFlashSale')->name('user.item.setFlashSale');

            Route::post('/slider', 'User\ItemController@slider')->name('user.item.slider');
            Route::post('/slider/remove', 'User\ItemController@sliderRemove')->name('user.item.slider-remove');
            Route::post('/db/slider/remove', 'User\ItemController@dbSliderRemove')->name('user.item.db-slider-remove');

            Route::post('/sub-category-getter', 'User\ItemController@subcatGetter')->name('user.item.subcatGetter');


            Route::get('{id}/getcategory', 'User\ItemController@getCategory')->name('user.item.getcategory');
            Route::post('/delete', 'User\ItemController@delete')->name('user.item.delete');
            Route::post('/bulk-delete', 'User\ItemController@bulkDelete')->name('user.item.bulk.delete');
            Route::post('/sliderupdate', 'User\ItemController@sliderupdate')->name('user.item.sliderupdate');


            Route::get('/{id}/{language}/variants', 'User\ItemController@variants')->name('user.item.variants');
            Route::post('/update', 'User\ItemController@update')->name('user.item.update');
            Route::get('/{id}/images', 'User\ItemController@images')->name('user.item.images');
        });

        Route::post('/orders/mail', 'Admin\ItemOrderController@mail')->name('user.orders.mail');

        // Product Order
        Route::prefix('orders')->group(function () {
            Route::get('/all/orders', 'User\ItemOrderController@all')->name('user.all.item.orders');
            Route::get('/pending/orders', 'User\ItemOrderController@pending')->name('user.pending.item.orders');
            Route::get('/processing/orders', 'User\ItemOrderController@processing')->name('user.processing.item.orders');
            Route::get('/completed/orders', 'User\ItemOrderController@completed')->name('user.completed.item.orders');
            Route::get('/rejected/orders', 'User\ItemOrderController@rejected')->name('user.rejected.item.orders');
            Route::post('/orders/status', 'User\ItemOrderController@status')->name('user.item.orders.status');
            Route::get('/orders/details/{id}', 'User\ItemOrderController@details')->name('user.item.details');
            Route::post('/order/delete', 'User\ItemOrderController@orderDelete')->name('user.item.order.delete');
            Route::post('/order/bulk-delete', 'User\ItemOrderController@bulkOrderDelete')->name('user.item.order.bulk.delete');
            Route::get('/orders/report', 'User\ItemOrderController@report')->name('user.orders.report');
            Route::get('/export/report', 'User\ItemOrderController@exportReport')->name('user.orders.export');
        });

        // Product Order end
    });
    //END SHOP MANAGEMENT

    // custom-page routes are goes here
    Route::prefix('pages/additional-pages')->group(function () {
        Route::get('/', 'User\PageController@index')->name('user.page.index');
        Route::get('/create', 'User\PageController@create')->name('user.page.create');
        Route::post('/store', 'User\PageController@store')->name('user.page.store')->middleware('limitCheck:custome_page,store');
        Route::get('/edit/{id}', 'User\PageController@edit')->name('user.page.edit');
        Route::post('/update/{id}', 'User\PageController@update')->name('user.page.update')->middleware('limitCheck:custome_page,update');
        Route::post('/delete', 'User\PageController@delete')->name('user.page.delete');
        Route::post('/bulk-delete', 'User\PageController@bulkDelete')->name('user.page.bulk.delete');
    });

    // user Section Customization Routes
    Route::get('pages/home-page-sections', 'User\HomePageTextController@sections')->name('user.sections.index');
    Route::post('/sections/update', 'User\HomePageTextController@updatesections')->name('user.sections.update')->middleware('limitCheck:items,update,without_ajax');

    Route::get('pages/home-page-item-highlights', 'User\HomePageTextController@item_highlight')->name('user.sections.item_highlight');
    Route::post('/item-highlights/update', 'User\HomePageTextController@item_highlight_update')->name('user.sections.item_highlight_update');

    Route::prefix('pages/images-&-texts')->group(function () {
        Route::get('', 'User\HomePageTextController@contentSection')->name('user.image_text_content.section');
        Route::get('/remove-image', 'User\HomePageTextController@removeImage')->name('user.remove_image');
    });
});
