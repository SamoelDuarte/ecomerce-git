<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\Language;
use App\Models\Feature;
use Validator;
use Session;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $lang_id = $lang->id;
        $data['features'] = Feature::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;
        return view('admin.home.feature.index', $data);
    }

    public function edit($id)
    {
        $data['feature'] = Feature::findOrFail($id);
        return view('admin.home.feature.edit', $data);
    }

    public function store(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'language_id' => 'required',
            'title' => 'required|max:50',
            'serial_number' => 'required|integer',

            'image' => [
                'required',
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
        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/feature/');
            $main_image =  Uploader::upload_picture($dir, $request->file('image'));
            $image = $main_image;
        } else {
            $image = null;
        }
        $feature = new Feature;
        $feature->icon = $image;
        $feature->language_id = $request->language_id;
        $feature->title = $request->title;
        $feature->text = $request->text;
        $feature->serial_number = $request->serial_number;
        $feature->save();
        Session::flash('success', __('Created Successfully'));
        return "success";
    }

    public function update(Request $request)
    {
        $img = $request->file('image');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'image' => 'required',
            'title' => 'required|max:50',
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
            return back()->withErrors($validator->errors());
        }

        $feature = Feature::findOrFail($request->feature_id);
        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/feature/');
            @unlink($dir . $feature->icon);
            $feature->icon = Uploader::upload_picture($dir, $request->file('image'));
        }
        $feature->title = $request->title;
        $feature->text = $request->text;
        $feature->serial_number = $request->serial_number;
        $feature->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete(Request $request)
    {

        $feature = Feature::findOrFail($request->feature_id);
        @unlink(public_path('assets/front/img/feature/') . $feature->icon);
        $feature->delete();

        Session::flash('success', __('Deleted Successfully'));
        return back();
    }
}
