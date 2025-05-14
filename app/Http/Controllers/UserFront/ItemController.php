<?php

namespace App\Http\Controllers\UserFront;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\CustomerWishList;
use App\Models\User\BasicSetting;
use App\Models\User\Language;
use App\Models\User\ProductVariantOption;
use App\Models\User\SEO;
use App\Models\User\UserCoupon;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserItemContent;
use App\Models\User\UserOfflineGateway;
use App\Models\User\UserPaymentGeteway;
use App\Models\User\UserShippingCharge;
use App\Models\User\UserShopSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ItemController extends Controller
{
    public function cart($domain)
    {
        if (!Session::has('cart')) {
            return redirect()->route('front.user.detail.view', getParam());
        }
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);

        if (Session::has('cart')) {
            $data['cart'] = Session::get('cart');
        } else {
            $data['cart'] = null;
        }
        $data['totalQty'] = array_sum(array_column($data['cart'], 'qty'));
        $data['totalCart'] = array_sum(array_column($data['cart'], 'total'));

        $userBs = BasicSetting::where('user_id', $user->id)->first();
        $version = $userBs->theme_version;
        if ($version == 'dark') {
            $version = 'default';
        }
        $data['version'] = $version;

        $data['seo'] = SEO::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->first();

        return view('user-front.cart', $data);
    }

    public function addToCart($domain, $id)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $cart = Session::get('cart');

        $user_id = $user->id;
        if (!is_null($cart) && is_array($cart)) {
            $cart = array_filter($cart, function ($item) use ($user_id) {
                return $item['user_id'] == $user_id;
            });
        }

        $data = explode(',,,', $id);
        $id = (int)$data[0];
        $qty = (int)$data[1];
        $total = (float)$data[2];
        $variant = json_decode($data[3], true);


        $item = UserItem::findOrFail($id);
        if ($item->type != 'digital') {
            // validations
            if ($qty < 1) {
                return response()->json(['error' => $keywords['Quantity must be 1 or more than 1'] ?? __('Quantity must be 1 or more than 1')]);
            }

            $totalVari = check_variation($id);

           if ($totalVari != 0 && ($totalVari > count((array) $variant))) {
                return response()->json(['error' => $keywords['You must select a variant'] ?? __('You must select a variant')]);
            }
            if (!$item) {
                abort(404);
            }
        }
        $ckey = uniqid();
        //check product flash amount
        $flash_info = flashAmountStatus($item->id, $item->current_price);
        $product_current_price = $flash_info['amount'];
        //check product flash amount end
        // if cart is empty then this the first product

        if (!$cart) {
            //check stock if not exist cart
            if (empty($variant)) {
                if (((int)$qty > $item->stock) && $item->type != 'digital') {
                    return response()->json(['error' => $keywords['Out of Stock'] ?? __('Out of Stock')]);
                }
            } else {
                foreach ($variant as $vant) {
                    if ((int)$qty > $vant['stock']) {
                        return response()->json(['error' => $vant['name'] . ' ' . $keywords['is Out of Stock'] ?? __('is Out of Stock')]);
                    }
                }
            }
            //check stock if not exist cart end

            $cart = [
                $ckey => [
                    "id" => $id,
                    "user_id" => $user_id,
                    "qty" => (int)$qty,
                    "variations" => $variant,
                    "product_price" => (float) (currency_converter($product_current_price, $item->id)),
                    "total" => $total,
                ]
            ];
            Session::put('cart', $cart);
            return response()->json(['message' => $keywords['Item added to your cart successfully'] ?? __('Item added to your cart successfully')]);
        }

        // if cart not empty then check if this product (with same variation) exist then increment quantity
        foreach ($cart as $key => $cartItem) {
            if ($cartItem["id"] == $id && $variant == $cartItem["variations"]) {

                //check stock if product exist in the cart
                if (empty($variant)) {
                    if (((int)$cart[$key]['qty'] + $qty > $item->stock) && $item->type != 'digital') {
                        return response()->json(['error' => $keywords['Out of Stock'] ?? __('Out of Stock')]);
                    }
                } else {
                    foreach ($variant as $vant) {
                        if ((int)$cart[$key]['qty'] + $qty > $vant['stock']) {
                            return response()->json(['error' => $vant['name'] . ' ' . $keywords['is Out of Stock'] ?? __('is Out of Stock')]);
                        }
                    }
                }
                //check stock if product exist in the cart end

                $cart[$key]['qty'] = (int)$cart[$key]['qty'] + $qty;
                $cart[$key]['total'] = (float)$cart[$key]['total'] + $total;
                Session::put('cart', $cart);
                return response()->json(['message' => $keywords['Item added to your cart successfully'] ?? __('Item added to your cart successfully')]);
            }
        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$ckey] = [
            "id" => $id,
            "user_id" => $user_id,
            "qty" => (int)$qty,
            "variations" => $variant,
            "product_price" => (float)(currency_converter($product_current_price, $item->id)),
            "total" => $total,
        ];
        Session::put('cart', $cart);
        return response()->json(['message' => $keywords['Item added to your cart successfully'] ?? __('Item added to your cart successfully')]);
    }

    public function addToWishlist($domain, $id)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        if (!Auth::guard('customer')->check()) {
            return response()->json(['error' => $keywords['Customer Login required'] ?? __('Customer Login required')]);
        }
        $wishlist = CustomerWishList::where('customer_id', Auth::guard('customer')->user()->id)->where('item_id', $id)->first();
        $data = explode(',,,', $id);
        $id = (int)$data[0];
        // if wishlist is empty then this the first Item for this user
        if (!$wishlist) {
            CustomerWishList::create([
                'customer_id' => Auth::guard('customer')->user()->id,
                'item_id' => $id,
                'user_id' => $user->id,
            ]);
        }
        return response()->json(['message' => $keywords['Item added to your wishlist'] ?? __('Item added to your wishlist')]);
    }
    public function removeToWishlist($domain, $id)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        if (env('DEMO_MODE') == 'active') {
            return response()->json(['message' => 'This is Demo version. You can not change anything.']);
        }
        $data['wishlist'] = CustomerWishList::where('item_id', $id)->delete();
        return response()->json(['status' => 'remove_from_wishlist', 'message' => $keywords['Item removed successfully'] ?? __('Item removed successfully')]);
    }
    public function cartitemremove($doamin, $uid)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);

        if ($uid) {
            $cart = Session::get('cart');
            if (isset($cart[$uid])) {
                unset($cart[$uid]);
                Session::put('cart', $cart);
            }
            $total = 0;
            $count = 0;
            foreach ($cart as $i) {
                $total += $i['product_price'] * $i['qty'];
                $count += $i['qty'];
            }
            $total = round($total, 2);
            return response()->json(['message' => $keywords['Item removed from your cart'] ?? __('Item removed from your cart'), 'count' => $count, 'total' => $total]);
        }
    }
    public function updatecart($doamin, Request $request)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $cart = Session::get('cart');
        $qtys = $request->qty;
        $i = 0;
        $isStockOut = 0;
        $stErrMsg = [];
        /* No need to return if stock out. rather let the loop run and keep the error message in a setErr[ ]. After the foreach done put the $cart in session and return.
        In ajax response foreach the setErr[ ] and print the error messages*/
        foreach ($cart as $cartKey => $cartItem) {

            $total = 0;
            $stErr = 0;

            // calculate total
            $vars = $cartItem["variations"];
            if (!empty($vars)) {
                foreach ($vars as $varKey => $variant) {
                    $stock = ProductVariantOption::where('id', $variant['option_id'])->pluck('stock')->first();

                    if ($stock < (int)$qtys[$i]) {
                        $temp = ($cartItem['name'] . ' : ' . $varKey . ' : ' . $variant['name'] . " ; " . $keywords['stock unavailable'] ?? __('stock unavailable'));

                        array_push($stErrMsg, $temp);
                        $stErr = 1;
                        $isStockOut = 1;
                    }
                    $total += (float)$variant["price"];
                }
            } else {

                $item = UserItem::where('id', $cartItem['id'])->where('type', 'physical')->first();
                if (!empty($item->stock)) {
                    if ($item->stock < $qtys[$i]) {
                        $temp = ($cartItem['name'] . " " . $keywords['stock unavailable'] ?? __('stock unavailable'));
                        array_push($stErrMsg, $temp);
                        $stErr = 1;
                        $isStockOut = 1;
                    }
                }
            }

            if ($stErr == 0) {

                $total += (float)$cartItem["product_price"];
                $total = $total * $qtys[$i];
                $cart[$cartKey]["qty"] = (int)$qtys[$i];
                // save total in the cart item
                $cart[$cartKey]["total"] = $total;
                Session::put('cart', $cart);
            }
            $i++;
        }

        if ($isStockOut == 0) {
            return response()->json(['message' => $keywords['Your cart has been updated'] ?? __('Your cart has been updated')]);
        } else {
            return response()->json($stErrMsg);
        }
    }

    public function checkout_process($domain)
    {
        return redirect()->route('front.user.checkout.final_step', getParam());
    }

    public function checkout($domain, Request $request)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $userShop = UserShopSetting::where('user_id', $user->id)->first();
        if ($userShop->catalog_mode == 1) {
            return back();
        }

        if (!Session::get('cart')) {
            Session::flash('error', $keywords['Your cart is currently empty'] ?? __('Your cart is currently empty'));
            return back();
        }
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $currentLanguage = Common::getUserCurrentLanguage($user->id);
        $data['pageHeading'] = $this->getUserPageHeading($currentLanguage);
        if (Session::has('cart')) {
            //remove cart if any product from other tenant
            $cart = session()->get('cart');
            $user_id = getUser()->id;
            if (!is_null($cart) && is_array($cart)) {
                $cart = array_filter($cart, function ($item) use ($user_id) {
                    return $item['user_id'] == $user_id;
                });
            }
            session()->put('cart', $cart);
            //remove cart if any product from other tenant end

            $data['cart'] = Session::get('cart');
        } else {
            $data['cart'] = null;
        }
        $data['shippings'] = UserShippingCharge::where('user_id', $user->id)->where('language_id', $userCurrentLang->id)->get();
        $data['offlines'] = UserOfflineGateway::where('user_id', $user->id)->get();
        $data['payment_gateways'] = UserPaymentGeteway::where('user_id', $user->id)->where('status', 1)->get();
        $data['discount'] = session()->has('user_coupon') && !empty(session()->get('user_coupon')) ? session()->get('user_coupon') : 0;
        // determining the theme version selected
        $userBs = BasicSetting::where('user_id', $user->id)->first();
        $version = $userBs->theme_version;
        if ($version == 'dark') {
            $version = 'default';
        }
        $data['version'] = $version;

        $data['seo'] = SEO::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->first();
        $data['userShop'] = $userShop;
        $anet = UserPaymentGeteway::where([['keyword', 'authorize.net'], ['user_id', $user->id]])->first();
        if ($anet) {
            $data['anerInfo'] = $anet->convertAutoData();
        } else {
            $data['anerInfo'] = [];
        }
        $stripe = UserPaymentGeteway::where([['keyword', 'stripe'], ['user_id', $user->id]])->first();
        if ($stripe) {
            $data['stripeInfo'] = $stripe->convertAutoData();
        } else {
            $data['stripeInfo'] = [];
        }
        return view('user-front.checkout', $data);
    }

    public function checkoutGuest($domain, Request $request)
    {
        session()->put('prevUrl', url()->previous());
        if (onlyDigitalItemsInCart()) {
            return redirect()->back();
        }
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $userShop = UserShopSetting::where('user_id', $user->id)->first();
        if (@$userShop->catalog_mode == 1) {
            Session::flash('error', $keywords['Guest checkout is currently unavailable. Please sign in or create an account to proceed'] ?? __('Guest checkout is currently unavailable. Please sign in or create an account to proceed'));
            return back();
        }

        if (!Session::get('cart')) {
            Session::flash('error', $keywords['Your cart is currently empty'] ?? __('Your cart is currently empty'));
            return back();
        }

        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        if (Session::has('cart')) {
            //remove cart if any product from other tenant
            $cart = session()->get('cart');
            $user_id = getUser()->id;

            if (!is_null($cart) && is_array($cart)) {
                $cart = array_filter($cart, function ($item) use ($user_id) {
                    return $item['user_id'] == $user_id;
                });
            }
            session()->put('cart', $cart);
            //remove cart if any product from other tenant end
            $data['cart'] = Session::get('cart');
        } else {
            $data['cart'] = null;
        }
        $data['shippings'] = UserShippingCharge::where('user_id', $user->id)->where('language_id', $userCurrentLang->id)->get();
        $data['offlines'] = UserOfflineGateway::where('user_id', $user->id)->get();
        $data['payment_gateways'] = UserPaymentGeteway::where('user_id', $user->id)->where('status', 1)->get();
        $data['discount'] = session()->has('user_coupon') && !empty(session()->get('user_coupon')) ? session()->get('user_coupon') : 0;
        // determining the theme version selected
        $userBs = BasicSetting::where('user_id', $user->id)->first();
        $version = $userBs->theme_version;
        if ($version == 'dark') {
            $version = 'default';
        }
        $data['version'] = $version;

        $data['seo'] = SEO::where('language_id', $userCurrentLang->id)->where('user_id', $user->id)->first();
        $data['userShop'] = $userShop;
        $anet = UserPaymentGeteway::where([['keyword', 'authorize.net'], ['user_id', $user->id]])->first();
        if ($anet) {
            $data['anerInfo'] = $anet->convertAutoData();
        } else {
            $data['anerInfo'] = [];
        }
        $stripe = UserPaymentGeteway::where([['keyword', 'stripe'], ['user_id', $user->id]])->first();
        if ($stripe) {
            $data['stripeInfo'] = $stripe->convertAutoData();
        } else {
            $data['stripeInfo'] = [];
        }
        return view('user-front.checkout', $data);
    }

    public function coupon(Request $request)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        $coupon = UserCoupon::where('code', $request->coupon)->where('user_id', $user->id)->first();
        if (session()->has('user_curr')) {
            $userCurrentCurr = UserCurrency::where('id', session()->get('user_curr'))->first();
            if (empty($userCurrentCurr)) {
                $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_curr', $userCurrentCurr->id);
            }
        } else {
            $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        if (empty($coupon)) {
            return response()->json(['status' => 'error', 'message' => $keywords['The coupon code you entered is not valid. Please check and try again'] ?? __('The coupon code you entered is not valid. Please check and try again')]);
        } else {
            $coupon_currency = $coupon->currency->value;
            $minimum_spend = ($coupon->minimum_spend / $coupon_currency) * $userCurrentCurr->value;
            if (cartTotal() < $minimum_spend) {
                return response()->json(['status' => 'error', 'message' => $keywords['Cart Total must be minimum'] ?? __('Cart Total must be minimum') . " " . $minimum_spend . " " . session()->get('user_curr_sign')]);
            }
            $start = Carbon::parse($coupon->start_date);
            $end = Carbon::parse($coupon->end_date);
            $timeZone = DB::table('user_basic_settings')->where('user_id', $user->id)->value('timezone');
            $today = Carbon::now($timeZone);

            // if coupon is active
            if ($today->greaterThanOrEqualTo($start) && $today->lessThan($end)) {
                $cartTotal = cartTotal();
                $value = $coupon->value;
                $type = $coupon->type;

                if ($type == 'fixed') {
                    $couponAmount = currency_converter($value);
                    if ($couponAmount > cartTotal()) {
                        return response()->json(['status' => 'error', 'message' => $keywords['The coupon discount is greater than your cart total'] ?? __('The coupon discount is greater than your cart total')]);
                    }
                    $couponAmount = currency_converter($value);
                } else {
                    $couponAmount = number_format((($cartTotal * $value) / 100), 2);
                }

                session()->put('user_coupon', round($couponAmount, 2));

                return response()->json(['status' => 'success', 'message' => $keywords['Coupon applied successfully'] ?? __('Coupon applied successfully')]);
            } else {
                return response()->json(['status' => 'error', 'message' => $keywords['The coupon code you entered is not valid'] ?? __('The coupon code you entered is not valid')]);
            }
        }
    }

    public function compare($domain)
    {
        $user = getUser();
        $user_id = $user->id;
        if (session()->has('user_lang')) {

            $data['userCurrentLang'] = Language::where('code', session()->get('user_lang'))->where('user_id', $user_id)->first();
            if (empty($data['userCurrentLang'])) {
                $data['userCurrentLang'] = Language::where('is_default', 1)->where('user_id', $user_id)->first();
            }
        } else {
            $data['userCurrentLang'] = Language::where('is_default', 1)->where('user_id', $user_id)->first();
        }
        $data['pageHeading'] = $this->getUserPageHeading($data['userCurrentLang']);
        $compare = Session::get('compare');
        if (!is_null($compare)) {
            $data['compare'] = array_filter($compare, function ($item) use ($user_id) {
                return $item['user_id'] == $user_id;
            });
        }
        $data['seo'] = SEO::where('language_id', $data['userCurrentLang']->id)->where('user_id', $user->id)->first();

        return view('user-front.compare', $data);
    }

    public function addToCompare($domain, $id)
    {
        $user = getUser();
        $keywords = Common::get_keywords($user->id);
        // Check and set user language
        if (session()->has('user_lang')) {
            $userCurrentLang = Language::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();
            if (empty($userCurrentLang)) {
                $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = Language::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        // Retrieve the current compare list from the session
        $compare = session()->get('compare', []);

        // Generate a unique key for the new comparison item
        $ckey = uniqid();

        // Check if the item is already in the compare list
        foreach ($compare as $key => $compareItem) {
            if ($compareItem["id"] == $id && $compareItem["user_id"] == $user->id) {
                return response()->json(['warning' => $keywords['This item is already in your compare list'] ?? __('This item is already in your compare list')]);
            }
        }

        // Add the new item to the compare list
        $compare[$ckey] = [
            "id" => (int) $id,
            "user_id" => $user->id, // Store the user_id alongside the item id
        ];

        // Save the updated compare list back to the session
        session()->put('compare', $compare);

        return response()->json(['message' => $keywords['Item added to your compare list successfully'] ?? __('Item added to your compare list successfully')]);
    }

    public function compareitemremove($doamin, $uid)
    {
        $user_id = getUser()->id;
        $keywords = Common::get_keywords($user_id);
        if ($uid) {
            $compare = Session::get('compare');
            if (isset($compare[$uid])) {
                unset($compare[$uid]);
                Session::put('compare', $compare);
            }

            return redirect()->route('front.user.compare', getParam())->with(['error' => $keywords['Item removed successfully'] ?? __('Item removed successfully')]);
        }
    }

    public function cartDropdown($domain)
    {
        return view('user-front.partials.cart-dropdown');
    }

    public function cartDropdownCount($domain)
    {
        $user = getUser();
        $user_id = $user->id;
        $cart = Session::get('cart');
        $count = 0;

        if ($cart) {
            if (!is_null($cart) && is_array($cart)) {
                $cart = array_filter($cart, function ($item) use ($user_id) {
                    return $item['user_id'] == $user_id;
                });
            }
            $count = count($cart);
        }
        return response()->json($count);
    }

    public function compareCount($domain)
    {
        $user = getUser();
        $user_id = $user->id;
        $count = 0;
        if (Session::get('compare')) {
            $compare = Session::get('compare');
            if (!is_null($compare) && is_array($compare)) {
                $compare = array_filter($compare, function ($item) use ($user_id) {
                    return $item['user_id'] == $user_id;
                });
            }
            $count = count($compare);
        }
        return response()->json($count);
    }

    public function wishlistCount($domain)
    {
        $count = 0;
        $user = getUser();
        if (!empty($user)) {
            if (Auth::guard('customer')->check()) {
                $count = CustomerWishList::where([['customer_id', Auth::guard('customer')->user()->id], ['user_id', $user->id]])
                    ->count();
            } else {
                $count = 0;
            }
        } else {
            $count = 0;
        }

        return response()->json($count);
    }
}
