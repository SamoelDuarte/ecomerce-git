<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Uploader;
use App\Models\User\CounterInformation;
use App\Models\User\CounterSection;
use App\Models\User\Language;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CounterInformationController extends Controller
{
    public function counter(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();

        $data['counters'] = CounterInformation::where([['language_id', $lang->id], ['user_id', $user_id]])->get();

        $data['data'] = CounterSection::where([['language_id', $lang->id], ['user_id', $user_id]])->first();

        $data['userLanguages'] = Language::where('user_id', $user_id)->get();
        $data['lang_id'] = $lang->id;

        return view('user.about.counter-section', $data);
    }

    public function updateInfo(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $lang_id = $lang->id;

        $in = $request->all();
        $counterInfo = CounterSection::where([['language_id', $lang_id], ['user_id', $user_id]])->first();
        if ($request->hasFile('image')) {
            $in['image'] = Uploader::update_picture(public_path('assets/front/img/user/about'), $request->file('image'), @$counterInfo->image);
        }

        if (empty($counterInfo)) {
            $in['user_id'] = $user_id;
            $in['language_id'] = $lang_id;
            CounterSection::create($in);
        } else {
            $counterInfo->update($in);
        }
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
        $in['user_id'] = Auth::guard('web')->user()->id;
        CounterInformation::create($in);

        Session::flash('success', __('Created successfully'));
        return 'success';
    }

    public function counter_edit($id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $counter = CounterInformation::where([['id', $id], ['user_id', $user_id]])->firstOrFail();
        $data['data'] = $counter;
        $data['userLanguages'] = Language::where('user_id', $user_id)->get();
        $data['d_lang'] = Language::where([['user_id', $user_id], ['id', $counter->language_id]])->first();

        return view('user.about.counter.edit', $data);
    }

    public function counter_update(Request $request)
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
        $user_id = Auth::guard('web')->user()->id;

        $counterInfo = CounterInformation::where([['id', $request->id], ['user_id', $user_id]])->firstOrFail();
        $in = $request->all();

        $counterInfo->update($in);

        Session::flash('success', __('Updated Successfully'));

        return back();
    }

    public function delete_counter($id)
    {
        $counterInfo = CounterInformation::where([['id', $id], ['user_id', Auth::guard('web')->user()->id]])->firstOrFail();
        $counterInfo->delete();

        return redirect()->back()->with('success', __('Deleted successfully'));
    }

    public function bulk_delete_counter(Request $request)
    {
        $ids = $request['ids'];
        foreach ($ids as $id) {
            $counterInfo = CounterInformation::where([['id', $id], ['user_id', Auth::guard('web')->user()->id]])->first();
            if ($counterInfo) {
                $counterInfo->delete();
            }
        }
        Session::flash('success', __('Deleted successfully'));
        return 'success';
    }
}
