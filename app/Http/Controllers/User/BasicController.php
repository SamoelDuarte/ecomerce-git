<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\Language as AdminLanguage;
use App\Models\Timezone;
use App\Models\User\BasicExtende;
use App\Models\User\BasicSetting;
use App\Models\User\Faq;
use App\Models\User\Language;
use App\Models\User\SEO;
use App\Models\User\UserPage;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Response;

class BasicController extends Controller
{
    public function themeVersion()
    {
        $userId = Auth::guard('web')->user()->id;
        $data = BasicSetting::where('user_id', $userId)->first();

        return view('user.settings.themes', ['data' => $data]);
    }

    public function updateThemeVersion(Request $request)
    {
        $rule = [
            'theme' => 'required'
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $data = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        $data->theme = $request->theme;
        $data->save();

        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }

    public function breadcrumb(Request $request)
    {
        $authId = Auth::guard('web')->user()->id;
        $uLang = Language::where('code', $request->language)->where('user_id', $authId)->first();
        $userCurrentLang = $uLang->id;

        $data['breadcrumb'] = BasicExtende::where([['language_id', $userCurrentLang],['user_id',$authId]])->pluck('breadcrumb')->first();
        $data['u_langs'] = Language::where('user_id', $authId)->get();
        return view('user.settings.breadcrumb', $data);
    }

    public function updatebreadcrumb(Request $request)
    {
        $img = $request->file('breadcrumb');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'breadcrumb' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__("Only png, jpg, jpeg image is allowed"));
                        }
                    }
                },
            ],
        ];

        $request->validate($rules);

        $authId = Auth::guard('web')->user()->id;
        $uLang = Language::where('code', $request->language)->where('user_id', $authId)->first();
        $userCurrentLang = $uLang->id;

        if ($request->hasFile('breadcrumb')) {
            $dir = public_path('assets/front/img/user/');
            $filename = Uploader::upload_picture($dir, $img);

            $bss = BasicExtende::where([['language_id', $userCurrentLang], ['user_id', $authId]])->first();
            if (!is_null($bss)) {
                if ($bss->breadcrumb) {
                    @unlink($dir . $bss->breadcrumb);
                }
                $bss->breadcrumb = $filename;
                $bss->user_id = Auth::guard('web')->user()->id;
                $bss->save();
            } else {
                $bs = new BasicExtende();
                $bs->language_id = $userCurrentLang;
                $bs->breadcrumb = $filename;
                $bs->user_id = Auth::guard('web')->user()->id;
                $bs->save();
            }
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function generalSettings()
    {
        $data['data'] = BasicSetting::where('user_id', Auth::guard('web')->user()->id)
            ->first();

        $data['timezones'] = Timezone::all();
        $data['dashboard_languages'] = AdminLanguage::get();
        return view('user.settings.general-settings', $data);
    }

    public function updateInfo(Request $request)
    {
        $logo = $request->file('logo');
        $favicon = $request->file('favicon');
        $preloader = $request->file('preloader');
        $allowedExts = array('jpg', 'png', 'jpeg', 'gif');

        $rules = [
            'base_currency_symbol_position' => 'required',
            'logo' => [
                function ($attribute, $value, $fail) use ($logo, $allowedExts) {
                    if (!empty($logo)) {
                        $ext = $logo->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__("Only png, jpg, jpeg image is allowed"));
                        }
                    }
                },
            ],
            'favicon' => [
                function ($attribute, $value, $fail) use ($favicon, $allowedExts) {
                    if (!empty($favicon)) {
                        $ext = $favicon->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__("Only png, jpg, jpeg image is allowed"));
                        }
                    }
                },
            ],
            'preloader' => [
                function ($attribute, $value, $fail) use ($preloader, $allowedExts) {
                    if (!empty($preloader)) {
                        $ext = $preloader->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__("Only png, jpg, jpeg, gif image is allowed"));
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $bss = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        $dir = public_path('assets/front/img/user/');
        if ($request->hasFile('logo')) {
            @unlink($dir . $bss->logo);
            $filename = Uploader::upload_picture($dir, $logo);
        } else {
            $filename = $bss->logo;
        }
        if ($request->hasFile('favicon')) {
            @unlink($dir . $bss->favicon);
            $favicon_name = Uploader::upload_picture($dir, $favicon);
        } else {
            $favicon_name = $bss->favicon;
        }

        if ($request->hasFile('preloader')) {
            @unlink($dir . $bss->preloader);
            $preloader_filename = Uploader::upload_picture($dir, $preloader);
        } else {
            $preloader_filename = $bss->preloader;
        }

        BasicSetting::where('user_id', Auth::guard('web')->user()->id)->update([
            'base_currency_symbol_position' => $request->base_currency_symbol_position,
            'logo' => $filename,
            'favicon' => $favicon_name,
            'preloader' => $preloader_filename,
            'preloader_status' => $request->preloader_status,
            'base_color' => $request->base_color,
            'timezone' => $request->timezone
        ]);
        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }

    public function seo(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', $userId)->firstOrFail();
        $langId = $language->id;

        // then, get the seo info of that language from db
        $seo = SEO::where('language_id', $langId)->where('user_id', $userId);

        if ($seo->count() == 0) {
            // if seo info of that language does not exist then create a new one
            SEO::create($request->except('language_id', 'user_id') + [
                'language_id' => $langId,
                'user_id' => $userId
            ]);
        }

        $information['language'] = $language;

        // then, get the seo info of that language from db
        $information['data'] = $seo->first();

        $information['decodedKeywords'] = isset($information['data']->custome_page_meta_keyword) ? json_decode($information['data']->custome_page_meta_keyword, true) : '';
        $information['decodedDescriptions'] = isset($information['data']->custome_page_meta_description) ? json_decode($information['data']->custome_page_meta_description, true) : '';

        // get all the languages from db
        $information['langs'] = Language::where('user_id', $userId)->get();
        $information['pages'] = UserPage::where('user_id', $userId)->get();

        return view('user.settings.seo', $information);
    }

    public function updateSEO(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', $user_id)->first();


        $langId = $language->id;

        // then, get the seo info of that language from db
        $seo = SEO::where('language_id', $langId)->where('user_id', $user_id)->first();

        // else update the existing seo info of that language
        $seo->update($request->all());

        Session::flash('success', __('Updated Successfully'));

        return redirect()->back();
    }

    public function faqindex(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();

        $lang_id = $lang->id;
        $data['faqs'] = Faq::where([['language_id', $lang_id], ['user_id', $user_id]])->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;
        return view('user.faq.index', $data);
    }

    public function faqstore(Request $request)
    {
        $rules = [
            'user_language_id' => 'required',
            'question' => 'required|max:255',
            'answer' => 'required',
            'serial_number' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $faq = new Faq;
        $faq->language_id = $request->user_language_id;
        $faq->user_id = Auth::guard('web')->user()->id;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->serial_number = $request->serial_number;
        $faq->save();

        Session::flash('success', __('Created successfully'));
        return "success";
    }

    public function faqupdate(Request $request)
    {
        $rules = [
            'question' => 'required|max:255',
            'answer' => 'required',
            'serial_number' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $faq = Faq::findOrFail($request->faq_id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->serial_number = $request->serial_number;
        $faq->save();

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function faqdelete(Request $request)
    {

        $faq = Faq::findOrFail($request->faq_id);
        $faq->delete();

        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function faqbulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $faq = Faq::findOrFail($id);
            $faq->delete();
        }
        Session::flash('success', __('Deleted successfully'));
        return "success";
    }


    public function cookieAlert(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $lang = Language::query()
            ->where('code', $request->language)
            ->where('user_id', $userId)
            ->first();

        $data['userLangs'] = Language::where('user_id', $userId)
            ->get();

        $data['lang_id'] = $lang->id;
        $data['abe'] = BasicExtende::query()
            ->where('user_id', $userId)
            ->where('language_id', $lang->id)
            ->first();
        return view('user.settings.cookie-alert', $data);
    }

    public function updatecookie(Request $request, $langid)
    {
        $userId = Auth::guard('web')->user()->id;
        $request->validate([
            'cookie_alert_status' => 'required',
            'cookie_alert_text' => 'required',
            'cookie_alert_button_text' => 'required|max:255',
        ]);

        $be = BasicExtende::query()
            ->where('user_id', $userId)
            ->where('language_id', $langid)
            ->first();
        if (empty($be)) {
            $be = new BasicExtende();
            $be->language_id = $langid;
            $be->user_id = $userId;
        }
        $be->cookie_alert_status = $request->cookie_alert_status;
        $be->cookie_alert_text = Purifier::clean($request->cookie_alert_text, 'youtube');
        $be->cookie_alert_button_text = $request->cookie_alert_button_text;
        $be->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }


    public function maintenance()
    {
        $data = BasicSetting::query()->where('user_id', Auth::guard('web')->user()->id)
            ->select('maintenance_img', 'maintenance_status', 'maintenance_msg', 'bypass_token')
            ->first();

        return view('user.settings.maintenance', ['data' => $data]);
    }


    public function updateMaintenance(Request $request)
    {
        $data = BasicSetting::query()
            ->where('user_id', Auth::guard('web')->user()->id)
            ->select('maintenance_img')
            ->first();

        $rules = $messages = [];

        if (!$request->filled('maintenance_img') && is_null($data->maintenance_img)) {
            $rules['maintenance_img'] = 'required';
            $messages['maintenance_img.required'] = __('The maintenance image field is required.');
        }
        if ($request->hasFile('maintenance_img')) {
            $rules['maintenance_img'] = new ImageMimeTypeRule();
        }

        $rules['maintenance_status'] = 'required';
        $rules['maintenance_msg'] = 'required';

        $messages['maintenance_msg.required'] = __('The maintenance message field is required.');
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }
        if ($request->hasFile('maintenance_img')) {
            $directory = public_path('assets/user-front/images/');
            $imageName = Uploader::update_picture($directory, $request->file('maintenance_img'), $data->maintenance_img);
        }
        BasicSetting::query()->updateOrInsert(
            ['user_id' => Auth::guard('web')->user()->id],
            $request->except(['_token', 'user_id', 'maintenance_img', 'maintenance_msg']) + [
                'maintenance_img' => $request->hasFile('maintenance_img') ? $imageName : $data->maintenance_img,
                'maintenance_msg' => Purifier::clean($request->maintenance_msg),
                'user_id' => Auth::guard('web')->user()->id
            ]
        );

        session()->flash('success', __('Maintenance Info updated successfully'));
        return "success";
    }

    public function userNotFoundPage(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->first();

        $data =  DB::table('user_basic_extendes')
            ->where([['user_id', Auth::guard('web')->user()->id], ['language_id', $language->id]])
            ->select('user_not_found_title', 'user_not_found_subtitle')
            ->first();

        $image = BasicSetting::query()
            ->where('user_id', Auth::guard('web')->user()->id)
            ->pluck('page_not_found_image')
            ->first();

        return view('user.404', compact('data', 'image'));
    }

    public function updateUserNotFoundPage(Request $request)
    {
        $user = Auth::guard('web')->user();

        // Fetch language and existing settings
        $language = Language::where('code', $request->language)
            ->where('user_id', $user->id)
            ->first();

        $data = BasicSetting::query()
            ->where('user_id', $user->id)
            ->select('page_not_found_image')
            ->first();

        // Validation rules and messages
        $rules = [
            'user_not_found_title' => 'required|max:255',
            'user_not_found_subtitle' => 'required|max:255',
        ];
        $messages = [];

        if (!$request->filled('page_not_found_image') && (is_null($data) || is_null($data->page_not_found_image))) {
            $rules['page_not_found_image'] = 'required';
            $messages['page_not_found_image.required'] = 'The image field is required.';
        }

        if ($request->hasFile('page_not_found_image')) {
            $rules['page_not_found_image'] = new ImageMimeTypeRule();
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()], 400);
        }

        // Handle image upload if necessary
        $imageName = $data->page_not_found_image ?? null;

        if ($request->hasFile('page_not_found_image')) {
            $directory = public_path('assets/user-front/images/');
            $imageName = is_null($imageName)
                ? Uploader::upload_picture($directory, $request->file('page_not_found_image'))
                : Uploader::update_picture($directory, $request->file('page_not_found_image'), $imageName);
        }

        // Update BasicExtende
        BasicExtende::updateOrInsert(
            ['user_id' => $user->id, 'language_id' => $language->id],
            $request->except(['_token', 'page_not_found_image']) + [
                'user_not_found_title' => $request->user_not_found_title,
                'user_not_found_subtitle' => $request->user_not_found_subtitle,
                'user_id' => $user->id,
            ]
        );

        BasicSetting::where('user_id', $user->id)
            ->update(['page_not_found_image' => $imageName]);

        session()->flash('success', __('Updated successfully'));
        return "success";
    }
}
