<?php

namespace App\Http\Controllers\Admin;

use App\Models\BasicSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\Language;
use App\Models\Testimonial;
use App\Models\BasicSetting as BS;
use App\Models\BasicExtended;
use Validator;
use Session;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $data['abs'] = $lang->basic_setting;
        $data['abe'] = $lang->basic_extended;
        $data['testimonials'] = Testimonial::where('language_id', $data['lang_id'])->orderBy('id', 'DESC')->get();

        return view('admin.home.testimonial.index', $data);
    }

    public function edit($id)
    {
        $data['testimonial'] = Testimonial::findOrFail($id);
        return view('admin.home.testimonial.edit', $data);
    }

    public function store(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'language_id' => 'required',
            'image' => 'required',
            'comment' => 'required',
            'name' => 'required|max:50',
            'designation' => 'required|max:50',
            'serial_number' => 'required|integer',
            'image' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
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

        $input = $request->all();

        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/testimonials/');
            $input['image'] =  Uploader::upload_picture($dir, $request->file('image'));
        }

        $testimonial = new Testimonial;

        $testimonial->create($input);

        Session::flash('success', __('Created Successfully'));
        return "success";
    }

    public function sideImageStore(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'image' => 'required',
            'image' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
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
        $input = $request->all();

        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/testimonials/');
            $input['image'] = Uploader::upload_picture($dir, $request->file('image'));
            $count = BasicSetting::all();
            foreach ($count as $c) {
                BasicExtended::query()->update(['testimonial_img' => $input['image']]);
            }
            Session::flash('success', __('Updated Successfully'));
        }
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'image' => 'required',
            'comment' => 'required',
            'name' => 'required|max:50',
            'designation' => 'required|max:50',
            'serial_number' => 'required|integer',
            'image' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
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
        $input = $request->all();

        $testimonial = Testimonial::findOrFail($request->testimonial_id);
        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/testimonials/');
            @unlink($dir . $testimonial->image);
            $input['image'] =  Uploader::upload_picture($dir, $request->file('image'));
        }

        $testimonial->update($input);
        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function textupdate(Request $request, $langid)
    {
        $request->validate([
            'testimonial_section_title' => 'required|max:25'
        ]);
        $bs = BS::where('language_id', $langid)->firstOrFail();
        $bs->testimonial_title = $request->testimonial_section_title;

        if ($request->hasFile('testimonial_bg_img')) {
            $be = BasicExtended::where('language_id', $langid)->firstOrFail();
            $dir = public_path('assets/front/img/');
            @unlink($dir . $be->testimonial_bg_img);
            $be->testimonial_bg_img = Uploader::upload_picture($dir, $request->file('testimonial_bg_img'));
            $be->save();
        }
        $bs->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete(Request $request)
    {
        $testimonial = Testimonial::findOrFail($request->testimonial_id);
        @unlink(public_path('assets/front/img/testimonials/') . $testimonial->image);
        $testimonial->delete();

        Session::flash('success', __('Deleted Successfully'));
        return back();
    }
}
