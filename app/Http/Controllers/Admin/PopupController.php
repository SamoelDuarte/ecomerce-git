<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Popup;
use Validator;
use Session;

class PopupController extends Controller
{
    public function index(Request $request)
    {
        $data['langs'] = Language::all();
        $lang = Language::where('code', $request->language)->firstOrFail();
        $lang_id = $lang->id;
        $data['popups'] = Popup::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        $data['lang'] = $lang;
        return view('admin.popups.index', $data);
    }

    public function types()
    {
        return view('admin.popups.types');
    }

    public function create()
    {
        $data['langs'] = Language::all();
        return view('admin.popups.create', $data);
    }

    public function edit($id)
    {
        $data['popup'] = Popup::findOrFail($id);
        $data['language'] = Language::findOrFail($data['popup']->language_id);
        return view('admin.popups.edit', $data);
    }

    public function store(Request $request)
    {
        $type = $request->type;

        $rules = [
            'name' => 'required',
            'language_id' => 'required',
            'serial_number' => 'required|integer',
            'delay' => 'required|integer',
        ];

        if ($type == 1 || $type == 4 || $type == 5 || $type == 7) {
            $image = $request->file('image');
            $allowedExts = array('jpg', 'png', 'jpeg');

            $rules['image'] = [
                'required',
                function ($attribute, $value, $fail) use ($image, $allowedExts) {
                    $extImage = $image->getClientOriginalExtension();
                    if (!in_array($extImage, $allowedExts)) {
                        return $fail(__('Only png, jpg, jpeg image is allowed'));
                    }
                }
            ];
        }

        if ($type == 2 || $type == 3 || $type == 6) {
            $background = $request->file('background_image');
            $allowedBgExts = array('jpg', 'png', 'jpeg');
            $rules['background_image'] = [
                'required',
                function ($attribute, $value, $fail) use ($background, $allowedBgExts) {
                    $extBackground = $background->getClientOriginalExtension();
                    if (!in_array($extBackground, $allowedBgExts)) {
                        return $fail(__('Only png, jpg, jpeg image is allowed'));
                    }
                }
            ];
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $rules['title'] = 'nullable';
            $rules['text'] = 'nullable';
        }

        if ($type == 2 || $type == 3) {
            $rules['background_color'] = 'required';
            $rules['background_opacity'] = 'required|numeric|max:1|min:0';
        }

        if ($type == 7) {
            $rules['background_color'] = 'required';
        }

        if ($type == 6 || $type == 7) {
            $rules['end_date'] = 'required';
            $rules['end_time'] = 'required';
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $rules['button_text'] = 'nullable';
            $rules['button_color'] = 'nullable';
        }

        if ($type == 2 || $type == 4 || $type == 6 || $type == 7) {
            $rules['button_url'] = 'nullable';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $popup = new Popup;
        $popup->name = $request->name;
        $popup->language_id = $request->language_id;
        $popup->serial_number = $request->serial_number;
        $popup->delay = $request->delay;
        $popup->type = $type;

        if ($type == 1 || $type == 4 || $type == 5 || $type == 7) {
            if ($request->hasFile('image')) {
                $directory = public_path('assets/front/img/popups/');
                $popup->image = Uploader::upload_picture($directory, $image);
            }
        }

        if ($type == 2 || $type == 3 || $type == 6) {
            if ($request->hasFile('background_image')) {
                $directory = public_path('assets/front/img/popups/');
                $popup->background_image = Uploader::upload_picture($directory, $background);
            }
        }

        if ($type == 2 || $type == 3) {
            $popup->background_color = $request->background_color;
            $popup->background_opacity = $request->background_opacity;
        }

        if ($type == 7) {
            $popup->background_color = $request->background_color;
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $popup->button_text = $request->button_text;
            $popup->button_color = $request->button_color;
        }

        if ($type == 2 || $type == 4 || $type == 6 || $type == 7) {
            $popup->button_url = $request->button_url;
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $popup->title = $request->title;
            $popup->text = $request->text;
        }

        if ($type == 6 || $type == 7) {
            $popup->end_date = $request->end_date;
            $popup->end_time = $request->end_time;
        }

        $popup->save();
        Session::flash('success', __('Created Successfully'));
        return "success";
    }

    public function update(Request $request)
    {
        $type = $request->type;
        $rules = [
            'name' => 'required',
            'serial_number' => 'required|integer',
            'delay' => 'required|integer',
        ];

        if ($type == 1 || $type == 4 || $type == 5 || $type == 7) {
            if ($request->hasFile('image')) {
                $image = $request->image;
                $allowedExts = array('jpg', 'png', 'jpeg');
                $image = $request->file('image');

                $rules['image'] = [
                    function ($attribute, $value, $fail) use ($image, $allowedExts) {
                        $extImage = $image->getClientOriginalExtension();
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                ];
            }
        }

        if ($type == 2 || $type == 3 || $type == 6) {
            if ($request->hasFile('background_image')) {
                $background = $request->background_image;
                $allowedBgExts = array('jpg', 'png', 'jpeg');
                $background = $request->file('background_image');

                $rules['background_image'] = [
                    function ($attribute, $value, $fail) use ($background, $allowedBgExts) {
                        $extBackground = $background->getClientOriginalExtension();
                        if (!in_array($extBackground, $allowedBgExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                ];
            }
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $rules['title'] = 'nullable';
            $rules['text'] = 'nullable';
        }

        if ($type == 2 || $type == 3) {
            $rules['background_color'] = 'required';
            $rules['background_opacity'] = 'required|numeric|max:1|min:0';
        }

        if ($type == 7) {
            $rules['background_color'] = 'required';
        }

        if ($type == 6 || $type == 7) {
            $rules['end_date'] = 'required';
            $rules['end_time'] = 'required';
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $rules['button_text'] = 'nullable';
            $rules['button_color'] = 'nullable';
        }

        if ($type == 2 || $type == 4 || $type == 6 || $type == 7) {
            $rules['button_url'] = 'nullable';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $popup = Popup::findOrFail($request->popup_id);
        $popup->name = $request->name;
        $popup->serial_number = $request->serial_number;
        $popup->delay = $request->delay;

        if ($type == 1 || $type == 4 || $type == 5 || $type == 7) {
            if ($request->hasFile('image')) {
                $directory = public_path('assets/front/img/popups/');
                @unlink($directory . $popup->image);
                $popup->image = Uploader::upload_picture($directory, $image);
            }
        }

        if ($type == 2 || $type == 3 || $type == 6) {
            if ($request->hasFile('background_image')) {
                $directory = public_path('assets/front/img/popups/');
                @unlink($directory . $popup->background_image);
                $popup->background_image = Uploader::upload_picture($directory, $background);
            }
        }

        if ($type == 2 || $type == 3) {
            $popup->background_color = $request->background_color;
            $popup->background_opacity = $request->background_opacity;
        }

        if ($type == 7) {
            $popup->background_color = $request->background_color;
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $popup->button_text = $request->button_text;
            $popup->button_color = $request->button_color;
        }

        if ($type == 2 || $type == 4 || $type == 6 || $type == 7) {
            $popup->button_url = $request->button_url;
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $popup->title = $request->title;
            $popup->text = $request->text;
        }

        if ($type == 6 || $type == 7) {
            $popup->end_date = $request->end_date;
            $popup->end_time = $request->end_time;
        }

        $popup->save();
        Session::flash('success', __('Updated Successfully'));
        return "success";
    }


    public function delete(Request $request)
    {
        $dir = public_path('assets/front/img/popups/');
        $popup = Popup::findOrFail($request->popup_id);
        @unlink($dir . $popup->image);
        @unlink($dir . $popup->background_image);
        $popup->delete();
        Session::flash('success', __('Popup has been deleted successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        $dir = public_path('assets/front/img/popups/');
        foreach ($ids as $id) {
            $popup = Popup::findOrFail($id);
            @unlink($dir . $popup->image);
            @unlink($dir . $popup->background_image);
            $popup->delete();
        }

        Session::flash('success', __('Deleted Successfully'));
        return "success";
    }

    public function status(Request $request)
    {
        $po = Popup::find($request->popup_id);
        $po->status = $request->status;
        $po->save();
        Session::flash('success', __("Status has been changed"));
        return back();
    }
}
