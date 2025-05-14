<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\BasicSetting;
use App\Models\User\Language;
use App\Models\User\Language as UserLanguage;
use App\Models\User\UserSection;
use App\Models\User\UserShopSetting;
use App\Rules\ImageMimeTypeRule;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomePageTextController extends Controller
{
    public function index(Request $request)
    {
        $uLang = UserLanguage::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $userCurrentLang = $uLang->id;
        $data['ubs'] = UserSection::where('user_id', Auth::guard('web')->user()->id)->where('language_id', $userCurrentLang)->first();
        $data['setting'] = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->firstOrFail();
        if (!empty($request->language)) {
            $data['language'] = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->firstOrFail();
        }
        $data['u_langs'] = UserLanguage::where('user_id', Auth::guard('web')->user()->id)->get();

        return view('user.home.home-page-text', $data);
    }

    public function update(Request $request, $langid)
    {
        $theme = DB::table('user_basic_settings')->where('user_id', Auth::guard('web')->user()->id)
            ->pluck('theme')->first();
        $userSec = UserSection::where('user_id', Auth::guard('web')->user()->id)->where('language_id', $langid)->first();

        $validations = [];

        // Video Section Image
        if (in_array($theme, ['fashion', 'furniture', 'kids']) && empty($userSec->video_background_image)) {
            $validations['video_background_image'] = new ImageMimeTypeRule();
        }

        // Featured Section Image
        if ($theme === 'vegetables' && empty($userSec->featured_img)) {
            $validations['featured_img'] = new ImageMimeTypeRule();
        }

        // Action Section Images
        if ($theme !== 'fashion') {
            if ($theme !== 'kids') {
                if (empty($userSec->action_section_background_image)) {
                    $validations['action_section_background_image'] = new ImageMimeTypeRule();
                }
                if (empty($userSec->action_section_side_image)) {
                    $validations['action_section_side_image'] = new ImageMimeTypeRule();
                }
            }
        }

        if (!empty($validations)) {
            $request->validate($validations);
        }


        $data = $request->except(['_token', 'language', 'video_background_image', 'featured_img', 'action_section_background_image', 'action_section_side_image']);

        // File upload handling
        $fileFields = [
            'video_background_image' => 'assets/front/img/hero_slider/',
            'featured_img' => 'assets/front/img/user/feature/',
            'action_section_background_image' => 'assets/front/img/cta/',
            'action_section_side_image' => 'assets/front/img/cta/',
        ];

        foreach ($fileFields as $field => $path) {
            if ($request->hasFile($field)) {
                $request->validate([$field => new ImageMimeTypeRule()]);

                $existingImage = $userSec->$field ?? null;
                $data[$field] = $existingImage
                    ? Uploader::update_picture(public_path($path), $request->file($field), $existingImage)
                    : Uploader::upload_picture(public_path($path), $request->file($field));
            }
        }

        UserSection::updateOrInsert(
            [
                'user_id' => Auth::guard('web')->user()->id,
                'language_id' => $langid,
            ],
            $data
        );

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function sections(Request $request)
    {
        $data['ubs'] = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->firstOrFail();

        if (!is_null($data['ubs']->additional_section_status) && $data['ubs']->additional_section_status != "null") {
            $data['additional_section_statuses']  = json_decode($data['ubs']->additional_section_status, true);
        } else {
            $data['additional_section_statuses'] = [];
        }
        $data['langid'] = UserLanguage::where([['is_default', 1], ['user_id', Auth::guard('web')->user()->id]])->first()->id;

        $themeConfig = config('theme_config');
        $currentTheme = $data['ubs']->theme;
        $data['sections'] = $themeConfig[$currentTheme]['sections'] ?? [];

        return view('user.home.sections', $data);
    }

    public function updatesections(Request $request)
    {
        $bs = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->firstOrFail();
        $bs->slider_section  = $request->slider_section;
        $bs->right_banner_section  = $request->right_banner_section;
        $bs->category_section  = $request->category_section;
        $bs->categoryProduct_section  = $request->categoryProduct_section;
        $bs->flash_section  = $request->flash_section;
        $bs->tab_section  = $request->tab_section;
        $bs->latest_product_section  = $request->latest_product_section;
        $bs->newsletter_section  = $request->newsletter_section;
        $bs->left_banner_section  = $request->left_banner_section;
        $bs->banners_section  = $request->banners_section;
        $bs->middle_banner_section  = $request->middle_banner_section;
        $bs->top_rated_section  = $request->top_rated_section;
        $bs->top_selling_section  = $request->top_selling_section;
        $bs->featuers_section  = $request->featuers_section;
        $bs->footer_section  = $request->footer_section;
        $bs->copyright_section  = $request->copyright_section;
        $bs->video_banner_section  = $request->video_banner_section;
        $bs->featured_section  = $request->featured_section;
        $bs->cta_section_status  = $request->cta_section_status;
        $bs->bottom_middle_banner_section  = $request->bottom_middle_banner_section;
        $bs->top_middle_banner_section  = $request->top_middle_banner_section;
        $bs->top_right_banner_section  = $request->top_right_banner_section;
        $bs->bottom_left_banner_section  = $request->bottom_left_banner_section;
        $bs->middle_right_banner_section  = $request->middle_right_banner_section;
        $bs->bottom_right_banner_section  = $request->bottom_right_banner_section;
        $bs->additional_section_status = json_encode($request->additional_sections, true);
        $bs->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function contentSection(Request $request)
    {
        $uLang = UserLanguage::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $userCurrentLang = $uLang->id;
        $data['ubs'] = UserSection::where('user_id', Auth::guard('web')->user()->id)->where('language_id', $userCurrentLang)->first();
        $data['setting'] = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->firstOrFail();
        if (!empty($request->language)) {
            $data['language'] = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->firstOrFail();
        }
        $data['u_langs'] = UserLanguage::where('user_id', Auth::guard('web')->user()->id)->get();
        return view('user.home.home-page-text', $data);
    }

    public function removeImage(Request $request)
    {
        $userCurrentLang = $request->language_id;

        $ubs = UserSection::where('user_id', Auth::guard('web')->user()->id)->where('language_id', $userCurrentLang)->first();

        if (!$ubs) {
            return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);
        }
        $filePath = null;
        $type = $request->name;

        switch ($type) {
            case 'video_background_image';
                $filePath = public_path('assets/front/img/hero_slider/') . $ubs->video_background_image;
                break;
            case 'featured_img';
                $filePath = public_path('assets/front/img/user/feature/') . $ubs->featured_img;
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Invalid type'], 400);
        }
        // Remove the existing image file if it exists
        if (!empty($ubs->$type) && file_exists($filePath)) {
            @unlink($filePath);
        }

        $ubs->update([$type => null]);

        session()->flash('success', __('Image remove successfully'));
        return response()->json(['status' => 'success']);
    }

    public function item_highlight()
    {
        $data['shopsettings'] = UserShopSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        return view('user.home.settings', $data);
    }
    public function item_highlight_update(Request $request)
    {
        $shopsettings = UserShopSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        if (!$shopsettings) {
            $shopsettings  = new UserShopSetting();
        }
        $shopsettings->user_id = Auth::guard('web')->user()->id;
        $shopsettings->top_selling_count =  $request->top_selling_count ?? 4;
        $shopsettings->top_rated_count =  $request->top_rated_count ?? 4;
        $shopsettings->categories_count =  $request->categories_count ?? 4;
        $shopsettings->subcategories_count =  $request->subcategories_count ?? 4;
        $shopsettings->flash_item_count =  $request->flash_item_count ?? 4;
        $shopsettings->latest_item_count =  $request->latest_item_count ?? 4;
        $shopsettings->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }
}
