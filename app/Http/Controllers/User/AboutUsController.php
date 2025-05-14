<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\AboutUs;
use App\Models\User\AboutUsFeatures;
use App\Models\User\BasicSetting;
use App\Models\User\HowitWorkSection;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class AboutUsController extends Controller
{
    public function features(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $lang_id = $lang->id;
        $data['collection'] = HowitWorkSection::where([['user_id', $user_id], ['language_id', $lang_id]])->get();
        $data['u_langs'] = Language::where('user_id', $user_id)->get();
        return view('user.home.how_it_work.index', $data);
    }

    public function about(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->firstOrFail();
        $lang_id = $lang->id;

        $data['data'] = AboutUs::where([['language_id', $lang_id], ['user_id', $user_id]])->first();
        $data['features'] = AboutUsFeatures::where([['language_id', $lang_id], ['user_id', $user_id]])->get();
        $data['lang_id'] = $lang_id;
        $data['userLanguages'] = Language::where('user_id', $user_id)->get();
        return view('user.about.about-us', $data);
    }

    public function updaetAbout(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $lang_id = $lang->id;

        $in = $request->all();
        $aboutUs = AboutUs::where([['language_id', $lang_id], ['user_id', $user_id]])->first();

        if ($request->hasFile('image')) {
            $in['image'] = Uploader::update_picture(public_path('assets/front/img/user/about'), $request->file('image'), @$aboutUs->image);
        }

        if (empty($aboutUs)) {
            $in['user_id'] = $user_id;
            $in['language_id'] = $lang_id;
            AboutUs::create($in);
        } else {
            $aboutUs->update($in);
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function feature_store(Request $request)
    {
        $rules = [
            'icon' => 'required',
            'title' => 'required|max:255',
            'subtitle' => 'required|max:255',
            'color' => 'required',
            'serial_number' => 'required',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $lang_id = $lang->id;

        $in = $request->all();
        if ($request->hasFile('image')) {
            $in['image'] = Uploader::upload_picture(public_path('assets/front/img/user/about'), $request->file('image'));
        }
        $in['user_id'] = $user_id;
        $in['language_id'] = $lang_id;
        AboutUsFeatures::create($in);

        Session::flash(__('Created successfully'));
        return 'success';
    }

    public function feature_edit($id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['d_lang'] = Language::where([['user_id', $user_id], ['is_default', 1]])->first();
        $data['data'] = AboutUsFeatures::where([['user_id', $user_id], ['id', $id]])->firstOrFail();
        return view('user.about.features.edit', $data);
    }

    public function feature_update(Request $request, $id)
    {
        $request->validate([
            'icon' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'color' => 'required',
            'serial_number' => 'required',
            'status' => 'required',
        ]);
        $user_id = Auth::guard('web')->user()->id;
        $data = AboutUsFeatures::where([['user_id', $user_id], ['id', $id]])->firstOrFail();
        $data->update($request->all());
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function delete_features(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data = AboutUsFeatures::where([['user_id', $user_id], ['id', $request->id]])->firstOrFail();
        $data->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }
    public function bulk_delete_features(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $user_id = Auth::guard('web')->user()->id;
            $data = AboutUsFeatures::where([['user_id', $user_id], ['id', $id]])->firstOrFail();
            $data->delete();
        }
        Session::flash('success', __('Deleted successfully'));
        return 'success';
    }

    public function sections()
    {
        $data['ubs'] = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->firstOrFail();

        if (!is_null($data['ubs']->about_additional_section_status) && $data['ubs']->about_additional_section_status != "null") {
            $data['additional_section_statuses']  = json_decode($data['ubs']->about_additional_section_status, true);
        } else {
            $data['additional_section_statuses'] = [];
        }
        $data['langid'] = Language::where([['is_default', 1], ['user_id', Auth::guard('web')->user()->id]])->first()->id;

        return view('user.about.sections', $data);
    }

    public function updatesections(Request $request)
    {
        $bs = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->firstOrFail();
        $bs->about_info_section  = $request->about_info_section;
        $bs->about_features_section  = $request->about_features_section;
        $bs->about_counter_section  = $request->about_counter_section;
        $bs->about_testimonial_section  = $request->about_testimonial_section;
        $bs->about_additional_section_status = json_encode($request->additional_sections, true);
        $bs->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
}
