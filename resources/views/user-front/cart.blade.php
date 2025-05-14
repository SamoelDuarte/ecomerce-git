@extends('user-front.layout')

@section('meta-description', !empty($seo) ? $seo->cart_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->cart_meta_keywords : '')


@section('breadcrumb_title', $pageHeading->cart_page ?? __('Cart'))
@section('page-title', $pageHeading->cart_page ?? __('Cart'))

@section('content')

  @php
    $cartTotal = 0;
    $countitem = 0;
    if ($cart) {
        foreach ($cart as $p) {
            $cartTotal += $p['total'];
            $countitem += $p['qty'];
        }
    }
    if (is_null($cart)) {
        $cart = [];
    }
    $user_currency = user_currency(Session::get('user_curr'));
  @endphp


  <!-- Cart Start -->
  <div class="shopping-area cart-page pt-100 pb-70">
    <div class="container">
      <div class="row gx-xl-5">
        <div class="col-lg-12 pb-70">
          <form action="#">
            <div id="refreshDiv">
              @if (count(@$cart) > 0)
                <div class="item-list border radius-md">

                  <div class="d-flex justify-content-between gap-2 flex-wrap ps-3 pe-3 p-10 mt-2">
                    <h4 class="d-flex gap-2"><span>{{ $keywords['Total Items'] ?? __('Total Items') }}:</span>
                      <span>{{ $totalQty }}</span>
                    </h4>
                    <h4 class="d-flex gap-2"><span>{{ $keywords['Total Price'] ?? __('Total Price') }}:</span>
                      <span>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $totalCart) }}</span>
                    </h4>
                  </div>

                  <table class="shopping-table">
                    <thead>
                      <tr class="table-heading">
                        <th class="prod-column ps-3">{{ $keywords['Products'] ?? __('Products') }} </th>
                        <th class="hide-column"></th>
                        <th>{{ $keywords['Quantity'] ?? __('Quantity') }} </th>
                        <th>{{ $keywords['Total'] ?? __('Total') }} </th>
                        <th class="pe-3 text-center">{{ $keywords['Remove'] ?? __('Remove') }} </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($cart as $key => $item)
                        @php
                          $id = $item['id'];
                          $product = App\Models\User\UserItem::with('currency')->findOrFail($item['id']);

                          $content = $product
                              ->itemContents()
                              ->where('language_id', $userCurrentLang->id)
                              ->select('title', 'slug')
                              ->first();

                          $flash_info = flashAmountStatus($product->id, $product->current_price);
                          $product_current_price = $flash_info['amount'];
                          $flash_status = $flash_info['status'];
                        @endphp
                        <tr class="item">
                          <td class="product-img ">
                            @if (!is_null($content))
                              <div class="image radius-sm">
                                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $content->slug]) }}"
                                  class="lazy-container radius-sm ratio
                                ratio-1-1"
                                  target="_blank">
                                  <img class="lazyload"
                                  src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product->thumbnail) }}"
                                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product->thumbnail) }}"
                                    alt="Product">
                                </a>
                              </div>
                            @else
                              <div class="image radius-sm">
                                <a href=""
                                  class="lazy-container radius-sm ratio
                                ratio-1-1">
                                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/user-front/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/user-front/images/placeholder.png') }}" alt="Product">
                                </a>
                              </div>
                            @endif
                          </td>
                          <td class="product-desc">
                            @if (!is_null($content))
                              <div class="product-desc-wrapper">
                                <h5 class="product-title lc-1 mb-1">
                                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $content->slug]) }}"
                                    target="_blank">{{ convertUtf8($content->title) }}</a>
                                </h5>

                                <p class="mb-2"><strong>{{ $keywords['Item Price'] ?? __('Item Price') }}:</strong>
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $item['product_price']) }}
                                </p>

                                @php
                                  $variation_total = 0;
                                @endphp
                                @if (!empty($item['variations']))
                                  <p class="mb-1"><strong>{{ $keywords['Variations'] ?? __('Variations') }}:</strong>
                                  </p>
                                  @foreach ($item['variations'] as $k => $itm)
                                    @php
                                      $variation_total = $variation_total + $itm['price'];

                                      //show variations name
                                      $vNameId = App\Models\User\ProductVariationContent::where(
                                          'product_variation_id',
                                          $itm['variation_id'],
                                      )
                                          ->pluck('variation_name')
                                          ->first();

                                      $variant_id = App\Models\VariantContent::where('id', $vNameId)
                                          ->pluck('variant_id')
                                          ->first();
                                      $variation_name = App\Models\VariantContent::where([
                                          ['variant_id', $variant_id],
                                          ['language_id', $userCurrentLang->id],
                                      ])
                                          ->pluck('name')
                                          ->first();

                                      //show variation options name
                                      $vOptionId = App\Models\User\ProductVariantOptionContent::where([
                                          ['language_id', $userCurrentLang->id],
                                          ['product_variant_option_id', $itm['option_id']],
                                      ])
                                          ->pluck('option_name')
                                          ->first();
                                      $vOptionName = App\Models\VariantOptionContent::where([
                                          ['language_id', $userCurrentLang->id],
                                          ['id', $vOptionId],
                                      ])
                                          ->pluck('option_name')
                                          ->first();
                                    @endphp
                                    <table class="variation-table mt-0">
                                      <tr class="small">
                                        <td class="p-0">
                                          <strong>{{ $variation_name }}: </strong>
                                        </td>
                                        <td class="p-0 cart_variants_price"><span class="mx-1">{{ $vOptionName }}
                                            -</span>
                                          (<i
                                            class="fas fa-plus"></i>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $itm['price']) }})
                                        </td>
                                      </tr>
                                    </table>
                                  @endforeach
                                @endif

                                @if ($shop_settings->item_rating_system == 1)
                                  <div class="d-flex align-items-center">
                                    <div class="product-ratings rate text-xsm">
                                      <div class="rating" style="width:{{ $product->rating * 20 }}%"></div>
                                    </div>
                                    <span class="ratings-total">({{ reviewCount($item['id']) }})</span>
                                  </div>
                                @endif
                              </div>
                            @endif
                          </td>

                          <td class="qty">

                            <div class="quantity-input d-flex">
                              <div class="quantity-down quantity-btn" id="quantityDown">
                                <i class="fal fa-minus"></i>
                              </div>
                              <input id="1" class="cart_qty" type="number" value="{{ $item['qty'] }}"
                                name="quantity">
                              <div class="quantity-up quantity-btn" id="quantityUP">
                                <i class="fal fa-plus"></i>
                              </div>
                            </div>
                          </td>

                          <td class="price">
                            <h5 class="m-0">
                              {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $item['total']) }}
                            </h5>
                          </td>
                          <td class="text-center remove">
                            <span class="fas fa-times cursor-pointer item-remove btn-remove btn-icon d-inline-block"
                              rel="{{ $id }}"
                              data-href="{{ route('front.cart.item.remove', ['uid' => $key, getParam()]) }}"></span>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="card">
                  <div class="card-body cart">
                    <div class="col-sm-12 empty-cart-cls text-center">
                      <svg width="258" height="203" viewBox="0 0 608 553" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                          d="M409.503 199.786C366.363 199.786 322.449 198.034 281.371 186.591C241.065 175.406 204.059 153.707 171.072 128.607C149.476 112.268 129.839 99.228 101.8 101.187C74.3703 102.671 48.1393 112.914 26.9623 130.411C-8.70471 161.645 -3.34372 219.526 10.9333 260.038C32.3743 321.218 97.6263 363.843 153.085 391.417C217.151 423.424 287.557 442.031 358.117 452.7C419.967 462.132 499.444 468.936 553.047 428.579C602.269 391.418 615.773 306.683 603.712 249.472C600.784 232.571 591.785 217.321 578.405 206.589C543.821 181.282 492.228 198.188 453.365 199.012C438.934 199.322 424.244 199.683 409.503 199.786Z"
                          fill="#F2F2F2" />
                        <path
                          d="M306.008 553C410.503 553 495.217 547.717 495.217 541.197C495.217 534.677 410.503 529.394 306.008 529.394C201.511 529.394 116.799 534.677 116.799 541.197C116.799 547.717 201.51 553 306.008 553Z"
                          fill="#F2F2F2" />
                        <path
                          d="M39.0234 86.137L106.027 80.467C114.382 79.735 122.735 81.896 129.688 86.588C136.64 91.279 141.77 98.2161 144.219 106.238L208.44 321.166L195.864 343.69C192.634 349.494 191.039 356.065 191.25 362.703C191.461 369.342 193.47 375.798 197.062 381.385C200.654 386.972 205.695 391.479 211.648 394.425C217.6 397.372 224.241 398.648 230.861 398.117L488.053 376.263"
                          stroke="#DBDDEF" stroke-width="5.1542" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                          d="M153.806 138.864L452.747 113.506C455.69 113.254 458.654 113.668 461.416 114.717C464.184 115.766 466.673 117.424 468.709 119.568C470.74 121.712 472.271 124.286 473.173 127.1C474.075 129.914 474.338 132.895 473.931 135.823L453.675 282.046C453.062 286.433 450.985 290.486 447.789 293.552C444.593 296.619 440.455 298.52 436.048 298.952L208.44 321.166L153.806 138.864Z"
                          fill="#4F60F9" stroke="#DBDDEF" stroke-width="5.1542" stroke-linecap="round"
                          stroke-linejoin="round" />
                        <path
                          d="M259.466 447.391C272.874 447.391 283.742 436.521 283.742 423.115C283.742 409.709 272.873 398.839 259.466 398.839C246.059 398.839 235.19 409.709 235.19 423.115C235.19 436.521 246.058 447.391 259.466 447.391Z"
                          fill="white" stroke="#DBDDEF" stroke-width="5.1542" stroke-linecap="round"
                          stroke-linejoin="round" />
                        <path
                          d="M428.522 433.063C441.928 433.063 452.798 422.193 452.798 408.787C452.798 395.379 441.928 384.511 428.522 384.511C415.116 384.511 404.246 395.38 404.246 408.787C404.246 422.193 415.116 433.063 428.522 433.063Z"
                          fill="white" stroke="#DBDDEF" stroke-width="5.1542" stroke-linecap="round"
                          stroke-linejoin="round" />
                        <path
                          d="M92.0824 74.6631L28.9134 80.0231C23.4394 80.4881 19.3774 85.3021 19.8424 90.7761C20.3064 96.2501 25.1204 100.311 30.5954 99.8471L93.7644 94.4871C99.2384 94.0231 103.299 89.2081 102.835 83.7341C102.37 78.2601 97.5564 74.1991 92.0824 74.6631Z"
                          fill="#DBDDEF" />
                        <path
                          d="M279.67 251.791C280.903 242.385 285.314 233.682 292.172 227.127C299.03 220.572 307.923 216.558 317.376 215.752C326.829 214.946 336.273 217.395 344.143 222.694C352.012 227.992 357.834 235.822 360.642 244.884"
                          fill="white" />
                        <path
                          d="M279.67 251.791C280.903 242.385 285.314 233.682 292.172 227.127C299.03 220.572 307.923 216.558 317.376 215.752C326.829 214.946 336.273 217.395 344.143 222.694C352.012 227.992 357.834 235.822 360.642 244.884"
                          stroke="white" stroke-width="5.1542" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                          d="M366.585 190.405C366.585 193.317 364.224 195.678 361.312 195.678C358.4 195.678 356.039 193.317 356.039 190.405C356.039 187.492 358.399 185.132 361.312 185.132C364.225 185.132 366.585 187.493 366.585 190.405Z"
                          fill="white" stroke="white" />
                        <path
                          d="M275.099 198.136C275.099 201.048 272.738 203.409 269.826 203.409C266.913 203.409 264.553 201.048 264.553 198.136C264.553 195.223 266.913 192.863 269.826 192.863C272.739 192.863 275.099 195.224 275.099 198.136Z"
                          fill="white" stroke="white" />
                        <path d="M107.212 415.951V438.165" stroke="#BABABA" stroke-width="5.1542"
                          stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M96.1313 427.032H118.294" stroke="#BABABA" stroke-width="5.1542"
                          stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M461.354 3V25.163" stroke="#BABABA" stroke-width="5.1542" stroke-linecap="round"
                          stroke-linejoin="round" />
                        <path d="M450.273 14.0811H472.436" stroke="#BABABA" stroke-width="5.1542"
                          stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M198.956 69.231V91.394" stroke="#BABABA" stroke-width="5.1542" stroke-linecap="round"
                          stroke-linejoin="round" />
                        <path d="M187.823 80.312H210.037" stroke="#BABABA" stroke-width="5.1542" stroke-linecap="round"
                          stroke-linejoin="round" />
                        <path
                          d="M516.658 150.718C519.56 150.718 521.915 148.364 521.915 145.461C521.915 142.558 519.56 140.204 516.658 140.204C513.756 140.204 511.401 142.558 511.401 145.461C511.401 148.364 513.756 150.718 516.658 150.718Z"
                          fill="white" stroke="#BABABA" stroke-width="5.1542" stroke-linecap="round"
                          stroke-linejoin="round" />
                        <path
                          d="M320.182 103.454C322.971 103.454 325.233 101.193 325.233 98.403C325.233 95.614 322.971 93.3521 320.182 93.3521C317.393 93.3521 315.131 95.614 315.131 98.403C315.131 101.193 317.392 103.454 320.182 103.454Z"
                          fill="#CFCFCF" />
                        <path
                          d="M354.096 470.018C356.885 470.018 359.147 467.755 359.147 464.967C359.147 462.179 356.885 459.916 354.096 459.916C351.307 459.916 349.045 462.179 349.045 464.967C349.045 467.755 351.307 470.018 354.096 470.018Z"
                          fill="#CFCFCF" />
                      </svg>

                      <h3><strong>{{ $keywords['Your Cart is Empty'] ?? __('Your Cart is Empty') }}</strong></h3>
                      <h4>
                        {{ $keywords['Add something to make me happy'] ?? __('Add something to make me happy') . ' :)' }}
                      </h4>
                      <a class="btn btn-md btn-primary radius-sm m-3"
                        href="{{ route('front.user.shop', getParam()) }}">{{ $keywords['Back to Shop'] ?? __('Back to Shop') }}
                      </a>
                    </div>
                  </div>
                </div>
              @endif
            </div>
            <div id="refreshButton">
              @if (count($cart) > 0)
                <div class="text-end mt-30 mb-30">
                  <button class="cart-btn btn btn-md btn-outline radius-sm" id="cartUpdate"
                    data-href="{{ route('front.user.cart.update', getParam()) }}"><span>{{ $keywords['Update Cart'] ?? __('Update Cart') }}
                    </span></button>
                  <a class="btn btn-md btn-primary radius-sm border"
                    href="{{ route('front.user.checkout', getParam()) }}">{{ $keywords['Checkout'] ?? __('Checkout') }}
                  </a>
                </div>
              @endif
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
  <!-- Cart End -->
@endsection
