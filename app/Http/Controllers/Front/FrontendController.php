<?php

namespace App\Http\Controllers\Front;

require_once __DIR__ . '/../../../../vendor/Transliterator/Transliterator.php';

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\MegaMailer;
use App\Models\AdditionalSection;
use App\Models\Admin\ImageText;
use App\Models\BasicExtended as BE;
use App\Models\BasicExtended;
use App\Models\BasicSetting as BS;
use App\Models\Bcategory;
use App\Models\Blog;
use App\Models\CounterInformation;
use App\Models\CounterSection;
use App\Models\Admin\UserCategory;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Language;
use App\Models\OfflineGateway;
use App\Models\Package;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PaymentGateway;
use App\Models\Process;
use App\Models\Seo;
use App\Models\Subscriber;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\User\Language as UserLanguage;
use App\Models\User\SEO as UserSeo;
use App\Models\User\UserHeading;
use App\Models\User\UserPage;
use App\Models\User\UserPageContent;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Purifier;
use Validator;

class FrontendController extends Controller
{
    public function __construct()
    {
        $bs = BS::first();
        $be = BE::first();

        Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
        Config::set('captcha.secret', $bs->google_recaptcha_secret_key);
        Config::set('mail.host', $be->smtp_host);
        Config::set('mail.port', $be->smtp_port);
        Config::set('mail.username', $be->smtp_username);
        Config::set('mail.password', $be->smtp_password);
        Config::set('mail.encryption', $be->encryption);
    }

    public function index()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $lang_id = $currentLang->id;

