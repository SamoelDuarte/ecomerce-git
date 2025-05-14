<?php

namespace App\Http\Controllers\Admin;

use App\Models\User\UserCurrency;
use App\Models\User\UserEmailTemplate;
use App\Models\User\UserPaymentGeteway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Http\Helpers\MegaMailer;
use App\Models\Admin\UserCategory;
use App\Http\Helpers\Uploader;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\BasicExtended;
use App\Models\BasicSetting;
use App\Models\Language;
use App\Models\Membership;
use App\Models\OfflineGateway;
use App\Models\Package;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\User\AdditionalSection;
use App\Models\User\AdditionalSectionContent;
use App\Models\User\Banner;
use App\Models\User\BasicExtende;
use App\Models\User\BasicSetting as UserBasicSetting;
use App\Models\User\BlogContent;
use App\Models\User\CallToAction;
use App\Models\User\CounterInformation;
use App\Models\User\CounterSection;
use App\Models\User\Faq;
use App\Models\User\HeroSlider;
use App\Models\User\HowitWorkSection;
use App\Models\User\Language as UserLanguage;
use App\Models\User\ProductHeroSlider;
use App\Models\User\SEO;
use App\Models\User\Social;
use App\Models\User\Tab;
use App\Models\User\Testimonial;
use App\Models\User\UserContact;
use App\Models\User\UserCoupon;
use App\Models\User\UserCustomDomain;
use App\Models\User\UserFooter;
use App\Models\User\UserHeader;
use App\Models\User\UserItem;
use App\Models\User\UserItemContent;
use App\Models\User\UserItemImage;
use App\Models\User\UserMenu;
use App\Models\User\UserOrder;
use App\Models\User\UserOrderItem;
use App\Models\User\UserPage;
use App\Models\User\UserPageContent;
use App\Models\User\UserPermission;
use App\Models\User\UserQrCode;
use App\Models\User\UserSection;
use App\Models\User\UserShippingCharge;
use App\Models\User\UserShopSetting;
use App\Models\User\UserUlink;
use App\Models\Variant;
use App\Models\VariantContent;
use App\Models\VariantOption;
use App\Models\VariantOptionContent;
use Auth;
use Carbon\Carbon;
use Hash;
use Session;
use Validator;

class RegisterUserController extends Controller
{
    public function index(Request $request)
    {
        if (session()->has('admin_lang')) {
            $lang_code = str_replace('admin_', '', session()->get('admin_lang'));
            $language = Language::where('code', $lang_code)->first();
            if (empty($language)) {
                $language = Language::where('is_default', 1)->first();
            }
        } else {
            $language = Language::where('is_default', 1)->first();
        }

        $term = $request->term;
        $users = User::when($term, function ($query, $term) {
            $query->where('username', 'like', '%' . $term . '%')->orWhere('email', 'like', '%' . $term . '%');
        })->orderBy('id', 'DESC')->paginate(10);

        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $gateways = $online->merge($offline);
        $packages = Package::query()->where('status', '1')->get();

        $categories = UserCategory::where('language_id', $language->id)->get();

        return view('admin.register_user.index', compact('users', 'gateways', 'packages', 'categories'));
    }

    public function view($id)
    {
        if (session()->has('admin_lang')) {
            $lang_code = str_replace('admin_', '', session()->get('admin_lang'));
            $language = Language::where('code', $lang_code)->first();
            if (empty($language)) {
                $language = Language::where('is_default', 1)->first();
            }
        } else {
            $language = Language::where('is_default', 1)->first();
        }

        $user = User::findOrFail($id);
        $packages = Package::query()->where('status', '1')->get();
        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $gateways = $online->merge($offline);
        $category =  UserCategory::query()->where([['language_id', $language->id], ['id', $user->category_id]])->pluck('name')->first();
        return view('admin.register_user.details', compact('user', 'packages', 'gateways', 'category'));
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|alpha_num|unique:users|not_in:admin',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'package_id' => 'required',
            'payment_gateway' => 'required',
            'online_status' => 'required',
            'shop_name' => 'required|max:255'
        ];

