<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\CounterInformation;
use App\Models\CounterSection;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CounterInformationController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $information['counters'] = CounterInformation::where('language_id', $language->id)->get();
        $information['counterSection'] = CounterSection::where('language_id', $language->id)->first();
        $information['langs'] = Language::all();
        $information['language'] = Language::where('is_default', 1)->first();

        return view('admin.about.counter-section.index', $information);
    }


    public function updateInfo(Request $request)
    {
        $image = $request->file('image');
        $allowedExts =  array('jpg', 'png', 'jpeg');
        $request->validate([
            'lang_code' => 'required',
            'title' => 'required',
            'text' => 'required',
            'image' => [
                function ($attribute, $value, $fail) use ($image, $allowedExts) {
                    if (!empty($image)) {
                        $ext = $image->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail(__('Only png, jpg, jpeg image is allowed'));
                        }
                    }
                },
            ],
        ]);

        $language = Language::query()->where('code', '=', $request->lang_code)->firstOrFail();
        $info = CounterSection::where('language_id', $language->id)->first();
        if (empty($info)) {
            $info = new CounterSection();
            $info->language_id = $language->id;
        }
        if ($request->hasFile('image')) {
            $dir = public_path('assets/front/img/counter-section/');
            $info->image =  Uploader::upload_picture($dir, $image);
        }

        $info->title = $request->title;
        $info->text = $request->text;
        $info->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function storeCounter(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'icon' => 'required',
            'amount' => 'required|numeric',
            'title' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $in = $request->all();
        CounterInformation::create($in);
        Session::flash('success', __('Created Successfully'));
        return 'success';
    }

    public function updateCounter(Request $request)
    {
        $rules = [
            'icon' => 'required',
            'amount' => 'required|numeric',
            'title' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $counterInfo = CounterInformation::query()->find($request->id);
        $counterInfo->update($request->except('language'));
        Session::flash('success', __('Updated Successfully'));
        return 'success';
    }

    public function destroyCounter($id)
    {
        $counterInfo = CounterInformation::query()->findOrFail($id);
        $counterInfo->delete();
        return redirect()->back()->with('success', __('Deleted Successfully'));
    }

    public function bulkDestroyCounter(Request $request)
    {
        $ids = $request['ids'];
        foreach ($ids as $id) {
            $counterInfo = CounterInformation::query()->find($id);
            $counterInfo->delete();
        }
        Session::flash('success', __('Deleted Successfully'));
        return 'success';
    }
}
