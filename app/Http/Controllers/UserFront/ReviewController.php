<?php

namespace App\Http\Controllers\UserFront;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User\UserItemReview;
use App\Models\User\UserItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Purifier;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('setlang');
    }

    public function reviewsubmit(Request $request)
    {
        $user_id = getUser()->id;
        $keywords = Common::get_keywords($user_id);
        if ($request->review || $request->comment) {
            if (UserItemReview::where('customer_id', Auth::guard('customer')->user()->id)->where('item_id', $request->item_id)->exists()) {
                $exists =  UserItemReview::where('customer_id', Auth::guard('customer')->user()->id)->where('item_id', $request->item_id)->first();
                if ($request->review) {
                    $exists->update([
                        'review' => Purifier::clean($request->review),
                    ]);
                    $avgreview = UserItemReview::where('item_id', $request->item_id)->avg('review');
                    UserItem::find($request->item_id)->update([
                        'rating' => $avgreview
                    ]);
                }
                if ($request->comment) {
                    $exists->update([
                        'comment' => Purifier::clean($request->comment),
                    ]);
                }
                Session::flash('success', $keywords['Updated successfully'] ?? __('Updated successfully'));
                return back();
            } else {
                $input = $request->all();
                $input['comment'] = Purifier::clean($request->comment);
                $input['customer_id'] = Auth::guard('customer')->user()->id;
                $data = new UserItemReview();
                $data->create($input);
                $avgreview = UserItemReview::where('item_id', $request->item_id)->avg('review');
                UserItem::find($request->item_id)->update([
                    'rating' => $avgreview
                ]);
                Session::flash('success', $keywords['Your review has been submitted successfully'] ?? __('Your review has been submitted successfully'));
                return back();
            }
        } else {
            Session::flash('error', $keywords['Review submission was not successful. Please try again'] ?? __('Review submission was not successful. Please try again'));
            return back();
        }
    }

    public function authcheck()
    {
        if (!Auth::guard('customer')->user()) {
            Session::put('link', url()->current());
            return redirect(route('customer.login', getParam()));
        }
    }
}
