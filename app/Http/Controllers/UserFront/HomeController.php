<?php

namespace App\Http\Controllers\UserFront;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\Common;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Banner;
use App\Models\User\BasicSetting;
use App\Models\User\Blog;
use App\Models\User\BlogCategory;
use App\Models\User\Faq;
use App\Models\User\HeroSlider;
use App\Models\User\Language as UserLanguage;
use App\Models\BasicExtended as BE;
use App\Models\User\BasicExtende;
use App\Models\User\AboutUs;
use App\Models\User\AboutUsFeatures;
use App\Models\User\AdditionalSection;
use App\Models\User\BlogContent;
use App\Models\User\CounterInformation;
use App\Models\User\CounterSection;
use App\Models\User\HowitWorkSection;
use App\Models\User\ProductHeroSlider;
use App\Models\User\SEO;
use App\Models\User\Tab;
use App\Models\User\Testimonial;
use App\Models\User\UserContact;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserItemCategory;
use App\Models\User\UserOrder;
use App\Models\User\UserOrderItem;
use App\Models\User\UserSection;
use App\Models\User\UserShopSetting;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class HomeController extends Controller
{
    public function userDetailView($domain)
    {
        $user = app('user');
        $userCurrentLang = app('userCurrentLang');

        $uLang = $userCurrentLang->id;
        $data['uLang'] = $userCurrentLang->id;
        $data['ubs'] = app('userBs');

        if (session()->has('user_curr')) {
            $userCurrentCurr = UserCurrency::where('id', session()->get('user_curr'))->firstOrFail();

            if (empty($userCurrentCurr)) {
                $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->firstOrFail();
                session()->put('user_curr', $userCurrentCurr->id);
            }
        } else {
            $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->firstOrFail();
            if ($userCurrentCurr) {
                session()->put('user_curr', $userCurrentCurr->id);
            } else {
                $userCurrentCurr = UserCurrency::where('user_id', $user->id)->firstOrFail();
                session()->put('user_curr', $userCurrentCurr->id);
            }
        }
        $curr_id = $userCurrentCurr->id;
        $data['userCurrentCurr'] = $curr_id;

        $data['sliders'] = HeroSlider::where('language_id', $userCurrentLang->id)
            ->where('user_id', $user->id)
            ->get();
        $data['hero_slider'] = BasicExtende::where('user_id', $user->id)
            ->where('language_id', $userCurrentLang->id)
            ->select('hero_section_background_image')
            ->first();

        if ($data['ubs']->theme == 'fashion' || $data['ubs']->theme == 'electronics' || $data['ubs']->theme == 'vegetables' || $data['ubs']->theme == 'manti') {
            $data['how_work_steps'] = HowitWorkSection::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->get();
        }


        if ($data['ubs']->theme == 'fashion') {
            $product_sliders = ProductHeroSlider::where('user_id', $user->id)->select('products')->first();
            $added_products = [];
            if ($product_sliders) {
                $added_products = json_decode($product_sliders->products, true);
                if (!is_array($added_products)) {
                    $added_products = [];
                }
            }

            $data['hero_product_sliders'] = DB::table('user_items')->where('user_items.user_id', $user->id)
                ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
                ->whereIn('user_items.id', $added_products)
                ->where('user_item_categories.status', '=', 1)
                ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.title', 'user_item_contents.slug', 'user_item_contents.summary')
                ->orderBy('user_items.id', 'DESC')
                ->where('user_item_contents.language_id', '=', $userCurrentLang->id)
                ->get();


            $data['banners'] = Banner::where('language_id', $userCurrentLang->id)
                ->where([['user_id', $user->id], ['position', 'middle']])
                ->orderBy('serial_number', 'asc')
                ->limit(3)
                ->get();
        } else {
            $data['hero_sliders'] = HeroSlider::where('language_id', $userCurrentLang->id)
                ->where('user_id', $user->id)
                ->orderBy('serial_number', 'asc')
                ->get();
            $data['banners'] = Banner::where('language_id', $userCurrentLang->id)
                ->where('user_id', $user->id)
                ->orderBy('serial_number', 'asc')
                ->get();
        }

        if ($data['ubs']->theme == 'manti') {
            $data['hero_banners'] = Banner::where('language_id', $userCurrentLang->id)
                ->where([['user_id', $user->id], ['position', 'hero_banner']])
                ->orderBy('serial_number', 'asc')
                ->get();
        }
        $shop_settings =  app('shop_settings');
        $shopSet = $shop_settings;
        $data['shopSet'] = $shopSet;

        $data['item_categories'] = UserItemCategory::where('language_id', $userCurrentLang->id)
            ->where([['user_id', $user->id], ['status', 1]])
            ->orderBy('serial_number', 'ASC')
            ->take($shopSet->categories_count)
            ->get();

        $data['flash_items']  = DB::table('user_items')->where('user_items.user_id', $user->id)
            ->where('user_items.flash', 1)
            ->where([
                [DB::raw('CONCAT(user_items.start_date, " ", user_items.start_time)'), '<=', Carbon::now()->format('Y-m-d H:i')],
                [DB::raw('CONCAT(user_items.end_date, " " , user_items.end_time)'), '>=', Carbon::now()->format('Y-m-d H:i')]
            ])
            ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_contents.category_id', '=', 'user_item_categories.id')
            ->select('user_items.*', 'user_items.id AS item_id', 'user_item_contents.*', 'user_item_categories.name AS category', 'user_item_categories.slug AS category_slug')
            ->orderBy('user_items.id', 'DESC')
            ->where('user_item_contents.language_id', '=', $userCurrentLang->id)
            ->where('user_item_categories.language_id', '=', $userCurrentLang->id)
            ->where([['user_items.status', 1], ['user_item_categories.status', '=', 1]])
            ->take($shopSet->flash_item_count)
            ->get();


        $data['keywords'] = json_decode($userCurrentLang->keywords, true);

        if ($data['ubs']->theme == 'electronics' || $data['ubs']->theme == 'kids') {
            $data['latest_items'] = UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
                ->where('user_items.user_id', $user->id)
                ->where('user_items.status', 1)
                ->where('user_item_categories.status', 1)
                ->with(['itemContents' => function ($q) use ($uLang) {
                    $q->where('language_id', '=', $uLang);
                }, 'sliders'])
                ->orderBy('user_items.updated_at', 'DESC')
                ->select('user_items.*')
                ->distinct()
                ->take($shopSet->latest_item_count)
                ->get();
        }

        $data['tabs'] = Tab::where('language_id', $userCurrentLang->id)
            ->where([['user_id', $user->id], ['status', 1]])
            ->orderBy('serial_number', 'ASC')
            ->get();

        if ($data['ubs']->theme == 'manti') {
            $data['featured_categories'] = UserItemCategory::where([
                ['language_id', $userCurrentLang->id],
                ['user_id', $user->id],
                ['is_feature', 1],
                ['status', 1],
            ])->get();
        }



        if (in_array($data['ubs']->theme, ['manti', 'vegetables', 'furniture'])) {
            $data['top_rated'] = UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
                ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
                ->where('user_items.status', 1)
                ->where('user_items.user_id', $user->id)
                ->where('user_item_categories.status', 1)
                ->with(['itemContents' => function ($q) use ($uLang) {
                    $q->where('language_id', '=', $uLang);
                }])
                ->where('user_items.rating', '>', 0)
                ->orderBy('user_items.rating', 'desc')
                ->take($shop_settings->top_rated_count)
                ->distinct()
                ->select('user_items.*')
                ->get();

            $data['top_selling'] = UserOrderItem::with(['item', 'item.itemContents' => function ($q) use ($uLang, $user) {
                $q->where('language_id', '=', $uLang);
            }])
                ->where('user_id', $user->id)
                ->groupBy(DB::raw('item_id'))
                ->take($shop_settings->top_selling_count)
                ->select(DB::raw('item_id, sum(qty) as quantity'))
                ->orderBy('quantity', 'desc')
                ->get();
        }

        $data['user'] = $user;

        $data['userSec'] = UserSection::where('user_id',  $user->id)->where('language_id', $userCurrentLang->id)->first();

        $data['seo'] = SEO::where('language_id', $uLang)->where('user_id', $user->id)
            ->select('home_meta_description', 'home_meta_keywords')
            ->first();
        $data['shop_settings'] = $shop_settings;

        //custom section
        $sections = [
            'hero_section',
            'categories_section',
            'middle_banner_section',
            'flash_section',
            'featuers_section',
            'tab_section',
            'call_to_action_section',
            'top_rate_top_selling_section'
        ];
        if ($data['ubs']->theme == 'manti') {
            $sections[] = 'featured_section';
            $sections[] = 'call_to_action_section';
        }
        if ($data['ubs']->theme == 'fashion' || $data['ubs']->theme == 'kids' || $data['ubs']->theme == 'furniture') {
            $sections[] = 'video_section';
        }
        if ($data['ubs']->theme == 'electronics' || $data['ubs']->theme == 'kids') {
            $sections[] = 'category_product_section';
            $sections[] = 'latest_product_section';
        }
        if ($data['ubs']->theme == 'kids' || $data['ubs']->theme == 'furniture') {
            $sections[] = 'banners_section';
        }

        $pageType = 'home';
        $allSections = AdditionalSection::whereIn('possition', $sections)
            ->where('page_type', $pageType)
            ->orderBy('serial_number', 'asc')
            ->get()
            ->groupBy('possition');
        foreach ($sections as $section) {
            $data["after_" . str_replace('_section', '', $section)] = $allSections->get($section, collect());
        }

        //remove cart if any product from other tenant
        $cart = session()->get('cart');
        $user_id = $user->id;

        if (!is_null($cart) && is_array($cart)) {
            $cart = array_filter($cart, function ($item) use ($user_id) {
                return $item['user_id'] == $user_id;
            });
        }
        session()->put('cart', $cart);
        //remove cart if any product from other tenant end

        if ($data['ubs']->theme == 'electronics') {
            return view('user-front.electronics.index', $data);
        } elseif ($data['ubs']->theme == 'fashion') {
            return view('user-front.fashion.index', $data);
        } elseif ($data['ubs']->theme == 'furniture') {
            return view('user-front.furniture.index', $data);
        } elseif ($data['ubs']->theme == 'kids') {
            return view('user-front.kids.index', $data);
        } elseif ($data['ubs']->theme == 'manti') {
            return view('user-front.manti.index', $data);
        } else {
            return view('user-front.grocery.index', $data);
        }
    }

    public function checkCurrentUser() {}

    public function changeUserLanguage($domain, $code): \Illuminate\Http\RedirectResponse
    {
        session()->put('user_lang', $code);
        $carts = session()->get('cart');
        return redirect()->back();
    }

    public function changeUserCurrency($domain, $id): \Illuminate\Http\RedirectResponse
    {
        $previous_currency = session()->get('user_curr');
        $carts = session()->get('cart');
        if (!empty($carts)) {
            foreach ($carts as $ckey => $cvalue) {
                if (is_array($cvalue)) {
                    $carts[$ckey]['product_price'] = change_curreny_value($cvalue['product_price'], $id, $previous_currency);
                    $carts[$ckey]['total'] = change_curreny_value($cvalue['total'], $id, $previous_currency);
                    //if has variation then update price
                    if (isset($cvalue['variations']) && is_array($cvalue['variations'])) {
                        foreach ($cvalue['variations'] as $variation_key => $variation_value) {
                            if (is_array($variation_value) && isset($variation_value['price'])) {
                                $carts[$ckey]['variations'][$variation_key]['price'] = change_curreny_value($variation_value['price'], $id, $previous_currency);
                            }
                        }
                    }
                }
            }
            Session::forget('cart');
            Session::put('cart', $carts);
        }
        session()->put('user_curr', $id);

        return redirect()->back();
    }

    public function invoice()
    {
        $user = getUser();
        $data['userBs'] = BasicSetting::where('user_id', $user->id)->first();
        $data['order']  = UserOrder::where('user_id', $user->id)->orderBy('id', 'desc')->first();

        $file_name = 'fahad' . ".pdf";
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('pdf.item', $data);
        $output = $pdf->output();
        $dir = public_path('assets/front/invoices/');
        @mkdir($dir, '0775', true);
        @file_put_contents($dir . $file_name, $output);

        return view('pdf.item', $data);
    }


    public function contactView()
    {
        $user = getUser();
        $basic_settings = BasicSetting::where('user_id', $user->id)->select('is_recaptcha', 'google_recaptcha_site_key', 'google_recaptcha_secret_key')->first();
        if ($basic_settings->is_recaptcha == 1) {
            Config::set('captcha.sitekey', $basic_settings->google_recaptcha_site_key);
            Config::set('captcha.secret', $basic_settings->google_recaptcha_secret_key);
        }

        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();

            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);
        $uLang = $userCurrentLang->id;
        $data['uLang'] = $userCurrentLang->id;

        $data['seo'] = SEO::where('language_id', $uLang)->where('user_id', $user->id)->first();
        $data['contact'] = UserContact::where('language_id', $uLang)->where('user_id', $user->id)->first();


        return view('user-front.contact', $data);
    }

    //contactMessage
    public function contactMessage(Request $request, $domain)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $use_bs = BasicSetting::where('user_id', $user->id)->first();
        $current_package = UserPermissionHelper::currentPackagePermission($user->id);
        if ($current_package) {
            $features = json_decode($current_package->features, true);
        } else {
            $features = [];
        }
        if ($use_bs->is_recaptcha == 1 && in_array('Google Recaptcha', $features)) {
            Config::set('captcha.sitekey', $use_bs->google_recaptcha_site_key);
            Config::set('captcha.secret', $use_bs->google_recaptcha_secret_key);
        }

        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
        ];
        $messages = [
            'name.required' => __('The name field is required.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('Please enter a valid email address.'),
            'subject.required' => __('The subject field is required.'),
        ];

        if ($use_bs->is_recaptcha == 1 && in_array('Google Recaptcha', $features)) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
        $messages = [
            'g-recaptcha-response.required' => $keywords['Please verify that you are not a robot'] ?? __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => $keywords['Captcha error! try again later or contact site admin'] ?? __('Captcha error! try again later or contact site admin'),
        ];
        $request->validate($rules, $messages);

        if (!is_null($user->email)) {
            $be =  BE::firstOrFail();
            if ($be->is_smtp == 1 && !is_null($use_bs->email)) {
                $subject = $request->subject;
                $message = '<p><strong>Enquirer Name: </strong>' . $request->name . '<br/><strong>Enquirer Mail: </strong>' . $request->email . '</p> <p>Message : ' . $request->message . '</p>';
                /******** Send mail to user ********/
                $data = [];
                $data['smtp_status'] = $be->is_smtp;
                $data['smtp_host'] = $be->smtp_host;
                $data['smtp_port'] = $be->smtp_port;
                $data['encryption'] = $be->encryption;
                $data['smtp_username'] = $be->smtp_username;
                $data['smtp_password'] = $be->smtp_password;

                //mail info in array
                $data['from_mail'] = $be->from_mail;
                $data['recipient'] = !is_null($use_bs->email) ? $use_bs->email : $user->email;
                $data['subject'] = $subject;
                $data['body'] = $message;
                BasicMailer::sendMail($data);
            }

            Session::flash('success', $keywords['Message sent successfully'] ?? __('Message sent successfully'));
            return back();
        } else {
            Session::flash('success', $keywords['Receipent Mail not set yet'] ?? __('Receipent Mail not set yet'));
            return back();
        }
    }

    public function faqs()
    {
        $user = getUser();

        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();

            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $currentLanguage = Common::getUserCurrentLanguage($user->id);
        $data['pageHeading'] = $this->getUserPageHeading($currentLanguage);

        $uLang = $userCurrentLang->id;
        $data['uLang'] = $userCurrentLang->id;


        $data['seo'] = SEO::where('language_id', $uLang)->where('user_id', $user->id)->first();

        $lang_id =  $userCurrentLang->id;
        $data['faqs'] = Faq::where('language_id', $lang_id)
            ->where('user_id', $user->id)
            ->orderBy('serial_number', 'ASC')
            ->get();
        return view('user-front.faq', $data);
    }


    public function userBlogs(Request $request, $domain)
    {
        $term = $catid = null;
        $user = getUser();

        $current_package = UserPermissionHelper::currentPackagePermission($user->id);
        $features = json_decode($current_package->features);
        if (is_array($features) && !in_array('Blog', $features)) {
            return view('errors.404');
        }

        $id = $user->id;
        $data['user'] = $user;
        $catid = $request->category;
        $term = $request->term;

        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);

        $data['blogs'] = DB::table('user_blogs')
            ->join('user_blog_contents', 'user_blogs.id', 'user_blog_contents.blog_id')
            ->join('user_blog_categories', 'user_blog_categories.id', '=', 'user_blog_contents.category_id')
            ->where([['user_blog_contents.language_id', $userCurrentLang->id], ['user_blogs.user_id', $id], ['user_blogs.status', 1], ['user_blog_categories.status', 1]])
            ->when($catid, function ($query, $catid) {
                return $query->where('user_blog_contents.category_id', $catid);
            })
            ->when($term, function ($query, $term) {
                return $query->where('user_blog_contents.title', 'LIKE', '%' . $term . '%');
            })
            ->select('user_blogs.*', 'user_blog_contents.*', 'user_blog_categories.name as categoryName', 'user_blog_categories.id as categoryId')
            ->orderBy('serial_number', 'ASC')
            ->paginate(6);


        $data['latestBlogs'] = DB::table('user_blogs')
            ->join('user_blog_contents', 'user_blogs.id', 'user_blog_contents.blog_id')
            ->join('user_blog_categories', 'user_blog_categories.id', '=', 'user_blog_contents.category_id')
            ->where([['user_blog_contents.language_id', $userCurrentLang->id], ['user_blogs.user_id', $id], ['user_blogs.status', 1]])
            ->select('user_blogs.*', 'user_blog_contents.title', 'user_blog_contents.slug', 'user_blog_categories.name as categoryName')
            ->orderBy('user_blogs.id', 'DESC')
            ->limit(3)->get();

        $data['blog_categories'] = BlogCategory::query()
            ->where('status', 1)
            ->orderBy('serial_number', 'ASC')
            ->where('language_id', $userCurrentLang->id)
            ->where('user_id', $id)
            ->get();

        $data['allCount'] = Blog::query()
            ->where('user_id', $id)
            ->count();

        $data['seo'] = SEO::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)
            ->select('blogs_meta_keywords', 'blogs_meta_description')
            ->first();

        return view('user-front.blogs', $data);
    }

    public function userBlogDetail($domain, $slug)
    {
        $user = getUser();
        $userId = $user->id;
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $blog_id = BlogContent::where([['user_blog_contents.user_id', $userId], ['user_blog_contents.slug', $slug]])->pluck('blog_id')->first();
        $data['blog'] = BlogContent::where([['user_blog_contents.user_id', $userId], ['user_blog_contents.blog_id', $blog_id], ['user_blog_contents.language_id', $userCurrentLang->id]])
            ->join('user_blog_categories', 'user_blog_categories.id', '=', 'user_blog_contents.category_id')
            ->select('user_blog_contents.*', 'user_blog_categories.name as categoryName')
            ->first();
        if (is_null($data['blog'])) {
            abort(404);
        }

        $data['latestBlogs'] = BlogContent::query()
            ->where([['user_id', $userId], ['language_id', $userCurrentLang->id], ['blog_id', '!=', $data['blog']->blog_id]])
            ->orderBy('id', 'DESC')
            ->limit(5)->get();


        $data['blog_categories'] = BlogCategory::query()
            ->where('status', 1)
            ->orderBy('serial_number', 'ASC')
            ->where('language_id', $userCurrentLang->id)
            ->where('user_id', $userId)
            ->get();

        $data['allCount'] = BlogContent::query()
            ->where('user_id', $userId)
            ->where('language_id', $userCurrentLang->id)
            ->count();

        $userId = $data['blog']->user_id;

        return view('user-front.blog-details', $data);
    }

    public function userAbout($domain)
    {
        $user = getUser();

        $current_package = UserPermissionHelper::currentPackagePermission($user->id);
        $features = json_decode($current_package->features);
        if (is_array($features) && !in_array('Blog', $features)) {
            return view('errors.404');
        }


        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);

        $data['how_work_steps'] = HowitWorkSection::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->get();

        $data['aboutInfo'] = AboutUs::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->first();
        $data['aboutFeatures'] = AboutUsFeatures::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->get();

        $data['counterSection'] = CounterSection::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->first();
        $data['counterInformations'] = CounterInformation::where([['language_id', $userCurrentLang->id], ['user_id', $user->id]])->get();

        $data['testimonial_info'] = UserSection::where([['user_id', $user->id], ['language_id', $userCurrentLang->id]])->first();

        $data['testimonials'] = Testimonial::where([['user_id', $user->id], ['language_id', $userCurrentLang->id]])->get();
        $data['uLang'] = $userCurrentLang->id;

        $data['seo'] = SEO::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)
            ->select('about_page_meta_keywords', 'about_page_meta_description')
            ->first();

        //custom section
        $sections = [
            'about_info_section',
            'features_section',
            'counter_section',
            'testimonial_section'
        ];

        $pageType = 'about';
        foreach ($sections as $section) {
            $data["after_" . str_replace('_section', '', $section)] = AdditionalSection::where('possition', $section)
                ->where('page_type', $pageType)
                ->orderBy('serial_number', 'asc')
                ->get();
        }

        return view('user-front.about', $data);
    }

    public function removeMaintenance($domain, $token)
    {
        Session::put('user-bypass-token', $token);
        return redirect()->route('front.user.detail.view', getParam());
    }
}
