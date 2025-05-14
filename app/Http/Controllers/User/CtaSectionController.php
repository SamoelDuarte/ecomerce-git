<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\CallToAction;
use App\Models\User\Language;
use Auth;
use Illuminate\Http\Request;
use Session;

class CtaSectionController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->where('user_id', Auth::guard('web')->user()->id)->firstOrFail();
        $info['data'] = CallToAction::where('language_id', $language->id)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->first();
        $info['language_id'] = $language->id;
        return view('user.home.call_to_action.index', $info);
    }

    public function update(Request $request)
    {
        $in = $request->all();
        $data = CallToAction::where('language_id', $request->language_id)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->first();

        $dir = public_path('assets/front/img/cta');
        if ($request->hasFile('background_image')) {
            $in['background_image'] = Uploader::update_picture($dir, $request->file('background_image'), @$data->background_image);
        }
        if ($request->hasFile('side_image')) {
            $in['side_image'] = Uploader::update_picture($dir, $request->file('side_image'), @$data->side_image);
        }

        if (empty($data)) {
            $in['user_id'] = Auth::guard('web')->user()->id;
            CallToAction::create($in);
        } else {
            $data->update($in);
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
}
