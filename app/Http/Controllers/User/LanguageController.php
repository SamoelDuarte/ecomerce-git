<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\BlogContent;
use App\Models\User\Language;
use App\Models\User\UserMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Session;
use Validator;


class LanguageController extends Controller
{
    public function index($lang = false)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['languages'] = Language::where('user_id', $user_id)->get();

        $current_package = UserPermissionHelper::currentPackagePermission($user_id);

        $data['language_limit'] = $current_package->language_limit;
        $data['total_languages'] = Language::where([['user_id', $user_id], ['type', '!=', 'admin']])->count();

        return view('user.language.index', $data);
    }


    public function store(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $language_limit = $current_package->language_limit;
        $total_languages = Language::where([['user_id', $user_id], ['type', '!=', 'admin']])->count();

        if ($language_limit < $total_languages) {
            Session::flash('warning', __('Custom language limit exceeded'));
            return "success";
        }

        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $language = Language::where([
                        ['code', $value],
                        ['user_id', Auth::guard('web')->user()->id]
                    ])->get();
                    if ($language->count() > 0) {
                        $fail(':attribute already taken');
                    }
                },
            ],
            'direction' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $deLang = Language::first();

        $in['name'] = $request->name;
        $in['code'] = $request->code;
        $in['rtl'] = $request->direction;
        $in['keywords'] = $deLang->keywords;
        $in['type'] = 'user';
        $in['user_id'] = Auth::guard('web')->user()->id;
        if (Language::where([
            ['is_default', 1],
            ['user_id', Auth::guard('web')->user()->id]
        ])->count() > 0) {
            $in['is_default'] = 0;
        } else {
            $in['is_default'] = 1;
        }
        $newLang = Language::create($in);
        $menus = array(
            array("text" => "Home", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "home"),
            array("text" => "Shop", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "shop"),
            array("text" => "Blog", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "blog"),
            array("text" => "FAQ", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "faq"),
            array("text" => "Contact", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "contact"),
            array("text" => "About Us", "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "about")
        );
        //create menu for new language
        UserMenu::create([
            'user_id' => Auth::guard('web')->user()->id,
            'language_id' => $newLang->id,
            'menus' => json_encode($menus, true),
        ]);

        Session::flash('success', __('Created successfully'));
        return "success";
    }

    public function edit($id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $language_limit = $current_package->language_limit;
        $total_languages = Language::where([['user_id', $user_id], ['type', '!=', 'admin']])->count();

        if ($language_limit < $total_languages) {
            Session::flash('warning', __('Custom language limit exceeded'));
            return back();
        }

        if ($id > 0) {
            $data['language'] = Language::where('user_id', Auth::guard('web')->user()->id)->where('id', $id)->firstOrFail();
        }
        $data['id'] = $id;
        return view('user.language.edit', $data);
    }


    public function update(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $language_limit = $current_package->language_limit;
        $total_languages = Language::where([['user_id', $user_id], ['type', '!=', 'admin']])->count();

        if ($language_limit < $total_languages) {
            Session::flash('warning', __('Custom Language Limit Exceeded'));
            return back();
        }

        $language = Language::findOrFail($request->language_id);

        if ($language->user_id != Auth::guard('web')->user()->id) {
            return;
        }

        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($language, $request) {
                    $langs = Language::where('user_id', Auth::guard('web')->user()->id)->where('id', '<>', $language->id)->get();
                    foreach ($langs as $key => $lang) {
                        if ($lang->code == $request->code) {
                            return $fail(__("Language code have to be unique"));
                        }
                    }
                }
            ],
            'direction' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $language->name = $request->name;
        $language->code = $request->code;
        $language->rtl = $request->direction;
        $language->user_id = Auth::guard('web')->user()->id;
        $language->save();

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function editKeyword($id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $language_limit = $current_package->language_limit;
        $total_languages = Language::where([['user_id', $user_id], ['type', '!=', 'admin']])->count();

        if ($language_limit < $total_languages) {
            Session::flash('warning', __('Custom Language Limit Exceeded'));
            return back();
        }
        $data['la'] = Language::where('user_id', Auth::guard('web')->user()->id)->where('id', $id)->firstOrFail();
        $data['cus_keywords'] = json_decode($data['la']->keywords, true);

        return view('user.language.edit-keyword', $data);
    }

    public function updateKeyword(Request $request, $id)
    {
        $lang = Language::findOrFail($id);
        if ($lang->user_id != Auth::guard('web')->user()->id) {
            return back();
        }
        $arrData = $request['keyValues'];
        $lang->keywords = json_encode($arrData);
        $lang->save();
        return back()->with('success', __('Updated Successfully'));
    }


    public function delete($id)
    {

        $la = Language::where('user_id', Auth::guard('web')->user()->id)->where('id', $id)->firstOrFail();
        if ($la->is_default == 1) {
            return back()->with('warning', __('The default language cannot be deleted'));
        }
        if (session()->get('lang') == $la->code) {
            session()->forget('lang');
        }

        // deleting testimonials for corresponding language
        if (!empty($la->services)) {
            $services = $la->services;
            if (!empty($services)) {
                foreach ($services as $service) {
                    @unlink(public_path('assets/front/img/user/services/') . $service->image);
                    $service->delete();
                }
            }
        }

        if (!empty($la->banners)) {
            $banners = $la->banners;
            if (!empty($banners)) {
                foreach ($banners as $banner) {
                    @unlink(public_path('assets/front/img/user/banners/') . $banner->banner_img);
                    $banner->delete();
                }
            }
        }

        // deleting blogs for corresponding language
        $blog_contents = BlogContent::where('language_id', $la->id)->get();
        if (!empty($blog_contents)) {
            foreach ($blog_contents as $blog) {
                $blog->delete();
            }
        }

        // deleting blog categories for corresponding language
        if (!empty($la->blog_categories)) {
            $blogCategories = $la->blog_categories;
            if (!empty($blogCategories)) {
                foreach ($blogCategories as $blogCategory) {
                    $blogCategory->delete();
                }
            }
        }

        if (!empty($la->category_variations)) {
            $category_variations = $la->category_variations;
            if (!empty($category_variations)) {
                foreach ($category_variations as $category_variation) {
                    $category_variation->delete();
                }
            }
        }

        if (!empty($la->contacts)) {
            $contacts = $la->contacts;
            if (!empty($contacts)) {
                foreach ($contacts as $contact) {
                    $contact->delete();
                }
            }
        }

        if (!empty($la->faqs)) {
            $faqs = $la->faqs;
            if (!empty($faqs)) {
                foreach ($faqs as $faq) {
                    $faq->delete();
                }
            }
        }

        if (!empty($la->footers)) {
            $footers = $la->footers;
            if (!empty($footers)) {
                foreach ($footers as $footer) {
                    $footer->delete();
                }
            }
        }

        if (!empty($la->headers)) {
            $headers = $la->headers;
            if (!empty($headers)) {
                foreach ($headers as $header) {
                    $header->delete();
                }
            }
        }

        if (!empty($la->hero_sliders)) {
            $hero_sliders = $la->hero_sliders;
            if (!empty($hero_sliders)) {
                foreach ($hero_sliders as $hero_slider) {
                    @unlink(public_path('assets/front/img/hero_slider/') . $hero_slider->img);
                    $hero_slider->delete();
                }
            }
        }

        if (!empty($la->itemInfo)) {
            $itemInfo = $la->itemInfo;
            if (!empty($itemInfo)) {
                foreach ($itemInfo as $Info) {
                    $Info->delete();
                }
            }
        }

        if (!empty($la->item_categories)) {
            $item_categories = $la->item_categories;
            if (!empty($item_categories)) {
                foreach ($item_categories as $item_categorie) {
                    @unlink(public_path('assets/front/img/user/items/categories/') . $item_categorie->image);
                    $item_categorie->delete();
                }
            }
        }

        if (!empty($la->item_sub_categories)) {
            $item_sub_categories = $la->item_sub_categories;
            if (!empty($item_sub_categories)) {
                foreach ($item_sub_categories as $item_sub_categorie) {
                    $item_sub_categorie->delete();
                }
            }
        }

        if (!empty($la->menus)) {
            $menus = $la->menus;
            if (!empty($menus)) {
                foreach ($menus as $menu) {
                    $menu->delete();
                }
            }
        }

        if (!empty($la->sections)) {
            $sections = $la->sections;
            if (!empty($sections)) {
                foreach ($sections as $section) {
                    $section->delete();
                }
            }
        }

        if (!empty($la->seos)) {
            $seos = $la->seos;
            if (!empty($seos)) {
                foreach ($seos as $seo) {
                    $seo->delete();
                }
            }
        }

        if (!empty($la->subscribers)) {
            $subscribers = $la->subscribers;
            if (!empty($subscribers)) {
                foreach ($subscribers as $subscriber) {

                    @unlink(public_path('assets/front/img/subscriber/') . $subscriber->side_img);
                    @unlink(public_path('assets/front/img/subscriber/') . $subscriber->bg_img);

                    $subscriber->delete();
                }
            }
        }

        if (!empty($la->sub_category_variations)) {
            $sub_category_variations = $la->sub_category_variations;
            if (!empty($sub_category_variations)) {
                foreach ($sub_category_variations as $sub_category_variation) {
                    $sub_category_variation->delete();
                }
            }
        }

        if (!empty($la->tabs)) {
            $tabs = $la->tabs;
            if (!empty($tabs)) {
                foreach ($tabs as $tab) {
                    $tab->delete();
                }
            }
        }

        if (!empty($la->video_sections)) {
            $video_sections = $la->video_sections;
            if (!empty($video_sections)) {
                foreach ($video_sections as $video_section) {
                    @unlink(public_path('assets/front/img/hero_slider/') . $video_section->img);
                    $video_section->delete();
                }
            }
        }


        // deleting seos for corresponding language
        if (!empty($la->seos)) {
            $seos = $la->seos;
            if (!empty($seos)) {
                foreach ($seos as $seo) {
                    $seo->delete();
                }
            }
        }

        // deleting home page texts for corresponding language
        if (!empty($la->home_page_texts)) {
            $home_page_texts = $la->home_page_texts;
            if (!empty($home_page_texts)) {
                foreach ($home_page_texts as $homeText) {
                    @unlink(public_path('assets/front/img/user/home_settings/') . $homeText->hero_image);
                    @unlink(public_path('assets/front/img/user/home_settings/') . $homeText->about_image);
                    @unlink(public_path('assets/front/img/user/home_settings/') . $homeText->skills_image);
                    @unlink(public_path('assets/front/img/user/home_settings/') . $homeText->achievement_image);
                    $homeText->delete();
                }
            }
        }

        // deleting seos for corresponding language
        if (!empty($la->achievements)) {
            $achievements = $la->achievements;
            if (!empty($achievements)) {
                foreach ($achievements as $achievement) {
                    $achievement->delete();
                }
            }
        }

        // if the the deletable language is the currently selected language in frontend then forget the selected language from session
        session()->forget('lang');
        $la->delete();
        return back()->with('success', __('Deleted successfully'));
    }


    public function default(Request $request, $id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $language_limit = $current_package->language_limit;
        $total_languages = Language::where([['user_id', $user_id], ['type', '!=', 'admin']])->count();

        if ($language_limit < $total_languages) {
            Session::flash('warning', __('The custom language limit has been exceeded'));
            return back();
        }

        Language::where('is_default', 1)->where('user_id', Auth::guard('web')->user()->id)->update(['is_default' => 0]);
        $lang = Language::find($id);
        $lang->is_default = 1;
        $lang->save();
        return back()->with('success', $lang->name . ' ' . __('language is set as default'));
    }

    public function dashboardDefault(Request $request, $id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $language_limit = $current_package->language_limit;
        $total_languages = Language::where([['user_id', $user_id], ['type', '!=', 'admin']])->count();

        if ($language_limit < $total_languages) {
            Session::flash('warning', __('The custom language limit has been exceeded'));
            return back();
        }
        $lang = Language::find($id);

        $adminLang = \App\Models\Language::where('code', $lang->code)->pluck('code')->first();
        if (is_null($adminLang)) {
            Session::flash('warning', __('The language you created cannot be set as the default for the dashboard'));
            return back();
        }

        Language::where('dashboard_default', 1)->where('user_id', Auth::guard('web')->user()->id)->update(['dashboard_default' => 0]);

        $lang->dashboard_default = 1;
        $lang->save();
        Cookie::queue('userDashboardLang', $lang->code);
        return back()->with('success', $lang->name . ' ' . __('language is set as default'));
    }

    public function rtlcheck($langid)
    {
        if ($langid > 0) {
            $lang = Language::find($langid);
        } else {
            return 0;
        }
        return $lang->rtl;
    }

    public function rtlcheck2($langid)
    {
        if ($langid > 0) {
            $lang = Language::find($langid);
            $categories = $lang->item_categories()->where('user_id', Auth::guard('web')->user()->id)->where('status', 1)->get();
        } else {
            return 0;
        }

        return ["lang" => $lang->rtl, "categories" => $categories];
    }

    public function addKeyword(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $language_limit = $current_package->language_limit;
        $total_languages = Language::where([['user_id', $user_id], ['type', '!=', 'admin']])->count();

        if ($language_limit < $total_languages) {
            Session::flash('warning', __('Custom Language Limit Exceeded'));
            return 'success';
        }

        $user_id = Auth::guard('web')->user()->id;
        $rules = [
            'keyword' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $languages = Language::where('user_id', $user_id)->get();
        foreach ($languages as $language) {
            // convert json encoded string into a php associative array
            $keywords = json_decode($language->keywords, true);
            $datas = [];
            $datas[$request->keyword] = $request->keyword;

            foreach ($keywords as $key => $keyword) {
                $datas[$key] = $keyword;
            }
            //put data
            $jsonData = json_encode($datas);
            $language->keywords = $jsonData;
            $language->save();
        }
        Session::flash('success', __('Created successfully'));
        return 'success';
    }
}
