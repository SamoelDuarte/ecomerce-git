<?php

namespace App\Http\Controllers\UserFront;

use App\Http\Controllers\Controller;
use App\Models\User\BasicSetting;
use App\Models\User\Language as UserLanguage;
use App\Models\User\ProductVariantOption;
use App\Models\User\ProductVariantOptionContent;
use App\Models\User\ProductVariation;
use App\Models\User\ProductVariationContent;
use App\Models\User\SEO;
use App\Models\User\UserCurrency;
use App\Models\User\UserItem;
use App\Models\User\UserItemCategory;
use App\Models\User\UserItemContent;
use App\Models\User\UserItemReview;
use App\Models\User\UserItemSubCategory;
use App\Models\VariantContent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    public function Shop(Request $request, $slug = null, $slug1 = null,)
    {
        $user = getUser();

        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();

            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $data['pageHeading'] = $this->getUserPageHeading($userCurrentLang);

        $uLang = $userCurrentLang->id;
        $data['uLang'] = $userCurrentLang->id;

        $selected_category = UserItemCategory::with('variations')->where('slug', $request->category)->where('language_id', $userCurrentLang->id)
            ->where([['user_id', $user->id], ['status', 1]])
            ->select('id')
            ->first();
        $data['selected_category'] = $selected_category;

        $selected_subcategory = UserItemSubCategory::with('variations')->where('slug', $request->subcategory)->where('language_id', $userCurrentLang->id)
            ->where([['user_id', $user->id], ['status', 1]])
            ->select('id')
            ->first();

        $data['selected_subcategory'] = $selected_subcategory;

        $selected_category_id = $selected_category ? $selected_category->id : null;
        $selected_subcategory_id = $selected_subcategory ? $selected_subcategory->id : null;

        if (!is_null($selected_category_id)) {
            $variants = VariantContent::where([['user_id', $user->id], ['category_id', $selected_category_id]])
                ->when($selected_subcategory_id, function ($query) use ($selected_subcategory_id) {
                    return $query->where('sub_category_id', $selected_subcategory_id);
                })
                ->get();
        } else {
            $variants = [];
        }
        $data['variants'] = $variants;

        $category = $subcategory = $min = $max = $keyword = $sort = null;

        if ($request->filled('category')) {
            $category = UserItemCategory::where([['slug', $request['category']], ['user_id', $user->id]])->select('id')->first();
            if ($category) {
                $category = $category->id;
            } else {
                $category = null;
            }
        }
        if ($request->filled('subcategory')) {
            $subcategory = UserItemSubCategory::where([['slug', $request['subcategory']], ['user_id', $user->id]])->select('id')->first();
            if ($subcategory) {
                $subcategory = $subcategory->id;
            } else {
                $subcategory = null;
            }
        }
        $userSelectedCurrency = UserCurrency::where('id', session()->get('user_curr'))->where('user_id', $user->id)->select('id', 'value', 'symbol')->first();
        $userDefaultCurrency = UserCurrency::where([['is_default', 1], ['user_id', $user->id]])->select('id', 'value', 'symbol')->first();

        if ($request->filled('min') && $request->filled('max')) {
            $min = $request['min'];
            $max = $request['max'];

            if ($userDefaultCurrency->id != session()->get('user_curr')) {
                $min = $min / $userSelectedCurrency->value;
                $max = $max / $userSelectedCurrency->value;
                $min = (float) $min;
                $max = (float) $max;
            } else {
                $min = (float) $min;
                $max = (float) $max;
            }
        }
        $data['symbol'] = $userSelectedCurrency->symbol ?? $userDefaultCurrency->symbol;
        if ($request->filled('keyword')) {
            $keyword = $request['keyword'];
        }
        if ($request->filled('sort')) {
            $sort = $request['sort'];
        }
        $on_sale = null;
        if ($request->filled('on_sale')) {
            $on_sale = $request->on_sale;
        }

        $data['items'] = UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
            ->leftJoin('user_item_sub_categories', 'user_item_sub_categories.id', '=', 'user_item_contents.subcategory_id')
            ->where('user_items.status', '=', 1)
            ->where('user_item_categories.status', '=', 1)
            ->where('user_item_contents.language_id', '=', $uLang)
            ->when($category, function ($query, $category) {
                return $query->where('user_item_categories.id', $category);
            })
            ->when($on_sale, function ($query) use ($on_sale) {
                return $on_sale === 'flash_sale'
                    ? $query->where('user_items.flash', 1)
                    : $query->where(function ($q) {
                        $q->where('user_items.flash', 1)
                            ->orWhere('user_items.previous_price', '>', 0);
                    });
            })
            ->when($subcategory, function ($query, $subcategory) {
                return $query->where('user_item_sub_categories.id', $subcategory)->where('user_item_sub_categories.status', '=', 1);
            })
            ->when(($min && $max), function ($query) use ($min, $max) {
                return $query->where('user_items.current_price', '>=', $min)->where('user_items.current_price', '<=', $max);
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where('user_item_contents.title', 'like', '%' . $keyword . '%');
            })
            ->select('user_items.*', 'user_item_contents.*', 'user_item_categories.*', 'user_item_categories.name as category_name', 'user_item_categories.slug as category_slug', 'user_item_contents.slug as product_slug')
            ->when($sort, function ($query, $sort) {
                if ($sort == 'new') {
                    return $query->orderBy('user_items.created_at', 'desc');
                } else if ($sort == 'old') {
                    return $query->orderBy('user_items.created_at', 'asc');
                } elseif ($sort == 'ascending') {
                    return $query->orderBy('user_items.current_price', 'asc');
                } elseif ($sort == 'descending') {
                    return $query->orderBy('user_items.current_price', 'desc');
                }
            }, function ($query) {
                return $query->orderByDesc('user_items.id');
            })
            ->where('user_items.user_id', $user->id)
            ->paginate(12);

        $data['minPrice'] = UserItem::where([['status', 1], ['user_id', $user->id]])->min('current_price');
        $data['maxPrice'] = UserItem::where([['status', 1], ['user_id', $user->id]])->max('current_price');

        $data['all_category_product_count'] = UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
            ->leftJoin('user_item_sub_categories', 'user_item_sub_categories.id', '=', 'user_item_contents.subcategory_id')
            ->where([
                ['user_items.status', '=', 1],
                ['user_items.user_id', $user->id],
                ['user_item_categories.status', '=', 1],
                ['user_item_contents.language_id', $userCurrentLang->id],
            ])
            ->where(function ($query) {
                $query->where('user_item_sub_categories.status', '=', 1)
                    ->orWhereNull('user_item_sub_categories.status'); // Allow NULL values
            })
            ->count();

        $data['seo'] = SEO::where('language_id', $uLang)->where('user_id', $user->id)
            ->select('shop_meta_keywords', 'shop_meta_description')
            ->first();
        $data['view_type'] = Session::has('view_type') ? Session::get('view_type') : null;
        return view('user-front.shop', $data);
    }

    public function ShopSearch(Request $request)
    {
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();

            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $uLang = $userCurrentLang->id;

        $data['categories'] = UserItemCategory::with('subcategories')->where('language_id', $userCurrentLang->id)
            ->where([['user_id', $user->id], ['status', 1]])
            ->get();

        $data['selected_category'] = UserItemCategory::with('variations')
            ->where([['slug', $request->category], ['language_id', $userCurrentLang->id], ['user_id', $user->id], ['status', 1]])
            ->select('id')
            ->first();

        $data['selected_subcategory'] = UserItemSubCategory::with('variations')->where('slug', $request->subcategory)->where('language_id', $userCurrentLang->id)
            ->where([['user_id', $user->id], ['status', 1]])
            ->select('id')
            ->first();
        $data['variants'] = VariantContent::where([['language_id', $uLang], ['user_id', $user->id]])->get();

        $category = $subcategory = $min = $max = $keyword = $sort = $rating = $variants = $on_sale = null;

        if ($request->filled('category')) {
            $category = UserItemCategory::where([['slug', $request['category']], ['language_id', $userCurrentLang->id], ['user_id', $user->id], ['status', 1]])
                ->select('id')
                ->first();
            if ($category) {
                $category = $category->id;
            } else {
                $category = null;
            }
        }
        if ($request->filled('subcategory')) {
            $subcategory = UserItemSubCategory::where([['slug', $request['subcategory']], ['language_id', $userCurrentLang->id], ['user_id', $user->id], ['status', 1]])
                ->select('id')
                ->first();
            if ($subcategory) {
                $subcategory = $subcategory->id;
            } else {
                $subcategory = null;
            }
        }
        if ($request->filled('min') && $request->filled('max')) {
            $min = $request['min'];
            $max = $request['max'];

            $userSelectedCurrency = UserCurrency::where('id', session()->get('user_curr'))->where('user_id', $user->id)->select('id', 'value')->first();

            $userDefaultCurrency = UserCurrency::where([['is_default', 1], ['user_id', $user->id]])->select('id', 'value')->first();

            if (!is_null($userSelectedCurrency) && $userDefaultCurrency->id != session()->get('user_curr')) {
                $min = $min / $userSelectedCurrency->value;
                $max = $max / $userSelectedCurrency->value;
                $min = (float) $min;
                $max = (float) $max;
            } else {
                $min = (float) $min;
                $max = (float) $max;
            }
        }
        if ($request->filled('keyword')) {
            $keyword = $request['keyword'];
        }
        if ($request->filled('sort')) {
            $sort = $request['sort'];
        }
        if ($request->filled('ratings')) {
            $rating = $request->ratings;
        }
        if ($request->filled('on_sale')) {
            $on_sale = $request->on_sale;
        }

        $productIds = [];
        $datas = [];

        if ($request->filled('variants')) {
            $variants = json_decode($request->variants, true);

            if (!empty($variants)) {
                foreach ($variants as $variant) {
                    $values = explode(':', $variant);
                    $variant_option = $values[0];
                    $variant_id = $values[1];

                    // get all item IDs that match the variant option and language
                    $variant_option_contents = ProductVariantOptionContent::where([
                        ['option_name', $variant_option],
                        ['language_id', $uLang]
                    ])->pluck('item_id')->toArray();

                    foreach ($variant_option_contents as $item_id) {
                        $datas[] = [
                            'variant_id' => $variant_id,
                            'item_id' => $item_id,
                            'option_name' => $variant_option
                        ];
                    }
                }
            }

            $productIds = array_unique(array_column($datas, 'item_id'));

            // now filter products that contain all selected variants
            foreach ($productIds as $key => $productId) {
                foreach ($variants as $variant) {
                    $values = explode(':', $variant);
                    $variant_option = $values[0];

                    $variant_exists = ProductVariantOptionContent::where([
                        ['option_name', $variant_option],
                        ['language_id', $uLang],
                        ['item_id', $productId]
                    ])->exists();

                    if (!$variant_exists) {
                        unset($productIds[$key]);
                        break;
                    }
                }
            }
        }

        // reset array keys
        $productIds = array_values($productIds);



        $data['uLang'] = $userCurrentLang->id;
        $items = UserItem::join('user_item_contents', 'user_items.id', '=', 'user_item_contents.item_id')
            ->join('user_item_categories', 'user_item_categories.id', '=', 'user_item_contents.category_id')
            ->leftJoin('user_item_sub_categories', 'user_item_sub_categories.id', '=', 'user_item_contents.subcategory_id')
            ->where('user_items.status', '=', 1)
            ->where('user_item_categories.status', '=', 1)
            ->where('user_item_contents.language_id', '=', $uLang)
            ->when($category, function ($query, $category) {
                return $query->where('user_item_categories.id', $category);
            })
            ->when($on_sale, function ($query) use ($on_sale) {
                return $on_sale === 'flash_sale'
                    ? $query->where('user_items.flash', 1)
                    : $query->where(function ($q) {
                        $q->where('user_items.flash', 1)
                            ->orWhere('user_items.previous_price', '>', 0);
                    });
            })
            ->when($subcategory, function ($query, $subcategory) {
                return $query->where('user_item_sub_categories.id', $subcategory)->where('user_item_sub_categories.status', '=', 1);
            })
            ->when($min !== null && $max !== null, function ($query) use ($min, $max) {
                return $query->whereBetween('user_items.current_price', [$min, $max]);
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where('user_item_contents.title', 'like', '%' . $keyword . '%');
            })->when($rating, function ($query) use ($rating) {
                return $query->where('user_items.rating', '>=', $rating);
            })
            ->when($variants, function ($query) use ($productIds) {
                return $query->whereIn('user_items.id', $productIds);
            })
            ->select('user_items.*', 'user_item_contents.*', 'user_item_categories.*', 'user_item_categories.name as category_name', 'user_item_categories.slug as category_slug', 'user_item_contents.slug as product_slug')
            ->when($sort, function ($query, $sort) {
                if ($sort == 'new') {
                    return $query->orderBy('user_items.created_at', 'desc');
                } else if ($sort == 'old') {
                    return $query->orderBy('user_items.created_at', 'asc');
                } elseif ($sort == 'ascending') {
                    return $query->orderByRaw('
                CASE
                    WHEN user_items.flash = true THEN user_items.current_price - (user_items.current_price * user_items.flash_amount / 100)
                    ELSE user_items.current_price
                END ASC
            ');
                } elseif ($sort == 'descending') {
                    return $query->orderByRaw('
                CASE
                    WHEN user_items.flash = true THEN user_items.current_price - (user_items.current_price * user_items.flash_amount / 100)
                    ELSE user_items.current_price
                END DESC
            ');
                }
            }, function ($query) {
                return $query->orderByDesc('user_items.id');
            })
            ->where('user_items.user_id', $user->id)
            ->paginate(12);

        $data['items'] = $items;

        $data['user_currency'] =  user_currency(Session::get('user_curr'));
        if ($request->filled('view_type')) {
            Session::put('view_type', $request->view_type);
        }

        if (Session::get('view_type') == 'list') {
            return view('user-front.shop-list', $data)->render();
        } else {
            return view('user-front.shop-grid', $data)->render();
        }
    }

    public function shop_type(Request $request)
    {
        Session::put('view_type', $request->type);
        return redirect()->route('front.user.shop', getParam());
    }

    public function get_variation(Request $request)
    {
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();

            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        $data['uLang'] = $userCurrentLang->id;

        $category_id = $subcategory_id = null;

        $category = UserItemCategory::where([['user_id', $user->id], ['slug', $request->category], ['status', 1]])->select('id')->first();

        if ($category) {
            $category_id = $category->id;
        }
        if ($request->filled('subcategory')) {
            $subcategory = UserItemSubCategory::where([['user_id', $user->id], ['slug', $request->subcategory]])->select('id')->first();
            if ($subcategory) {
                $subcategory_id = $subcategory->id;
            }
        }

        $variants = collect();
        if (!is_null($category_id)) {
            $variants = VariantContent::where([['user_id', $user->id], ['category_id', $category_id]])
                ->when($subcategory_id, function ($query) use ($subcategory_id) {
                    return $query->where('sub_category_id', $subcategory_id);
                })
                ->get();
        }

        if ($request->filled('subcategory') && $variants == '[]') {
            $items = UserItemContent::where([
                ['category_id', $category_id],
                ['language_id', $userCurrentLang->id],
                ['subcategory_id', $subcategory_id]
            ])->get();
            $variantIds = [];
            if ($items) {
                foreach ($items as $item) {
                    if (check_variation($item->item_id)) {
                        $variantIds = array_merge(
                            $variantIds,
                            ProductVariationContent::where([
                                ['item_id', $item->item_id],
                                ['language_id', $userCurrentLang->id]
                            ])->pluck('variation_name')->toArray()
                        );
                    }
                }
            }
            $variants = VariantContent::whereIn('id', $variantIds)->get();
        }

        $data['variants'] = $variants;
        return view('user-front.variants', $data)->render();
    }

    public function get_productVariation(Request $request)
    {
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();

            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $data['language_id'] = $userCurrentLang->id;

        $data['product_variations'] = ProductVariation::where('item_id', $request->item_id)->get();
        $data['item_id'] = $request->item_id;

        return view('user-front.variant-content', $data)->render();
    }

    public function productDetails($domain, $slug)
    {
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();

            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }
        $uLang = $userCurrentLang->id;
        $itemId =  UserItemContent::where([['slug', $slug], ['user_id', $user->id]])->pluck('item_id')->firstOrFail();

        $data['uLang'] = $userCurrentLang->id;

        $data['product'] = UserItemContent::with('item', 'item.sliders', 'variations')
            ->where('language_id', '=', $uLang)
            ->where('item_id', $itemId)
            ->first();

        if (is_null($data['product'])) {
            abort(404);
        }

        $category_id = $data['product']->category_id;
        $category = UserItemCategory::where([['id', $category_id], ['status', 1]])->select('slug')->first();
        $data['category_slug'] = @$category->slug;

        $data['related_product'] = UserItemContent::with('item', 'item.sliders', 'variations')
            ->where('language_id', '=', $uLang)
            ->where('category_id', '=', $category_id)
            ->where('slug', '!=', $slug)
            ->get();
        $data['ubs'] = BasicSetting::where('user_id', $user->id)->firstOrFail();

        $data['reviews'] = UserItemReview::where('item_id', $data['product']->item_id)->get();
        $data['product_variations'] = ProductVariation::where('item_id', $data['product']->item_id)->get();
        $data['item_id'] = $data['product']->item_id;
        return view('user-front.product_details', $data);
    }

    public function productDetailsQuickview($domain, $slug)
    {
        $user = getUser();
        if (session()->has('user_lang')) {
            $userCurrentLang = UserLanguage::where('code', session()->get('user_lang'))->where('user_id', $user->id)->first();

            if (empty($userCurrentLang)) {
                $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
                session()->put('user_lang', $userCurrentLang->code);
            }
        } else {
            $userCurrentLang = UserLanguage::where('is_default', 1)->where('user_id', $user->id)->first();
        }

        session()->put('user_lang', $userCurrentLang->code);

        $uLang = $userCurrentLang->id;
        $data['uLang'] = $uLang;

        $data['product'] = UserItemContent::with(['item' => ['sliders'], 'variations'])
            ->where('language_id', $uLang)
            ->where('slug', $slug)
            ->first();
        $data['item_id'] = $data['product']->item_id;

        $data['product_variations'] = ProductVariation::where('item_id', $data['product']->item_id)->get();
        $data['item_id'] = $data['product']->item_id;

        return view('user-front.partials.quick-view-modal', $data);
    }
}
