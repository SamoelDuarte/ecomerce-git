<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\Banner;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BannerSectionController extends Controller
{
    public function bannerSection(Request $request)
    {

        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        // also, get the banner info of that language from db
        $information['banners'] = banner::where('language_id', $language->id)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('user.home.banner_section.index', $information);
    }

    public static function countBanner($userId, $position, $lang)
    {
        return Banner::where([['user_id', $userId], ['language_id', $lang], ['position', $position]])->count();
    }

    public function storebanner(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $theme = DB::table('user_basic_settings')->where('user_id', $userId)->pluck('theme')->first();
        $totalBanners = self::countBanner($userId, $request->position, $request->user_language_id);


        if ($theme === 'vegetables') {
            if ($request->position == 'right' && $totalBanners >= 2) {
                return response()->json(['status' => 'fail', 'message' => __('The right position allows a maximum of 2 banners')]);
            } elseif ($request->position == 'bottom_left' && $totalBanners >= 1) {
                return response()->json(['status' => 'fail', 'message' => __('The bottom left position allows only 1 banner')]);
            }
        }
        if ($theme === 'furniture') {
            if ($request->position == 'bottom_middle' && $totalBanners >= 1) {
                return response()->json(['status' => 'fail', 'message' => __('The bottom middle position allows a maximum of 1 banners')]);
            }
        }
        if ($theme === 'fashion') {
            if ($request->position == 'middle' && $totalBanners >= 3) {
                return response()->json(['status' => 'fail', 'message' => __('The bottom middle position allows a maximum of 3 banners')]);
            }
        }
        if ($theme === 'electronics') {
            if ($request->position == 'top_right' && $totalBanners >= 3) {
                return response()->json(['status' => 'fail', 'message' => __('The bottom middle position allows a maximum of 3 banners')]);
            } elseif ($request->position == 'bottom_left' && $totalBanners >= 2) {
                return response()->json(['status' => 'fail', 'message' => __('The bottom left position allows only 2 banner')]);
            }
        }
        if ($theme === 'manti') {
            if ($request->position == 'bottom_right' && $totalBanners >= 1) {
                return response()->json(['status' => 'fail', 'message' => __('The bottom middle position allows a maximum of 1 banners')]);
            }
        }


        $request->validate(
            [
                'user_language_id' => 'required',
                'banner_img' => 'required|mimes:jpeg,jpg,png|max:1000',
                'banner_url' => 'required',
                'serial_number' => 'required'
            ],
            [
                'banner_img.required' => __('The banner image field is required.'),
                'banner_url.required' => __('The banner url field is required.'),
            ]
        );

        if ($request->hasFile('banner_img')) {
            $request['image_name'] = Uploader::upload_picture(public_path('assets/front/img/user/banners'), $request->file('banner_img'));
        }
        banner::create($request->except('banner_img', 'user_id') + [
            'user_id' => Auth::guard('web')->user()->id,
            'language_id' => $request->user_language_id,
            'banner_img' => $request->image_name,
            'banner_url' => $request->banner_url,
            'title' => $request->itle,
            'subtitle' => $request->subtitle,
            'text' => $request->text,
            'button_text' => $request->btn_text,
            'position' => $request->position,
            'serial_number' => $request->serial_number,
        ]);
        Session::flash('success', __('Created successfully'));
        return 'success';
    }

    public function updatebanner(Request $request)
    {

        $banner = Banner::where([['user_id', Auth::guard('web')->user()->id], ['id', $request->banner_id]])->firstOrFail();

        $rules = [
            'banner_url' => 'required',
            'serial_number' => 'required'
        ];
        $messages = [
            'banner_url.required' => __('The banner url field is required'),
            'serial_number.required' => __("The serial number field is required")
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $request['image_name'] = $banner->banner_img;
        if ($request->hasFile('banner_img')) {
            $request['image_name'] = Uploader::update_picture(public_path('assets/front/img/user/banners'), $request->file('banner_img'), $banner->banner_img);
        }
        $banner->update($request->except('banner_img') + [
            'banner_img' => $request->image_name
        ]);
        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }

    public function deletebanner(Request $request)
    {
        $banner = banner::where('user_id', Auth::guard('web')->user()->id)->where('id', $request->banner_id)->firstOrFail();
        @unlink(public_path('assets/front/img/user/banners/') . $banner->banner_img);
        $banner->delete();

        Session::flash('success', __('Deleted successfully'));
        return redirect()->back();
    }
}
