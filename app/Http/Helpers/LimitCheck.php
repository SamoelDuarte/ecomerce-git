<?php

namespace App\Http\Helpers;

use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;

class LimitCheck
{
    public static function current_package($user_id)
    {
        $id = Membership::query()->where([
            ['user_id', '=', $user_id],
            ['status', '=', 1],
            ['start_date', '<=', Carbon::now()->format('Y-m-d')],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->pluck('package_id')->first();

        return $id;
    }
    public static function blogLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('post_limit')->findOrFail($packageId);
        }
        return isset($packageId) && isset($package) ? $package->post_limit : 0;
    }

    public static function itemLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('product_limit')->findOrFail($packageId);
        }
        return isset($packageId) && isset($package) ? $package->product_limit : 0;
    }

    public static function catLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('categories_limit')->findOrFail($packageId);
        }
        return isset($packageId) && isset($package) ? $package->categories_limit : 0;
    }

    public static function subcatLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('subcategories_limit')->findOrFail($packageId);
        }
        return isset($packageId) && isset($package) ? $package->subcategories_limit : 0;
    }

    public static function langLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('language_limit')->findOrFail($packageId);
        }
        return isset($packageId) && isset($package) ? $package->language_limit : 0;
    }

    public static function pageLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('number_of_custom_page')->findOrFail($packageId);
        }
        return isset($packageId) && isset($package) ? $package->number_of_custom_page : 0;
    }

    public static function orderLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('order_limit')->findOrFail($packageId);
        }
        return isset($packageId) && isset($package) ? $package->order_limit : 0;
    }

    /**
     * count all feates from here
     * */
    public static function packageFeaturesCount(int $user_id)
    {
        $user = User::find($user_id);
        $prevTotalLang = Language::count();

        $featuresCount = [];
        $featuresCount['categories'] = $user->item_categories->count();
        $featuresCount['subcategories'] = $user->item_sub_categories->count();
        $featuresCount['languages'] = $user->languages->count() - $prevTotalLang;
        $featuresCount['items'] = $user->items->count();
        $featuresCount['custome_page'] = $user->custome_page->count();
        $featuresCount['blogs'] = $user->blogs->count();

        return $featuresCount;
    }
}
