<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\BasicExtended;
use App\Models\User\Language;
use App\Models\User\UserFooter;
use App\Traits\LanguageFallbackTrait;
use Auth;
use Illuminate\Http\Request;
use Purifier;
use Session;
use Validator;

class FooterSectionController extends Controller
{
    use LanguageFallbackTrait;
    public function index(Request $request)
    {
        $lang = $this->getLanguageWithFallback($request->language, Auth::guard('web')->user()->id);

        $data['lang_id'] = $lang->id;
        $data['footer'] = UserFooter::where('language_id', $lang->id)->where('user_id', Auth::guard('web')->user()->id)->first();

        return view('user.footer.logo-text', $data);
    }

    public function update(Request $request, $langid)
    {

        $img = $request->file('file');
        $background_image = $request->file('footer_background_image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'copyright_text' => 'nullable',
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__("Only png, jpg, jpeg image is allowed"));
                        }
                    }
                },
            ],
            'useful_links_title' => 'nullable|max:50',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }


        $bs = UserFooter::where('language_id', $langid)->where('user_id', Auth::guard('web')->user()->id)->first();
        $lang = Language::where('id', $langid)->where('user_id', Auth::guard('web')->user()->id)->first();
        // Se não encontrar a linguagem por ID, usar fallback
        if (!$lang) {
            $lang = $this->getLanguageWithFallback(null, Auth::guard('web')->user()->id);
        }


        if ($request->hasFile('file')) {
            $dir = public_path('assets/front/img/footer/');
            @unlink($dir  . $bs->footer_logo);
            $footer_logo = Uploader::upload_picture($dir, $img);
        } else {
            $footer_logo = $bs->footer_logo ?? NULL;
        }

        if ($request->hasFile('footer_background_image')) {
            $dir = public_path('assets/front/img/footer/');
            @mkdir($dir, 0775, true);
            $background_image = Uploader::upload_picture($dir, $background_image);
        } else {
            $background_image = $bs->background_image ?? NULL;
        }

        if (!empty($bs)) {
            $bs->footer_text = $request->footer_text;
            $bs->user_id = Auth::guard('web')->user()->id;
            $bs->language_id = $lang->id;
            $bs->useful_links_title = $request->useful_links_title;
            $bs->copyright_text = Purifier::clean($request->copyright_text);
            $bs->footer_logo =  $footer_logo;
            $bs->background_image =  $background_image;
            $bs->subscriber_title =  $request->subscriber_title;
            $bs->subscriber_text =  $request->subscriber_text;
            $bs->save();
        } else {
            @unlink($dir  . $bs->background_image);

            $bs = new UserFooter();
            $bs->user_id = Auth::guard('web')->user()->id;
            $bs->language_id = $lang->id;
            $bs->footer_text = $request->footer_text;
            $bs->useful_links_title = $request->useful_links_title;
            $bs->copyright_text = Purifier::clean($request->copyright_text);
            $bs->footer_logo =  $footer_logo;
            $bs->background_image =  $background_image;
            $bs->subscriber_title =  $request->subscriber_title;
            $bs->subscriber_text =  $request->subscriber_text;
            $bs->save();
        }

        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }

    public function removeImage(Request $request)
    {
        $type = $request->type;
        $langid = $request->language_id;
        $be = BasicExtended::where('language_id', $langid)->firstOrFail();
        if ($type == "bottom") {
            @unlink(public_path("assets/front/img/") . $be->footer_bottom_img);
            $be->footer_bottom_img = NULL;
            $be->save();
        }
        Session::flash('success', __('Removed successfully'));
        return "success";
    }
}
