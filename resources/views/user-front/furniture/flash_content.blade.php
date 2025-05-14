   <!-- New Product Start -->
  <section class="new-product ptb-100 lazy" >
    <div class="container">
      <div class="row gx-xl-5 align-items-center">
        <div class="col-lg-4">
          <div class="content mb-30">
            <h2 class="title mb-30">
              {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Flash Section')) }}
              <span class="line left_right_slide_anim"></span>
            </h2>
            <p class="text mb-40">{{ $userSec->flash_section_subtitle ?? '' }} </p>
            @if (count($flash_items) > 0)
              <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
                class="btn btn-lg btn-primary">
                {{ $keywords['View_Collection'] ?? __('View Collection') }} </a>
            @endif
          </div>
        </div>
        <div class="col-lg-8">

          <div class="product-slider mb-30" id="pro-slider-furniture-skeleton"
            data-slick='{"dots": true, "slidesToShow": 3}'>

            <div class="product-default product-default-2 product-center mb-30 ">
              <figure class="product-img skeleton skeleton-big-img"></figure>
              <div class="product-details">
                <div class="product-countdown justify-content-center mb-3">
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                </div>
                <div class="btn-icon-group btn-inline mb-10 d-flex justify-content-center">
                  <div class="skeleton skeleton-category"></div>
                </div>
                <div class="skeleton skeleton-title"></div>

                <div class="d-flex justify-content-center align-items-center">
                  <div class="skeleton skeleton-ratings">
                  </div>
                </div>

                <div class="product-price mb-0">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>

              </div>
            </div>
            <div class="product-default product-default-2 product-center mb-30 ">
              <figure class="product-img skeleton skeleton-big-img"></figure>
              <div class="product-details">
                <div class="product-countdown justify-content-center mb-3">
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                </div>
                <div class="btn-icon-group btn-inline mb-10 d-flex justify-content-center">
                  <div class="skeleton skeleton-category"></div>
                </div>
                <div class="skeleton skeleton-title"></div>

                <div class="d-flex justify-content-center align-items-center">
                  <div class="skeleton skeleton-ratings">
                  </div>
                </div>

                <div class="product-price mb-0">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>

              </div>
            </div>
            <div class="product-default product-default-2 product-center mb-30 ">
              <figure class="product-img skeleton skeleton-big-img"></figure>
              <div class="product-details">
                <div class="product-countdown justify-content-center mb-3">
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                </div>
                <div class="btn-icon-group btn-inline mb-10 d-flex justify-content-center">
                  <div class="skeleton skeleton-category"></div>
                </div>
                <div class="skeleton skeleton-title"></div>

                <div class="d-flex justify-content-center align-items-center">
                  <div class="skeleton skeleton-ratings">
                  </div>
                </div>

                <div class="product-price mb-0">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>

              </div>
            </div>

          </div>

        </div>
      </div>
    </div>
  </section>
  <!-- New Product Start -->
 <!-- New Product Start -->


  <section class="new-product ptb-100 actual-content">
    <div class="container">
      <div class="row gx-xl-5 align-items-center">
        <div class="col-lg-4">
          <div class="content mb-30">
            <h2 class="title mb-30">
              {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Flash Section')) }}
              <span class="line left_right_slide_anim"></span>
            </h2>
            <p class="text mb-40">{{ $userSec->flash_section_subtitle ?? '' }} </p>
            @if (count($flash_items) > 0)
              <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
                class="btn btn-lg btn-primary">
                {{ $keywords['View_Collection'] ?? __('View Collection') }} </a>
            @endif
          </div>
        </div>
        <div class="col-lg-8">
          @if (count($flash_items) == 0)
            <h5 class="">
              {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
            </h5>
          @else
            <div class="product-slider mb-30" id="pro-slider-furniture" data-slick='{"dots": true, "slidesToShow": 3}'>
              @foreach ($flash_items as $item)
                <div class="product-default product-default-2 product-center mb-30 ">
                  <figure class="product-img">
                    <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                      class="lazy-container ratio ratio-1-1">
                      <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }} "
                        alt="Product">
                      <img class="lazyload hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }} "
                        alt="Product">
                    </a>
                  </figure>
                  <div class="product-details">
                    <div class="product-countdown justify-content-center mb-3"
                      data-start_date="{{ $item->start_date }}" data-end_time="{{ $item->end_time }}"
                      data-end_date="{{ $item->end_date }}" data-item_id="{{ $item->item_id }}">
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
                    <div class="btn-icon-group btn-inline mb-10">
                      <a href="#" class="btn btn-sm btn-icon radius-0 w-auto icon-start hover-hide"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Cart"><i
                          class="fal fa-shopping-bag"></i> {{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}
                      </a>
                      <div class="hover-show">
                        @if ($shop_settings->catalog_mode != 1)
                          @php
                            $current_price =
                                $item->flash == 1
                                    ? $item->current_price - $item->current_price * ($item->flash_amount / 100)
                                    : $item->current_price;
                          @endphp
                          <a class=" btn btn-icon radius-0 cart-link cursor-pointer" data-title="{{ $item->title }}"
                            data-current_price="{{ currency_converter($current_price) }}"
                            data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                            data-totalVari="{{ check_variation($item->item_id) }}"
                            data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                            data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                            data-bs-toggle="tooltip" data-placement="top"
                            title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                              class="far fa-shopping-cart "></i></a>
                        @endif

                        <a class="btn btn-icon radius-0 quick-view-link" data-bs-toggle="tooltip"
                          data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                          data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                          title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i></a>
                        <a onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                          class="btn btn-icon radius-0" data-bs-toggle="tooltip" data-bs-placement="top"
                          title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i></a>

                        @php
                          $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                          $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                        @endphp
                        <a href="#"
                          class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                          data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                          data-href="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                            class="fal fa-heart"></i>
                        </a>
                      </div>
                    </div>
                    <h3 class="product-title lc-1">
                      <a
                        href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ $item->title }}</a>
                    </h3>

                    @if ($shop_settings->item_rating_system == 1)
                      <div class="d-flex justify-content-center align-items-center">
                        <div class="product-ratings rate text-xsm">
                          <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                        </div>
                        <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                      </div>
                    @endif

                    <div class="product-price mb-0">
                      @if ($item->flash == 1)
                        <span class="new-price">
                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price - $item->current_price * ($item->flash_amount / 100))) }}
                        </span>

                        <span class="old-price text-decoration-line-through ms-2">
                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                        </span>
                      @else
                        <span class="new-price">
                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                        </span>
                        <span class="old-price">{{ currency_converter($item->previous_price) }}</span>
                      @endif
                    </div>

                    @if ($item->flash == 1)
                      <span
                        class="label-discount-percentage"><x-flash-icon></x-flash-icon>{{ $item->flash_amount }}%</span>
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
  <!-- New Product Start -->