        $messages = [
            'package_id.required' => __('The package field is required'),
            'online_status.required' => __('The publicly hidden field is required'),
            'shop_name' => __('The shop name field is required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user = User::where('username', $request['username']);
        if ($user->count() == 0) {
            $user = User::create([
                'email' => $request['email'],
                'username' => $request['username'],
                'password' => bcrypt($request['password']),
                'online_status' => $request["online_status"],
                'status' => 1,
                'email_verified' => 1,
                'shop_name' => $request['shop_name'],
                'category_id' => $request['category'],
            ]);
        }

        if ($user) {
            $langCount = User\Language::where('user_id', $user->id)->where('is_default', 1)->count();
            $adminLangs = Language::get();
            if ($langCount == 0) {
                //create language for admin
                foreach ($adminLangs as $lang) {
                    $language = User\Language::create([
                        'name' => $lang->name,
                        'code' => $lang->code,
                        'is_default' => $lang->is_default,
                        'dashboard_default' => $lang->dashboard_default,
                        'rtl' => $lang->rtl,
                        'type' => 'admin',
                        'user_id' => $user->id,
                        'keywords' => $lang->customer_keywords
                    ]);

                    $menus = array(
                        array("text" => "Home", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "home"),
                        array("text" => "Blog", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "blog"),
                        array("text" => "FAQ", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "faq"),
                        array("text" => "Shop", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "shop"),
                        array("text" => "Contact", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "contact")
                    );

                    //create user default menus
                    UserMenu::create([
                        'user_id' => $user->id,
                        'language_id' => $language->id,
                        'menus' => json_encode($menus, true),
                    ]);
                }
            }

            //create user default currency usd
            $currCount = UserCurrency::where('user_id', $user->id)->where('is_default', 1)->count();
            if ($currCount == 0) {
                UserCurrency::create([
                    'text' => 'USD',
                    'symbol' => '$',
                    'value' => '1',
                    'is_default' => 1,
                    'text_position' => 'left',
                    'symbol_position' => 'left',
                    'user_id' => $user->id,
                ]);
            }

            $package = Package::find($request['package_id']);
            $be = BasicExtended::first();
            $bs = BasicSetting::select('website_title')->first();
            $transaction_id = UserPermissionHelper::uniqidReal(8);

            $startDate = Carbon::today()->format('Y-m-d');
            if ($package->term === "monthly") {
                $endDate = Carbon::today()->addMonth()->format('Y-m-d');
            } elseif ($package->term === "yearly") {
                $endDate = Carbon::today()->addYear()->format('Y-m-d');
            } elseif ($package->term === "lifetime") {
                $endDate = Carbon::maxValue()->format('d-m-Y');
            }

            Membership::create([
                'price' => $package->price,
                'currency' => $be->base_currency_text ? $be->base_currency_text : "USD",
                'currency_symbol' => $be->base_currency_symbol ? $be->base_currency_symbol : $be->base_currency_text,
                'payment_method' => $request["payment_gateway"],
                'transaction_id' => $transaction_id ? $transaction_id : 0,
                'status' => 1,
                'is_trial' => 0,
                'trial_days' => 0,
                'receipt' => $request["receipt_name"] ? $request["receipt_name"] : null,
                'transaction_details' => null,
                'settings' => json_encode($be),
                'package_id' => $request['package_id'],
                'user_id' => $user->id,
                'start_date' => Carbon::parse($startDate),
                'expire_date' => Carbon::parse($endDate),
            ]);
            $package = Package::findOrFail($request['package_id']);
            $features = json_decode($package->features, true);
            $features[] = "Contact";
            $features[] = "Footer Mail";
            $features[] = "Profile Listing";
            UserPermission::create([
                'package_id' => $request['package_id'],
                'user_id' => $user->id,
                'permissions' => json_encode($features)
            ]);

            //create user basic settings table
            UserBasicSetting::create([
                'user_id' => $user->id,
                'theme' => 'vegetables',
            ]);

            // create payment gateways
            $payment_keywords = ['flutterwave', 'razorpay', 'paytm', 'paystack', 'instamojo', 'stripe', 'paypal', 'mollie', 'mercadopago', 'authorize.net', 'midtrans', 'iyzico', 'toyyibpay', 'phonepe', 'yoco', 'xendit', 'myfatoorah', 'paytabs', 'perfect_money'];
            foreach ($payment_keywords as $key => $value) {
                UserPaymentGeteway::create([
                    'title' => null,
                    'user_id' => $user->id,
                    'details' => null,
                    'keyword' => $value,
                    'subtitle' => null,
                    'name' => ucfirst($value),
                    'type' => 'automatic',
                    'information' => null
                ]);
            }

            // create email template
            $this->storeEmailTemplate($user->id);

            //create user shop settings
            $shop_settings = new UserShopSetting();
            $shop_settings->user_id = $user->id;
            $shop_settings->catalog_mode = 0;
            $shop_settings->item_rating_system = 1;
            $shop_settings->top_rated_count = 5;
            $shop_settings->top_selling_count = 5;
            $shop_settings->save();

            //create footer
            $footer = new UserFooter();
            $footer->footer_text = 'lorem ispum dummy text.';
            $footer->user_id = $user->id;
            $footer->language_id = $language->id;
            $footer->useful_links_title = 'Useful Links';
            $footer->copyright_text = null;
            $footer->footer_logo =  null;
            $footer->background_image =  null;
            $footer->save();


            //send email to user
            $requestData = [
                'start_date' => $startDate,
                'expire_date' => $endDate,
                'payment_method' => $request['payment_gateway']
            ];
            $file_name = Common::makeInvoice($requestData, "membership", $user, null, $package->price, $request['payment_gateway'], null, $be->base_currency_text_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title,2);

            $mailer = new MegaMailer();
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
            $data = [
                'toMail' => $user->email,
                'toName' => $user->fname,
                'username' => $user->username,
                'package_title' => $package->title,
                'package_price' => ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : ''),
                'activation_date' => $startDate->toFormattedDateString(),
                'expire_date' => $endDate->toFormattedDateString(),
                'membership_invoice' => $file_name,
                'website_title' => $bs->website_title,
                'templateType' => 'registration_with_premium_package',
                'type' => 'registrationWithPremiumPackage'
            ];
            $mailer->mailFromAdmin($data);
        }

        Session::flash('success', __('Created Successfully'));
        return "success";
    }

    private function storeEmailTemplate($user_id)
    {
        $templates = [
            'email_verification' => [
                'email_subject' => 'Please Verify Your Email Address',
                'email_body' => '<p>Dear {customer_name},</p>
                <p>Thank you for signing up with {website_title}! To complete your registration and activate your account, please verify your email address by clicking the link below:</p>
                <p>{verification_link}</p>
                <p>If you didn’t sign up for an account with {website_title}, please ignore this message.</p>
                <p>If you have any questions or need assistance, feel free to contact us.</p>
                <p>Best regards,</p>
                <p><strong>{website_title}</strong></p>'
            ],
            'product_order' => [
                'email_subject' => 'Product Order Confirmation',
                'email_body' => "<p>Dear {customer_name},</p>
                <p>Thank you for your order with {website_title}! We're excited to let you know that we’ve received your order. Below, you’ll find the details of your purchase.</p>
                <h3><strong>Order Summary</strong></h3>
                <ul>
                <li><strong>Order Number:</strong> {order_number}</li>
                <li><strong>Order Details:</strong> {order_link}</li>
                </ul>
                <h3><strong>Shipping Information</strong></h3>
                <ul>
                <li><strong>Name:</strong> {shipping_fname} {shipping_lname}</li>
                <li><strong>Address:</strong> {shipping_address}, {shipping_city}, {shipping_country}</li>
                <li><strong>Phone Number:</strong> {shipping_number}</li>
                </ul>
                <h3><strong>Billing Information</strong></h3>
                <ul>
                <li><strong>Name:</strong> {billing_fname} {billing_lname}</li>
                <li><strong>Address:</strong> {billing_address}, {billing_city}, {billing_country}</li>
                <li><strong>Phone Number:</strong> {billing_number}</li>
                </ul>
                <p>We will process and ship your order shortly, and you’ll receive an email notification once your items are on the way.</p>
                <p>If you have any questions or need assistance, please feel free to contact our customer support team.</p>
                <p>Thank you for choosing {website_title}!</p>
                <p>Best regards,<br><strong>{website_title}</strong></p>"
            ],
            'reset_password' => [
                'email_subject' => 'Reset Your Password',
                'email_body' => "<p>Dear {customer_name},</p>
                <p>We received a request to reset your password for your account on {website_title}. To proceed, please click the link below to reset your password:</p>
                <p>{password_reset_link}</p>
                <p>If you didn’t request a password reset, please ignore this email. Your account remains secure.</p>
                <p>If you need further assistance, feel free to reach out to our support team.</p>
                <p>Best regards,<br><strong>{website_title}</strong></p>"
            ],
            'product_order_status' => [
                'email_subject' => 'Product Order Status',
                'email_body' => "<p>Dear {customer_name},</p>
                <p>We wanted to provide you with an update on the status of your order with {website_title}.</p>
                <h3><strong>Order Status: {order_status}</strong></h3>
                <p>We are working hard to ensure your order is processed and shipped as quickly as possible. You can always log in to your account for the latest updates on your order.</p>
                <p>If you have any questions or need assistance, don’t hesitate to contact our customer support team. We’re here to help!</p>
                <p>Thank you for shopping with {website_title}.</p>
                <p>Best regards,<br><strong>{website_title}</strong></p>"
            ]
        ];

        foreach ($templates as $key => $val) {
            UserEmailTemplate::create([
                'user_id' => $user_id,
                'email_type' => $key,
                'email_subject' => $val['email_subject'],
                'email_body' => $val['email_body'],
            ]);
        }
    }

