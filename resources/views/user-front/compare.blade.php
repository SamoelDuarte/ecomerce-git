@extends('user-front.layout')
@section('meta-description', !empty($seo) ? $seo->compare_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->compare_meta_keywords : '')
@section('breadcrumb_title', $pageHeading->compare_page ?? __('Compare'))
@section('page-title', $pageHeading->compare_page ?? __('Compare'))
@section('content')
  @php
    $user_currency = user_currency(Session::get('user_curr'));
  @endphp

  <!-- Compare Start -->
  <div class="compare-area ptb-100">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          @if (!empty($compare))
            <div class="table-wrapper table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr class="th-compare">
                    <th class="product-name">{{ $keywords['Action'] ?? __('Action') }}</th>
                    @foreach ($compare as $key => $compareItem)
                      @php
                        $item = \App\Models\User\UserItemContent::where('item_id', $compareItem['id'])
                            ->where('language_id', '=', $userCurrentLang->id)
                            ->with(['item'])
                            ->first();

                      @endphp
                      <th>
                        <a href="{{ route('front.compare.item.remove', ['uid' => $key, getParam()]) }}"
                          class="btn cmp-remove"><i class="fa fa-times"></i> {{ $keywords['Remove'] ?? __('Remove') }}</a>
                      </th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th class="product-name">{{ $keywords['Product Name'] ?? __('Product Name') }}</th>
                    @foreach ($compare as $compareItem)
                      @php
                        $item = \App\Models\User\UserItemContent::where('item_id', $compareItem['id'])
                            ->where('language_id', '=', $userCurrentLang->id)
                            ->with(['item'])
                            ->first();
                      @endphp
                      @if ($item)
                        <td>
                          <h5 class="lc-2 fs-18"><a
                              href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                              target="_blank">{{ truncateString($item->title, 55) }}</a></h5>
                        </td>
                      @endif
                    @endforeach
                  </tr>
                  <tr>
                    <th class="product-name">{{ $keywords['Product Image'] ?? __('Product Image') }}</th>
                    @foreach ($compare as $compareItem)
                      @php
                        $item = \App\Models\User\UserItemContent::where('item_id', $compareItem['id'])
                            ->where('language_id', '=', $userCurrentLang->id)
                            ->with(['item'])
                            ->first();
                      @endphp
                      @if ($item)
                        <td class="compare_image">
                          <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                            target="_blank"> <img
                            src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->item->thumbnail) }}"
                              alt="" class="featured-image lazyload"></a>
                        </td>
                      @endif
                    @endforeach
                  </tr>
                  <tr>
                    <th class="product-name">{{ $keywords['Product Price'] ?? __('Product Price') }}</th>
                    @foreach ($compare as $compareItem)
                      @php
                        $item = \App\Models\User\UserItemContent::where('item_id', $compareItem['id'])
                            ->where('language_id', '=', $userCurrentLang->id)
                            ->with(['item'])
                            ->first();

                        if (!is_null($item)) {
                            $flash_info = flashAmountStatus($item->item->id, $item->item->current_price);
                            $product_current_price = $flash_info['amount'];
                            $flash_status = $flash_info['status'];
                        }
                      @endphp
                      @if (!is_null($item))
                        <td>
                          <div class="price-area">
                            <div class="price-text">
                              @if ($flash_status == true)
                                <span class="new-price">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                </span>
                                <span class="old-price line_through">
                                  <del>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->item->current_price)) }}</del>
                                </span>
                                &nbsp;
                                <span class="old-price">{{ $item->item->flash_amount }}%
                                  {{ $keywords['OFF'] ?? __('OFF') }}</span>
                              @else
                                <span
                                  class="new-price">{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->item->current_price)) }}</span>
                                <span
                                  class="old-price line_through">{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->item->previous_price)) }}</span>
                              @endif
                            </div>

                            <div class="price-btn d-flex">
                              <a href="javascript:void(0)" class="btn btn-icon hover-show radius-sm quick-view-link"
                                data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                                data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                                title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i></a>

                              @php
                                $customer_id = Auth::guard('customer')->check()
                                    ? Auth::guard('customer')->user()->id
                                    : null;
                                $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                              @endphp
                              <a href="#"
                                class="btn btn-icon hover-show radius-sm {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                data-url="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                                data-removeUrl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                                title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                                  class="fal fa-heart"></i></a>

                              @if ($shop_settings->catalog_mode != 1)
                                <a class=" btn btn-icon hover-show radius-sm cart-link cursor-pointer"
                                  data-title="{{ $item->title }}"
                                  data-current_price="{{ currency_converter($product_current_price) }}"
                                  data-item_id="{{ $item->item->id }}"
                                  data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                                  data-language_id="{{ $userCurrentLang->id }}"
                                  data-href="{{ route('front.user.add.cart', ['id' => $item->item->id, getParam()]) }}"
                                  data-toggle="tooltip" data-placement="top" title="{{ __('Add to Cart') }}"><i
                                    class="far fa-shopping-cart "></i>
                                </a>
                              @endif
                            </div>


                          </div>

                        </td>
                      @endif
                    @endforeach
                  </tr>
                  <tr>
                    <th class="product-name">{{ $keywords['Variations'] ?? __('Variations') }}</th>
                    @foreach ($compare as $compareItem)
                      @php
                        $product_variations = App\Models\User\ProductVariation::where([
                            ['item_id', $compareItem['id']],
                        ])->get();
                      @endphp
                      <td>
                        @if (count($product_variations) > 0)
                          <div class="variation-wrapper">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                              data-bs-target="#showVariation">{{ $keywords['Show Variations'] ?? __('Show Variations') }}</button>
                          </div>
                          <!-- variation modal-->
                          <div class="modal fade" id="showVariation" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered radius-sm" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title" id="exampleModalLongTitle">
                                    <span></span>
                                    <small class="ml-2">
                                      {{ $keywords['Variations'] ?? __('Variations') }}
                                    </small>
                                  </h4>
                                  <button type="button" class="close variatmodal-close" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div class="row">
                                    @foreach ($product_variations as $product_variation)
                                      @php
                                        $product_variation_contents = App\Models\User\ProductVariationContent::where([
                                            ['product_variation_id', $product_variation->id],
                                            ['language_id', $userCurrentLang->id],
                                        ])->get();
                                        $variant_content_options = App\Models\User\ProductVariantOption::where([
                                            ['product_variation_id', $product_variation->id],
                                        ])->get();
                                      @endphp
                                      @foreach ($product_variation_contents as $product_variation_content)
                                        @php
                                          $variant_content = App\Models\VariantContent::where(
                                              'id',
                                              $product_variation_content->variation_name,
                                          )->first();
                                        @endphp
                                        <div class="col-lg-6">
                                          <div class="variation-header">
                                            <h6 class="title mb-0">{{ @$variant_content->name }}</h6>
                                            <h6 class="title mb-0">{{ $keywords['Price'] ?? __('Price') }}</h6>
                                          </div>
                                          <div class="body">
                                            @foreach ($variant_content_options as $variant_content_option)
                                              @php
                                                $variant_option_contents = App\Models\User\ProductVariantOptionContent::where(
                                                    [
                                                        ['product_variant_option_id', $variant_content_option->id],
                                                        ['language_id', $userCurrentLang->id],
                                                    ],
                                                )->first();
                                              @endphp
                                              <div class="variation-item">
                                                <p>{{ @$variant_option_contents->option_content->option_name }}</p>
                                                <p>
                                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($variant_content_option->price)) }}
                                                </p>
                                              </div>
                                            @endforeach
                                          </div>
                                        </div>
                                      @endforeach
                                    @endforeach
                                  </div>
                                </div>
                                <div class="card-footer bg-white"></div>
                              </div>
                            </div>
                          </div>
                        @else
                          {{ $keywords['Not available'] ?? __('Not available') }}
                        @endif
                      </td>
                    @endforeach
                  </tr>
                  <tr>
                    @if ($shop_settings->item_rating_system == 1)
                      <th class="product-name">{{ $keywords['Ratings'] ?? __('Ratings') }}</th>
                    @endif
                    @foreach ($compare as $compareItem)
                      @php
                        $item = \App\Models\User\UserItemContent::where('item_id', $compareItem['id'])
                            ->where('language_id', '=', $userCurrentLang->id)
                            ->with([
                                'variations' => function ($q) use ($userCurrentLang) {
                                    $q->where('language_id', '=', $userCurrentLang->id);
                                },
                                'item',
                            ])
                            ->first();
                      @endphp
                      @if ($item && $shop_settings->item_rating_system == 1)
                        <td>
                          <div class="d-flex justify-content-center align-items-center">
                            <div class="product-ratings rate text-xsm">
                              <div class="rating" style="width:{{ $item->item->rating * 20 }}%"></div>
                            </div>
                            <span class="ratings-total">({{ reviewCount($compareItem['id']) }})</span>
                          </div>
                        </td>
                      @endif
                    @endforeach
                  </tr>
                  <tr>
                    <th class="product-name">{{ $keywords['Product Description'] ?? __('Product Description') }}</th>
                    @foreach ($compare as $compareItem)
                      @php
                        $item = \App\Models\User\UserItemContent::where('item_id', $compareItem['id'])
                            ->where('language_id', '=', $userCurrentLang->id)
                            ->with(['item'])
                            ->first();
                      @endphp
                      @if ($item)
                        <td class="item-row">
                          <p class="description-compare">
                            {!! substr(strip_tags($item->description), 0, 150) !!}
                          </p>
                        </td>
                      @endif
                    @endforeach
                  </tr>
                  <tr>
                    <th class="product-name">{{ $keywords['Availability'] ?? __('Availability') }}</th>
                    @foreach ($compare as $compareItem)
                      @php
                        $item = \App\Models\User\UserItemContent::where('item_id', $compareItem['id'])
                            ->where('language_id', '=', $userCurrentLang->id)
                            ->with(['item'])
                            ->first();
                      @endphp
                      @if ($item)
                        <td class="available-stock">
                          @if ($item->item->type == 'physical')
                            @php
                              $varitaion_stock = VariationStock($compareItem['id']);
                            @endphp
                            @if ($varitaion_stock['has_variation'] == 'yes')
                              @if ($varitaion_stock['stock'] == 'yes')
                                <span class="badge bg-success"><i class="fa fa-check"></i>
                                  {{ $keywords['In Stock'] ?? __('In Stock') }}</span>
                              @else
                                <span class="badge bg-danger"><i class="fa fa-times"></i>
                                  {{ $keywords['Out of Stock'] ?? __('Out of Stock') }}</span>
                              @endif
                            @else
                              @if ($item->item->stock > 0)
                                <span class="badge bg-success"><i class="fa fa-check"></i>
                                  {{ $keywords['In Stock'] ?? __('In Stock') }}</span>
                              @else
                                <span class="badge bg-danger"><i class="fa fa-times"></i>
                                  {{ $keywords['Out of Stock'] ?? __('Out of Stock') }}</span>
                              @endif
                            @endif
                          @else
                            <span class="badge bg-success">{{ $keywords['In Stock'] ?? __('In Stock') }}</span>
                          @endif
                        </td>
                      @endif
                    @endforeach
                  </tr>
                </tbody>
              </table>
            </div>
          @else
            <div class="card">
              <div class="card-body cart">
                <div class="col-sm-12 empty-cart-cls text-center">
                  <i class="far fa-shopping-bag empty-icon"></i>
                  <h3>
                    <strong>{{ $keywords['No product found to compare'] ?? __('No product found to compare') }}</strong>
                  </h3>
                  <a class="btn btn-md btn-primary radius-sm m-3"
                    href="{{ route('front.user.shop', getParam()) }}">{{ $keywords['Back to Shop'] ?? __('Back to Shop') }}
                  </a>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- Compare End -->

  {{-- Variation Modal Starts --}}
  <div class="modal fade variation-modal" id="variationModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title @if (request()->is('admin/*')) text-white @endif" id="exampleModalLongTitle">
            <span></span>
            <small class="ml-2">
              ({{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
              <span id="productPrice"></span>
              {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }})
            </small>
          </h4>
          <button type="button" class="close variatmodal-close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fal fa-times"></i></span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row" id="variants">
            {{-- All variants will be appended here by jquery --}}
          </div>
        </div>
        <div class="modal-footer justify-content-center">
          <div class="variation-modal-groupbtn">
            <div class="modal-quantity">
              <span class="minus quantity-btn"><i class="fas fa-minus"></i></span>
              <input class="form-control" type="number" name="cart-amount" value="1" min="1">
              <span class="plus quantity-btn"><i class="fas fa-plus"></i></span>
            </div>

            <button type="button" class="btn btn-primary btn-block text-uppercase modal-cart-link">
              <i class="fas fa-cart-plus"></i>
              <span class="d-block">{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}</span>
              <i class="fas fa-spinner d-none"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Variation Modal Ends --}}
  <!-- Quick View Modal Start -->
  <div class="modal custom-modal quick-view-modal fade" id="quickViewModal" tabindex="-1"
    aria-labelledby="quickViewModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content radius-sm">
        <button type="button" class="close_modal_btn" data-bs-dismiss="modal" aria-label="Close"><i
            class="fal fa-times"></i></button>
        <div class="modal-body">
          <div class="product-single-default">
            <div class="row gx-0" id="quickViewModalContent">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Quick View Modal End -->
@endsection