        $data['processes'] = Process::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['features'] = Feature::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['featured_users'] = User::where([
            ['featured', 1],
            ['status', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->orderBy('feature_time', 'DESC')->get();


        $data['templates'] = User::where([
            ['preview_template', 1],
            ['status', 1],
            ['online_status', 1],
            ['featured', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->orderBy('template_serial_number', 'ASC')->get();


        $data['testimonials'] = Testimonial::where('language_id', $lang_id)
            ->orderBy('serial_number', 'ASC')
            ->get();
        $data['blogs'] = Blog::where('language_id', $lang_id)->orderBy('id', 'DESC')->take(3)->get();

        $data['partners'] = Partner::orderBy('serial_number', 'ASC')
            ->get();

        $data['seo'] = Seo::where('language_id', $lang_id)->first();

        $terms = [];
        if (Package::query()->where('status', '1')->where('featured', '1')->where('term', 'monthly')->count() > 0) {
            $terms[] = 'Monthly';
        }
        if (Package::query()->where('status', '1')->where('featured', '1')->where('term', 'yearly')->count() > 0) {
            $terms[] = 'Yearly';
        }
        if (Package::query()->where('status', '1')->where('featured', '1')->where('term', 'lifetime')->count() > 0) {
            $terms[] = 'Lifetime';
        }
        $data['terms'] = $terms;

        $be = BasicExtended::select('package_features')->firstOrFail();
        $allPfeatures = $be->package_features ? $be->package_features : "[]";
        $data['allPfeatures'] = json_decode($allPfeatures, true);

        $sections = [
            'hero_section',
            'partner_section',
            'work_process_section',
            'template_section',
            'features_section',
            'pricing_section',
            'featured_shop_section',
            'testimonial_section',
            'blog_section'
        ];
        $pageType = 'home';
        foreach ($sections as $section) {
            $data["after_" . str_replace('_section', '', $section)] = AdditionalSection::where('possition', $section)
                ->where('page_type', $pageType)
                ->orderBy('serial_number', 'asc')
                ->get();
        }
        $data['homeSec'] = ImageText::where('language_id', $lang_id)->first();
        $data['lang_id'] = $lang_id;
        return view('front.index', $data);
    }

    public function about()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $lang_id = $currentLang->id;

          $data['homeSec'] = ImageText::where('language_id', $lang_id)->first();

        $data['processes'] = Process::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();
        $data['features'] = Feature::where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();

        $data['testimonials'] = Testimonial::where('language_id', $lang_id)
            ->orderBy('serial_number', 'ASC')
            ->get();
        $data['blogs'] = Blog::where('language_id', $lang_id)->orderBy('id', 'DESC')->take(3)->get();

        $data['partners'] = Partner::orderBy('serial_number', 'ASC')
            ->get();

        $data['seo'] = Seo::where('language_id', $lang_id)->first();

        $data['counters'] = CounterInformation::where('language_id', $lang_id)->get();

        $data['counterSection'] = CounterSection::where('language_id', $lang_id)->first();

        $sections = [
            'features_section',
            'work_process_section',
            'counter_section',
            'testimonial_section',
            'blog_section'
        ];
        $pageType = 'about';
        foreach ($sections as $section) {
            $data["after_" . str_replace('_section', '', $section)] = AdditionalSection::where('possition', $section)
                ->where('page_type', $pageType)
                ->orderBy('serial_number', 'asc')
                ->get();
        }

        $data['lang_id'] = $lang_id;

        return view('front.about', $data);
    }

    public function subscribe(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:subscribers'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->getMessageBag()
            ], 400);
        }

        $subsc = new Subscriber;
        $subsc->email = $request->email;
        $subsc->save();

        return response()->json([
            'success' => __('You have successfully subscribed to our newsletter')
        ], 200);
    }

    public function loginView()
    {
        return view('front.login');
    }

    public function checkUsername($username)
    {
        $count = User::where('username', $username)->count();
        $status = $count > 0 ? true : false;
        return response()->json($status);
    }

    public function step1($status, $id)
    {
        if (Auth::check()) {
            return redirect()->route('user.plan.extend.index');
        }
        $data['status'] = $status;
        $data['id'] = $id;
        $package = Package::findOrFail($id);
        $data['package'] = $package;

        $hasSubdomain = false;
        $features = [];
        if (!empty($package->features)) {
            $features = json_decode($package->features, true);
        }
        if (is_array($features) && in_array('Subdomain', $features)) {
            $hasSubdomain = true;
        }
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $data['categories'] = UserCategory::where('language_id', $currentLang->id)->get();
        $data['hasSubdomain'] = $hasSubdomain;
        return view('front.step', $data);
    }

    public function step2(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['data'] = $request->session()->get('data');

        return view('front.checkout', $data);
    }

    public function checkout(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|alpha_num|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $seo = Seo::where('language_id', $currentLang->id)->first();
        $be = $currentLang->basic_extended;
        $data['bex'] = $be;
        $data['username'] = $request->username;
        $data['email'] = $request->email;
        $data['password'] = $request->password;
        $data['status'] = $request->status;
        $data['category'] = $request->category;
        $data['id'] = $request->id;
        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $data['offline'] = $offline;
        $data['payment_methods'] = $online->merge($offline);
        $data['package'] = Package::query()->findOrFail($request->id);
        $data['seo'] = $seo;
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        // dd($data['payment_methods']);
        $request->session()->put('data', $data);
        return redirect()->route('front.registration.step2');
    }

    // packages start
    public function pricing(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

        $data['bex'] = BE::first();
        $data['abs'] = BS::first();

        $terms = [];
        if (Package::query()->where('status', '1')->where('term', 'monthly')->count() > 0) {
            $terms[] = 'Monthly';
        }
        if (Package::query()->where('status', '1')->where('term', 'yearly')->count() > 0) {
            $terms[] = 'Yearly';
        }
        if (Package::query()->where('status', '1')->where('term', 'lifetime')->count() > 0) {
            $terms[] = 'Lifetime';
        }
        $data['terms'] = $terms;

        $be = BasicExtended::select('package_features')->firstOrFail();
        $allPfeatures = $be->package_features ? $be->package_features : "[]";
        $data['allPfeatures'] = json_decode($allPfeatures, true);

        return view('front.pricing', $data);
    }

    // blog section start
    public function blogs(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

        $data['currentLang'] = $currentLang;

        $lang_id = $currentLang->id;

        $category = $request->category;
        if (!empty($category)) {
            $data['category'] = Bcategory::findOrFail($category);
        }
        $title = $request->title;

        $data['bcats'] = Bcategory::where('language_id', $lang_id)->where('status', 1)->orderBy('serial_number', 'ASC')->get();

        $data['blogs'] = Blog::when($category, function ($query, $category) {
            return $query->where('bcategory_id', $category);
        })
            ->when($title, function ($query, $title) {
                return $query->where('title', 'like', '%' . $title . '%');
            })
            ->when($currentLang, function ($query, $currentLang) {
                return $query->where('language_id', $currentLang->id);
            })->orderBy('serial_number', 'ASC')->paginate(4);
        return view('front.blogs', $data);
    }

    public function blogdetails($slug, $id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $lang_id = $currentLang->id;
        $data['blog'] = Blog::findOrFail($id);
        $data['bcats'] = Bcategory::where('status', 1)->where('language_id', $lang_id)->orderBy('serial_number', 'ASC')->get();

        $data['related_blogs'] = Blog::where([
            ['bcategory_id', $data['blog']->bcategory_id],
            ['language_id', $lang_id],
            ['id', '!=', $id],
        ])->limit(5)->get();

        return view('front.blog-details', $data);
    }

    public function contactView()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        $data['recaptchaInfo'] = BS::select('is_recaptcha')->first();

        return view('front.contact', $data);
    }

