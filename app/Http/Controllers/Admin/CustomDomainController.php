<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\MegaMailer;
use App\Models\BasicExtended;
use App\Models\BasicSetting;
use App\Models\User\UserCustomDomain;
use Illuminate\Http\Request;
use Purifier;
use Session;
use Validator;

class CustomDomainController extends Controller
{
    public function texts()
    {
        $data['abe'] = BasicExtended::select('domain_request_success_message', 'cname_record_section_title', 'cname_record_section_text')->first();
        return view('admin.domains.custom-texts', $data);
    }

    public function updateTexts(Request $request)
    {
        $rules = [
            'success_message' => 'required|max:255',
            'cname_record_section_title' => 'required|max:255',
            'cname_record_section_text' => 'required'
        ];
        $request->validate($rules);

        $be = BasicExtended::first();
        $be->domain_request_success_message = clean($request->success_message);
        $be->cname_record_section_title = $request->cname_record_section_title;
        $be->cname_record_section_text = Purifier::clean($request->cname_record_section_text, 'youtube');
        $be->save();

        Session::flash('success', __('Updated Successfully'));
        return back();
    }

    public function index(Request $request)
    {
        $rcDomains = UserCustomDomain::orderBy('id', 'DESC')
            ->when($request->domain, function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->where('current_domain', 'LIKE', '%' . $request->domain . '%')
                        ->orWhere('requested_domain', 'LIKE', '%' . $request->domain . '%');
                });
            })
            ->when($request->username, function ($query) use ($request) {
                return $query->whereHas('user', function ($query) use ($request) {
                    $query->where('username', $request->username);
                });
            });
        if (empty($request->type)) {
            $rcDomains = $rcDomains->paginate(10);
        } elseif ($request->type == 'pending') {
            $rcDomains = $rcDomains->where('status', 0)->paginate(10);
        } elseif ($request->type == 'connected') {
            $rcDomains = $rcDomains->where('status', 1)->paginate(10);
        } elseif ($request->type == 'rejected') {
            $rcDomains = $rcDomains->where('status', 2)->paginate(10);
        } else {
            return view('errors.404');
        }
        $data['rcDomains'] = $rcDomains;
        return view('admin.domains.custom', $data);
    }

    public function status(Request $request)
    {
        $rcDomain = UserCustomDomain::findOrFail($request->domain_id);
        $rcDomain->status = $request->status;
        $rcDomain->save();

        // if the requested domain is connected
        if ($request->status == 1) {
            if (!empty($rcDomain->user)) {
                $user = $rcDomain->user;

                $bs = BasicSetting::firstOrFail();
                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $user->email,
                    'toName' => $user->fname,
                    'username' => $user->username,
                    'requested_domain' => $rcDomain->requested_domain,
                    'previous_domain' => !empty($rcDomain->current_domain) ? $rcDomain->current_domain : 'Not Available',
                    'website_title' => $bs->website_title,
                    'templateType' => 'custom_domain_connected',
                    'type' => 'customDomainConnected'
                ];
                $mailer->mailFromAdmin($data);
            }
        } elseif ($request->status == 2) {
            if (!empty($rcDomain->user)) {
                $user = $rcDomain->user;
                $currDomCount = $user->custom_domains()->where('status', 1)->count();
                if ($currDomCount > 0) {
                    $currDom = $user->custom_domains()->where('status', 1)->orderBy('id', 'DESC')->first()->requested_domain;
                }

                $bs = BasicSetting::firstOrFail();
                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $user->email,
                    'toName' => $user->fname,
                    'username' => $user->username,
                    'requested_domain' => $rcDomain->requested_domain,
                    'current_domain' => !empty($currDom) ? $currDom : 'Not Available',
                    'website_title' => $bs->website_title,
                    'templateType' => 'custom_domain_rejected',
                    'type' => 'customDomainRejected'
                ];
                $mailer->mailFromAdmin($data);
            }
        }
        Session::flash('success', __('Updated Successfully'));
        return back();
    }


    public function mail(Request $request)
    {
        $rules = [
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $be = BasicExtended::first();
        $data = [];
        //add smtp info in array
        $data['smtp_status'] = $be->is_smtp;
        $data['smtp_host'] = $be->smtp_host;
        $data['smtp_username'] = $be->smtp_username;
        $data['smtp_password'] = $be->smtp_password;
        $data['encryption'] = $be->encryption;
        $data['smtp_port'] = $be->smtp_port;

        //mail info in array
        $data['from_mail'] = $be->from_mail;
        $data['recipient'] = $request->email;
        $data['subject'] = $request->subject;
        $data['body'] = nl2br($request->message);

        // Send Mail
        BasicMailer::sendMail($data);
        if ($be->is_smtp == 1) {
            Session::flash('success', __('Mail sent successfully'));
        } else {
            Session::flash('success', __('Mail could not be sent'));
        }
        return "success";
    }

    public function delete(Request $request)
    {
        $domainId = $request->domain_id;
        $cdomain = UserCustomDomain::findOrFail($domainId);
        $cdomain->delete();
        Session::flash('success', __('Custom domain has been deleted successfully'));
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $cdomain = UserCustomDomain::findOrFail($id);
            $cdomain->delete();
        }
        Session::flash('success', __('Custom domains have been deleted successfully'));
        return "success";
    }
}
