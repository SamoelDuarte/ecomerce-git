<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Language;
use App\Models\User\UserPage;
use App\Models\User\UserPageContent;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Purifier;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->firstOrFail();
        $lang_id = $lang->id;

        $apages = UserPage::join('user_page_contents', 'user_page_contents.page_id', 'user_pages.id')
            ->where('user_page_contents.language_id', $lang->id)
            ->select('user_pages.*', 'user_page_contents.title', 'user_page_contents.slug')
            ->get();
        $data['apages']  = $apages;

        $data['lang_id'] = $lang_id;


        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $features = json_decode($current_package->features, true);
        if (is_array($features) && in_array("Custom Page", $features)) {
            $data['custom_page_limit'] = $current_package->number_of_custom_page;
            return view('user.page.index', $data);
        } else {
            return redirect('user-dashboard');
        }
    }

    public function create()
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['de_lang'] = Language::where('is_default', 1)->where('user_id', $user_id)->firstOrFail();

        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $features = json_decode($current_package->features, true);
        if (is_array($features) && in_array("Custom Page", $features)) {
            $c_pages = UserPage::where('user_id', Auth::guard('web')->user()->id)->count();
            if ($c_pages > $current_package->number_of_custom_page) {
                Session::flash('warning', __('Delete Additional Page to Create New Page!'));
                return redirect()->route('user.page.index', ['language' => $data['de_lang']->code])->with('success');
            }
        }

        $data['userLangs'] = Language::where('user_id', $user_id)->get();
        return view('user.page.create', $data);
    }

    public function store(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;

        $rules = [
            'status' => 'required',
            'serial_number' => 'required'
        ];

        $languages = Language::where('user_id', $user_id)->get();
        // $defaulLang = Language::where('user_id', Auth::guard('web')->user()->id)
        //     ->where('is_default', 1)
        //     ->first();
        // $rules[$defaulLang->code . '_title'] = 'required';
        // $rules[$defaulLang->code . '_body'] = 'required';
        // $slug = make_slug($request[$defaulLang->code . '_title']);
        // $rules[$defaulLang->code . '_title'] = [
        //     'required',
        //     'max:255',
        //     function ($attribute, $value, $fail) use ($slug, $defaulLang, $user_id) {
        //         $bis = UserPageContent::where('language_id', $defaulLang->id)->where('user_id', $user_id)->get();
        //         foreach ($bis as $key => $bi) {
        //             if (strtolower($slug) == strtolower($bi->slug)) {
        //                 $fail(__('The title field must be unique for') . ' ' . $defaulLang->name . ' ' . __('language'));
        //             }
        //         }
        //     }
        // ];


        foreach ($languages as $language) {
            $code = $language->code;
            $langaugeName = ' ' . $language->name . ' ' . __('language');

            if (
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_body')
            ) {
                $rules[$code . '_title'] = 'required';
                $rules[$code . '_body'] = 'required';
                $slug = make_slug($request[$code . '_title']);
                $rules[$code . '_title'] = [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug, $language, $user_id) {
                        $bis = UserPageContent::where('language_id', $language->id)->where('user_id', $user_id)->get();
                        foreach ($bis as $key => $bi) {
                            if (strtolower($slug) == strtolower($bi->slug)) {
                                $fail(__('The title field must be unique for') . ' ' . $language->name . ' ' . __('language'));
                            }
                        }
                    }
                ];
            }

            $messages[$code . '_title.required'] = __('The title field is required') . $langaugeName;;
            $messages[$code . '_body.required'] = __('The body field is required') . $langaugeName;;
            $messages[$code . '_title.unique'] = __('The title field must be unique for') . $langaugeName;
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $in = $request->all();
        $in['user_id'] = $user_id;
        $page = UserPage::create($in);

        foreach ($languages as $language) {
            if (
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_body')
            ) {
                $pageContent = new UserPageContent();
                $pageContent->page_id = $page->id;
                $pageContent->user_id = $user_id;
                $pageContent->language_id = $language->id;
                $pageContent->title = $request[$language->code . '_title'];
                $pageContent->slug = make_slug($request[$language->code . '_title']);
                $pageContent->body = Purifier::clean($request[$language->code . '_body'], 'youtube');
                $pageContent->save();
            }
        }

        Session::flash('success', __('Created successfully'));
        return "success";
    }

    public function edit($pageID)
    {
        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        $features = json_decode($current_package->features, true);
        if (is_array($features) && in_array("Custom Page", $features)) {
            $c_pages = UserPage::where('user_id', Auth::guard('web')->user()->id)->count();
            if ($c_pages > $current_package->number_of_custom_page) {
                Session::flash('warning', __('Delete page to enable editing'));
                return redirect()->back()->with('success');
            }
        }

        $user_id  = Auth::guard('web')->user()->id;
        $data['page'] = UserPage::where([['user_id', $user_id], ['id', $pageID]])->firstOrFail();
        $data['de_lang'] = Language::where('is_default', 1)->where('user_id', $user_id)->firstOrFail();
        $data['userLangs'] = Language::where('user_id', $user_id)->get();
        return view('user.page.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $rules = [
            'status' => 'required',
            'serial_number' => 'required'
        ];

        $languages = Language::where('user_id', $user_id)->get();
        // $defaulLang = Language::where('user_id', Auth::guard('web')->user()->id)
        //     ->where('is_default', 1)
        //     ->first();
        // $rules[$defaulLang->code . '_title'] = 'required';
        // $rules[$defaulLang->code . '_body'] = 'required';
        // $slug = make_slug($request[$defaulLang->code . '_title']);
        // $rules[$defaulLang->code . '_title'] = [
        //     'required',
        //     'max:255',
        //     function ($attribute, $value, $fail) use ($slug, $defaulLang, $user_id) {
        //         $bis = UserPageContent::where('language_id', $defaulLang->id)->where('user_id', $user_id)->get();
        //         foreach ($bis as $key => $bi) {
        //             if (strtolower($slug) == strtolower($bi->slug)) {
        //                 $fail(__('The title field must be unique for') . ' ' . $defaulLang->name . ' ' . __('language'));
        //             }
        //         }
        //     }
        // ];


        foreach ($languages as $language) {
            $code = $language->code;
            $langaugeName = ' ' . $language->name . ' ' . __('language');

            if (
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_body')
            ) {
                $rules[$code . '_title'] = 'required';
                $rules[$code . '_body'] = 'required';
                $slug = make_slug($request[$code . '_title']);
                $rules[$code . '_title'] = [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug, $language, $user_id, $id) {
                        $bis = UserPageContent::where('language_id', $language->id)->where('user_id', $user_id)->where('page_id', '<>', $id)->get();
                        foreach ($bis as $key => $bi) {
                            if (strtolower($slug) == strtolower($bi->slug)) {
                                $fail(__('The title field must be unique for') . ' ' . $language->name . ' ' . __('language'));
                            }
                        }
                    }
                ];
            }

            $messages[$code . '_title.required'] = __('The title field is required') . $langaugeName;;
            $messages[$code . '_body.required'] = __('The body field is required') . $langaugeName;;
            $messages[$code . '_title.unique'] = __('The title field must be unique for') . $langaugeName;
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $in = $request->all();

        $page = UserPage::where([['id', $id], ['user_id', $user_id]])->firstOrFail();
        $page->update($in);

        foreach ($languages as $language) {
            $pageContent = UserPageContent::where([['page_id', $page->id], ['language_id', $language->id]])->first();
            if (empty($pageContent)) {
                $pageContent = new UserPageContent();
                $pageContent->page_id = $page->id;
                $pageContent->user_id = $user_id;
                $pageContent->language_id = $language->id;
            }
            if (
                // empty($pageContent) ||
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_body')
            ) {
                $pageContent->title = $request[$language->code . '_title'];
                $pageContent->slug = make_slug($request[$language->code . '_title']);
                $pageContent->body = Purifier::clean($request[$language->code . '_body'], 'youtube');
                $pageContent->save();
            }
        }
        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function delete(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $pageID = $request->pageid;

        $page = UserPage::where([['id', $pageID], ['user_id', $user_id]])->first();
        $pageContents = UserPageContent::where('page_id', $pageID)->get();
        foreach ($pageContents as $pageContent) {
            $pageContent->delete();
        }
        $page->delete();
        Session::flash('success', __('Deleted successfully'));
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        $user_id = Auth::guard('web')->user()->id;

        foreach ($ids as $id) {
            $page = UserPage::where([['id', $id], ['user_id', $user_id]])->first();
            $pageContents = UserPageContent::where('page_id', $id)->get();
            foreach ($pageContents as $pageContent) {
                $pageContent->delete();
            }
            $page->delete();
        }

        Session::flash('success', __('Deleted successfully'));
        return "success";
    }
}
