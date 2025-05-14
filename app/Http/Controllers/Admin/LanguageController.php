<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreRequest;
use App\Models\BasicExtended as BE;
use App\Models\BasicSetting as BS;
use App\Models\Language;
use App\Models\Menu;
use App\Models\User;
use App\Models\User\Language as UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Session;
use Validator;


class LanguageController extends Controller
{
    public function index($lang = false)
    {
        $data['languages'] = Language::all();
        return view('admin.language.index', $data);
    }

    public function store(StoreRequest $request)
    {
        // retrieve all default language json files
        $data = file_get_contents(resource_path('lang/') . 'default.json');
        $admin_data = file_get_contents(resource_path('lang/') . 'admin_default.json');
        $user_data = file_get_contents(resource_path('lang/') . 'user_default.json');

        // create new language json files
        $json_file = trim($request->code) . '.json';
        $admin_json_file = 'admin_' . trim($request->code) . '.json';
        $user_json_file = 'user_' . trim($request->code) . '.json';

        // retrieve all default language json file path
        $path = resource_path('lang/') . $json_file;
        $admin_path = resource_path('lang/') . $admin_json_file;
        $user_path = resource_path('lang/') . $user_json_file;

        //put all default langauge json file content into new langauge
        File::put($path, $data);
        File::put($admin_path, $admin_data);
        File::put($user_path, $user_data);

        //create a new langauge
        $defaultLang = Language::where('is_default', 1)->select('customer_keywords')->first();
        $in['customer_keywords'] = $defaultLang->customer_keywords;
        $in['name'] = $request->name;
        $in['code'] = $request->code;
        $in['rtl'] = $request->direction;
        if (Language::where('is_default', 1)->count() > 0) {
            $in['is_default'] = 0;
        } else {
            $in['is_default'] = 1;
        }
        $lang = Language::create($in);

        // define the path for the language folder
        $langFolderPath = resource_path('lang/' . $lang->code);
        $adminDestinationFolder = resource_path('lang/' . 'admin_' . $lang->code);
        $userDestinationFolder = resource_path('lang/' . 'user_' . $lang->code);
        $this->copyFolder($langFolderPath, $adminDestinationFolder);
        $this->copyFolder($langFolderPath, $userDestinationFolder);

        if (!file_exists($langFolderPath)) {
            mkdir($langFolderPath, 0755, true);
        }
        // define the source path for the existing language files
        $sourcePath = resource_path('lang/admin_' . $lang->code);
        // Check if the source directory exists
        if (is_dir($sourcePath)) {
            $files = scandir($sourcePath);
            foreach ($files as $file) {
                // Skip the current and parent directory indicators
                if ($file !== '.' && $file !== '..') {
                    // Copy each file to the new language folder
                    copy($sourcePath . '/' . $file, $langFolderPath . '/' . $file);
                }
            }
        }
        // Load validation attributes
        $validationFilePath = resource_path('lang/admin_' . $lang->code . '/validation.php');

        //update existing keywords for validation attributes
        $newKeys = $this->validationMessage();
        $this->updateValidationAttribute($newKeys, $admin_data, $validationFilePath);

        /// language add also user_languages table for user
        if ($lang) {

            $users = User::get();
            foreach ($users as $user) {
                $userLangs = $user->languages()->get();
                $updateUserLang = false;

                if ($userLangs) {
                    foreach ($userLangs as $uLang) {
                        if ($uLang && $uLang->code == $request->code) {
                            $uLang->update([
                                'type' => 'admin'
                            ]);
                            $updateUserLang = true;
                        }
                    }
                }
                if ($updateUserLang == false) {
                    UserLanguage::create([
                        'user_id' => $user->id,
                        'type' => 'admin',
                        'name' => $request->name,
                        'code' => $request->code,
                        'is_default' => 0,
                        'rtl' => $request->direction,
                        'keywords' => $defaultLang->customer_keywords
                    ]);
                }
            }

            $menu = [];
            $menu[] = [
                'text' => 'Home',
                "href" => "",
                "icon" => "empty",
                "target" => "_self",
                "title" => "",
                "type" => "home"
            ];
            $menu[] = [
                'text' => 'Shops',
                "href" => "",
                "icon" => "empty",
                "target" => "_self",
                "title" => "",
                "type" => "listings"
            ];
            $menu[] = [
                'text' => 'Pricing',
                "href" => "",
                "icon" => "empty",
                "target" => "_self",
                "title" => "",
                "type" => "pricing"
            ];
            $menu[] = [
                'text' => 'Templates',
                "href" => "",
                "icon" => "empty",
                "target" => "_self",
                "title" => "",
                "type" => "templates"
            ];
            $menu[] = [
                'text' => 'Blog',
                "href" => "",
                "icon" => "empty",
                "target" => "_self",
                "title" => "",
                "type" => "blog"
            ];
            $menu[] = [
                'text' => 'Contact',
                "href" => "",
                "icon" => "empty",
                "target" => "_self",
                "title" => "",
                "type" => "contact"
            ];

            Menu::create([
                'language_id' => $lang->id,
                'menus' => json_encode($menu, true)
            ]);
        }

        // duplicate First row of basic_settings for current language
        $dbs = Language::where('is_default', 1)->first()->basic_setting;
        $cols = json_decode($dbs, true);
        $bs = new BS;
        foreach ($cols as $key => $value) {
            // if the column is 'id' [primary key] then skip it
            if ($key == 'id') {
                continue;
            }

            // create favicon image using default language image & save unique name in database
            if ($key == 'favicon') {
                // take default lang image
                $dimg = url('/assets/front/img/') . '/' . $dbs->favicon;

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->favicon, ".")) !== FALSE) {
                    $ext = substr($dbs->favicon, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/') . $newImgName);

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create logo image using default language image & save unique name in database
            if ($key == 'logo') {
                // take default lang image
                $dimg = url('/assets/front/img/') . '/' . $dbs->logo;

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->logo, ".")) !== FALSE) {
                    $ext = substr($dbs->logo, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/') . $newImgName);

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create logo image using default language image & save unique name in database
            if ($key == 'preloader') {
                // take default lang image
                $dimg = url('/assets/front/img/') . '/' . $dbs->preloader;

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->preloader, ".")) !== FALSE) {
                    $ext = substr($dbs->preloader, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/') . $newImgName);

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create logo image using default language image & save unique name in database
            if ($key == 'maintenance_img') {
                // take default lang image
                $dimg = url('/assets/front/img/') . '/' . $dbs->maintenance_img;
                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->maintenance_img, ".")) !== FALSE) {
                    $ext = substr($dbs->maintenance_img, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/') . $newImgName);

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create breadcrumb image using default language image & save unique name in database
            if ($key == 'breadcrumb') {
                // take default lang image
                $dimg = url('/assets/front/img/') . '/' . $dbs->breadcrumb;

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->breadcrumb, ".")) !== FALSE) {
                    $ext = substr($dbs->breadcrumb, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/') . $newImgName);

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create footer_logo image using default language image & save unique name in database
            if ($key == 'footer_logo') {
                // take default lang image
                $dimg = url('/assets/front/img/') . '/' . $dbs->footer_logo;

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->footer_logo, ".")) !== FALSE) {
                    $ext = substr($dbs->footer_logo, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/') . $newImgName);

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            // create intro_main_image image using default language image & save unique name in database
            if ($key == 'intro_main_image') {
                // take default lang image
                $dimg = url('/assets/front/img/') . '/' . $dbs->intro_main_image;

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbs->intro_main_image, ".")) !== FALSE) {
                    $ext = substr($dbs->intro_main_image, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/') . $newImgName);

                // save the unique name in database
                $bs[$key] = $newImgName;

                // continue the loop
                continue;
            }

            $bs[$key] = $value;
        }
        $bs['language_id'] = $lang->id;
        $bs->save();

