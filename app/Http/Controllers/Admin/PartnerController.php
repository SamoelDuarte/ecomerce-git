<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\Partner;
use App\Models\Language;
use Illuminate\Support\Facades\Session;
use Validator;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $lang_id = $lang->id;
        $data['partners'] = Partner::orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;
        return view('admin.home.partner.index', $data);
    }

    public function edit($id)
    {
        $data['partner'] = Partner::findOrFail($id);
        return view('admin.home.partner.edit', $data);
    }

    public function upload(Request $request)
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

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'partner']);
        }

        $dir = public_path('assets/front/img/partners/');
        $filename =  Uploader::upload_picture($dir, $request->file('file'));
        Session::put('partner_image', $filename);

        return response()->json(['status' => "session_put", "image" => "partner_image", 'filename' => $filename]);
    }

    public function store(Request $request)
    {
        $rules = [
            'image' => 'required',
            'url' => 'required|max:255',
            'serial_number' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $filename = null;
        if ($request->hasFile('image')) {
            $directory = public_path("assets/front/img/partners/");
            $img = $request->file('image');
            $filename = Uploader::upload_picture($directory, $img);
        }
        $partner = new Partner;
        $partner->url = $request->url;
        $partner->image = $filename;
        $partner->serial_number = $request->serial_number;
        $partner->save();

        Session::flash('success', __('Created Successfully'));
        return "success";
    }

    public function uploadUpdate(Request $request, $id)
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

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'partner']);
        }

        $partner = Partner::findOrFail($id);
        if ($request->hasFile('file')) {
            $dir = public_path('assets/front/img/partners/');
            @unlink($dir . $partner->image);
            $partner->image = Uploader::upload_picture($dir, $img);
            $partner->save();
        }
        return response()->json(['status' => "success", "image" => "Partner", 'partner' => $partner]);
    }

    public function update(Request $request)
    {
        $rules = [
            'url' => 'required|max:255',
            'serial_number' => 'required|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $partner = Partner::query()->findOrFail($request->partner_id);
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $dir = public_path('assets/front/img/partners/');
            @unlink($dir . $partner->image);
            $partner->image = Uploader::upload_picture($dir, $img);
        }
        $partner->url = $request->url;
        $partner->serial_number = $request->serial_number;
        $partner->save();

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function delete(Request $request)
    {

        $partner = Partner::findOrFail($request->partner_id);
        @unlink(public_path('assets/front/img/partners/') . $partner->image);
        $partner->delete();

        Session::flash('success', __('Deleted Successfully'));
        return back();
    }
}
