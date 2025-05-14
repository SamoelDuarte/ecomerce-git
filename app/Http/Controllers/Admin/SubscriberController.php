<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\Subscriber;
use App\Models\BasicExtended;
use Session;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->term;
        $data['subscs'] = Subscriber::when($term, function ($query, $term) {
            return $query->where('email', 'LIKE', '%' . $term . '%');
        })->orderBy('id', 'DESC')->paginate(10);

        return view('admin.subscribers.index', $data);
    }

    public function mailsubscriber()
    {
        return view('admin.subscribers.mail');
    }

    public function subscsendmail(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required'
        ]);
        $sub = $request->subject;
        $msg = $request->message;

        $subscs = Subscriber::get();
        $be = BasicExtended::first();
        $data = [];
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_port'] = $be->smtp_port;
        $data['encryption'] = $be->encryption;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;

        //mail be in array
        $data['from_mail'] = $be->from_mail;

        $data['subject'] = $sub;
        $data['body'] = $msg;

        // Send Mail
        if ($be->is_smtp == 1) {
            foreach ($subscs as $key => $subsc) {
                $data['recipient'] = $subsc->email;
                BasicMailer::sendMail($data);
            }
            Session::flash('success', __('The email has been successfully sent'));
        } else {
            Session::flash('success', __('The email could not be sent'));
        }
        return back();
    }


    public function delete(Request $request)
    {
        $subscriber = Subscriber::findOrFail($request->subscriber_id);
        $subscriber->delete();
        Session::flash('success', __('Deleted Successfully'));
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $subscriber = Subscriber::findOrFail($id);
            $subscriber->delete();
        }
        Session::flash('success', __('Deleted Successfully'));
        return "success";
    }
}
