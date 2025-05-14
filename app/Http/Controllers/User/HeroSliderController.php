<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\BasicExtende;
use App\Models\User\BasicSetting;
use App\Models\User\HeroSlider;
use App\Models\User\Language;
use App\Models\User\ProductHeroSlider;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Purifier;

class HeroSliderController extends Controller
{
    public function sliderVersion(Request $request)
    {
        // first, get the language info from db
        $language = \App\Models\User\Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        // then, get the slider version info of that language from db
        $information['sliders'] = HeroSlider::where('language_id', $language->id)
            ->orderBy('id', 'desc')
            ->where('user_id', Auth::guard('web')->user()->id)
            ->where('is_static', 0)
            ->get();

        $data['ubs'] = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->firstOrFail();

        return view('user.home.hero_section.slider_version', $information);
    }
    public function createSlider(Request $request)
    {
        // get the language info from db
        $language = \App\Models\User\Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $information['language'] = $language;
        return view('user.home.hero_section.create_slider', $information);
    }

    public function storeSliderInfo(Request $request): \Illuminate\Http\RedirectResponse
    {
        $userBs = DB::table('user_basic_settings')->select('theme')->where('user_id', Auth::guard('web')->user()->id)->first();
        $request->validate(
            [
                'title' => 'nullable|max:255',
                'subtitle' => 'nullable|max:255',
                'text' => 'nullable|max:255',
                'btn_name' => 'nullable|max:255',
                'btn_url' => 'nullable|max:255',
                'serial_number' => 'required',
                'slider_img' => $userBs->theme == 'vegetables' || $userBs->theme == 'electronics' ? 'required|mimes:jpeg,jpg,png|max:1000' : '',
                'user_language_id' => 'required',
            ]
        );
        if ($request->hasFile('slider_img')) {
            $request['image_name'] = Uploader::upload_picture(public_path('assets/front/img/hero_slider'), $request->file('slider_img'));
        }

        HeroSlider::create($request->except('language_id', 'img', 'user_id', 'title') + [
            'language_id' => $request->user_language_id,
            'img' => $request->image_name,
            'user_id' => Auth::guard('web')->user()->id,
            'title' => Purifier::clean($request->title, 'youtube')
        ]);
        Session::flash('success', __('Created successfully'));
        return redirect()->back();
    }

    public function editSlider(Request $request, $id)
    {
        // get the language info from db
        $language = \App\Models\User\Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $information['language'] = $language;
        // get the slider info from db for update
        $information['slider'] = HeroSlider::findOrFail($id);
        return view('user.home.hero_section.edit_slider', $information);
    }

