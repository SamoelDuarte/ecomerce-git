<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserCurrency;
use Illuminate\Http\Request;
use Auth;
use Session;
use Validator;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $data['currencies'] = UserCurrency::where('user_id', Auth::guard('web')->user()->id)->get();
        $data['user_id'] = Auth::guard('web')->user()->id;
        $data['default_currency'] = UserCurrency::where([['user_id', Auth::guard('web')->user()->id], ['is_default', 1]])->first();

        return view('user.settings.currency.index', $data);
    }

    public function store(Request $request)
    {
        $rules = UserCurrency::where('user_id', $request->user_id)->where('text', $request->text)->where('symbol', $request->symbol)->first();

        if (!empty($rules)) {
            Session::flash('warning', __('The currency already exists'));
            return 'success';
        }
        $rules = [
            'text' => 'required',
            'symbol' => 'required',
            'value' => 'required',
        ];
        $messages['value.required'] = __('The rate field is required').'.';
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        //--- Logic Section
        $data = new UserCurrency();
        $data->user_id = $request->user_id;
        $data->text = $request->text;
        $data->symbol = $request->symbol;
        $data->value = $request->value;
        $data->text_position = $request->text_position;
        $data->symbol_position = $request->symbol_position;
        $data->save();
        Session::flash('success', __('Created successfully'));
        return 'success';
    }

    public function update(Request $request)
    {
        $rules = [
            'text' => 'required',
            'symbol' => 'required',
            'value' => 'required',
        ];
        $messages['value.required'] = __('The rate field is required').'.';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $data = UserCurrency::findOrFail($request->id);
        $input = $request->all();
        $data->update($input);
        Session::flash('success', __('Updated Successfully'));
        return "success";
    }

    public function status($id1, $id2)
    {
        $data = UserCurrency::findOrFail($id1);
        session()->put('user_curr', $id1);
        $data->is_default = $id2;
        $data->value =  1;
        $data->update();
        $data = UserCurrency::where('id', '!=', $id1)->update(['is_default' => 0]);
        Session::flash('success', __('Updated Successfully'));
        return redirect()->route('user-currency-index', getParam());
    }

    public function delete(Request $request)
    {
        $currency = UserCurrency::findOrFail($request->currency_id);
        if ($currency->is_default == 1) {
            Session::flash('warning', __('Cannot delete the default currency'));
            return redirect()->route('user-currency-index', getParam());
        }
        $currency->delete();

        Session::flash('success', __('Deleted successfully'));
        return redirect()->route('user-currency-index', getParam());
    }
}
