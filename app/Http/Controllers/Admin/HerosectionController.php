<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Admin\ImageText;
use Validator;
use Session;

class HerosectionController extends Controller
{
    public function imgtext(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['data'] = ImageText::where('language_id', $lang->id)->first();

        return view('admin.home.hero.img-text', $data);
    }

    public function update(Request $request, $langid)
    {
        $sideImg = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'image' => [
                function ($attribute, $value, $fail) use ($request, $sideImg, $allowedExts) {
                    if ($request->hasFile('image')) {
                        $ext = $sideImg->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
            'hero_section_title' => 'nullable|max:255',
            'hero_section_text' => 'nullable|max:255',
            'hero_section_desc' => 'nullable|max:255',
            'hero_section_button_text' => 'nullable|max:30',
            'hero_section_button_url' => 'nullable',
            'hero_section_video_url' => 'nullable',
            'features_section_title' => 'nullable|max:255',
            'features_section_subtitle' => 'nullable|max:255',
            'features_section_text' => 'nullable|max:255',
            'features_section_btn_text' => 'nullable|max:30',
            'features_section_btn_url' => 'nullable',
            'features_section_video_url' => 'nullable',
            'blog_section_title' => 'nullable|max:255',
            'partner_section_title' => 'nullable|max:255',
            'partner_section_subtitle' => 'nullable|max:255',
            'work_process_section_title' => 'nullable|max:255',
            'template_section_title' => 'nullable|max:255',
            'template_section_subtitle' => 'nullable|max:255',
            'pricing_section_title' => 'nullable|max:255',
            'pricing_section_subtitle' => 'nullable|max:255',
            'featured_shop_section_title' => 'nullable|max:255',
            'featured_shop_section_subtitle' => 'nullable|max:255',
            'testimonial_section_title' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $content = ImageText::where('language_id', $langid)->first();
        $data = $request->except(['_token', 'language', 'image', 'unique_id']);

        $dir = public_path('assets/front/img/');
        if ($request->hasFile('image')) {
            $existingImage = $content ? $content->image : null;

            if (!is_null($existingImage)) {
                $data['image'] = Uploader::update_picture($dir, $request->file('image'), $existingImage);
            } else {
                $data['image'] = Uploader::upload_picture($dir, $request->file('image'));
            }
        }

        ImageText::updateOrInsert(
            [
                'unique_id' => 12345,
                'language_id' => $langid,
            ],
            $data
        );

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }
}
