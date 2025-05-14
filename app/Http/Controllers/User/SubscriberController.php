<?php

namespace App\Http\Controllers\User;

use Session;
use Illuminate\Http\Request;
use App\Models\User\BasicSetting;
use App\Models\BasicExtended as AdminBasicExtended;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\User\UserNewsletterSubscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;
        $data['subscs'] = UserNewsletterSubscriber::where('user_id', Auth::guard('web')->user()->id)
            ->when($term, function ($query, $term) {
                return $query->where('email', 'LIKE', '%' . $term . '%');
            })->orderBy('id', 'DESC')->paginate(10);
        return view('user.subscribers.index', $data);
    }

    //Usersubscribe

    public function Usersubscribe($domain, Request $request)
    {
        $user = getUser();
        if ($user) {
            $user_id = $user->id;
        } else {
            return response()->json([
                'success' => __('Something went wrong')
            ], 200);
        }

        $rules = [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($user_id) {
                    $subcriber = UserNewsletterSubscriber::where([['user_id', $user_id], ['email', $value]])->count();
                    if ($subcriber > 0) {
                        return $fail(__('The email address has already been taken'));
                    }
                }
            ]
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->getMessageBag()], 400);
        }
        $user = getUser();
        $id = $user->id;
        $subsc = new UserNewsletterSubscriber();
        $subsc->email = $request->email;
        $subsc->user_id = $id;
        $subsc->save();
        return response()->json([
            'success' => __('You have successfully subscribed to our newsletter')
        ], 200);
    }

    public function mailsubscriber()
    {
        return view('user.subscribers.mail');
    }

    public function getMailInformation()
    {
        $data['info'] = BasicSetting::where('user_id', Auth::guard('web')->user()->id)->select('email', 'from_name')->first();
        return view('user.subscribers.mail-information', $data);
    }

    public function storeMailInformation(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'from_name' => 'required'
        ], [
            'email.required' => __('The email field is required'),
            'from_name.required' => __('The from name field is required')
        ]);
        $info = \App\Models\User\BasicSetting::where('user_id', Auth::guard('web')->user()->id)->first();
        $info->email = $request->email;
        $info->from_name = $request->from_name;
        $info->save();
        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function subscsendmail(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);

        $sub = $request->subject;
        $msg = $request->message;

        $subscs = UserNewsletterSubscriber::all();
        $be = AdminBasicExtended::first();

        /******** Send mail to user ********/
        $data = [];
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_port'] = $be->smtp_port;
        $data['encryption'] = $be->encryption;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;

        //mail info in array
        $data['from_mail'] = $be->from_mail;

        $data['subject'] = $sub;
        $data['body'] = $msg;

        foreach ($subscs as $key => $subsc) {
            $data['recipient'] = $subsc->email;
            BasicMailer::sendMail($data);
        }
        Session::flash('success', __('The mail has been sent successfully'));
        return back();
    }


    public function delete(Request $request)
    {
        UserNewsletterSubscriber::findOrFail($request->subscriber_id)->delete();
        Session::flash('success', __('Deleted successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            UserNewsletterSubscriber::findOrFail($id)->delete();
        }
        Session::flash('success', __('Deleted successfully'));
        return "success";
    }
}