    public function userban(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->status = $request->status;
        $user->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function emailStatus(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->update([
            'email_verified' => $request->email_verified,
        ]);

        Session::flash('success', __('Email status has been updated for') . ' ' . $user->username);
        return back();
    }

    public function userFeatured(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->featured = $request->featured;
        $user->feature_time = now();
        $user->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function userTemplate(Request $request)
    {
        if ($request->template == 1) {
            $prevImg = $request->file('preview_image');
            $allowedExts = array('jpg', 'png', 'jpeg');

            $rules = [
                'serial_number' => 'required|integer',
                'preview_image' => [
                    'required',
                    function ($attribute, $value, $fail) use ($prevImg, $allowedExts) {
                        if (!empty($prevImg)) {
                            $ext = $prevImg->getClientOriginalExtension();
                            if (!in_array($ext, $allowedExts)) {
                                return $fail(__('Only png, jpg, jpeg image is allowed'));
                            }
                        }
                    },
                ]
            ];


            $request->validate($rules);
        }

        $user = User::where('id', $request->user_id)->first();
        $dir = public_path('assets/front/img/template-previews/');
        if ($request->template == 1) {
            if ($request->hasFile('preview_image')) {
                @unlink($dir . $user->template_img);
                $user->template_img = Uploader::upload_picture($dir, $request->file('preview_image'));
            }
            $user->template_serial_number = $request->serial_number;
        } else {
            @unlink($dir . $user->template_img);
            $user->template_img = NULL;
            $user->template_serial_number = 0;
        }
        $user->preview_template = $request->template;
        $user->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function userUpdateTemplate(Request $request)
    {
        $prevImg = $request->file('preview_image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'serial_number' => 'required|integer',
            'preview_image' => [
                function ($attribute, $value, $fail) use ($prevImg, $allowedExts) {
                    if (!empty($prevImg)) {
                        $ext = $prevImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ]
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user = User::where('id', $request->user_id)->first();
        if ($request->hasFile('preview_image')) {
            $dir = public_path('assets/front/img/template-previews/');
            @unlink($dir . $user->template_img);
            $user->template_img = Uploader::upload_picture($dir, $request->file('preview_image'));
        }
        $user->template_serial_number = $request->serial_number;
        $user->save();


        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function changePass($id)
    {
        $data['user'] = User::findOrFail($id);
        return view('admin.register_user.password', $data);
    }

    public function updatePassword(Request $request)
    {
        $messages = [
            'npass.required' => __('New password is required'),
            'cfpass.required' => __('Confirm password is required'),
        ];

        $request->validate([
            'npass' => 'required',
            'cfpass' => 'required',
        ], $messages);

        $user = User::findOrFail($request->user_id);
        if ($request->npass == $request->cfpass) {
            $input['password'] = Hash::make($request->npass);
        } else {
            return back()->with('warning', __('Confirm password does not match'));
        }
        $user->update($input);
        Session::flash('success', __('Password update for') . ' ' . $user->username);
        return back();
    }

    public function delete(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $banners = Banner::where('user_id', $user->id)->get();
        foreach ($banners as $banner) {
            @unlink(public_path('assets/front/img/user/banners/') . $banner->banner_img);
            $banner->delete();
        }

        //delete user_basic_extendes
        $user_basic_extendes = BasicExtende::where('user_id', $user->id)->get();
        foreach ($user_basic_extendes as $user_basic_extend) {
            $user_basic_extend->delete();
        }
        //delete user_basic_settings
        $user_basic_settings = UserBasicSetting::where('user_id', $user->id)->get();
        foreach ($user_basic_settings as $user_basic_setting) {
            $user_basic_setting->delete();
        }

        if (!empty($user->blogs)) {
            $blogs = $user->blogs;
            if (!empty($blogs)) {
                foreach ($blogs as $blog) {
                    @unlink(public_path('assets/front/img/user/blogs/') . $blog->image);
                    $blog->delete();
                }
            }
        }

        //delete blog contents
        $user_blog_contents = BlogContent::where('user_id', $user->id)->get();
        foreach ($user_blog_contents as $user_blog_content) {
            $user_blog_content->delete();
        }

        if (!empty($user->blog_categories)) {
            $blogCategories = $user->blog_categories;
            if (!empty($blogCategories)) {
                foreach ($blogCategories as $blogCategory) {
                    $blogCategory->delete();
                }
            }
        }

        if (!empty($user->category_variations)) {
            $category_variations = $user->category_variations;
            if (!empty($category_variations)) {
                foreach ($category_variations as $category_variation) {
                    $category_variation->delete();
                }
            }
        }

        $contacts = UserContact::where('user_id', $user->id)->get();
        foreach ($contacts as $contact) {
            $contact->delete();
        }

        $faqs  = Faq::where('user_id', $user->id)->get();
        foreach ($faqs as $faq) {
            $faq->delete();
        }

        $footers = UserFooter::where('user_id', $user->id)->get();
        foreach ($footers as $footer) {
            $footer->delete();
        }

        $headers = UserHeader::where('user_id', $user->id)->get();
        foreach ($headers as $header) {
            $header->delete();
        }

        $hero_sliders = HeroSlider::where('user_id', $user->id)->get();
        foreach ($hero_sliders as $hero_slider) {
            @unlink(public_path('assets/front/img/hero_slider/') . $hero_slider->img);
            $hero_slider->delete();
        }

        if (!empty($user->itemInfo)) {
            $itemInfo = $user->itemInfo;
            if (!empty($itemInfo)) {
                foreach ($itemInfo as $Info) {
                    $Info->delete();
                }
            }
        }

        if (!empty($user->item_categories)) {
            $item_categories = $user->item_categories;
            if (!empty($item_categories)) {
                foreach ($item_categories as $item_categorie) {
                    @unlink(public_path('assets/front/img/user/items/categories/') . $item_categorie->image);
                    $item_categorie->delete();
                }
            }
        }

        if (!empty($user->item_sub_categories)) {
            $item_sub_categories = $user->item_sub_categories;
            if (!empty($item_sub_categories)) {
                foreach ($item_sub_categories as $item_sub_categorie) {
                    $item_sub_categorie->delete();
                }
            }
        }

        $menus = UserMenu::where('user_id', $user->id)->get();
        foreach ($menus as $menu) {
            $menu->delete();
        }

        $sections = UserSection::where('user_id', $user->id)->get();
        foreach ($sections as $section) {
            $section->delete();
        }

        $seos = SEO::where('user_id', $user->id)->get();
        foreach ($seos as $seo) {
            $seo->delete();
        }

        if (!empty($user->sub_category_variations)) {
            $sub_category_variations = $user->sub_category_variations;
            if (!empty($sub_category_variations)) {
                foreach ($sub_category_variations as $sub_category_variation) {
                    $sub_category_variation->delete();
                }
            }
        }

        $tabs = Tab::where('user_id', $user->id)->get();
        foreach ($tabs as $tab) {
            $tab->delete();
        }

        //varitaion delete
        $variants = Variant::where('user_id', $user->id)->get();
        foreach ($variants as $variant) {
            //delete variant content
            $variant_contents = VariantContent::where('variant_id', $variant->id)->get();
            foreach ($variant_contents as $variant_content) {
                $variant_content->delete();
            }

            //delete variant option
            $variant_options = VariantOption::where([['variant_id', $variant->id], ['user_id', $user->id]])->get();
            foreach ($variant_options as $variant_option) {
                $variant_option->delete();
            }
            //delete variant option contents
            $variation_option_contents = VariantOptionContent::where('variant_id', $variant->id)->get();
            foreach ($variation_option_contents as $variation_option_content) {
                $variation_option_content->delete();
            }

            $variant->delete();
        }
        //varitaion delete end

        //delete additional section
        $additional_sections = AdditionalSection::where('user_id', $user->id)->get();
        foreach ($additional_sections as $additional_section) {
            $additional_section_contents = AdditionalSectionContent::where('addition_section_id', $additional_section->id)->get();
            foreach ($additional_section_contents as $additional_section_content) {
                $additional_section_content->delete();
            }
            $additional_section->delete();
        }
        //delete additional section end

        //user_call_to_actions
        $user_call_to_actions = CallToAction::where('user_id', $user->id)->get();
        foreach ($user_call_to_actions as $user_call_to_action) {
            @unlink(public_path('assets/front/img/cta/') . $user_call_to_action->side_image);
            @unlink(public_path('assets/front/img/cta/') . $user_call_to_action->background_image);
            $user_call_to_action->delete();
        }

        $user_counter_informations = CounterInformation::where('user_id', $user->id)->get();
        foreach ($user_counter_informations as $user_counter_information) {
            $user_counter_information->delete();
        }

        //user_counter_sections
        $user_counter_sections = CounterSection::where('user_id', $user->id)->get();
        foreach ($user_counter_sections as $user_counter_section) {
            @unlink(public_path('assets/front/img/user/about/' . $user_counter_section->image));
            $user_counter_section->delete();
        }

        // user_coupons
        $user_coupons = UserCoupon::where('user_id', $user->id)->get();
        foreach ($user_coupons as $user_coupon) {
            $user_coupon->delete();
        }

        // user_currencies
        $user_currencies = UserCurrency::where('user_id', $user->id)->get();
        foreach ($user_currencies as $user_currency) {
            $user_currency->delete();
        }

        // user_custom_domains
        $user_custom_domains = UserCustomDomain::where('user_id', $user->id)->get();
        foreach ($user_custom_domains as $user_custom_domain) {
            $user_custom_domain->delete();
        }
        // user_email_templates
        $user_email_templates = UserEmailTemplate::where('user_id', $user->id)->get();
        foreach ($user_email_templates as $user_email_template) {
            $user_email_template->delete();
        }

        //user_howit_work_sections
        $user_howit_work_sections = HowitWorkSection::where('user_id', $user->id)->get();
        foreach ($user_howit_work_sections as $user_howit_work_section) {
            $user_howit_work_section->delete();
        }
        //user_items
        $user_items = UserItem::where('user_id', $user->id)->get();
        foreach ($user_items as $user_item) {
            @unlink(public_path('assets/front/img/user/items/thumbnail/') . $user_item->thumbnail);

            //user_item_images
            $user_item_images = UserItemImage::where('item_id', $user_item->id)->get();
            foreach ($user_item_images as $user_item_image) {
                @unlink(public_path('assets/front/img/user/items/slider-images/') . $user_item_image->image);
                $user_item_image->delete();
            }

            $user_item->delete();
        }

        //user_item_contents
        $user_item_contents = UserItemContent::where('user_id', $user->id)->get();
        foreach ($user_item_contents as $user_item_content) {
            $user_item_content->delete();
        }

        //user_languages
        $user_languages = UserLanguage::where('user_id', $user->id)->get();
        foreach ($user_languages as $user_language) {
            $user_language->delete();
        }

        //user_newsletter_subscribers
        $user_newsletter_subscribers = UserLanguage::where('user_id', $user->id)->get();
        foreach ($user_newsletter_subscribers as $user_newsletter_subscriber) {
            $user_newsletter_subscriber->delete();
        }
        //user_offline_gateways
        $user_offline_gateways = UserLanguage::where('user_id', $user->id)->get();
        foreach ($user_offline_gateways as $user_offline_gateway) {
            $user_offline_gateway->delete();
        }

        $user_orders = UserOrder::where('user_id', $user->id)->get();
        foreach ($user_orders as $user_order) {
            @unlink(public_path('assets/front/invoices/') . $user_order->invoice_number);
            @unlink(public_path('assets/front/receipt/') . $user_order->receipt);
            $user_order->delete();
        }

        $user_order_items = UserOrderItem::where('user_id', $user->id)->get();
        foreach ($user_order_items as $user_order_item) {
            $user_order_item->delete();
        }

        $user_pages = UserPage::where('user_id', $user->id)->get();
        foreach ($user_pages as $user_page) {
            $user_page->delete();
        }

        $user_page_contents = UserPageContent::where('user_id', $user->id)->get();
        foreach ($user_page_contents as $user_page_content) {
            $user_page_content->delete();
        }
        $user_payment_gateways = UserPaymentGeteway::where('user_id', $user->id)->get();
        foreach ($user_payment_gateways as $user_payment_gateway) {
            $user_payment_gateway->delete();
        }
        $user_permissions = UserPermission::where('user_id', $user->id)->get();
        foreach ($user_permissions as $user_permission) {
            $user_permission->delete();
        }

        $user_product_hero_sliders = ProductHeroSlider::where('user_id', $user->id)->get();
        foreach ($user_product_hero_sliders as $user_product_hero_slider) {
            $user_product_hero_slider->delete();
        }
        $user_qr_codes = UserQrCode::where('user_id', $user->id)->get();
        foreach ($user_qr_codes as $user_qr_code) {
            $user_qr_code->delete();
        }

        $user_shipping_charges = UserShippingCharge::where('user_id', $user->id)->get();
        foreach ($user_shipping_charges as $user_shipping_charge) {
            $user_shipping_charge->delete();
        }

        $user_shop_settings = UserShopSetting::where('user_id', $user->id)->get();
        foreach ($user_shop_settings as $user_shop_setting) {
            $user_shop_setting->delete();
        }

        $user_testimonials = Testimonial::where('user_id', $user->id)->get();
        foreach ($user_testimonials as $user_testimonial) {
            @unlink(public_path('assets/front/img/testimonials/') . $user_testimonial->image);
            $user_testimonial->delete();
        }

        $user_socials = Social::where('user_id', $user->id)->get();
        foreach ($user_socials as $user_social) {
            $user_social->delete();
        }

        $user_ulinks = UserUlink::where('user_id', $user->id)->get();
        foreach ($user_ulinks as $user_ulink) {
            $user_ulink->delete();
        }

        @unlink(public_path('assets/front/img/user/') . $user->photo);
        $user->delete();

        Session::flash('success', __('Deleted Successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $user = User::findOrFail($id);

            $banners = Banner::where('user_id', $id)->get();
            foreach ($banners as $banner) {
                @unlink(public_path('assets/front/img/user/banners/') . $banner->banner_img);
                $banner->delete();
            }

            //delete user_basic_extendes
            $user_basic_extendes = BasicExtende::where('user_id', $user->id)->get();
            foreach ($user_basic_extendes as $user_basic_extend) {
                $user_basic_extend->delete();
            }
            //delete user_basic_settings
            $user_basic_settings = UserBasicSetting::where('user_id', $user->id)->get();
            foreach ($user_basic_settings as $user_basic_setting) {
                $user_basic_setting->delete();
            }

            if (!empty($user->blogs)) {
                $blogs = $user->blogs;
                if (!empty($blogs)) {
                    foreach ($blogs as $blog) {
                        @unlink(public_path('assets/front/img/user/blogs/') . $blog->image);
                        $blog->delete();
                    }
                }
            }

            //delete blog contents
            $user_blog_contents = BlogContent::where('user_id', $user->id)->get();
            foreach ($user_blog_contents as $user_blog_content) {
                $user_blog_content->delete();
            }

            if (!empty($user->blog_categories)) {
                $blogCategories = $user->blog_categories;
                if (!empty($blogCategories)) {
                    foreach ($blogCategories as $blogCategory) {
                        $blogCategory->delete();
                    }
                }
            }

            if (!empty($user->category_variations)) {
                $category_variations = $user->category_variations;
                if (!empty($category_variations)) {
                    foreach ($category_variations as $category_variation) {
                        $category_variation->delete();
                    }
                }
            }

            $contacts = UserContact::where('user_id', $user->id)->get();
            foreach ($contacts as $contact) {
                $contact->delete();
            }

            $faqs  = Faq::where('user_id', $user->id)->get();
            foreach ($faqs as $faq) {
                $faq->delete();
            }

            $footers = UserFooter::where('user_id', $user->id)->get();
            foreach ($footers as $footer) {
                $footer->delete();
            }

            $headers = UserHeader::where('user_id', $user->id)->get();
            foreach ($headers as $header) {
                $header->delete();
            }

            $hero_sliders = HeroSlider::where('user_id', $user->id)->get();
            foreach ($hero_sliders as $hero_slider) {
                @unlink(public_path('assets/front/img/hero_slider/') . $hero_slider->img);
                $hero_slider->delete();
            }

            if (!empty($user->itemInfo)) {
                $itemInfo = $user->itemInfo;
                if (!empty($itemInfo)) {
                    foreach ($itemInfo as $Info) {
                        $Info->delete();
                    }
                }
            }

            if (!empty($user->item_categories)) {
                $item_categories = $user->item_categories;
                if (!empty($item_categories)) {
                    foreach ($item_categories as $item_categorie) {
                        @unlink(public_path('assets/front/img/user/items/categories/') . $item_categorie->image);
                        $item_categorie->delete();
                    }
                }
            }

            if (!empty($user->item_sub_categories)) {
                $item_sub_categories = $user->item_sub_categories;
                if (!empty($item_sub_categories)) {
                    foreach ($item_sub_categories as $item_sub_categorie) {
                        $item_sub_categorie->delete();
                    }
                }
            }

            $menus = UserMenu::where('user_id', $user->id)->get();
            foreach ($menus as $menu) {
                $menu->delete();
            }

            $sections = UserSection::where('user_id', $user->id)->get();
            foreach ($sections as $section) {
                $section->delete();
            }

            $seos = SEO::where('user_id', $user->id)->get();
            foreach ($seos as $seo) {
                $seo->delete();
            }

            if (!empty($user->sub_category_variations)) {
                $sub_category_variations = $user->sub_category_variations;
                if (!empty($sub_category_variations)) {
                    foreach ($sub_category_variations as $sub_category_variation) {
                        $sub_category_variation->delete();
                    }
                }
            }

            $tabs = Tab::where('user_id', $user->id)->get();
            foreach ($tabs as $tab) {
                $tab->delete();
            }

            //varitaion delete
            $variants = Variant::where('user_id', $user->id)->get();
            foreach ($variants as $variant) {
                //delete variant content
                $variant_contents = VariantContent::where('variant_id', $variant->id)->get();
                foreach ($variant_contents as $variant_content) {
                    $variant_content->delete();
                }

                //delete variant option
                $variant_options = VariantOption::where([['variant_id', $variant->id], ['user_id', $user->id]])->get();
                foreach ($variant_options as $variant_option) {
                    $variant_option->delete();
                }
                //delete variant option contents
                $variation_option_contents = VariantOptionContent::where('variant_id', $variant->id)->get();
                foreach ($variation_option_contents as $variation_option_content) {
                    $variation_option_content->delete();
                }

                $variant->delete();
            }
            //varitaion delete end

            //delete additional section
            $additional_sections = AdditionalSection::where('user_id', $user->id)->get();
            foreach ($additional_sections as $additional_section) {
                $additional_section_contents = AdditionalSectionContent::where('addition_section_id', $additional_section->id)->get();
                foreach ($additional_section_contents as $additional_section_content) {
                    $additional_section_content->delete();
                }
                $additional_section->delete();
            }
            //delete additional section end

            $user_counter_informations = CounterInformation::where('user_id', $user->id)->get();
            foreach ($user_counter_informations as $user_counter_information) {
                $user_counter_information->delete();
            }

            //user_counter_sections
            $user_counter_sections = CounterSection::where('user_id', $user->id)->get();
            foreach ($user_counter_sections as $user_counter_section) {
                @unlink(public_path('assets/front/img/user/about/' . $user_counter_section->image));
                $user_counter_section->delete();
            }

            // user_coupons
            $user_coupons = UserCoupon::where('user_id', $user->id)->get();
            foreach ($user_coupons as $user_coupon) {
                $user_coupon->delete();
            }

            // user_currencies
            $user_currencies = UserCurrency::where('user_id', $user->id)->get();
            foreach ($user_currencies as $user_currency) {
                $user_currency->delete();
            }

            // user_custom_domains
            $user_custom_domains = UserCustomDomain::where('user_id', $user->id)->get();
            foreach ($user_custom_domains as $user_custom_domain) {
                $user_custom_domain->delete();
            }
            // user_email_templates
            $user_email_templates = UserEmailTemplate::where('user_id', $user->id)->get();
            foreach ($user_email_templates as $user_email_template) {
                $user_email_template->delete();
            }

            //user_howit_work_sections
            $user_howit_work_sections = HowitWorkSection::where('user_id', $user->id)->get();
            foreach ($user_howit_work_sections as $user_howit_work_section) {
                $user_howit_work_section->delete();
            }

            //user_items
            $user_items = UserItem::where('user_id', $user->id)->get();
            foreach ($user_items as $user_item) {
                @unlink(public_path('assets/front/img/user/items/thumbnail/') . $user_item->thumbnail);

                $user_items = UserItem::where('user_id', $user->id)->get();
                foreach ($user_items as $user_item) {
                    @unlink(public_path('assets/front/img/user/items/thumbnail/') . $user_item->thumbnail);

                    //user_item_images
                    $user_item_images = UserItemImage::where('item_id', $user_item->id)->get();
                    foreach ($user_item_images as $user_item_image) {
                        @unlink(public_path('assets/front/img/user/items/slider-images/') . $user_item_image->image);
                        $user_item_image->delete();
                    }

                    $user_item->delete();
                }

                $user_item->delete();
            }

            //user_item_contents
            $user_item_contents = UserItemContent::where('user_id', $user->id)->get();
            foreach ($user_item_contents as $user_item_content) {
                $user_item_content->delete();
            }

            //user_languages
            $user_languages = UserLanguage::where('user_id', $user->id)->get();
            foreach ($user_languages as $user_language) {
                $user_language->delete();
            }

            //user_newsletter_subscribers
            $user_newsletter_subscribers = UserLanguage::where('user_id', $user->id)->get();
            foreach ($user_newsletter_subscribers as $user_newsletter_subscriber) {
                $user_newsletter_subscriber->delete();
            }
            //user_offline_gateways
            $user_offline_gateways = UserLanguage::where('user_id', $user->id)->get();
            foreach ($user_offline_gateways as $user_offline_gateway) {
                $user_offline_gateway->delete();
            }

            $user_orders = UserOrder::where('user_id', $user->id)->get();
            foreach ($user_orders as $user_order) {
                @unlink(public_path('assets/front/invoices/') . $user_order->invoice_number);
                @unlink(public_path('assets/front/receipt/') . $user_order->receipt);
                $user_order->delete();
            }

            $user_order_items = UserOrderItem::where('user_id', $user->id)->get();
            foreach ($user_order_items as $user_order_item) {
                $user_order_item->delete();
            }

            $user_pages = UserPage::where('user_id', $user->id)->get();
            foreach ($user_pages as $user_page) {
                $user_page->delete();
            }

            $user_page_contents = UserPageContent::where('user_id', $user->id)->get();
            foreach ($user_page_contents as $user_page_content) {
                $user_page_content->delete();
            }
            $user_payment_gateways = UserPaymentGeteway::where('user_id', $user->id)->get();
            foreach ($user_payment_gateways as $user_payment_gateway) {
                $user_payment_gateway->delete();
            }
            $user_permissions = UserPermission::where('user_id', $user->id)->get();
            foreach ($user_permissions as $user_permission) {
                $user_permission->delete();
            }

            $user_product_hero_sliders = ProductHeroSlider::where('user_id', $user->id)->get();
            foreach ($user_product_hero_sliders as $user_product_hero_slider) {
                $user_product_hero_slider->delete();
            }
            $user_qr_codes = UserQrCode::where('user_id', $user->id)->get();
            foreach ($user_qr_codes as $user_qr_code) {
                $user_qr_code->delete();
            }
            $user_shipping_charges = UserShippingCharge::where('user_id', $user->id)->get();
            foreach ($user_shipping_charges as $user_shipping_charge) {
                $user_shipping_charge->delete();
            }
            $user_shop_settings = UserShopSetting::where('user_id', $user->id)->get();
            foreach ($user_shop_settings as $user_shop_setting) {
                $user_shop_setting->delete();
            }

            $user_socials = Social::where('user_id', $user->id)->get();
            foreach ($user_socials as $user_social) {
                $user_social->delete();
            }

            $user_testimonials = Testimonial::where('user_id', $user->id)->get();
            foreach ($user_testimonials as $user_testimonial) {
                @unlink(public_path('assets/front/img/testimonials/') . $user_testimonial->image);
                $user_testimonial->delete();
            }
            $user_ulinks = UserUlink::where('user_id', $user->id)->get();
            foreach ($user_ulinks as $user_ulink) {
                $user_ulink->delete();
            }

            $user_socials = Social::where('user_id', $user->id)->get();
            foreach ($user_socials as $user_social) {
                $user_social->delete();
            }

            @unlink(public_path('assets/front/img/user/') . $user->photo);
            $user->delete();
        }

        Session::flash('success', __('Deleted Successfully'));
        return "success";
    }

    public function removeCurrPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $currMembership = UserPermissionHelper::currMembOrPending($userId);
        $currPackage = Package::select('title')->findOrFail($currMembership->package_id);
        $nextMembership = UserPermissionHelper::nextMembership($userId);
        $be = BasicExtended::first();
        $bs = BasicSetting::select('website_title')->first();

        $today = Carbon::now();

        // just expire the current package
        $currMembership->expire_date = $today->subDay();
        $currMembership->modified = 1;
        if ($currMembership->status == 0) {
            $currMembership->status = 2;
        }
        $currMembership->save();

        // if next package exists
        if (!empty($nextMembership)) {
            $nextPackage = Package::find($nextMembership->package_id);

            $nextMembership->start_date = Carbon::parse(Carbon::today()->format('d-m-Y'));
            if ($nextPackage->term == 'monthly') {
                $nextMembership->expire_date = Carbon::parse(Carbon::today()->addMonth()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'yearly') {
                $nextMembership->expire_date = Carbon::parse(Carbon::today()->addYear()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'lifetime') {
                $nextMembership->expire_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            $nextMembership->save();
        }

        $this->sendMail(NULL, NULL, $request->payment_method, $user, $bs, $be, 'admin_removed_current_package', NULL, $currPackage->title);

        Session::flash('success', __('The current package has been successfully removed'));
        return back();
    }


    public function sendMail($memb, $package, $paymentMethod, $user, $bs, $be, $mailType, $replacedPackage = NULL, $removedPackage = NULL)
    {

        if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
            $transaction_id = UserPermissionHelper::uniqidReal(8);
            $activation = $memb->start_date;
            $expire = $memb->expire_date;
            $info['start_date'] = $activation->toFormattedDateString();
            $info['expire_date'] = $expire->toFormattedDateString();
            $info['payment_method'] = $paymentMethod;

            $file_name = Common::makeInvoice($info, "membership", $user, NULL, $package->price, "Stripe", $user->phone, $be->base_currency_symbol_position, $be->base_currency_symbol, $be->base_currency_text, $transaction_id, $package->title,1);
        }

        $mailer = new MegaMailer();
        $data = [
            'toMail' => $user->email,
            'toName' => $user->fname,
            'username' => $user->username,
            'website_title' => $bs->website_title,
            'templateType' => $mailType
        ];

        if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
            $data['package_title'] = $package->title;
            $data['package_price'] = ($be->base_currency_text_position == 'left' ? $be->base_currency_text . ' ' : '') . $package->price . ($be->base_currency_text_position == 'right' ? ' ' . $be->base_currency_text : '');
            $data['activation_date'] = $activation->toFormattedDateString();
            $data['expire_date'] = Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString();
            $data['membership_invoice'] = $file_name;
        }
        if ($mailType != 'admin_removed_current_package' || $mailType != 'admin_removed_next_package') {
            $data['removed_package_title'] = $removedPackage;
        }

        if (!empty($replacedPackage)) {
            $data['replaced_package'] = $replacedPackage;
        }

        $mailer->mailFromAdmin($data);
    }


    public function changeCurrPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $currMembership = UserPermissionHelper::currMembOrPending($userId);
        $nextMembership = UserPermissionHelper::nextMembership($userId);

        $be = BasicExtended::first();
        $bs = BasicSetting::select('website_title')->first();

        $selectedPackage = Package::find($request->package_id);

        // if the user has a next package to activate & selected package is 'lifetime' package
        if (!empty($nextMembership) && $selectedPackage->term == 'lifetime') {
            Session::flash('membership_warning', __('To add a Lifetime package as Current Package, You have to remove the next package'));
            return back();
        }

        // expire the current package
        $currMembership->expire_date = Carbon::parse(Carbon::now()->subDay()->format('d-m-Y'));
        $currMembership->modified = 1;
        if ($currMembership->status == 0) {
            $currMembership->status = 2;
        }
        $currMembership->save();

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::now()->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::now()->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::maxValue()->format('d-m-Y');
        }
        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $be->base_currency_text,
            'currency_symbol' => $be->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => json_encode($be),
            'package_id' => $selectedPackage->id,
            'user_id' => $userId,
            'start_date' => Carbon::parse(Carbon::now()->format('d-m-Y')),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        // if the user has a next package to activate & selected package is not 'lifetime' package
        if (!empty($nextMembership) && $selectedPackage->term != 'lifetime') {
            $nextPackage = Package::find($nextMembership->package_id);

            // calculate & store next membership's start_date
            $nextMembership->start_date = Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'));

            // calculate & store expire date for next membership
            if ($nextPackage->term == 'monthly') {
                $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addMonth()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'yearly') {
                $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addYear()->format('d-m-Y'));
            } else {
                $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            $nextMembership->expire_date = $exDate;
            $nextMembership->save();
        }


        $currentPackage = Package::select('title')->findOrFail($currMembership->package_id);
        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $user, $bs, $be, 'admin_changed_current_package', $currentPackage->title);


        Session::flash('success', __('The current package has been successfully changed'));
        return back();
    }

    public function addCurrPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $be = BasicExtended::first();
        $bs = BasicSetting::select('website_title')->first();

        $selectedPackage = Package::find($request->package_id);

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::now()->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::now()->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::maxValue()->format('d-m-Y');
        }
        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $be->base_currency_text,
            'currency_symbol' => $be->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => json_encode($be),
            'package_id' => $selectedPackage->id,
            'user_id' => $userId,
            'start_date' => Carbon::parse(Carbon::now()->format('d-m-Y')),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $user, $bs, $be, 'admin_added_current_package');
        Session::flash('success', __('The current package has been successfully added'));
        return back();
    }

    public function removeNextPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $be = BasicExtended::first();
        $bs = BasicSetting::select('website_title')->first();
        $nextMembership = UserPermissionHelper::nextMembership($userId);
        // set the start_date to unlimited
        $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        $nextMembership->modified = 1;
        $nextMembership->save();

        $nextPackage = Package::select('title')->findOrFail($nextMembership->package_id);


        $this->sendMail(NULL, NULL, $request->payment_method, $user, $bs, $be, 'admin_removed_next_package', NULL, $nextPackage->title);

        Session::flash('success', __('The next package has been successfully removed'));
        return back();
    }

    public function changeNextPackage(Request $request)
    {
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        $bs = BasicSetting::select('website_title')->first();
        $be = BasicExtended::first();
        $nextMembership = UserPermissionHelper::nextMembership($userId);
        $nextPackage = Package::find($nextMembership->package_id);
        $selectedPackage = Package::find($request->package_id);

        $prevStartDate = $nextMembership->start_date;
        // set the start_date to unlimited
        $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        $nextMembership->modified = 1;
        $nextMembership->save();

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::parse($prevStartDate)->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::parse($prevStartDate)->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        }

        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $be->base_currency_text,
            'currency_symbol' => $be->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => json_encode($be),
            'package_id' => $selectedPackage->id,
            'user_id' => $userId,
            'start_date' => Carbon::parse($prevStartDate),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $user, $bs, $be, 'admin_changed_next_package', $nextPackage->title);

