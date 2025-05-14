<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\Admin\Heading;
use App\Models\BasicExtended;
use App\Models\BasicSetting;
use App\Models\Language;
use App\Models\Page;
use App\Models\Seo;
use App\Models\Timezone;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Purifier;
use Validator;

class BasicController extends Controller
{
    public function generalSetting()
    {
        $data['abs'] = BasicSetting::firstOrFail();
        $data['abe'] = BasicExtended::firstOrFail();
        $data['timezones'] = Timezone::all();
        return view('admin.basic.general-settings', $data);
    }

    public function updateGeneralSetting(Request $request)
    {
        $allowedExts = array('jpg', 'png', 'jpeg', 'ico');
        $allowedExts2 =  array('jpg', 'png', 'jpeg');
        $allowedExts3 = array('jpg', 'png', 'jpeg', 'gif', 'svg');
        $img = $request->file('favicon');
        $img2 = $request->file('logo');
        $img3 = $request->file('preloader');
        $rules = [
            'website_title' => 'required',
            'timezone' => 'required',
            'base_color' => 'required',
            'base_color_2' => 'required',
            'base_currency_symbol' => 'required',
            'base_currency_symbol_position' => 'required',
            'base_currency_text' => 'required',
            'base_currency_text_position' => 'required',
            'base_currency_rate' => 'required|numeric',
            'favicon' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
            'logo' => [
                function ($attribute, $value, $fail) use ($img2, $allowedExts2) {
                    if (!empty($img2)) {
                        $ext = $img2->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts2)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
            'preloader_status' => 'required',
            'preloader' => [
                function ($attribute, $value, $fail) use ($img3, $allowedExts3) {
                    if (!empty($img3)) {
                        $ext = $img3->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts3)) {
                            return $fail(__("Only gif, png, jpg, jpeg image is allowed"));
                        }
                    }
                },
            ]
        ];

        $request->validate($rules);

        $dir = public_path('assets/front/img/');
        if ($request->hasFile('favicon')) {
            $favicon_name = Uploader::upload_picture($dir, $img);
        }

        if ($request->hasFile('logo')) {
            $logoname = Uploader::upload_picture($dir, $img2);
        }

        if ($request->hasFile('preloader')) {
            $preloadername = Uploader::upload_picture($dir, $img3);
        }

        $bss = BasicSetting::all();
        foreach ($bss as $key => $bs) {
            $bs->website_title = $request->website_title;
            $bs->base_color = $request->base_color;
            $bs->time_format = $request->time_format;
            $bs->base_color_2 = $request->base_color_2;
            $bs->favicon = $request->hasFile('favicon') ? $favicon_name : $bs->favicon;
            $bs->logo = $request->hasFile('logo') ? $logoname : $bs->logo;
            $bs->preloader = $request->hasFile('preloader') ? $preloadername : $bs->preloader;
            $bs->save();
        }

        $bes = BasicExtended::all();
        foreach ($bes as $key => $be) {
            $be->base_currency_symbol = $request->base_currency_symbol;
            $be->base_currency_symbol_position = $request->base_currency_symbol_position;
            $be->base_currency_text = $request->base_currency_text;
            $be->base_currency_text_position = $request->base_currency_text_position;
            $be->base_currency_rate = $request->base_currency_rate;
            $be->timezone = $request->timezone;
            $be->save();
        }
        // set timezone in .env
        if ($request->has('timezone') && $request->filled('timezone')) {
            $arr = ['TIMEZONE' => $request->timezone];
            setEnvironmentValue($arr);
            \Artisan::call('config:clear');
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }


    public function updateslider(Request $request, $lang)
    {
        $be = BasicExtended::where('language_id', $lang)->firstOrFail();

        $dir = public_path('assets/front/img/');
        @mkdir($dir, 0775, true);
        if ($request->hasFile('slider_shape_img')) {
            @unlink($dir . $be->slider_shape_img);
            $filename = Uploader::upload_picture($dir, $request->slider_shape_img);
            $be->slider_shape_img = $filename;
        }

        if ($request->hasFile('slider_bottom_img')) {
            @unlink($dir . $be->slider_bottom_img);
            $filename = Uploader::upload_picture($dir, $request->slider_bottom_img);
            $be->slider_bottom_img = $filename;
        }

        $be->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function breadcrumb(Request $request)
    {
        $data['abs'] = BasicSetting::firstOrFail();
        return view('admin.basic.breadcrumb', $data);
    }

    public function updatebreadcrumb(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'breadcrumb']);
        }
        if ($request->hasFile('file')) {
            $dir = public_path('assets/front/img/');
            @mkdir($dir, 0775, true);
            $filename = Uploader::upload_picture($dir, $img);

            $bss = BasicSetting::all();
            foreach ($bss as $key => $bs) {
                @unlink($dir . $bs->breadcrumb);
                $bs->breadcrumb = $filename;
                $bs->save();
            }
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }


