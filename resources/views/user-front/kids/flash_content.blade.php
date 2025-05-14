<!-- Product Start -->
<section class="products pt-100 mt-20 lazy">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title title-inline mb-20">
          <h2 class="title mb-20">
            {{ $userSec->flash_section_title ?? ($keywords['Flash Section'] ?? __('Flash Section')) }}</h2>
          @if (count($flash_items) > 0)
            <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
              class="btn btn-md btn-primary rounded-pill icon-end shadow mb-20">
              {{ $keywords['View More'] ?? __('View More') }}
              <span class="icon"><i class="fal fa-arrow-right"></i></span>
            </a>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-6">

          <div class="mb-30">

            <div class="product-default product-default-4 radius-lg mb-30 color-1">
              <div class="row align-items-center">
                <div class="col-lg-6">
                  <figure class="product-img skeleton skeleton-big-img"></figure>
                </div>
                <div class="col-lg-6">
                  <div class="product-details">
                    <div class="skeleton skeleton-category"></div>
                    <div class="skeleton skeleton-title"></div>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mt-2 mb-10">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                    </div>

                    <div class="product-countdown">
                      <span class="count-period skeleton skeleton-btn-icon"></span>
                      <span class="count-period skeleton skeleton-btn-icon"></span>
                      <span class="count-period skeleton skeleton-btn-icon"></span>
                      <span class="count-period skeleton skeleton-btn-icon"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6">

          <div class="mb-30">

            <div class="product-default product-default-4 radius-lg mb-30 color-1">
              <div class="row align-items-center">
                <div class="col-lg-6">
                  <figure class="product-img skeleton skeleton-big-img"></figure>
                </div>
                <div class="col-lg-6">
                  <div class="product-details">
                    <div class="skeleton skeleton-category"></div>
                    <div class="skeleton skeleton-title"></div>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mt-2 mb-10">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                    </div>

                    <div class="product-countdown">
                      <span class="count-period skeleton skeleton-btn-icon"></span>
                      <span class="count-period skeleton skeleton-btn-icon"></span>
                      <span class="count-period skeleton skeleton-btn-icon"></span>
                      <span class="count-period skeleton skeleton-btn-icon"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bg Shape -->
  <div class="shape">
    <img class="shape-1" src="{{ asset('assets/front/img/shape/shape-5.png') }}" alt="Shape">
    <img class="shape-2" src="{{ asset('assets/front/img/shape/shape-6.png') }}" alt="Shape">
    <img class="shape-3" src="{{ asset('assets/front/img/shape/shape-7.png') }}" alt="Shape">
  </div>
</section>
<!-- Product End -->


<!-- Product Start -->
<section class="products pt-100 mt-20 actual-content">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title title-inline mb-20">
          <h2 class="title mb-20">
            {{ $userSec->flash_section_title ?? ($keywords['Flash Section'] ?? __('Flash Section')) }}</h2>
          @if (count($flash_items) > 0)
            <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
              class="btn btn-md btn-primary rounded-pill icon-end shadow mb-20">
              {{ $keywords['View More'] ?? __('View More') }}
              <span class="icon"><i class="fal fa-arrow-right"></i></span>
            </a>
          @endif
        </div>
      </div>
      <div class="col-12">
        @if (count($flash_items) == 0)
          <h5 class="title text-center mb-20">
            {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
          </h5>
        @else
          <div class="product-slider mb-30" id="pro-slider-kid-1"
            data-slick='{
              "dots": true,
              "slidesToShow": 2,
              "responsive": [
                  {
                      "breakpoint": 1300,
                      "settings": {
                          "slidesToShow": 2
                      }
                  },
                  {
                      "breakpoint": 1200,
                      "settings": {
                          "slidesToShow": 1
                      }
                  }
              ]
            }'>

            @foreach ($flash_items as $item)
              <div class="product-default product-default-4 radius-lg mb-30 color-1">
                <div class="row align-items-center">
                  <div class="col-lg-6">
                    <figure class="product-img radius-md">
                      <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                        class="lazy-container ratio ratio-1-1">
                        <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                          alt="Product">
                        <img class="lazyload hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                          alt="Product">
                      </a>

                      <div class="btn-icon-group btn-inline text-center mt-10">

                        @if ($shop_settings->catalog_mode != 1)
                          @php
                            $current_pricce =
                                $item->flash == 1
                                    ? $item->current_price - $item->current_price * ($item->flash_amount / 100)
                                    : $item->current_price;
                          @endphp
                          <a class="btn btn-icon rounded-pill color-primary cart-link cursor-pointer"
                            data-title="{{ $item->title }}"
                            data-current_price="{{ currency_converter($current_pricce) }}"
                            data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                            data-totalVari="{{ check_variation($item->item_id) }}"
                            data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                            data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                              class="fal fa-shopping-cart "></i></a>
                        @endif

                        <a onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                          class="btn btn-icon rounded-pill color-primary ms-0" data-bs-toggle="tooltip"
                          data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                            class="fal fa-random"></i></a>

                        <a class="btn btn-icon rounded-pill color-primary quick-view-link" data-bs-toggle="tooltip"
                          data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                          data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                          title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i></a>

                        @php
                          $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                          $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                        @endphp
                        <a href="#"
                          class="btn btn-icon rounded-pill color-primary {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                          data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                          data-href="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                            class="fal fa-heart"></i></a>

                      </div>
                    </figure>
                  </div>
                  <div class="col-lg-6">
                    <div class="product-details">
                      @if (!is_null(@$item->category))
                        <a href="{{ route('front.user.shop', ['category' => $item->category_slug, getParam()]) }}"
                          class="product-category text-sm">{{ @$item->category }}</a>
                      @endif
                      <h3 class="product-title lc-2">
                        <a
                          href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ $item->title }}</a>
                      </h3>

                      @if ($shop_settings->item_rating_system == 1)
                        <div class="d-flex align-items-center">
                          <div class="product-ratings rate text-xsm">
                            <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                          </div>
                          <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                        </div>
                      @endif

                      <div class="product-price mt-2 mb-10">
                        @if ($item->flash == 1)
                          <span class="new-price">
                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price - $item->current_price * ($item->flash_amount / 100))) }}
                          </span>

                          <span class="old-price text-decoration-line-through">
                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                          </span>
                          &nbsp;
                          <span class="old-price ">{{ $item->flash_amount }}%
                            {{ $keywords['OFF'] ?? __('OFF') }}</span>
                        @else
                          <span class="new-price">
                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                          </span>
                          <span class="old-price text-decoration-line-through">
                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->previous_price)) }}
                          </span>
                        @endif

                      </div>

                      <div class="product-countdown" data-start_date="{{ $item->start_date }}"
                        data-end_time="{{ $item->end_time }}" data-end_date="{{ $item->end_date }}"
                        data-item_id="{{ $item->item_id }}">
                        <div id="" class="count radius-sm days">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Days'] ?? __('Days') }} </span>
                        </div>
                        <div id="" class="count radius-sm hours">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
                        </div>
                        <div id="" class="count radius-sm minutes">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
                        </div>
                        <div id="" class="count radius-sm seconds">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            @endforeach

          </div>
        @endif
      </div>
    </div>
  </div>
  <!-- Bg Shape -->
  <div class="shape">
    <img class="shape-1" src="{{ asset('assets/front/img/shape/shape-5.png') }}" alt="Shape">
    <img class="shape-2" src="{{ asset('assets/front/img/shape/shape-6.png') }}" alt="Shape">
    <img class="shape-3" src="{{ asset('assets/front/img/shape/shape-7.png') }}" alt="Shape">
  </div>
</section>
<!-- Product End -->