        Session::flash('success', __('The next package has been successfully changed'));
        return back();
    }

    public function addNextPackage(Request $request)
    {
        $userId = $request->user_id;

        $hasPendingMemb = UserPermissionHelper::hasPendingMembership($userId);
        if ($hasPendingMemb) {
            Session::flash('membership_warning', __('This user already has a pending package. Please take action (change, remove, approve, or reject) on that package first'));
            return back();
        }

        $currMembership = UserPermissionHelper::userPackage($userId);
        $currPackage = Package::find($currMembership->package_id);
        $be = BasicExtended::first();
        $user = User::findOrFail($userId);
        $bs = BasicSetting::select('website_title')->first();

        $selectedPackage = Package::find($request->package_id);

        if ($currMembership->is_trial == 1) {
            Session::flash('membership_warning', __('If your current package is trial package, then you have to change / remove the current package first'));
            return back();
        }


        // if current package is not lifetime package
        if ($currPackage->term != 'lifetime') {
            // calculate expire date for selected package
            if ($selectedPackage->term == 'monthly') {
                $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addMonth()->format('d-m-Y');
            } elseif ($selectedPackage->term == 'yearly') {
                $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addYear()->format('d-m-Y');
            } elseif ($selectedPackage->term == 'lifetime') {
                $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            // store a new membership for selected package
            $selectedMemb = Membership::create([
                'price' => $selectedPackage->price,
                'currency' => $be->base_currency_text,
                'currency_symbol' => $be->base_currency_symbol,
                'payment_method' => $request->payment_method,
                'transaction_id' => uniqid(),
                'status' => 1,
                'receipt' => NULL,
                'transaction_details' => NULL,
                'settings' => json_encode($be),
                'package_id' => $selectedPackage->id,
                'user_id' => $userId,
                'start_date' => Carbon::parse(Carbon::parse($currMembership->expire_date)->addDay()->format('d-m-Y')),
                'expire_date' => Carbon::parse($exDate),
                'is_trial' => 0,
                'trial_days' => 0,
            ]);

            $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $user, $bs, $be, 'admin_added_next_package');
        } else {
            Session::flash('membership_warning', __('If your current package is a lifetime package, you must change or remove it first'));
            return back();
        }
        Session::flash('success', __('The next package has been successfully added'));
        return back();
    }

    public function secret_login($id)
    {
        $user = User::findOrFail($id);
        Auth::guard('web')->login($user);
        Session::put('secrect_login', true);
        return redirect()->route('user-dashboard');
    }


    public function category(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $categories = UserCategory::where('language_id', $lang->id)->orderBy('id', 'DESC')->get();
        return view('admin.register_user.category.index', compact('categories'));
    }
    public function categoryStore(Request $request)
    {
        $rules = [
            'status' => 'required',
            'serial_number' => 'required|integer',
        ];
        $messages = [];
        $languages = Language::query()->get();
        $defaulLang = Language::query()->where('is_default', 1)->first();

        $rules[$defaulLang->code . '_name'] = 'required|max:255|unique:user_categories,name';
        $messages[$defaulLang->code . '_name.required'] = __('The category name field is required for') . ' ' . $defaulLang->name . ' ' . __('language');
        $messages[$defaulLang->code . '_name.unique'] = __('The category name has already been taken for') . ' ' . $defaulLang->name . ' ' . __('language');


        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $index_id = uniqid();
        foreach ($languages as $language) {
            if ($language->is_default == 1 || $request->filled($language->code . '_name')) {
                $category = new UserCategory;
                $category->unique_id = $index_id;
                $category->language_id = $language->id;
                $category->name = $request[$language->code . '_name'];
                $category->slug = make_slug($request[$language->code . '_name']);
                $category->status = $request->status;
                $category->serial_number = $request->serial_number;
                $category->save();
            }
        }

        Session::flash('success', __('Created Successfully'));
        return "success";
    }
    public function categoryEdit(Request $request, $id)
    {
        $data['data'] = UserCategory::findOrFail($id);
        $data['languages'] = Language::get();
        return view('admin.register_user.category.edit', $data);
    }
    public function categoryUpdate(Request $request)
    {
        $rules = [
            'status' => 'required',
            'serial_number' => 'required|integer',
        ];

        $defaulLang = Language::query()->where('is_default', 1)->first();
        $languages = Language::get();

        $rules[$defaulLang->code . '_name'] = 'required|max:255|unique:user_categories,name,' . $request->category_id;
        $messages[$defaulLang->code . '_name.required'] = __('The category name field is required for') . ' ' . $defaulLang->name . ' ' . __('language');
        $messages[$defaulLang->code . '_name.unique'] = __('The category name has already been taken for') . ' ' . $defaulLang->name . ' ' . __('language');

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $category = UserCategory::findOrFail($request->category_id);
        $unique_id = is_null($category->unique_id) ? uniqid() : $category->unique_id;

        foreach ($languages as $language) {
            if ($request->filled($language->code . '_name')) {
                $category = UserCategory::where('id', $request[$language->code . '_id'])->first();

                if (empty($category)) {
                    $category = new UserCategory();
                }
                $category->unique_id = $unique_id;
                $category->language_id = $language->id;
                $category->name = $request[$language->code . '_name'];
                $category->slug = make_slug($request[$language->code . '_name']);
                $category->status = $request->status;
                $category->serial_number = $request->serial_number;
                $category->save();
            }
        }

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }


    public function categoryDelete(Request $request)
    {
        $bcategory = UserCategory::where('id', $request->category_id)->firstOrFail();
        $bcategory->delete();

        Session::flash('success', __('Deleted Successfully'));
        return back();
    }
}
