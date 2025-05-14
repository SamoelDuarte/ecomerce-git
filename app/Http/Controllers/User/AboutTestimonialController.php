<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\Language;
use App\Models\User\Testimonial;
use App\Models\User\UserSection;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class AboutTestimonialController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where([['code', $request->language], ['user_id', $user_id]])->firstOrFail();
        $lang_id = $lang->id;
        $data['userLanguages'] = Language::where('user_id', $user_id)->get();

        $data['testimonials'] = Testimonial::where([['user_id', $user_id], ['language_id', $lang_id]])->get();
        $data['lang_id'] = $lang_id;
        $data['data'] = UserSection::where([['user_id', $user_id], ['language_id', $lang_id]])->first();
        return view('user.about.testimonials.index', $data);
    }

    public function updateInfo(Request $request)
    {
        $messages = [
            'testimonial_section_title.required' => __('The title feild is required'),
            'testimonial_section_subtitle.required' => __('The subtitle feild is required'),
        ];
        $request->validate([
            'language_id' => 'required',
            'testimonial_section_title' => 'nullable',
            'testimonial_section_subtitle' => 'nullable',
        ], $messages);
        $user_id = Auth::guard('web')->user()->id;
        $data = UserSection::where([['user_id', $user_id], ['language_id', $request->language_id]])->first();
        $in = $request->all();
        if (empty($data)) {
            $in['user_id'] = $user_id;
            $in['language_id'] = $request->language_id;
            $data = UserSection::create($in);
        } else {
            $data->update($in);
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function store(Request $request)
    {
        $rules = [
            'user_language_id' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png',
            'name' => 'required',
            'designation' => 'required',
            'rating' => 'required',
            'color' => 'required',
            'comment' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $in = $request->all();
        if ($request->hasFile('image')) {
            $in['image'] = Uploader::upload_picture(public_path('assets/front/img/user/about/testimonial'), $request->file('image'));
        }

        $in['language_id'] = $request->user_language_id;
        $in['user_id'] = Auth::guard('web')->user()->id;
        Testimonial::create($in);
        Session::flash(__('Created successfully'));
        return 'success';
    }

    public function edit($id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['data'] = Testimonial::where([['user_id', $user_id], ['id', $id]])->firstOrFail();
        $d_lang = Language::where([['user_id', $user_id], ['id', $data['data']->language_id]])->first();
        $data['d_lang'] = $d_lang;
        return view('user.about.testimonials.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $rules = [
            'image' => 'mimes:jpg,jpeg,png',
            'name' => 'required',
            'designation' => 'required',
            'rating' => 'required',
            'color' => 'required',
            'comment' => 'required',
        ];
        $request->validate($rules);
        $data = Testimonial::where([['user_id', $user_id], ['id', $id]])->firstOrFail();
        $in = $request->all();
        if ($request->hasFile('image')) {
            $in['image'] = Uploader::update_picture(public_path('assets/front/img/user/about/testimonial'), $request->file('image'), @$data->image);
        }
        $data->update($in);
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete(Request $request)
    {
        $testimonial_id = $request->testimonial_id;
        $data = Testimonial::where('id', $testimonial_id)->firstOrFail();
        @unlink(public_path('assets/front/img/user/about/testimonial') . $data->image);
        $data->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }
    public function bulk_delete(Request $request)
    {

        $ids = $request->ids;
        foreach ($ids as $id) {
            $data = Testimonial::where('id', $id)->first();
            if ($data) {
                @unlink(public_path('assets/front/img/user/about/testimonial') . $data->image);
                $data->delete();
            }
        }
        Session::flash('success', __('Deleted successfully'));
        return 'success';
    }
}
