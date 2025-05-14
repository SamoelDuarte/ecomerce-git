<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;
use Hash;
use Validator;

class RegisterCustomerController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $term = $request->term;

        $users = Customer::where('user_id', $user_id)
            ->when($term && trim($term) !== '', function ($query, $term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('username', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%');
                });
            })->orderBy('id', 'DESC')->paginate(10);


        return view('user.register_user.index', compact('users'));
    }

    public function view($id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $user = Customer::where([['user_id', $user_id], ['id', $id]])->firstOrFail();
        return view('user.register_user.details', compact('user',));
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|alpha_num|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $user = Customer::where('username', $request['username']);
        if ($user->count() == 0) {
            $user = Customer::create([
                'user_id' => Auth::guard('web')->user()->id,
                'email' => $request['email'],
                'username' => $request['username'],
                'password' => bcrypt($request['password']),
                'status' => 1,
                'email_verified' => 1,
            ]);
        }
        Session::flash('success', __('Created successfully'));
        return "success";
    }

    public function emailStatus(Request $request)
    {
        $user = Customer::findOrFail($request->user_id);
        if ($request->email_verified == 1) {
            $user->update([
                'email_verified' => $request->email_verified,
                'email_verified_at' => Carbon::now(),
            ]);
        } else {
            $user->update([
                'email_verified' => $request->email_verified,
                'email_verified_at' => null,
            ]);
        }


        Session::flash('success', __('Email status updated for') . ' ' . $user->username);
        return back();
    }

    public function userban(Request $request)
    {
        $user = Customer::where('id', $request->user_id)->first();
        $user->status = $request->status;
        $user->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function changePass($id)
    {
        $data['user'] = Customer::findOrFail($id);
        return view('user.register_user.password', $data);
    }

    public function updatePassword(Request $request)
    {

        $messages = [
            'npass.required' => __('New password is required'),
            'cfpass.required' => __('Confirm password is required'),
        ];

        $request->validate([
            'npass' => 'required',
            'cfpass' => 'required',
        ], $messages);


        $user = Customer::findOrFail($request->user_id);
        if ($request->npass == $request->cfpass) {
            $input['password'] = Hash::make($request->npass);
        } else {
            return back()->with('warning', __('Confirm password does not match'));
        }
        $user->update($input);

        Session::flash('success', __('Password update for') . ' ' . $user->username);
        return back();
    }



    public function delete(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $user = Customer::where([['user_id', $user_id], ['id', $request->user_id]])->firstOrFail();
        $user->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        $user_id = Auth::guard('web')->user()->id;
        foreach ($ids as $id) {
            $user = Customer::where([['user_id', $user_id], ['id', $id]])->firstOrFail();
            $user->delete();
        }

        Session::flash('success', __('Deleted successfully'));
        return "success";
    }

    public function secret_login($id)
    {
        $customer = Customer::where('id', $id)->first();
        Auth::guard('customer')->login($customer);
        $user = User::where('id', $customer->user_id)->first();
        return redirect()->route('customer.dashboard', $user->username);
    }
}