    public function faqs()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();

        $lang_id = $currentLang->id;
        $data['faqs'] = Faq::where('language_id', $lang_id)
            ->orderBy('serial_number', 'asc')
            ->get();
        return view('front.faq', $data);
    }

    public function dynamicPage($slug)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $data['page'] = Page::where('slug', $slug)->firstOrFail();
        $pageId = $data['page']->id;

        //custom seo info
        $seoInfo = SEO::select('custome_page_meta_keyword', 'custome_page_meta_description')
            ->where('language_id', $currentLang->id)
            ->first();
        $metaKeyword = isset($seoInfo->custome_page_meta_keyword) ? json_decode($seoInfo->custome_page_meta_keyword, true) : '';
        $metaDescription = isset($seoInfo->custome_page_meta_description) ? json_decode($seoInfo->custome_page_meta_description, true) : '';
        $data['meta_keywords'] = isset($metaKeyword[$pageId]) ? $metaKeyword[$pageId] : '';
        $data['meta_description'] = isset($metaDescription[$pageId]) ? $metaDescription[$pageId] : '';

        //custom page page heading
        $pageHeading = UserHeading::select('custom_page_heading')
            ->where('language_id', $currentLang->id)
            ->select('custom_page_heading')
            ->first();
        $pageHeading = isset($pageHeading->custom_page_heading) ? json_decode($pageHeading->custom_page_heading, true) : [];
        $data['title'] = (is_array($pageHeading) && isset($pageHeading[$pageId])) ? $pageHeading[$pageId] : '';

        return view('front.dynamic', $data);
    }

    public function shops(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['pageHeading'] = $this->getPageHeading($currentLang);
        $data['seo'] = Seo::where('language_id', $currentLang->id)->first();
        $data['categories'] = UserCategory::query()->where('language_id', $currentLang->id)->get();

        $categoryId = $userIds= [];

        $data['users'] = null;

        if ($request->has('category')) {
            $categoryId = UserCategory::where([['language_id', $currentLang->id], ['slug', $request->category]])->pluck('unique_id')->first();

            $userIds =  User::where('category_id', $categoryId)->pluck('id')->toArray();
        }
        $users = User::where('online_status', 1)
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })
            ->whereHas('permissions', function ($q) {
                $q->where('permissions', 'LIKE', '%"Profile Listing"%');
            })
            ->when($request->shop_name, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('username', 'like', '%' . $request->shop_name . '%')
                        ->orWhere('shop_name', 'like', '%' . $request->shop_name . '%');
                });
            })
            ->when($request->location, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('city', 'like', '%' . $request->location . '%')
                        ->orWhere('country', 'like', '%' . $request->location . '%');
                });
            })
            ->when($request->category, function ($q) use ($userIds) {
                return $q->where(function ($query) use ($userIds) {
                    $query->whereIn('id', $userIds);
                });
            })

            ->orderBy('id', 'DESC')
            ->paginate(9);

        $data['users'] = $users;

        return view('front.shops', $data);
    }

    public function customPage($domain, $slug)
    {
        $user = getUser();
        $id = $user->id;

        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $pageId = UserPageContent::where([['slug', $slug], ['user_id', $id]])->pluck('page_id')->firstOrFail();

        $data['page'] = UserPageContent::where([['page_id', $pageId], ['language_id', $userCurrentLang->id], ['user_id', $id]])->first();

        if (is_null($data['page'])) {
            abort(404);
        }

        UserPage::where([['id', $data['page']->page_id], ['status', 1]])->firstOrFail();

        //custom seo info
        $seoInfo = UserSeo::select('custome_page_meta_keyword', 'custome_page_meta_description')
            ->where([['language_id', $userCurrentLang->id], ['user_id', $id]])
            ->first();
        $metaKeyword = isset($seoInfo->custome_page_meta_keyword) ? json_decode($seoInfo->custome_page_meta_keyword, true) : '';
        $metaDescription = isset($seoInfo->custome_page_meta_description) ? json_decode($seoInfo->custome_page_meta_description, true) : '';
        $data['meta_keywords'] = isset($metaKeyword[$pageId]) ? $metaKeyword[$pageId] : '';
        $data['meta_description'] = isset($metaDescription[$pageId]) ? $metaDescription[$pageId] : '';


        //custom page page heading
        $pageHeading = UserHeading::select('custom_page_heading')
            ->where([['language_id', $userCurrentLang->id], ['user_id', $id]])
            ->select('custom_page_heading')
            ->first();
        $pageHeading = isset($pageHeading->custom_page_heading) ? json_decode($pageHeading->custom_page_heading, true) : [];
        $data['title'] = (is_array($pageHeading) && isset($pageHeading[$pageId])) ? $pageHeading[$pageId] : '';

        return view('user-front.custom-page', $data);
    }

    public function paymentInstruction(Request $request)
    {
        $offline = OfflineGateway::where('name', $request->name)
            ->select('short_description', 'instructions', 'is_receipt')
            ->first();
        return response()->json([
            'description' => $offline->short_description,
            'instructions' => $offline->instructions,
            'is_receipt' => $offline->is_receipt
        ]);
    }

    public function contactMessage($domain, Request $request)
    {
        $rules = [
            'fullname' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];
        $request->validate($rules);

        $toUser = User::query()->findOrFail($request->id);
        $data['toMail'] = $toUser->email;
        $data['toName'] = $toUser->username;

        $data['subject'] = $request->subject;
        $data['body'] = "<div>$request->message</div><br>
                         <strong>For further contact with the enquirer please use the below information:</strong><br>
                         <strong>Enquirer Name:</strong> $request->fullname <br>
                         <strong>Enquirer Mail:</strong> $request->email <br>
                         ";
        $mailer = new MegaMailer();
        $mailer->mailContactMessage($data);
        Session::flash('success', __('Mail sent successfully'));
        return back();
    }

    public function adminContactMessage(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];

        $bs = BS::select('is_recaptcha')->first();

        if ($bs->is_recaptcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
        $messages = [
            'g-recaptcha-response.required' => __('Please verify that you are not a robot'),
            'g-recaptcha-response.captcha' => __('Captcha error! try again later or contact site admin'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }



        $be =  BE::firstOrFail();
        if ($be->is_smtp == 1) {
            $subject = $request->subject;

            $msg = '<p>A new quote request has been sent.<br/><strong>Client Name: </strong>' . $request->name . '<br/><strong>Client Mail: </strong>' . $request->email . '</p><p>Message : ' . nl2br(Purifier::clean($request->message, 'youtube')) . '</p>';

            $data = [];
            //add smtp info in array
            $data['smtp_status'] = $be->is_smtp;
            $data['smtp_host'] = $be->smtp_host;
            $data['smtp_username'] = $be->smtp_username;
            $data['smtp_password'] = $be->smtp_password;
            $data['encryption'] = $be->encryption;
            $data['smtp_port'] = $be->smtp_port;

            //mail info in array
            $data['from_mail'] = $be->from_mail;
            $data['recipient'] = $be->to_mail;
            $data['subject'] = $subject;
            $data['body'] = $msg;

            // Send Mail
            BasicMailer::sendMail($data);
        }

        Session::flash('success', __('Message sent successfully'));
        return back();
    }

    public function changeLanguage($lang): \Illuminate\Http\RedirectResponse
    {
        session()->put('lang', $lang);
        app()->setLocale($lang);
        return redirect()->back();
    }
    public function changeUserLanguage(Request $request, $domain)
    {
        session()->put('user_lang', $request->code);
        return redirect()->route('front.user.detail.view', $domain);
    }

    public function templates()
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $data['templates'] = User::where([
            ['preview_template', 1],
            ['status', 1],
            ['online_status', 1]
        ])
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->orderBy('template_serial_number', 'ASC')->get();

        $data['pageHeading'] = $this->getPageHeading($currentLang);


        return view('front.template', $data);
    }
}
