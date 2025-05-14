@extends('user-front.layout')

@section('breadcrumb_title', $pageHeading->wishlist_page ?? __('Wishlist'))
@section('page-title', $pageHeading->wishlist_page ?? __('Wishlist'))
@section('content')
  @php
    $user_currency = user_currency(Session::get('user_curr'));
  @endphp
  <!-- Wishlist Start -->
  <div class="shopping-area user-dashboard ptb-100">
    <div class="container">
      <div class="row justify-content-center gx-xl-5">
        @includeIf('user-front.customer.side-navbar')
        <div class="col-lg-9">
          <form action="#">
            @if (count($wishlist) > 0)
              <div class="item-list border radius-md">
                <table class="shopping-table">
                  <thead>
                    <tr class="table-heading">
                      <th class="ps-3" scope="col" colspan="2"> {{ $keywords['Product'] ?? __('Product') }} </th>
                      <th scope="col"> {{ $keywords['Unit Price'] ?? __('Unit Price') }} </th>
                      <th scope="col"> {{ $keywords['Stock'] ?? __('Stock') }} </th>
                      <th scope="col"> {{ $keywords['Action'] ?? __('Action') }} </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($wishlist as $wishlistItem)
                      @php
                        $lang = $language->id;
                        $item = \App\Models\User\UserItemContent::where([
                            ['item_id', $wishlistItem->item_id],
                            ['user_id', $user_id],
                        ])
                            ->where('language_id', '=', $lang)
                            ->with([
                                'variations' => function ($q) use ($lang) {
                                    $q->where('language_id', '=', $lang);
                                },
                                'item',
                            ])
                            ->first();
                      @endphp
                      @if ($item)
                        <tr class="item">
                          <td class="product-img">
                            <div class="image">
                              <a href="{{ route('front.user.productDetails', ['slug' => $item->slug, getParam()]) }}"
                                class="lazy-container radius-md ratio ratio-1-1" target="_blank">
                                <img class="lazyload"
                                src="{{ asset('assets/front/images/placeholder.png') }}"
                                  data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->item->thumbnail) }}"
                                  data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->item->thumbnail) }}"
                                  alt="Product">
                              </a>
                            </div>
                          </td>
                          <td class="product-desc">
                            <div class="product-desc-wrapper">
                              <h5 class="product-title lc-2 mb-1">
                                <a target="_blank"
                                  href="{{ route('front.user.productDetails', ['slug' => $item->slug, getParam()]) }}">
                                  {{ truncateString($item->title, 55) }}</a>
                              </h5>

                              @if ($shop_settings->item_rating_system == 1)
                                <div class="d-flex align-items-center">
                                  <div class="product-ratings rate text-xsm">
                                    <div class="rating" style="width:{{ $item->item->rating * 20 }}%"></div>
                                  </div>
                                  <span class="ratings-total">({{ reviewCount($item->item->id) }})</span>
                                </div>
                              @endif
                            </div>
                          </td>
                          @php
                            $flash_info = flashAmountStatus($item->item->id, $item->item->current_price);
                            $product_current_price = $flash_info['amount'];
                            $flash_status = $flash_info['status'];
                          @endphp
                          <td class="product-price">
                            <h4 class="m-0">
                              {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                            </h4>
                          </td>
                          <td class="product-availability">
                            @if ($item->item->type == 'physical')
                              @php
                                $varitaion_stock = VariationStock($item->item->id);
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
                              <span class="badge bg-success"><i class="fa fa-check"></i>
                                {{ $keywords['In Stock'] ?? __('In Stock') }}</span>
                            @endif
                          </td>
                          <td>
                            <div class="wishlist-action-btn gap-10">
                              @if ($shop_settings->catalog_mode != 1)
                                <a class=" btn btn-icon hover-show radius-sm cart-link cursor-pointer mb-0-important"
                                  data-title="{{ $item->title }}"
                                  data-current_price="{{ currency_converter($product_current_price) }}"
                                  data-item_id="{{ $item->item->id }}" data-language_id="{{ $lang }}"
                                  data-variations="{{ check_variation($item->item->id) > 0 ? 'yes' : null }}"
                                  data-href="{{ route('front.user.add.cart', ['id' => $item->item->id, getParam()]) }}"
                                  data-bs-toggle="tooltip" data-bs-placement="top"
                                  title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                    class="far fa-shopping-cart "></i></a>
                              @endif
                              {{-- <div class="checkbox"> --}}
                              <button class="btn btn-icon btn-remove mx-auto cursor-pointer item-remove"
                                rel="{{ $item->id }}" data-pg="wish"
                                data-href="{{ route('customer.removefromWish', ['id' => $wishlistItem->id, getParam()]) }} "
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $keywords['Remove item'] ?? __('Remove item') }}"><i
                                  class="fal fa-times"></i></button>
                              {{-- </div> --}}
                            </div>
                          </td>
                        </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="card">
                <div class="card-body cart">
                  <div class="col-sm-12 empty-cart-cls text-center">
                    <i class="far fa-heart empty-icon"></i>
                    <h3><strong>{{ $keywords['No Wishlist Item Found'] ?? __('No Wishlist Item Found') }}</strong></h3>
                    <a class="btn btn-md btn-primary radius-sm m-3"
                      href="{{ route('front.user.shop', getParam()) }}">{{ $keywords['Back to Shop'] ?? __('Back to Shop') }}
                    </a>
                  </div>
                </div>
              </div>
            @endif
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Wishlist End -->


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
              <span class="d-block">{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}</span>
              <i class="fas fa-spinner d-none"></i>
            </button>

          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