    public function updateSliderInfo(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'title' => 'nullable|max:255',
            'subtitle' => 'nullable|max:255',
            'text' => 'nullable|max:255',
            'btn_name' => 'nullable|max:255',
            'btn_url' => 'nullable|max:255',
            'serial_number' => 'required',
        ], [
            'title.max' => __('The title field can contain maximum 255 characters'),
            'subtitle.max' => __('The subtitle field can contain maximum 255 characters'),
            'text.max' => __('The text field can contain maximum 255 characters'),
            'btn_name.max' => __('The button name field can contain maximum 255 characters'),
            'btn_url.max' => __('The button url field can contain maximum 255 characters'),
            'serial_number.required' => __('The serial number field is required'),
        ]);
        $slider = HeroSlider::where('user_id', Auth::guard('web')->user()->id)->where('id', $id)->firstOrFail();
        $request['image_name'] = $slider->img;
        if ($request->hasFile('slider_img')) {
            $request['image_name'] = Uploader::update_picture(public_path('assets/front/img/hero_slider'), $request->file('slider_img'), $slider->img);
        }
        $slider->update($request->except('img', 'title') + [
            'img' => $request->image_name,
            'title' => Purifier::clean($request->title, 'youtube')
        ]);
        Session::flash('success', __('Updated Successfully'));
        return redirect()->back();
    }

    public function deleteSlider(Request $request)
    {
        $slider = HeroSlider::findOrFail($request->slider_id);
        @unlink(public_path('assets/front/img/hero_slider/') . $slider->img);
        $slider->delete();
        Session::flash('success', __('Deleted successfully'));
        return redirect()->back();
    }

    public function updateStaticSlider(Request $request, $language)
    {
        $lang = \App\Models\User\Language::where('code', $language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $data = HeroSlider::where([
            ['user_id', Auth::guard('web')->user()->id],
            ['language_id', $lang->id],
            ['is_static', 1],
        ])->first();
        if (is_null($data)) {
            $data = new HeroSlider;
        }


        if (
            empty($data->slider_img) &&
            !$request->hasFile('slider_img')
        ) {
            $rules['slider_img'] = 'required|mimes:jpeg,jpg,png';
        }
        $messages = [
            'slider_img.required' => __('The image field is required')
        ];
        $validator = Validator::make($request->all(), $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $image = isset($data) ? $data->img : null;
        $request['image_name'] = $image;
        if ($request->hasFile('slider_img')) {
            $request['image_name'] = Uploader::update_picture(public_path('assets/front/img/hero_slider/'), $request->file('slider_img'), $image);
        }

        $data->img = $request->image_name;
        $data->language_id = $lang->id;

        $data->title = $request->title ?? null;
        $data->text = $request->text ?? null;
        $data->subtitle = $request->subtitle ?? null;
        $data->btn_name = $request->btn_name ?? null;
        $data->btn_url = $request->btn_url ?? null;
        $data->video_url = $request->video_url ?? null;

        $data->is_static = 1;
        $data->user_id = Auth::guard('web')->user()->id;
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function HeroSecBgImg(Request $request)
    {
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();
        $data = BasicExtende::where('user_id', Auth::guard('web')->user()->id)
            ->where('language_id', $lang->id)
            ->select('hero_section_background_image')
            ->first();
        return view('user.home.hero_section.background-image', compact('data'));
    }

    public function updateHeroSecBgImg(Request $request)
    {
        $lang = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();

        $data = BasicExtende::where('user_id', Auth::guard('web')->user()->id)->where('language_id', $lang->id)->first();

        if (empty($data->hero_section_background_image) && !$request->hasFile('hero_section_background_image')) {
            $rules['hero_section_background_image'] = 'required|mimes:jpeg,jpg,png';
        }

        $messages = [
            'hero_section_background_image.required' => __('The background image field is required')
        ];
        $validator = Validator::make($request->all(), $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $image = isset($data) ? $data->hero_section_background_image : null;

        if (is_null($data)) {
            $data = new BasicExtende();
            $data->language_id = $lang->id;
            $data->user_id = Auth::guard('web')->user()->id;
        }

        $image_name = $image;

        if ($request->hasFile('hero_section_background_image')) {
            if (!is_null($image)) {
                $image_name = Uploader::update_picture(public_path('assets/front/img/hero_slider/'), $request->file('hero_section_background_image'), $image);
            } else {
                $image_name = Uploader::upload_picture(public_path('assets/front/img/hero_slider/'), $request->file('hero_section_background_image'));
            }
        }

        $data->hero_section_background_image = $image_name;
        $data->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function productSlider()
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where([['user_id', $user_id], ['is_default', 1]])->first();

        $data['items'] = DB::table('user_items')->where('user_items.user_id', Auth::guard('web')->user()->id)
            ->Join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->select('user_items.id AS item_id', 'user_item_contents.title')
            ->orderBy('user_items.id', 'DESC')
            ->where('user_item_contents.language_id', '=', $lang->id)
            ->get();

        $product_sliders = ProductHeroSlider::where('user_id', $user_id)->first();
        if ($product_sliders) {
            $data['added_products'] = json_decode($product_sliders->products, true);
        } else {
            $data['added_products'] = [];
        }
        return view('user.home.hero_section.product-slider', $data);
    }

    public function updateProductSlider(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $product_sliders = ProductHeroSlider::where('user_id', $user_id)->first();
        if (empty($product_sliders)) {
            $product_sliders = new ProductHeroSlider();
        }
        $product_sliders->user_id = $user_id;
        $product_sliders->products = json_encode($request->products, true);
        $product_sliders->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
}
