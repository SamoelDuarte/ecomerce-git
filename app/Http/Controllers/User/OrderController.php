<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\UserItem;
use App\Models\User\UserOrderItem;
use Auth;
use Session;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function digitalDownload(Request $request)
    {
        $product = UserItem::find($request->product_id);
        $count = UserOrderItem::where('item_id', $request->product_id)->where('customer_id', Auth::guard('customer')->user()->id)->count();
        // if the auth user didn't purchase the item
        if ($count == 0) {
            return back();
        }
        $pathToFile = storage_path('digital_products/') . $product->download_file;
        if (file_exists($pathToFile)) {
            return response()->download($pathToFile);
        } else {
            Session::flash('error', __('No downloadable file exists'));
            return back();
        }
    }
}
