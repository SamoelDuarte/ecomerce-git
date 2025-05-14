<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\User\BasicSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PluginController extends Controller
{
    public function plugins()
    {
        $current_package = UserPermissionHelper::currentPackagePermission(Auth::guard('web')->user()->id);
        if ($current_package) {
            $features = json_decode($current_package->features, true);
        } else {
            $features = [];
        }
        return view('user.settings.plugins', compact('features'));
    }

    public function updategooglelogin(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $bs = BasicSetting::where('user_id', $userId)->first();
        $bs->is_google_login = $request->is_google_login;
        $bs->google_client_id = $request->google_client_id;
        $bs->google_client_secret = $request->google_client_secret;
        $bs->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
    public function updateWhatsapp(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $bs = BasicSetting::where('user_id', $userId)->first();
        $bs->is_whatsapp = $request->is_whatsapp;
        $bs->whatsapp_number = $request->whatsapp_number;
        $bs->whatsapp_header_title = $request->whatsapp_header_title;
        $bs->whatsapp_popup_message = $request->whatsapp_popup_message;
        $bs->whatsapp_popup = $request->whatsapp_popup;
        $bs->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function updateTawkTo(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $bs = BasicSetting::where('user_id', $userId)->first();
        $bs->tak_to_property_id = $request->tak_to_property_id;
        $bs->tak_to_widget_id = $request->tak_to_widget_id;
        $bs->is_tawkto = $request->is_tawkto;
        $bs->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function updateDisqus(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $bs = BasicSetting::where('user_id', $userId)->first();
        $bs->is_disqus = $request->is_disqus;
        $bs->disqus_shortname = $request->disqus_shortname;
        $bs->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function updateGoogleAnalytics(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $bs = BasicSetting::where('user_id', $userId)->first();
        $bs->measurement_id = $request->measurement_id;
        $bs->is_analytics = $request->is_analytics;
        $bs->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
    public function updateRecaptcha(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $bs = BasicSetting::where('user_id', $userId)->first();
        $bs->is_recaptcha = $request->is_recaptcha;
        $bs->google_recaptcha_site_key = $request->google_recaptcha_site_key;
        $bs->google_recaptcha_secret_key = $request->google_recaptcha_secret_key;
        $bs->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
    public function updatePixel(Request $request)
    {
        $userId = Auth::guard('web')->user()->id;
        $bs = BasicSetting::where('user_id', $userId)->first();
        $bs->pixel_id = $request->pixel_id;
        $bs->is_facebook_pixel = $request->is_facebook_pixel ?? 0;
        $bs->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }
}
