<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Models\User\UserHeading;
use App\Models\User\UserPageContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HeadingController extends Controller
{
    public function index(Request $request)
    {
        $authId = Auth::guard('web')->user()->id;
        $uLang = Language::where('code', $request->language)->where('user_id', $authId)->first();
        $userCurrentLang = $uLang->id;

        $data['heading'] = UserHeading::where('user_id', $authId)->where('language_id', $userCurrentLang)->first();

        if (!empty($request->language)) {
            $data['language'] = Language::where('code', $request->language)->where('user_id', $authId)->firstOrFail();
        }
        $data['decodedHeadings'] = isset($data['heading']->custom_page_heading) ? json_decode($data['heading']->custom_page_heading, true) : '';

        $data['pages'] = UserPageContent::where([['user_id', $authId], ['language_id', $userCurrentLang]])->select('title', 'id')->get();

        $data['u_langs'] = Language::where('user_id', $authId)->get();
        return view('user.heading.index', $data);
    }

    public function update(Request $request)
    {
        $data = [
            'shop_page' => $request->shop_page,
            'blog_page' => $request->blog_page,
            'contact_page' => $request->contact_page,
            'about_page' => $request->about_page,
            'compare_page' => $request->compare_page,
            'wishlist_page' => $request->wishlist_page,
            'cart_page' => $request->cart_page,
            'login_page' => $request->login_page,
            'signup_page' => $request->signup_page,
            'forget_password_page' => $request->forget_password_page,
            'dashboard_page' => $request->dashboard_page,
            'orders_page' => $request->orders_page,
            'edit_profile_page' => $request->edit_profile_page,
            'billing_details_page' => $request->billing_details_page,
            'shipping_details_page' => $request->shipping_details_page,
            'change_password_page' => $request->change_password_page,
            'custom_page_heading'=>$request->custom_page_heading,
            'not_found_page'=>$request->not_found_page,
            'user_id' => Auth::guard('web')->user()->id,
            'language_id' => $request->language_id,
        ];

        // Update or insert into `user_headings` table
        UserHeading::updateOrInsert(
            [
                'user_id' => Auth::guard('web')->user()->id,
                'language_id' => $request->language_id,
            ],
            $data
        );
        Session::flash('success', __('Updated Successfully'));
        return "success";
    }
}
