<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\Blog;
use App\Models\User\BlogCategory;
use App\Models\User\BlogContent;
use App\Models\User\Language;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Purifier;
use Validator;
use Illuminate\Support\Facades\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        if ($request->has('language')) {
            $lang = Language::where([
                ['code', $request->language],
                ['user_id', $user_id]
            ])->first();
        } else {
            $lang = Language::where([
                ['is_default', 1],
                ['user_id', $user_id]
            ])
                ->first();
        }

        $data['blogs'] = DB::table('user_blogs')
            ->join('user_blog_contents', 'user_blogs.id', 'user_blog_contents.blog_id')
            ->join('user_blog_categories', 'user_blog_categories.id', '=', 'user_blog_contents.category_id')
            ->where([['user_blog_contents.language_id', $lang->id], ['user_blogs.user_id', $user_id]])
            ->select('user_blogs.*', 'user_blog_contents.title', 'user_blog_categories.name')
            ->orderBy('serial_number', 'asc')
            ->get();

        $data['bcats'] = BlogCategory::where([
            ['language_id', '=', $lang->id],
            ['user_id', '=', $user_id],
            ['status', '=', 1]
        ])
            ->orderBy('serial_number', 'ASC')
            ->get();

        $data['lang'] = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $features = json_decode($current_package->features);
        if (is_array($features) && in_array('Blog', $features)) {
            $data['post_limit'] = $current_package->post_limit;
        } else {
            return redirect()->route('user-dashboard');
        }
        $data['total_post'] = Blog::where('user_id', $user_id)->count();

        return view('user.blog.blog.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['lang'] =  Language::where([['code', $request->language], ['user_id', $user_id]])->firstOrFail();

        $data['categories'] = DB::table('user_blog_categories')
            ->where('language_id', $data['lang']->id)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();


        $data['userLangs'] = Language::where([
            ['user_id', $user_id]
        ])->get();

        $data['de_lang'] = Language::where([
            ['is_default', 1],
            ['user_id', $user_id]
        ])->first();
        return view('user.blog.blog.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $post_limit = $current_package->post_limit;

        $total_post = Blog::where('user_id', $user_id)->count();
        $total_post = $total_post + 1;

        if ($post_limit <= $total_post) {
            Session::flash('warning', __('The blog post limit has been exceeded'));
            return "success";
        }

        $ruleArray = [
            'thumbnail' => 'required',
            'serial_number' => 'required',
            'status' => 'required',
            'category_id' => 'required'
        ];

        $messageArray = [];

        $languages = Language::where('user_id', $user_id)->get();


        foreach ($languages as $language) {
            $code = $language->code;
            $langaugeName = ' ' . $language->name . ' ' . __('language');

            if (
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_content') ||
                $request->input($code . '_author') ||
                $request->input($code . '_meta_keyword') ||
                $request->input($code . '_meta_description')
            ) {
                $slug = make_slug($request[$code . '_title']);
                $ruleArray[$code . '_title'] = [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug, $language, $user_id) {
                        $bis = BlogContent::where('language_id', $language->id)->where('user_id', $user_id)->get();
                        foreach ($bis as $key => $bi) {
                            if (strtolower($slug) == strtolower($bi->slug)) {
                                $fail(__('The title field must be unique for') . ' ' . $language->name . ' ' . __('language'));
                            }
                        }
                    }
                ];
                $ruleArray[$code . '_content'] = 'required';
                $ruleArray[$code . '_author'] = 'required';
            }

            //messsge array
            $messageArray[$code . '_title.required'] = __('The title field is required for') . $langaugeName;
            $messageArray[$code . '_title.max'] = __('The title field cannot contain more than 255 characters for') . $langaugeName;
            $messageArray[$code . '_title.unique'] = __('The title field must be unique for') . $langaugeName;
            $messageArray[$code . '_author.required'] = __('The author field is required for') . $langaugeName;
            $messageArray[$code . '_content.required'] = __('The content field is required for') . $langaugeName;
        }

        $validator = Validator::make($request->all(), $ruleArray, $messageArray);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $input = $request->all();
        $input['user_id'] = $user_id;

        $thumbnail = $request->file('thumbnail');
        if ($request->hasFile('thumbnail')) {
            $dir = public_path('assets/front/img/user/blogs/');
            @mkdir($dir, 0775, true);

            //cover image saved start
            $imageData = file_get_contents($thumbnail->getRealPath());
            $thumbnail_name = uniqid() . '.png';
            $directory = 'public/images/';

            // Save the image to storage
            Storage::put($directory . $thumbnail_name, $imageData);
            // Resize the image (optional)
            $image1 = Image::make(Storage::path($directory . $thumbnail_name));

            $image1->resize(900, 600);
            $image1->save();

            File::move(Storage::path($directory . $thumbnail_name), $dir . $thumbnail_name);
            $input['image'] = $thumbnail_name;
        }
        $timeZone = DB::table('user_basic_settings')->where('user_id', Auth::guard('web')->user()->id)->value('timezone');

        $blog = Blog::create([
            'user_id' => $user_id,
            'image' => $thumbnail_name,
            'status' => $request->status,
            'serial_number' => $request->serial_number,
            'created_at' => Carbon::now()->setTimezone($timeZone),
            'updated_at' => Carbon::now()->setTimezone($timeZone),
        ]);

        foreach ($languages as $language) {
            $code = $language->code;
            if (
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_content') ||
                $request->input($code . '_author') ||
                $request->input($code . '_meta_keyword') ||
                $request->input($code . '_meta_description')
            ) {
                $categoryId = BlogCategory::where([['unique_id', $request->category_id], ['language_id', $language->id]])->pluck('id')->first();
                $blogContent = new BlogContent();
                $blogContent->language_id = $language->id;
                $blogContent->user_id = $user_id;
                $blogContent->blog_id = $blog->id;
                $blogContent->title = $request[$code . '_title'];
                $blogContent->slug = make_slug($request[$code . '_title']);
                $blogContent->author = $request[$code . '_author'];
                $blogContent->category_id = $categoryId;
                $blogContent->content = Purifier::clean($request[$code . '_content'], 'youtube');
                $blogContent->meta_keywords = $request[$code . '_meta_keywords'];
                $blogContent->meta_description = $request[$code . '_meta_description'];
                $blogContent->save();
            }
        }

        Session::flash('success', __('Created successfully'));
        return "success";
    }


    public function update_status(Request $request)
    {
        $request->validate([
            'id' => "required",
            'status' => "required",
        ]);
        $user_id = Auth::guard('web')->user()->id;
        $blog = Blog::where([['user_id', $user_id], ['id', $request->id]])->firstOrFail();
        if ($blog) {
            $blog->status = $request->status;
            $blog->save();
        }
        Session::flash('success', 'Updated Successfully');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return
     */
    public function edit(Request $request, $id)
    {
        $currentLang = Language::where('code', $request->language)->pluck('id')->firstOrFail();
        $user_id = Auth::guard('web')->user()->id;
        $data['blog'] = Blog::where([['user_id', $user_id], ['id', $id]])->firstOrFail();

        $data['userLangs'] = Language::where([
            ['user_id', $user_id]
        ])->get();

        $data['de_lang'] = Language::where([
            ['is_default', 1],
            ['user_id', $user_id]
        ])->first();


        $data['title'] = BlogContent::where([['blog_id', $data['blog']->id], ['language_id', $currentLang]])->pluck('title')->first();

        $current_package = UserPermissionHelper::currentPackagePermission($user_id);
        $post_limit = $current_package->post_limit;
        $total_post = Blog::where('user_id', $user_id)->count();
        if ($total_post > $post_limit) {
            $delete_blog = $total_post - $post_limit;
            Session::flash('warning', __('Delete') . ' ' . $delete_blog . ' ' . __('Blog to Enable Edit Function'));
            return redirect()->back();
        }

        return view('user.blog.blog.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $ruleArray = [
            'serial_number' => 'required',
            'status' => 'required',
            'category_id' => 'required'
        ];

        $messageArray = [];

        $languages = Language::where('user_id', $user_id)->get();

        foreach ($languages as $language) {
            $code = $language->code;
            $langaugeName = ' ' . $language->name . ' ' . __('language');

            if (
                $language->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_content') ||
                $request->input($code . '_author') ||
                $request->input($code . '_meta_keyword') ||
                $request->input($code . '_meta_description')
            ) {
                $slug = make_slug($request[$code . '_title']);
                $ruleArray[$code . '_title'] = [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug, $language, $user_id, $request) {
                        $bis = BlogContent::where('language_id', $language->id)->where('user_id', $user_id)->where('blog_id', '<>', $request->blog_id)->get();
                        foreach ($bis as $key => $bi) {
                            if (strtolower($slug) == strtolower($bi->slug)) {
                                $fail(__('The title field must be unique for') . ' ' . $language->name . ' ' . __('language'));
                            }
                        }
                    }
                ];
                $ruleArray[$code . '_content'] = 'required';
                $ruleArray[$code . '_author'] = 'required';
            }

            //messsge array
            $messageArray[$code . '_title.required'] = __('The title field is required for') . $langaugeName;
            $messageArray[$code . '_title.max'] = __('The title field cannot contain more than 255 characters for') . $langaugeName;
            $messageArray[$code . '_title.unique'] = __('The title field must be unique for') . $langaugeName;
            $messageArray[$code . '_author.required'] = __('The author field is required for') . $langaugeName;
            $messageArray[$code . '_content.required'] = __('The content field is required for') . $langaugeName;
        }

        $validator = Validator::make($request->all(), $ruleArray, $messageArray);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $input = $request->all();
        $blog = Blog::where([['id', $request->blog_id], ['user_id', $user_id]])->firstOrFail();

        $thumbnail = $request->file('thumbnail');
        if ($request->hasFile('thumbnail')) {
            $dir = public_path('assets/front/img/user/blogs/');
            @mkdir($dir, 0775, true);
            @unlink($dir . $blog->image);

            //cover image saved start
            $imageData = file_get_contents($thumbnail->getRealPath());
            $thumbnail_name = uniqid() . '.png';
            $directory = 'public/images/';

            // Save the image to storage
            Storage::put($directory . $thumbnail_name, $imageData);
            // Resize the image (optional)
            $image1 = Image::make(Storage::path($directory . $thumbnail_name));

            $image1->resize(900, 600);
            $image1->save();

            File::move(Storage::path($directory . $thumbnail_name), $dir . $thumbnail_name);
            $timeZone = DB::table('user_basic_settings')->where('user_id', Auth::guard('web')->user()->id)->value('timezone');
            $input['image'] = $thumbnail_name;
        }


        $blog->update($input);

        foreach ($languages as $lang) {
            $code = $lang->code;
            if (
                $lang->is_default == 1 ||
                $request->input($code . '_title') ||
                $request->input($code . '_content') ||
                $request->input($code . '_author') ||
                $request->input($code . '_meta_keyword') ||
                $request->input($code . '_meta_description')
            ) {
                $categoryId = BlogCategory::where([['unique_id', $request->category_id], ['language_id', $lang->id]])->pluck('id')->first();
                $blogContent = BlogContent::where([['blog_id', $blog->id], ['language_id', $lang->id], ['user_id', $user_id]])->first();
                if (empty($blogContent)) {
                    $blogContent = new BlogContent();
                    $blogContent->language_id = $lang->id;
                    $blogContent->blog_id = $blog->id;
                    $blogContent->user_id = $user_id;
                }
                $blogContent->title = $request[$lang->code . '_title'];
                $blogContent->slug = make_slug($request[$lang->code . '_title']);
                $blogContent->author = $request[$lang->code . '_author'];
                $blogContent->category_id = $categoryId;
                $blogContent->content = Purifier::clean($request[$lang->code . '_content'], 'youtube');
                $blogContent->meta_keywords = $request[$lang->code . '_meta_keywords'];
                $blogContent->meta_description = $request[$lang->code . '_meta_description'];
                $blogContent->save();
            }
        }

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getcats(Request $request)
    {
        return BlogCategory::where([
            ['language_id', $request->language_id],
            ['user_id', '=', Auth::guard('web')->user()->id],
            ['status', '=', 1]
        ])->get();
    }

    public function delete(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $blog = Blog::where([['user_id', $user_id], ['id', $request->blog_id]])->firstOrFail();
        if ($blog) {
            $blogContents = BlogContent::where('blog_id', $request->blog_id)->get();
            foreach ($blogContents as $blogContent) {
                $blogContent->delete();
            }
        }
        @unlink(public_path('assets/front/img/user/blogs/') . $blog->image);
        $blog->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $ids = $request->ids;
        foreach ($ids as $id) {
            $blog = Blog::where([['user_id', $user_id], ['id', $id]])->firstOrFail();
            if ($blog) {
                $blogContents = BlogContent::where('blog_id', $id)->get();
                foreach ($blogContents as $blogContent) {
                    $blogContent->delete();
                }
            }
            @unlink(public_path('assets/front/img/user/blogs/') . $blog->image);
            $blog->delete();
        }
        Session::flash('success', __('Deleted successfully'));
        return "success";
    }
}
