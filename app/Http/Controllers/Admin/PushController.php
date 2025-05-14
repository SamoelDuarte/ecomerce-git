<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Notifications\PushDemo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Notification;
use Session;

class PushController extends Controller
{
    public function settings()
    {
        return view('admin.pushnotification.settings');
    }

    public function updateSettings(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
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

        if (env('VAPID_PUBLIC_KEY') == null) {
            $rules['vapid_public_key'] = 'required';
        }
        if (env('VAPID_PRIVATE_KEY') == null) {
            $rules['vapid_private_key'] = 'required';
        }

        $request->validate($rules);
        if ($request->hasFile('file')) {
            @unlink(public_path("assets/front/img/pushnotification_icon.png"));
            $dir = public_path('assets/front/img/');
            @mkdir($dir, 0775, true);
            $request->file('file')->move($dir, 'pushnotification_icon.png');
        }

        $array = [
            'VAPID_PUBLIC_KEY' => $request->vapid_public_key,
            'VAPID_PRIVATE_KEY' => $request->vapid_private_key
        ];

        setEnvironmentValue($array);
        Artisan::call('config:clear');
        session()->flash('success', __('Updated Successfully'));
        return back();
    }

    public function send()
    {
        return view('admin.pushnotification.send');
    }

    public function push(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'button_url' => 'required',
            'button_text' => 'required'
        ]);
        $title = $request->title;
        $message = $request->message;
        $buttonText = $request->button_text;
        $buttonURL = $request->button_url;
        Notification::send(Guest::all(), new PushDemo($title, $message, $buttonText, $buttonURL));
        Session::flash('success', __('Push notification has been sent successfully'));
        return redirect()->route('admin.pushnotification.send');
    }
}