    public function script()
    {
        $data = BasicSetting::first();
        return view('admin.basic.scripts', ['data' => $data]);
    }

    public function updatescript(Request $request)
    {

        $bss = BasicSetting::all();

        foreach ($bss as $bs) {
            $bs->tak_to_property_id = $request->tak_to_property_id;
            $bs->tak_to_widget_id = $request->tak_to_widget_id;
            $bs->is_tawkto = $request->is_tawkto;

            $bs->is_disqus = $request->is_disqus;
            $bs->is_user_disqus = $request->is_user_disqus;
            $bs->disqus_shortname = $request->disqus_shortname;

            $bs->is_recaptcha = $request->is_recaptcha;
            $bs->google_recaptcha_site_key = $request->google_recaptcha_site_key;
            $bs->google_recaptcha_secret_key = $request->google_recaptcha_secret_key;

            $bs->is_whatsapp = $request->is_whatsapp;
            $bs->whatsapp_number = $request->whatsapp_number;
            $bs->whatsapp_header_title = $request->whatsapp_header_title;
            $bs->whatsapp_popup_message = Purifier::clean($request->whatsapp_popup_message);
            $bs->whatsapp_popup = $request->whatsapp_popup;

            $bs->save();
        }

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function maintainance()
    {
        $data = BasicSetting::select('maintainance_mode', 'maintenance_img', 'maintenance_status', 'maintainance_text', 'secret_path')
            ->first();

        return view('admin.basic.maintainance', ['data' => $data]);
    }

    public function updatemaintainance(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
            'maintenance_status' => 'required',
            'maintainance_text' => 'required'
        ];

        $message = [
            'maintainance_text.required' => __('The maintenance message field is required')
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $bs = BasicSetting::first();

        $dir = public_path("assets/front/img/");
        @mkdir($dir, 0775, true);
        // first, get the maintenance image from db
        if ($request->hasFile('file')) {
            @unlink($dir . $bs->maintenance_img);
            $filename = Uploader::upload_picture($dir, $request->file('file'));
        }

        $down = "down";
        if ($request->filled('secret_path')) {
            $down .= " --secret=" . $request->secret_path;
        }
        if ($request->maintenance_status == 1) {
            @unlink(storage_path('framework/down'));
            Artisan::call($down);
        } else {
            Artisan::call('up');
        }
        $bs->update([
            'maintenance_img' => $request->hasFile('file') ? $filename : $bs->maintenance_img,
            'maintenance_status' => $request->maintenance_status,
            'maintainance_text' => Purifier::clean($request->maintainance_text, 'youtube'),
            'secret_path' => $request->secret_path
        ]);
        Session::flash('success', __('Updated Successfully'));
        return redirect()->back();
    }

    public function sections(Request $request)
    {
        $data['abs'] = BasicSetting::first();
        if (!is_null($data['abs']->additional_section_status) && $data['abs']->additional_section_status != "null") {
            $data['additional_section_statuses']  = json_decode($data['abs']->additional_section_status, true);
        } else {
            $data['additional_section_statuses'] = [];
        }
        $language = Language::where('is_default', 1)->first();
        $data['languge_id'] = $language->id;
        return view('admin.home.sections', $data);
    }

    public function updatesections(Request $request)
    {
        $bss = BasicSetting::all();
        $in = $request->all();
        $in['additional_section_status'] = json_encode($request->additional_sections, true);
        unset($in['additional_sections']);
        foreach ($bss as $key => $bs) {
            $bs->update($in);
        }

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function aboutSectionInfo()
    {
        $data['abs'] = BasicSetting::first();
        if (!is_null($data['abs']->about_additional_section_status) && $data['abs']->about_additional_section_status != "null") {
            $data['additional_section_statuses']  = json_decode($data['abs']->about_additional_section_status, true);
        } else {
            $data['additional_section_statuses'] = [];
        }
        $language = Language::where('is_default', 1)->first();
        $data['languge_id'] = $language->id;
        return view('admin.about.sections', $data);
    }

    public function aboutSectionInfoUpdate(Request $request)
    {
        $bss = BasicSetting::all();
        $in = $request->all();
        $in['about_additional_section_status'] = json_encode($request->additional_sections, true);
        unset($in['additional_sections']);
        foreach ($bss as $key => $bs) {
            $bs->update($in);
        }

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function cookiealert(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abe'] = $lang->basic_extended;

        return view('admin.basic.cookie', $data);
    }

    public function updatecookie(Request $request, $langid)
    {
        $request->validate([
            'cookie_alert_status' => 'required',
            'cookie_alert_text' => 'required',
            'cookie_alert_button_text' => 'required|max:25',
        ]);

        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        $be->cookie_alert_status = $request->cookie_alert_status;
        $be->cookie_alert_text = Purifier::clean($request->cookie_alert_text);
        $be->cookie_alert_button_text = $request->cookie_alert_button_text;
        $be->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function seo(Request $request)
    {
        $language = Language::where('code', $request->language)->firstOrFail();
        $langId = $language->id;
        $seo = Seo::where('language_id', $langId);

        if ($seo->count() == 0) {
            Seo::create($request->except('language_id') + [
                'language_id' => $langId
            ]);
        }

        $information['language'] = $language;
        $information['data'] = $seo->first();
        $information['langs'] = Language::all();

        $information['decodedKeywords'] = isset($information['data']->custome_page_meta_keyword) ? json_decode($information['data']->custome_page_meta_keyword, true) : '';
        $information['decodedDescriptions'] = isset($information['data']->custome_page_meta_description) ? json_decode($information['data']->custome_page_meta_description, true) : '';

        $information['pages'] = Page::where('language_id', $langId)->get();

        return view('admin.basic.seo', $information);
    }

    public function updateSEO(Request $request)
    {
        $language = Language::where('code', $request->language)->firstOrFail();
        $langId = $language->id;
        $seo = SEO::where('language_id', $langId)->first();
        $seo->update($request->all());
        Session::flash('success', __('Updated Sccessfully'));
        return redirect()->back();
    }

    public function heading(Request $request)
    {
        $data['language'] = Language::where('code', $request->language)->firstOrFail();
        $data['heading'] = Heading::where('language_id', $data['language']->id)->first();
        $data['decodedHeadings'] = isset($data['heading']->custom_page_heading) ? json_decode($data['heading']->custom_page_heading, true) : '';
        $data['pages'] = Page::where('language_id', $data['language']->id)->get();
        return view('admin.heading.index', $data);
    }
    public function update_heading(Request $request)
    {
        $data = [
            'template_title' => request('template_title'),
            'pricing_title' => request('pricing_title'),
            'shop_title' => request('shop_title'),
            'faq_title' => request('faq_title'),
            'contact_title' => request('contact_title'),
            'blog_title' => request('blog_title'),
            'login_title' => request('login_title'),
            'reset_password_title' => request('reset_password_title'),
            'signup_title' => request('signup_title'),
            'checkout_title' => request('checkout_title'),
            'custom_page_heading' => request('custom_page_heading'),
            'not_found_title' => request('not_found_title'),
            'language_id' => $request->language_id
        ];

        Heading::updateOrInsert(['language_id' => $request->language_id], $data);

        session()->flash('success', __('Updated successfully'));
        return 'success';
    }
}
