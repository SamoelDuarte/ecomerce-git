<?php

namespace App\Http\Controllers\User;

use App\Models\User\UserCurrency;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User\UserShippingCharge;

class ShopSettingController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $lang = Language::where('code', $request->language)->where('user_id', $user_id)->first();
        $lang_id = $lang->id;

        $data['shippings'] = UserShippingCharge::where('user_id', $user_id)
            ->where('language_id', $lang_id)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        $data['lang_id'] = $lang_id;
        $data['default_currency'] = UserCurrency::where([['is_default', 1], ['user_id', $user_id]])->first();
        return view('user.item.shop_setting.index', $data);
    }


    public function store(Request $request)
    {
        $rules = [
            'user_language_id' => 'required',
            'title' => 'required',
            'text' => 'required|max:255',
            'charge' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $input = $request->all();
        $input['language_id'] = $request->user_language_id;
        $user_currency = UserCurrency::where('is_default', 1)->where('user_id', Auth::guard('web')->user()->id)->first();
        if (empty($user_currency)) {
            Session::flash('warning', __('Please add a currency first'));
            return "success";
        }
        $input['currency_id'] = $user_currency->id;

        $input['user_id'] = Auth::guard('web')->user()->id;

        $data = new UserShippingCharge();
        $data->create($input);

        Session::flash('success', __('Created successfully'));
        return "success";
    }

    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',
            'text' => 'required|max:255',
            'charge' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $data = UserShippingCharge::findOrFail($request->shipping_id);

        $data->update($request->all());

        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function delete(Request $request)
    {
        $data = UserShippingCharge::findOrFail($request->shipping_id);
        $data->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }
}