        // duplicate First row of basic_extendeds for current language
        $dbe = Language::where('is_default', 1)->first()->basic_extended;
        $be = BE::firstOrFail();
        $cols = json_decode($be, true);
        $be = new BE;
        foreach ($cols as $key => $value) {
            // if the column is 'id' [primary key] then skip it
            if ($key == 'id') {
                continue;
            }

            // create hero image using default language image & save unique name in database
            if ($key == 'hero_img') {
                // take default lang image
                $dimg = url('/assets/front/img/') . '/' . $dbe->hero_img;

                // copy paste the default language image with different unique name
                $filename = uniqid();
                if (($pos = strpos($dbe->hero_img, ".")) !== FALSE) {
                    $ext = substr($dbe->hero_img, $pos + 1);
                }
                $newImgName = $filename . '.' . $ext;

                @copy($dimg, public_path('assets/front/img/') . $newImgName);

                // save the unique name in database
                $be[$key] = $newImgName;

                // continue the loop
                continue;
            }
            $be[$key] = $value;
        }
        $be['language_id'] = $lang->id;
        $be->save();

        Session::flash('success', __('Created Successfully'));
        return "success";
    }

    public function edit($id)
    {
        if ($id > 0) {
            $data['language'] = Language::findOrFail($id);
        }
        $data['id'] = $id;

        return view('admin.language.edit', $data);
    }


    public function update(Request $request)
    {
        $language = Language::findOrFail($request->language_id);

        $rules = [
            'name' => 'required|max:255',
            'code' => [
                'required',
                'max:255',
                Rule::unique('languages')->ignore($language->id),
            ],
            'direction' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        //delete old file
        @unlink(resource_path('lang/') . $language->code . '.json');
        @unlink(resource_path('lang/') . 'admin_' . $language->code . '.json');
        @unlink(resource_path('lang/') . 'user_' . $language->code . '.json');

        //add new file name for admin front
        $data = file_get_contents(resource_path('lang/') . 'default.json');
        $json_file = trim(strtolower($request->code)) . '.json';
        $path = resource_path('lang/') . $json_file;

        //add new file name for admin dashboard
        $adminData = file_get_contents(resource_path('lang/') . 'admin_default.json');
        $admin_json = trim(strtolower('admin_' . $request->code)) . '.json';
        $adminPath = resource_path('lang/') . $admin_json;

        //add new file name for admin dashboard
        $userData = file_get_contents(resource_path('lang/') . 'user_default.json');
        $user_json = trim(strtolower('user_' . $request->code)) . '.json';
        $userPath = resource_path('lang/') . $user_json;

        File::put($path, $data);
        File::put($adminPath, $adminData);
        File::put($userPath, $userData);

        $language->name = $request->name;
        $language->code = $request->code;
        $language->rtl = $request->direction;
        $language->save();

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function editKeyword($id)
    {
        if ($id > 0) {
            $la = Language::findOrFail($id);
            $json = file_get_contents(resource_path('lang/') . $la->code . '.json');
            $json = json_decode($json, true);
            $list_lang = Language::all();

            if (empty($json)) {
                return back()->with('alert', 'File Not Found.');
            }

            return view('admin.language.edit-keyword', compact('json', 'la'));
        } elseif ($id == 0) {
            $json = file_get_contents(resource_path('lang/') . 'default.json');
            $json = json_decode($json, true);
            if (empty($json)) {
                return back()->with('alert', __('File Not Found'));
            }
            return view('admin.language.edit-keyword', compact('json'));
        }
    }

    public function updateKeyword(Request $request, $id)
    {
        $lang = Language::findOrFail($id);
        $content = json_encode($request->keys);
        if ($content === 'null') {
            return back()->with('alert', __('At Least One Field Should Be Fill-up'));
        }
        file_put_contents(resource_path('lang/') . $lang->code . '.json', $content);

        //=====validation messages
        $validationData = include resource_path('lang/' . $lang->code . '/validation.php');
        $validationAttributes = $validationData['attributes'];
        $validationFilePath = resource_path('lang/' . $lang->code . '/validation.php');

        if (is_array($validationAttributes)) {
            foreach ($this->validationMessageFrontend() as $key => $value) {
                if (!array_key_exists($key, $validationAttributes)) {
                    $validationAttributes[$key] = $value;
                }
            }
        }

        foreach ($request->keys as $key => $value) {
            if (array_key_exists($key, $validationAttributes)) {
                $validationAttributes[$key] = $value;
            }
        }

        $validationData['attributes'] = $validationAttributes;
        $validationContent = "<?php\n\nreturn " . var_export($validationData, true) . ";\n";

        file_put_contents($validationFilePath, $validationContent);
        return back()->with('success', __('Updated successfully'));
    }


    public function delete($id)
    {
        $la = Language::findOrFail($id);
        if ($la->is_default == 1) {
            return back()->with('warning', __('The default language cannot be deleted'));
        }
        @unlink(public_path('assets/front/img/languages/') . $la->icon);
        @unlink(resource_path('lang/') . $la->code . '.json');
        @unlink(resource_path('lang/admin_') . $la->code . '.json');
        @unlink(resource_path('lang/user_') . $la->code . '.json');

        File::deleteDirectory(resource_path('lang/') . 'admin_' . $la->code);
        File::deleteDirectory(resource_path('lang/') . 'user_' . $la->code);

        if (session()->get('lang') == $la->code) {
            session()->forget('lang');
        }

        // deleting basic_settings and basic_extended for corresponding language & unlink images
        $bs = $la->basic_setting;
        if (!empty($bs)) {
            $dir = public_path('assets/front/img/');
            @unlink($dir . $bs->favicon);

            @unlink($dir . $bs->logo);

            @unlink($dir . $bs->preloader);

            @unlink($dir . $bs->breadcrumb);

            @unlink($dir . $bs->intro_main_image);

            @unlink($dir . $bs->footer_logo);

            @unlink($dir . $bs->maintenance_img);

            $bs->delete();
        }
        $be = $la->basic_extended;
        if (!empty($be)) {
            @unlink($dir . $be->hero_img);
            $be->delete();
        }

        // deleting pages for corresponding language
        if (!empty($la->pages)) {
            $la->pages()->delete();
        }

        // deleting testimonials for corresponding language
        if (!empty($la->testimonials)) {
            $testimonials = $la->testimonials;
            foreach ($testimonials as $testimonial) {
                @unlink(public_path('assets/front/img/testimonials/') . $testimonial->image);
                $testimonial->delete();
            }
        }

        // deleting feature for corresponding language
        if (!empty($la->features)) {
            $features = $la->features;
            foreach ($features as $feature) {
                $feature->delete();
            }
        }

        // deleting services for corresponding language
        if (!empty($la->blogs)) {
            $blogs = $la->blogs;
            foreach ($blogs as $blog) {
                @unlink(public_path('assets/front/img/blogs/') . $blog->main_image);
                $blog->delete();
            }
        }

        // deleting blog categories for corresponding language
        if (!empty($la->bcategories)) {
            $bcategories = $la->bcategories;
            foreach ($bcategories as $bcat) {
                $bcat->delete();
            }
        }

        // deleting partners for corresponding language
        if (!empty($la->partners)) {
            $partners = $la->partners;
            foreach ($partners as $partner) {
                @unlink(public_path('assets/front/img/partners/') . $partner->image);
                $partner->delete();
            }
        }

        // deleting processes for corresponding language
        if (!empty($la->processes)) {
            $processes = $la->processes;
            foreach ($processes as $process) {
                @unlink(public_path('assets/front/img/process/') . $process->image);
                $process->delete();
            }
        }

        // deleting partners for corresponding language
        if (!empty($la->popups)) {
            $popups = $la->popups;
            foreach ($popups as $popup) {
                @unlink(public_path('assets/front/img/popups/') . $popup->background_image);
                @unlink(public_path('assets/front/img/popups/') . $popup->image);
                $popup->delete();
            }
        }

        // deleting useful links for corresponding language
        if (!empty($la->ulinks)) {
            $la->ulinks()->delete();
        }

        // deleting faqs for corresponding language
        if (!empty($la->faqs)) {
            $la->faqs()->delete();
        }

        // deleting menus for corresponding language
        if (!empty($la->menus)) {
            $la->menus()->delete();
        }

        // deleting seo for corresponding language
        if (!empty($la->seo)) {
            $la->seo->delete();
        }

        // if the the deletable language is the currently selected language in frontend then forget the selected language from session
        session()->forget('lang');

        $la->delete();
        return back()->with('success', __('Deleted successfully'));
    }

    public function default(Request $request, $id)
    {
        Language::where('is_default', 1)->update(['is_default' => 0]);
        $lang = Language::find($id);
        $lang->is_default = 1;
        $lang->save();
        return back()->with('success', $lang->name . ' ' . __('laguage is set as defualt'));
    }
    public function dashboardDefault(Request $request, $id)
    {
        Language::where('dashboard_default', 1)->update(['dashboard_default' => 0]);
        $lang = Language::find($id);
        $lang->dashboard_default = 1;
        $lang->save();
        Session::put('admin_lang', 'admin_' . $lang->code);
        app()->setLocale('admin_' . $lang->code);
        return back()->with('success', $lang->name . ' ' . __('laguage is set as defualt'));
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

    public function addKeyword(Request $request)
    {
        $rules = [
            'keyword' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        $languages = Language::get();
        foreach ($languages as $language) {
            // get all the keywords of the selected language
            $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

            // convert json encoded string into a php associative array
            $keywords = json_decode($jsonData, true);
            $datas = [];
            $datas[$request->keyword] = $request->keyword;

            foreach ($keywords as $key => $keyword) {
                $datas[$key] = $keyword;
            }
            //put data
            $jsonData = json_encode($datas);

            $fileLocated = resource_path('lang/') . $language->code . '.json';

            // put all the keywords in the selected language file
            file_put_contents($fileLocated, $jsonData);
        }

        //for default json
        // get all the keywords of the selected language
        $jsonData = file_get_contents(resource_path('lang/') . 'default.json');

        // convert json encoded string into a php associative array
        $keywords = json_decode($jsonData, true);
        $datas = [];
        $datas[$request->keyword] = $request->keyword;

        foreach ($keywords as $key => $keyword) {
            $datas[$key] = $keyword;
        }
        //put data
        $jsonData = json_encode($datas);

        $fileLocated = resource_path('lang/') . 'default.json';

        // put all the keywords in the selected language file
        file_put_contents($fileLocated, $jsonData);

        Session::flash('success', __('Created Successfully'));
        return 'success';
    }

    public function addKeywordForAdmin(Request $request)
    {
        $rules = [
            'keyword' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        $languages = Language::get();
        foreach ($languages as $language) {
            // get all the keywords of the selected language
            $jsonData = file_get_contents(resource_path('lang/') . 'admin_' . $language->code . '.json');

            // convert json encoded string into a php associative array
            $keywords = json_decode($jsonData, true);
            $datas = [];
            $datas[$request->keyword] = $request->keyword;

            foreach ($keywords as $key => $keyword) {
                $datas[$key] = $keyword;
            }
            //put data
            $jsonData = json_encode($datas);

            $fileLocated = resource_path('lang/') . 'admin_' . $language->code . '.json';

            // put all the keywords in the selected language file
            file_put_contents($fileLocated, $jsonData);
        }

        //for default json
        // get all the keywords of the selected language
        $jsonData = file_get_contents(resource_path('lang/') . 'admin_default.json');

        // convert json encoded string into a php associative array
        $keywords = json_decode($jsonData, true);
        $datas = [];
        $datas[$request->keyword] = $request->keyword;

        foreach ($keywords as $key => $keyword) {
            $datas[$key] = $keyword;
        }
        //put data
        $jsonData = json_encode($datas);

        $fileLocated = resource_path('lang/') . 'admin_default.json';

        // put all the keywords in the selected language file
        file_put_contents($fileLocated, $jsonData);

        Session::flash('success', __('Created Successfully'));
        return 'success';
    }

    public function editAdminKeyword($id)
    {
        if ($id > 0) {
            $la = Language::findOrFail($id);
            $json = file_get_contents(resource_path('lang/') . 'admin_' . $la->code . '.json');
            $json = json_decode($json, true);
            $list_lang = Language::all();
            if (empty($json)) {
                return back()->with('alert', __('File Not Found'));
            }
            return view('admin.language.edit-admin-keyword', compact('json', 'la'));
        } elseif ($id == 0) {
            $json = file_get_contents(resource_path('lang/') . 'admin_default.json');
            $json = json_decode($json, true);
            if (empty($json)) {
                return back()->with('alert', __('File Not Found.'));
            }
            return view('admin.language.edit-admin-keyword', compact('json'));
        }
    }

    public function updateAdminKeyword(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        $newkeywordsArr = $request['keys'];
        if (count($newkeywordsArr) === 0) {
            return back()->with('alert', __('At least one field should be filled out'));
        }
        //=== language messages
        $existingkeywordsArr = [];
        $fileLocated = resource_path('lang/') . 'admin_' . $language->code . '.json';
        if (file_exists($fileLocated)) {
            $existingkeywordsArr = json_decode(file_get_contents($fileLocated), true) ?? [];
        }
        $requestKeywordsArr = array_merge($existingkeywordsArr, $newkeywordsArr);
        file_put_contents(resource_path('lang/') . 'admin_' . $language->code . '.json', json_encode($requestKeywordsArr));

        //=====validation messages
        $validationData = include resource_path('lang/admin_' . $language->code . '/validation.php');
        $validationAttributes = $validationData['attributes'];
        $validationFilePath = resource_path('lang/admin_' . $language->code . '/validation.php');

        if (is_array($validationAttributes)) {
            foreach ($this->validationMessage() as $key => $value) {
                if (!array_key_exists($key, $validationAttributes)) {
                    $validationAttributes[$key] = $value;
                }
            }
        }

        foreach ($requestKeywordsArr as $key => $value) {
            if (array_key_exists($key, $validationAttributes)) {
                $validationAttributes[$key] = $value;
            }
        }

        $validationData['attributes'] = $validationAttributes;
        $validationContent = "<?php\n\nreturn " . var_export($validationData, true) . ";\n";

        file_put_contents($validationFilePath, $validationContent);

        return back()->with('success', __('Updated Successfully'));
    }

    public function editUserKeyword($id)
    {
        if ($id > 0) {
            $la = Language::findOrFail($id);
            $json = file_get_contents(resource_path('lang/') . 'user_' . $la->code . '.json');
            $json = json_decode($json, true);
            $list_lang = Language::all();

            if (empty($json)) {
                return back()->with('alert', 'File Not Found.');
            }

            return view('admin.language.user-dashboard-keyword', compact('json', 'la'));
        } elseif ($id == 0) {
            $json = file_get_contents(resource_path('lang/') . 'user_default.json');
            $json = json_decode($json, true);
            if (empty($json)) {
                return back()->with('alert', __('File Not Found'));
            }
            return view('admin.language.user-dashboard-keyword', compact('json'));
        }
    }

    public function updateUserDashboardKeyword($id, Request $request)
    {
        $lang = Language::findOrFail($id);
        $content = json_encode($request->keys);
        if ($content === 'null') {
            return back()->with('alert', __('At Least One Field Should Be Fill-up'));
        }

        // Load validation attributes
        $validationFilePath = resource_path('lang/user_' . $lang->code . '/validation.php');
        //update existing attributes
        $newKeys = $this->validationMessage();
        $this->updateValidationAttribute($newKeys, $content, $validationFilePath);

        file_put_contents(resource_path('lang/') . 'user_' . $lang->code . '.json', $content);
        return  back()->with('success', __('Updated Successfully'));
    }

    public function editUserFrontendKeyword($id)
    {
        $la = Language::findOrFail($id);
        $json = json_decode($la->customer_keywords, true);
        return view('admin.language.edit-user-frontend-keyword', compact('json', 'la'));
    }

    public function updateCustomerKeyword($id, Request $request)
    {
        $lang = Language::findOrFail($id);
        $content = json_encode($request->keys);
        if ($content === 'null') {
            return back()->with('alert', __('At Least One Field Should Be Fill-up'));
        }
        $lang->customer_keywords = $content;
        $lang->save();

        Session::flash('success', __('Updated successfully'));
        return back();
    }

    protected function validationMessage()
    {
        return [
            "possition" => "possition",
            "serial_number" => "serial number",
            "icon" => "icon",
            "amount" => "amount",
            "color" => "color",
            "title" => "title",
            "about_features_section_status" => "about features section status",
            "about_work_process_section_status" => "about work process section status",
            "about_counter_section_status" => "about counter section status",
            "about_testimonial_section_status" => "about testimonial section status",
            "about_blog_section_status" => "about blog_section status",
            "additional_sections" => "additional sections",
            "email_type" => "email type",
            "email_subject" => "email subject",
            "email_body" => "email body",
            "is_smtp" => "is smtp",
            "smtp_host" => "smtp host",
            "encryption" => "encryption",
            "smtp_username" => "smtp username",
            "smtp_password" => "smtp password",
            "from_mail" => "from mail",
            "to_mail" => "to mail",
            "url" => "url",
            "file" => "file",
            "cookie_alert_status" => "cookie alert status",
            "cookie_alert_button_text" => "cookie alert button text",
            "cookie_alert_text" => "cookie alert text",
            "website_title" => "website title",
            "favicon" => "favicon",
            "logo" => "logo",
            "preloader" => "preloader",
            "preloader_status" => "preloader status",
            "timezone" => "timezone",
            "base_color" => "base color",
            "base_color_2" => "base color two",
            "base_currency_symbol" => "base currency symbol",
            "base_currency_symbol_position" => "base currency symbol position",
            "base_currency_text" => "base currency text",
            "base_currency_text_position" => "base currency text position",
            "base_currency_rate" => "base currency rate",
            "maintenance_status" => "maintenance mode status",
            "maintainance_text" => "maintainance mode text",
            "secret_path" => "secret path",
            "name" => "name",
            "status" => "status",
            "image" => "image",
            "category" => "category",
            "content" => "content",
            "success_message" => "success message",
            "cname_record_section_title" => "cname record section title",
            "cname_record_section_text" => "cname record section text",
            "email" => "email",
            "subject" => "subject",
            "message" => "message",
            "question" => "question",
            "answer" => "answer",
            "footer_text" => "footer text",
            "useful_links_title" => "useful links title",
            "contact_info_title" => "contact info title",
            "newsletter_title" => "newsletter title",
            "newsletter_subtitle" => "newsletter subtitle",
            "copyright_text" => "copyright text",
            "short_description" => "short description",
            "instructions" => "instructions",
            "is_receipt" => "receipt image",
            "key" => "key",
            "secret_key" => "secret key",
            "perfect_money_wallet_id" => "perfect money wallet id",
            "token" => "token",
            "public_key" => "public key",
            "secret" => "secret",
            "server_key" => "server key",
            "client_id" => "client id",
            "client_secret" => "client secret",
            "category_code" => "category code",
            "login_id" => "login id",
            "transaction_key" => "transaction key",
            "api_key" => "api key",
            "country" => "country",
            "profile_id" => "profile id",
            "api_endpoint" => "api endpoint",
            "merchant_id" => "merchant id",
            "salt_key" => "salt key",
            "salt_index" => "salt index",
            "merchant id" => "merchant id",
            "website" => "Merchant website",
            "industry" => "industry type id",
            "text" => "text",
            "hero_section_title" => "hero section title",
            "hero_section_text" => "hero section text",
            "hero_section_desc" => "hero section description",
            "hero_section_button_text" => "hero section button text",
            "hero_section_button_url" => "hero section button url",
            "hero_section_video_url" => "hero section video url",
            "designation" => "designation",
            "comment" => "comment",
            "intro_title" => "title",
            "intro_subtitle" => "subtitle",
            "intro_text" => "text",
            "intro_button_text" => "button text",
            "intro_button_url" => "button url",
            "intro_video_url" => "video url",
            "partner_title" => "partner section title",
            "partner_subtitle" => "partner section subtitle",
            "work_process_title" => "work process section title",
            "preview_templates_title" => "preview templates section title",
            "preview_templates_subtitle" => "preview templates section subtitle",
            "pricing_title" => "pricing section title",
            "pricing_subtitle" => "pricing section subtitle",
            "featured_users_title" => "featured shop section title",
            "featured_users_subtitle" => "featured shop section subtitle",
            "testimonial_title" => "testimonial section title",
            "blog_subtitle" => "blog section subtitle",
            "code" => "code",
            "direction" => "direction",
            "keyword" => "keyword",
            "price" => "price",
            "term" => "term",
            "featured" => "featured",
            "recommended" => "recommended",
            "post_limit" => "post limit",
            "product_limit" => "product limit",
            "categories_limit" => "categories limit",
            "subcategories_limit" => "subcategories limit",
            "order_limit" => "order limit",
            "number_of_custom_page" => "number of custom page",
            "language_limit" => "language limit",
            "is_trial" => "trial",
            "trial_days" => "trial days",
            "expiration_reminder" => "expiration reminder",
            "body" => "body",
            "background_image" => "background image",
            "end_date" => "end date",
            "end_time" => "end time",
            "background_color" => "background color",
            "background_opacity" => "background opacity",
            "button_text" => "button text",
            "button_color" => "button color",
            "button_url" => "button url",
            "delay" => "delay",
            "old_password" => "old password",
            "password_confirmation" => "password confirmation",
            "first_name" => "first name",
            "last_name" => "last name",
            "vapid_public_key" => "vapid public key",
            "vapid_private_key" => "vapid private key",
            "package_id" => "package",
            "payment_method" => "payment method",
            "npass" => "new password",
            "cfpass" => "confirm password",
            "preview_image" => "preview image",
            "sitemap_url" => "sitemap url",
            "role_id" => "role",
            "contact_addresses" => "contact addresses",
            "contact_text" => "contact text",
            "contact_numbers" => "contact numbers",
            "contact_mails" => "contact mails",
            "subtitle" => "subtitle",
            "rating" => "rating",
            "user_language_id" => "language",
            "language_id" => "language",
            "header_text" => "header text",
            "header_middle_text" => "header middle text",
            "banner_url" => "banner url",
            "btn_text" => "button text",
            "side_image" => "side image",
            "featured_img" => "featured img",
            "hero_section_background_image" => "hero section background image",
            "slider_img" => "slider image",
            "btn_name" => "button name",
            "btn_url" => "button url",
            "video_url" => "video url",
            "video_button_text" => "video button text",
            "tabImage_img" => "Image",
            "tabImage_url" => "url",
            "type" => "type",
            "value" => "value",
            "start_date" => "start date",
            "minimum_spend" => "minimum spend",
            "charge" => "charge",
            "category_id" => "category",
            "sub_category_id" => "subcategory",
            "stock" => "stock",
            "file_type" => "file type",
            "download_file" => "download file",
            "sku" => "sku",
            "current_price" => "current price",
            "previous_price" => "previous price",
            "tax" => "tax",
            "top_selling_count" => "top selling count",
            "size" => "size",
            "margin" => "margin",
            "sign" => "sign",
            "currency_position" => "currency position",
            "smtp_port" => "smtp port",
            "from_name" => "from name",
            "occupation" => "occupation",
            "external_link_status" => "external link status",
            "external_link" => "external link",
            "short_details" => "short details",
            "video" => "video",
            "summary_background_color" => "summary background color",
            "call_button_color" => "call button color",
            "whatsapp_button_color" => "whatsapp button color",
            "mail_button_color" => "mail button color",
            "add_to_contact_button_color" => "add to contact button color",
            "phone_icon_color" => "phone icon color",
            "email_icon_color" => "email icon color",
            "address_icon_color" => "address icon color",
            "website_url_icon_color" => "website url icon color",
            "profile_image" => "profile image",
            "cover_image" => "cover image",
            "company" => "company",
            "address" => "address",
            "phone" => "phone",
            "website_url" => "website url",
            "introduction" => "introduction",
            "city" => "city",
            "state" => "state",
            "thumbnail" => "thumbnail",
            "symbol" => "symbol",
            "password" => "password",
            "old_password" => "current password",
        ];
    }
    protected function validationMessageFrontend()
    {
        return [
            'first_name'       => 'first name',
            'last_name'        => 'last name',
            'username'         => 'username',
            'password'         => 'password',
            'email'            => 'email',
            'phone'            => 'phone',
            'city'             => 'city',
            'country'          => 'country',
            'price'            => 'price',
            'payment_method'   => 'payment method',
            'receipt'          => 'receipt',
            'stripeToken'      => 'stripe token',
            'opaqueDataDescriptor' => 'opaqueDataDescriptor',
            'post_code'        => 'post code',
            'identity_number'  => 'identity number',
            'current_password' => 'current password',
            'new_password'     => 'new password',
            'new_password_confirmation' => 'new password confirmation',
            'shipping_fname'   => 'shipping fname',
            'shipping_lname'   => 'shipping lname',
            'shipping_email'   => 'shipping email',
            'shipping_number'  => 'shipping number',
            'shipping_city'    => 'shipping city',
            'shipping_address' => 'shipping address',
            'shipping_country' => 'shipping country',
            "billing_fname"    => 'billing fname',
            "billing_lname"    => 'billing lname',
            "billing_email"    => 'billing email',
            "billing_number"   => 'billing number',
            "billing_city"     => 'billing city',
            "billing_address"  => 'billing address',
            "billing_country"  => 'billing country',
            "fullname"  => 'full name',
            "subject"  => 'subject',
            "message"  => 'message',
        ];
    }


    public function updateValidationAttribute($newKeys, $content, $validationFilePath)
    {
        try {
            // Load the existing validation array
            $validation = include($validationFilePath);

            // Ensure 'attributes' key exists
            if (!isset($validation['attributes']) || !is_array($validation['attributes'])) {
                $validation['attributes'] = [];
            }
        } catch (\Exception $e) {
            session()->flash('warning', __('Please provide a valid language code!'));
            return;
        }


        //update existing keys
        foreach ($newKeys as $key => $value) {
            if (!array_key_exists($key, $validation['attributes'])) {
                $validation['attributes'][$key] = $value;
            }
        }

        // update values which matching keys with new values
        $decodedContent = json_decode($content, true);
        if (is_array($decodedContent)) {
            foreach ($decodedContent as $key => $value) {
                if (array_key_exists($key, $validation['attributes'])) {
                    $validation['attributes'][$key] = $value;
                }
            }
        }

        //save the changes in validation attributes array
        $validationContent = "<?php\n\nreturn " . var_export($validation, true) . ";\n";
        file_put_contents($validationFilePath, $validationContent);
    }

    public function copyFolder($sourcePath, $destinationPath)
    {
        if (!File::exists($sourcePath)) {
            return false;
        }

        if (File::exists($destinationPath)) {
            File::deleteDirectory($destinationPath);
        }
        return File::copyDirectory($sourcePath, $destinationPath);
    }
}
