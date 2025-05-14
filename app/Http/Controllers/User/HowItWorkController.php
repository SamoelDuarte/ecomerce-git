<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\HowitWorkSection;
use App\Models\User\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class HowItWorkController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $lang_id = $lang->id;
        $data['collection'] = HowitWorkSection::where([['user_id', $user_id], ['language_id', $lang_id]])->get();
        $data['u_langs'] = Language::where('user_id', $user_id)->get();
        return view('user.home.how_it_work.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'user_language_id' => 'required',
            'icon' => 'required',
            'title' => 'required',
            'text' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }
        $data = $request->all();
        $data['user_id'] = Auth::guard('web')->user()->id;
        $data['language_id'] = $request->user_language_id;
        HowitWorkSection::create($data);
        Session::flash('success', __('Created successfully'));
        return 'success';
    }
    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',
            'text' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $data = HowitWorkSection::where([['id', $request->id], ['user_id', Auth::guard('web')->user()->id]])->first();
        if ($data) {
            $data->update($request->all());
        }
        Session::flash(__('Updated successfully'));
        return 'success';
    }

    public function delete(Request $request)
    {
        $data = HowitWorkSection::where([['user_id', Auth::guard('web')->user()->id], ['id', $request->faq_id]])->firstOrFail();
        $data->delete();
        Session::flash('success',  __('Deleted successfully'));
        return back();
    }
}
