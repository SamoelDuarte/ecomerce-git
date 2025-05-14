<?php

use App\Http\Helpers\UserPermissionHelper;
use App\Models\CustomerWishList;
use App\Models\Language;
use App\Models\Page;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\User\ProductVariation;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserShopSetting;
use App\Models\User\UserItemContent;
use App\Models\User\UserItemReview;
use App\Models\User\UserPageContent;
use App\Models\User\UserPaymentGeteway;
use Carbon\Carbon;


if (!function_exists('truncateString')) {
    function truncateString($string, $maxLength)
    {
        return strlen($string) > $maxLength ? mb_substr($string, 0, $maxLength, 'UTF-8') . '...' : $string;
    }
}


if (!function_exists('setEnvironmentValue')) {
    function setEnvironmentValue(array $values)
    {

        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {

                $str .= "\n"; // In case the searched variable is in the last line without \n
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) return false;
        return true;
    }
}


if (!function_exists('replaceBaseUrl')) {
    function replaceBaseUrl($html)
    {
        $startDelimiter = 'src="';
        $endDelimiter = '/assets/front/img/summernote';
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;
        while (false !== ($contentStart = strpos($html, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($html, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $html = substr_replace($html, url('/'), $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $html;
    }
}

if (!function_exists('convertUtf8')) {
    function convertUtf8($value)
    {
        return mb_detect_encoding($value, mb_detect_order(), true) === 'UTF-8' ? $value : mb_convert_encoding($value, 'UTF-8');
    }
}

if (!function_exists('make_slug')) {
    function make_slug($string)
    {
        $slug = preg_replace('/\s+/u', '-', trim($string));
        $slug = str_replace("/", "", $slug);
        $slug = str_replace("?", "", $slug);
        $slug = str_replace("%", "", $slug);
        $slug = str_replace("&", "-", $slug);
        return mb_strtolower($slug, 'UTF-8');
    }
}

if (!function_exists('make_input_name')) {
    function make_input_name($string)
    {
        return preg_replace('/\s+/u', '_', trim($string));
    }
}

if (!function_exists('hasCategory')) {
    function hasCategory($version)
    {
        if (strpos($version, "no_category") !== false) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('isDark')) {
    function isDark($version)
    {
        if (strpos($version, "dark") !== false) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('slug_create')) {
    function slug_create($val)
    {
        $slug = preg_replace('/\s+/u', '-', trim($val));
        $slug = str_replace("/", "", $slug);
        $slug = str_replace("?", "", $slug);
        return mb_strtolower($slug, 'UTF-8');
    }
}

if (!function_exists('hex2rgb')) {
    function hex2rgb($colour)
    {
        if ($colour[0] == '#') {
            $colour = substr($colour, 1);
        }
        if (strlen($colour) == 6) {
            list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
        } elseif (strlen($colour) == 3) {
            list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
        } else {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array('red' => $r, 'green' => $g, 'blue' => $b);
    }
}

if (!function_exists('getHref')) {
    function getHref($link)
    {
        $href = "#";

        if ($link["type"] == 'home') {
            $href = route('front.index');
        } else if ($link["type"] == 'profiles') {
            $href = route('front.user.view');
        } else if ($link["type"] == 'listings') {
            $href = route('front.user.view');
        } else if ($link["type"] == 'pricing') {
            $href = route('front.pricing');
        } else if ($link["type"] == 'faq') {
            $href = route('front.faq.view');
        } else if ($link["type"] == 'blog') {
            $href = route('front.blogs');
        } else if ($link["type"] == 'contact') {
            $href = route('front.contact');
        } else if ($link["type"] == 'templates') {
            $href = route('front.templates.view');
        } else if ($link["type"] == 'about') {
            $href = route('front.about');
        } else if ($link["type"] == 'custom') {
            if (empty($link["href"])) {
                $href = "#";
            } else {
                $href = $link["href"];
            }
        } else {
            $pageid = (int) $link["type"];
            $page = Page::find($pageid);
            if (!empty($page)) {
                $href = route('front.dynamicPage', [$page->slug]);
            } else {
                $href = "#";
            }
        }

        return $href;
    }
}

if (!function_exists('create_menu')) {
    function create_menu($arr)
    {
        echo '<ul class="sub-menu">';

        foreach ($arr["children"] as $el) {

            // determine if the class is 'submenus' or not
            $class = 'class="nav-item"';
            if (array_key_exists("children", $el)) {
                $class = 'class="nav-item submenus"';
            }
            // determine the href
            $href = getHref($el);

            echo '<li ' . $class . '>';
            echo '<a  href="' . $href . '" target="' . $el["target"] . '">' . $el["text"] . '</a>';
            if (array_key_exists("children", $el)) {
                create_menu($el);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}

if (!function_exists('format_price')) {

    function format_price($value): string
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()
                ->get('lang'))
                ->first();
        } else {
            $currentLang = Language::where('is_default', 1)
                ->first();
        }
        $bex = $currentLang->basic_extended;
        if ($bex->base_currency_symbol_position == 'left') {
            return $bex->base_currency_symbol . $value;
        } else {
            return  $value . $bex->base_currency_symbol;
        }
    }
}


// if (!function_exists('currency_converter')) {
//     function currency_converter($value): string
//     {
//         if (Session::has('myfatoorah_user')) {
//             $user = Session::get('myfatoorah_user');
//         } else {
//             $user = getUser();
//         }

//         if (session()->has('user_curr')) {
//             $userCurrentCurr = UserCurrency::where('id', session()->get('user_curr'))->select('id', 'value')->first();

//             if (empty($userCurrentCurr)) {
//                 $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->select('id', 'value')->first();
//                 session()->put('user_curr', $userCurrentCurr->id);
//             }
//         } else {
//             $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->select('value', 'id')->first();
//         }
//         $userDefaultCurrency = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->select('id', 'value')->first();

//         if ($userDefaultCurrency->id != session()->get('user_curr')) {
//             $price = $value * $userCurrentCurr->value;
//         } else {
//             $price = $value;
//         }
//         return number_format($price, 2, '.', '');
//     }
// }
if (!function_exists('currency_converter')) {
    function currency_converter($value): string
    {
        $userCurrentCurr = app('userCurrentCurr');
        $userDefaultCurrency = app('userDefaultCurrency');

        if ($userDefaultCurrency->id != session()->get('user_curr')) {
            $price = $value * $userCurrentCurr->value;
        } else {
            $price = $value;
        }
        return number_format($price, 2, '.', '');
    }
}

if (!function_exists('currency_converter_shipping')) {

    function currency_converter_shipping($value, $shipping_id): string
    {
        // if (Session::has('myfatoorah_user')) {
        //     $user = Session::get('myfatoorah_user');
        // } else {
        //     $user = getUser();
        // }
        // if (session()->has('user_curr')) {
        //     $userCurrentCurr = UserCurrency::where('id', session()->get('user_curr'))->first();

        //     if (empty($userCurrentCurr)) {
        //         $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
        //         session()->put('user_curr', $userCurrentCurr->id);
        //     }
        // } else {
        //     $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
        // }

        // $userDefaultCurrency = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->select('id')->first();
        $userCurrentCurr = app('userCurrentCurr');
        $userDefaultCurrency = app('userDefaultCurrency');
        if ($userDefaultCurrency->id != session()->get('user_curr')) {
            $price = $value * $userCurrentCurr->value;
        } else {
            $price = $value;
        }
        return round($price, 2);
    }
}

if (!function_exists('change_curreny_value')) {
    function change_curreny_value($value, $id, $previous_currency_id)
    {
        $currency = UserCurrency::where('id', $id)->first();
        $previous_currency = UserCurrency::where('id', $previous_currency_id)->first();

        //if selected currency and current currency not equal
        if ($currency->id != $previous_currency_id) {
            if ($previous_currency->is_default == 1) {
                $price = $value * $currency->value;
            } else {
                $price = $value / $previous_currency->value;
            }
        } else {
            $price = $value;
        }
        return round($price, 2);
    }
}

if (!function_exists('currency_sign')) {

    function currency_sign(): string
    {
        $user = getUser();
        if (session()->has('user_curr')) {
            $userCurrentCurr = UserCurrency::where('id', session()->get('user_curr'))->first();

            if (empty($userCurrentCurr)) {
                $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_curr', $userCurrentCurr->id);
            }
        } else {
            $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $curr_sign = $userCurrentCurr->symbol;
        return $curr_sign;
    }
}

if (!function_exists('currency_value')) {

    function currency_value(): string
    {
        $user = getUser();
        if (session()->has('user_curr')) {
            $userCurrentCurr = UserCurrency::where('id', session()->get('user_curr'))->first();

            if (empty($userCurrentCurr)) {
                $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_curr', $userCurrentCurr->id);
            }
        } else {
            $userCurrentCurr = UserCurrency::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $curr_value = $userCurrentCurr->value;
        return $curr_value;
    }
}

if (!function_exists('currency_converter_user')) {

    function currency_converter_user($value, $currency_id): string
    {
        if (empty($value)) {
            $value = 0;
        }
        $data = UserCurrency::where('is_default', 1)->where('user_id',  Auth::guard('web')->user()->id)->first();
        $userCurrentCurrID = $data->id;
        $userCurrentCurrValue = $data->value;

        $order_curr  = UserCurrency::where('id', $currency_id)->first();

        if ($currency_id != $userCurrentCurrID) {
            $price = ($value / $order_curr->value) * $userCurrentCurrValue;
            $price_new = number_format($price, 2);
        } else {
            $price_new = $value;
        }

        return $price_new;
    }
}

if (!function_exists('currency_sign_user')) {

    function currency_sign_user(): string
    {
        $data = UserCurrency::where('is_default', 1)->where('user_id',  Auth::guard('web')->user()->id)->first();
        $sign = $data->symbol;
        return $sign;
    }
}
if (!function_exists('user_currency')) {

    function user_currency($id)
    {
        $user_id = getUser()->id;
        $data = UserCurrency::where('id', $id)->select('symbol', 'symbol_position', 'user_id')->first();

        if (is_null($data) || $data->user_id != $user_id) {
            $data = UserCurrency::where([['is_default', 1], ['user_id', $user_id]])->select('symbol', 'symbol_position', 'user_id')->first();
        }
        return $data;
    }
}
if (!function_exists('symbolPrice')) {
    function symbolPrice($position, $symbol, $price)
    {
        if ($position == 'left') {
            $value = $symbol . $price;
        } else {
            $value = $price . $symbol;
        }

        return $value;
    }
}

if (!function_exists('textPrice')) {
    function textPrice($position, $text, $price)
    {
        if ($position == 'left') {
            $value = $text . ' ' . $price;
        } else {
            $value = $price . ' ' . $text;
        }

        return $value;
    }
}

if (!function_exists('currencyPrice')) {

    function currencyPrice($currency_id, $price)
    {
        $currency = UserCurrency::where('id', $currency_id)->first();
        if ($currency) {
            if ($currency->symbol_position == 'left') {
                $value = $currency->symbol . $price;
            } else {
                $value = $price . $currency->symbol;
            }
            return $value;
        }
    }
}

if (!function_exists('currencyTextPrice')) {

    function currencyTextPrice($currency_id, $price)
    {
        $currency = UserCurrency::where('id', $currency_id)->first();
        if ($currency) {
            if ($currency->text_position == 'left') {
                $value = $currency->text . ' ' . $price;
            } else {
                $value = $price . ' ' . $currency->text;
            }
            return $value;
        }
    }
}

if (!function_exists('userSymbolPrice')) {

    function userSymbolPrice($price, $position, $symbol)
    {
        if (is_null($price)) {
            $price = 0;
        }
        if ($position == 'left') {
            $value = $symbol . $price;
        } else {
            $value = $price . $symbol;
        }
        return $value;
    }
}



if (!function_exists('getUserHref')) {
    function getUserHref($link, $lang_id = null)
    {
        $href = "#";
        if ($link["type"] == 'home') {
            $href = route('front.user.detail.view', getParam());
        } else if ($link["type"] == 'blog') {
            $href = route('front.user.blogs', getParam());
        } else if ($link["type"] == 'contact') {
            $href = route('front.user.contact', getParam());
        } else if ($link["type"] == 'about') {
            $href = route('front.user.about', getParam());
        } else if ($link["type"] == 'faq') {
            $href = route('front.user.faq', getParam());
        } else if ($link["type"] == 'shop') {
            $href = route('front.user.shop', getParam());
        } else if ($link["type"] == 'custom') {
            if (empty($link["href"])) {
                $href = "#";
            } else {
                $href = $link["href"];
            }
        } else {
            $pageid = (int)$link["type"];
            $page = UserPageContent::where([['page_id', $pageid], ['language_id', $lang_id]])->first();
            if (!empty($page)) {
                $href = route('front.user.custom.page', [getParam(), $page->slug]);
            } else {
                $href = "#";
            }
        }
        return $href;
    }
}

if (!function_exists('currency_converter_customer')) {

    function currency_converter_customer($value, $order_currency_id): string
    {
        if (empty($value)) {
            $value = 0;
        }

        $data = UserCurrency::find(session()->get('user_curr'));
        $userCurrentCurrID = $data->id;
        $userCurrentCurrValue = $data->value;

        $order_curr  = UserCurrency::where('id', $order_currency_id)->first();

        if ($order_currency_id != $userCurrentCurrID) {
            $price = ($value / $order_curr->value) * $userCurrentCurrValue;
            $price_new = round($price, 2);
        } else {
            $price_new = $value;
        }

        return $price_new;
    }
}

if (!function_exists('reviewCount')) {

    function reviewCount($id)
    {
        $data = UserItemReview::where('item_id', $id)->count();
        return $data;
    }
}


if (!function_exists('getParam')) {

    function getParam()
    {
        $parsedUrl = parse_url(url()->current());
        $host = str_replace("www.", "", $parsedUrl['host']);

        // if it is path based URL, then return {username}
        if (strpos($host, env('WEBSITE_HOST')) !== false && $host == env('WEBSITE_HOST')) {
            $path = explode('/', $parsedUrl['path']);
            return $path[1];
        }

        // if it is a subdomain / custom domain , then return the host (username.domain.ext / custom_domain.ext)
        return $host;
    }
}

// checks if 'current package has subdomain ?'

if (!function_exists('cPackageHasSubdomain')) {
    function cPackageHasSubdomain($user)
    {
        $currPackageFeatures = UserPermissionHelper::packagePermission($user->id);
        $currPackageFeatures = json_decode($currPackageFeatures, true);

        // if the current package does not contain subdomain
        if (empty($currPackageFeatures) || !is_array($currPackageFeatures) || !in_array('Subdomain', $currPackageFeatures)) {
            return false;
        }
        return true;
    }
}


// checks if 'current package has custom domain ?'
if (!function_exists('cPackageHasCdomain')) {
    function cPackageHasCdomain($user)
    {
        $currPackageFeatures = UserPermissionHelper::packagePermission($user->id);
        $currPackageFeatures = json_decode($currPackageFeatures, true);

        if (empty($currPackageFeatures) || !is_array($currPackageFeatures) || !in_array('Custom Domain', $currPackageFeatures)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('getCdomain')) {

    function getCdomain($user)
    {
        $cdomains = $user->custom_domains()->where('status', 1);
        return $cdomains->count() > 0 ? $cdomains->orderBy('id', 'DESC')->first()->requested_domain : false;
    }
}



if (!function_exists('getUser')) {

    function getUser()
    {

        $parsedUrl = parse_url(url()->current());

        $host =  $parsedUrl['host'];

        // if the current URL contains the website domain
        if (strpos($host, env('WEBSITE_HOST')) !== false) {
            $host = str_replace('www.', '', $host);
            // if current URL is a path based URL
            if ($host == env('WEBSITE_HOST')) {
                $path = explode('/', $parsedUrl['path']);
                $username = $path[1];
            }
            // if the current URL is a subdomain
            else {
                $hostArr = explode('.', $host);
                $username = $hostArr[0];
            }


            if (($host == $username . '.' . env('WEBSITE_HOST')) || ($host . '/' . $username == env('WEBSITE_HOST') . '/' . $username)) {
                $user = User::where('username', $username)
                    ->where('online_status', 1)
                    ->where('status', 1)
                    ->whereHas('memberships', function ($q) {
                        $q->where('status', '=', 1)
                            ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                            ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
                    })
                    ->firstOrFail();


                // if the current url is a subdomain
                if ($host != env('WEBSITE_HOST')) {
                    if (!cPackageHasSubdomain($user)) {
                        return view('errors.404');
                    }
                }

                return $user;
            }
        }



        // Always include 'www.' at the begining of host
        if (substr($host, 0, 4) == 'www.') {
            $host = $host;
        } else {
            $host = 'www.' . $host;
        }

        $user = User::where('online_status', 1)
            ->where('status', 1)
            ->whereHas('user_custom_domains', function ($q) use ($host) {
                $q->where('status', '=', 1)
                    ->where(function ($query) use ($host) {
                        $query->where('requested_domain', '=', $host)
                            ->orWhere('requested_domain', '=', str_replace("www.", "", $host));
                    });
                // fetch the custom domain , if it matches 'with www.' URL or 'without www.' URL
            })
            ->whereHas('memberships', function ($q) {
                $q->where('status', '=', 1)
                    ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
                    ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'));
            })->firstOrFail();

        if (!cPackageHasCdomain($user)) {
            return view('errors.404');
        }



        return $user;
    }
}

if (!function_exists('cartTotal')) {
    function cartTotal()
    {
        $total = 0;
        if (session()->has('cart') && !empty(session()->get('cart'))) {
            $cart = session()->get('cart');
            $user_id = getUser()->id;

            if (!is_null($cart) && is_array($cart)) {
                $cart = array_filter($cart, function ($item) use ($user_id) {
                    return $item['user_id'] == $user_id;
                });
                foreach ($cart as $key => $cartItem) {
                    $total += $cartItem['total'];
                }
            }
        }

        return round($total, 2);
    }
}

if (!function_exists('flashAmountStatus')) {
    function flashAmountStatus($porduct_id, $current_price)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s'); // Including seconds
        $product = DB::table('user_items')
            ->where([['user_items.flash', 1], ['id', $porduct_id]])
            ->where(function ($query) use ($now) {
                // 12-hour format handling
                $query->orWhere([
                    [DB::raw('CONCAT(user_items.start_date, " ", STR_TO_DATE(user_items.start_time, "%h:%i %p"))'), '<=', $now],
                    [DB::raw('CONCAT(user_items.end_date, " ", STR_TO_DATE(user_items.end_time, "%h:%i %p"))'), '>=', $now],
                ]);

                // 24-hour format handling
                $query->orWhere([
                    [DB::raw('CONCAT(user_items.start_date, " ", user_items.start_time)'), '<=', $now],
                    [DB::raw('CONCAT(user_items.end_date, " ", user_items.end_time)'), '>=', $now],
                ]);
            })->select('current_price', 'flash_amount')->first();



        if ($product) {
            $amount = $product->current_price - $product->current_price * ($product->flash_amount / 100);
            $data = [
                'amount' => $amount,
                'status' => true,
            ];
        } else {
            $amount = $current_price;
            $data = [
                'amount' => $amount,
                'status' => false,
            ];
        }
        return $data;
    }
}

if (!function_exists('cartSubTotal')) {
    function cartSubTotal()
    {
        $coupon = session()->has('user_coupon') && !empty(session()->get('user_coupon')) ? session()->get('user_coupon') : 0;
        $cartTotal = cartTotal();
        $subTotal = $cartTotal - $coupon;

        return round($subTotal, 2);
    }
}

if (!function_exists('onlyDigitalItemsInCart')) {
    function onlyDigitalItemsInCart()
    {
        $cart = session()->get('cart', []);
        if (!empty($cart)) {
            foreach ($cart as $key => $cartItem) {
                $item = UserItem::findorFail($cartItem["id"]);
                if ($item->type == 'digital') {
                    return true;
                }
            }
        }
        return false;
    }
}



if (!function_exists('onlyDigitalItems')) {
    function onlyDigitalItems($order)
    {

        $oitems = $order->orderitems;
        foreach ($oitems as $key => $oitem) {

            if ($oitem->item->type != 'digital') {
                return false;
            }
        }

        return true;
    }
}
if (!function_exists('tax')) {
    function tax()
    {
        if (Session::has('myfatoorah_user')) {
            $user = Session::get('myfatoorah_user');
        } else {
            $user = getUser();
        }
        $bex = UserShopSetting::where('user_id', $user->id)->first();
        $tax = $bex->tax;
        if (session()->has('cart') && !empty(session()->get('cart'))) {
            $tax = (cartSubTotal() * $tax) / 100;
        }

        return round($tax, 2);
    }
}
if (!function_exists('tax_percentage')) {
    function tax_percentage()
    {
        if (Session::has('myfatoorah_user')) {
            $user = Session::get('myfatoorah_user');
        } else {
            $user = getUser();
        }
        $bex = UserShopSetting::where('user_id', $user->id)->first();
        return $bex->tax;
    }
}


if (!function_exists('coupon')) {
    function coupon()
    {
        return session()->has('coupon') && !empty(session()->get('coupon')) ? round(session()->get('coupon'), 2) : 0.00;
    }
}


if (!function_exists('detailsUrl')) {

    function detailsUrl($user)
    {
        return '//' . env('WEBSITE_HOST') . '/' . $user->username;
    }
}

if (!function_exists('ProductCountByCategory')) {

    function ProductCountByCategory($language_id, $category_id)
    {
        return UserItemContent::where([['language_id', $language_id], ['category_id', $category_id]])->count();
    }
}
if (!function_exists('hexToRgba')) {

    function hexToRgba($hex, $alpha = .5)
    {
        // Remove the hash at the start if it's there
        $hex = ltrim($hex, '#');

        // Parse the hex color
        if (strlen($hex) == 6) {
            list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
        } elseif (strlen($hex) == 3) {
            list($r, $g, $b) = sscanf($hex, "%1x%1x%1x");
            $r = $r * 17;
            $g = $g * 17;
            $b = $b * 17;
        } else {
            return '10, 71, 46';
        }

        // Ensure alpha is between 0 and 1
        $alpha = min(max($alpha, 0), 1);

        // Return the rgba color code
        return "$r, $g, $b";
    }
}

if (!function_exists('paytabInfo')) {
    function paytabInfo($type, $user_id = null)
    {
        if ($type == 'user') {
            $paytabs = UserPaymentGeteway::where([['user_id', $user_id], ['keyword', 'paytabs']])->first();
        } else {
            $paytabs = PaymentGateway::where('keyword', 'paytabs')->first();
        }
        $paytabsInfo = json_decode($paytabs->information, true);
        if ($paytabsInfo['country'] == 'global') {
            $currency = 'USD';
        } elseif ($paytabsInfo['country'] == 'sa') {
            $currency = 'SAR';
        } elseif ($paytabsInfo['country'] == 'uae') {
            $currency = 'AED';
        } elseif ($paytabsInfo['country'] == 'egypt') {
            $currency = 'EGP';
        } elseif ($paytabsInfo['country'] == 'oman') {
            $currency = 'OMR';
        } elseif ($paytabsInfo['country'] == 'jordan') {
            $currency = 'JOD';
        } elseif ($paytabsInfo['country'] == 'iraq') {
            $currency = 'IQD';
        } else {
            $currency = 'USD';
        }
        return [
            'server_key' => $paytabsInfo['server_key'],
            'profile_id' => $paytabsInfo['profile_id'],
            'url'        => $paytabsInfo['api_endpoint'],
            'currency'   => $currency,
        ];
    }


    function check_variation($item_id)
    {
        $product_variations = ProductVariation::where('item_id', $item_id)->count();
        return $product_variations;
    }
}

if (!function_exists('detectTextDirection')) {
    function detectTextDirection($text)
    {
        $length = mb_strlen($text, 'UTF-8');
        $rtlCount = 0;
        $ltrCount = 0;

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            $direction = IntlChar::charDirection($char);

            if (
                $direction == IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT
                || $direction == IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_ARABIC
                || $direction == IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_EMBEDDING
                || $direction == IntlChar::CHAR_DIRECTION_RIGHT_TO_LEFT_OVERRIDE
            ) {
                $rtlCount++;
            } else {
                $ltrCount++;
            }
        }

        if ($rtlCount > $ltrCount) {
            return 'rtl'; // Right-to-left
        } elseif ($ltrCount > $rtlCount) {
            return 'ltr'; // Left-to-right
        } else {
            return 'rtl'; // If both counts are equal, or if text is empty
        }
    }
}

if (!function_exists('flasSaleActive')) {
    function flasSaleActive($end_date, $end_time)
    {
        $date = Carbon::parse($end_date . ' ' . $end_time);
        if ($date->isPast()) {
            return 'deactive';
        } else {
            return 'active';
        }
    }
}
if (!function_exists('VariationStock')) {
    function VariationStock($item_id)
    {
        $product_variations = App\Models\User\ProductVariation::where([
            ['item_id', $item_id],
        ])->get();
        $varitaion_stock = [];
        if (count($product_variations) > 0) {
            $varitaion_stock['has_variation'] = 'yes';
            foreach ($product_variations as $product_variation) {
                $product_variation_options = App\Models\User\ProductVariantOption::where(
                    'product_variation_id',
                    $product_variation->id,
                )->get();
                foreach ($product_variation_options as $product_variation_option) {
                    if ($product_variation_option->stock > 0) {
                        $varitaion_stock['stock'] = 'yes';
                        break;
                    } else {
                        $varitaion_stock['stock'] = 'no';
                        continue;
                    }
                }
            }
        } else {
            $varitaion_stock['has_variation'] = 'no';
            $varitaion_stock['stock'] = 'no';
        }
        return $varitaion_stock;
    }
}

if (!function_exists('checkWishList')) {
    function checkWishList($item_id, $customer_id)
    {
        $check = CustomerWishList::where([['customer_id', $customer_id], ['item_id', $item_id]])->first();
        if ($check) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('canonicalUrl')) {
    function canonicalUrl()
    {
        $user = getUser();

        if ($user->subdomain_status == 1) {
            $domain = getParam() . '.' . env('WEBSITE_HOST');
        } else {
            $domain = env('WEBSITE_HOST');
        }

        // check if the user has a custom domain
        if (getCdomain($user) !== false) {
            $domain = getCdomain($user);
        }

        if (!preg_match('/^https?:\/\//', $domain)) {
            // current request's scheme (http or https) to the domain
            $scheme = request()->getScheme() . '://';
            $domain = $scheme . ltrim($domain, '/');
        }

        //current path and decode URL-encoded characters
        $path = urldecode(request()->path());

        if ($user->subdomain_status == 1 || getCdomain($user) !== false) {
            $subdomain = getParam();
            $pathSegments = explode('/', $path);
            if ($pathSegments[0] === $subdomain) {
                array_shift($pathSegments);
                $path = implode('/', $pathSegments);
            }
        }

        $path = str_replace(['–', ',', ' '], '-', $path);
        $path = preg_replace('/-+/', '-', $path);
        $path = strtolower($path);

        $canonicalUrl = rtrim($domain, '/') . '/' . ltrim($path, '/');
        return $canonicalUrl;
    }
}
