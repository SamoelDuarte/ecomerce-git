<section class="section pb-100 lazy">
  <div class="container">
    <div class="row align-items-start">

      <div class="col-lg-4">
        <div class="product-default product_first_item radius-md mb-20">
          <div class="product-details pt-30">
            <h3 class="product-title skeleton skeleton-title"></h3>
            <div class="product-ratings rate text-xsm skeleton skeleton-ratings"></div>

            <div class="product-price mb-0">
              <span class="new-price skeleton skeleton-price"></span>
              <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
            </div>

            <figure class="product-img skeleton skeleton-big-img"></figure>
            <div class="btn-icon-group btn-inline text-center pb-2">
              <div class="product-countdown justify-content-center hover-hide">
                <span class="count-period skeleton skeleton-btn-icon"></span>
                <span class="count-period skeleton skeleton-btn-icon"></span>
                <span class="count-period skeleton skeleton-btn-icon"></span>
                <span class="count-period skeleton skeleton-btn-icon"></span>
              </div>

            </div>
            <span class="skeleton skeleton-price">
            </span>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="section-title title-inline mb-30">
          <h2 class="title mb-0">
            {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}
          </h2>
          <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
            class="btn btn-md btn-primary radius-sm">{{ $keywords['Shop More'] ?? __('Shop More') }}</a>
        </div>
        <div class="row">

          <div class="col-lg-4 col-md-6">
            <div class="product-default radius-md mb-30">
              <figure class="product-img mb-20 skeleton skeleton-img"></figure>
              <div class="product-details">
                <div class="btn-icon-group btn-inline text-center">
                  <div class="product-countdown justify-content-center hover-hide">
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                  </div>
                </div>

                <div class="skeleton skeleton-category"></div>
                <h4 class="skeleton skeleton-title"></h4>
                <div class="skeleton skeleton-ratings"></div>

                <div class="product-price mb-0">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>
              </div>
              <span class="skeleton skeleton-price"></span>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="product-default radius-md mb-30">
              <figure class="product-img mb-20 skeleton skeleton-img"></figure>
              <div class="product-details">
                <div class="btn-icon-group btn-inline text-center">
                  <div class="product-countdown justify-content-center hover-hide">
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                  </div>
                </div>

                <div class="skeleton skeleton-category"></div>
                <h4 class="skeleton skeleton-title"></h4>
                <div class="skeleton skeleton-ratings"></div>

                <div class="product-price mb-0">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>
              </div>
              <span class="skeleton skeleton-price"></span>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="product-default radius-md mb-30">
              <figure class="product-img mb-20 skeleton skeleton-img"></figure>
              <div class="product-details">
                <div class="btn-icon-group btn-inline text-center">
                  <div class="product-countdown justify-content-center hover-hide">
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                    <span class="count-period skeleton skeleton-btn-icon"></span>
                  </div>
                </div>

                <div class="skeleton skeleton-category"></div>
                <h4 class="skeleton skeleton-title"></h4>
                <div class="skeleton skeleton-ratings"></div>

                <div class="product-price mb-0">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>
              </div>
              <span class="skeleton skeleton-price"></span>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>



<section class="section pb-100 actual-content">
  <div class="container">
    @if (count($flash_items) > 0)
      <div class="row align-items-start">
        @php
          $firstItem = $flash_items->first();
        @endphp
        <div class="col-lg-4">
          <!-- product-default -->
          <div class="product-default product_first_item product-default-2 radius-md mb-20">
            <div class="product-details pt-30">
              <h3 class="product-title lc-2">
                <a
                  href="{{ route('front.user.productDetails', [getParam(), 'slug' => $firstItem->slug]) }}">{{ $firstItem->title }}</a>
              </h3>
              @if ($shop_settings->item_rating_system == 1)
                <div class="d-flex align-items-center">
                  <div class="product-ratings rate text-xsm">
                    <div class="rating" style="width:{{ $firstItem->rating * 20 }}%"></div>
                  </div>
                  <span class="ratings-total">({{ reviewCount($firstItem->item_id) }})</span>
                </div>
              @endif

              <div class="product-price mb-0">
                <span
                  class="new-price">{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($firstItem->current_price - $firstItem->current_price * ($firstItem->flash_amount / 100))) }}</span>
                <span
                  class="old-price text-decoration-line-through">{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($firstItem->current_price)) }}</span>
              </div>

              <figure class="product-img">
                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $firstItem->slug]) }}"
                  class="lazy-container ratio ratio-1-1" tabindex="0">
                  <img class="default-img ls-is-cached lazyload"
                    src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $firstItem->thumbnail) }}"
                    alt="Product">
                  <img class="hover-img ls-is-cached lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $firstItem->thumbnail) }}"
                    alt="Product">
                </a>
              </figure>
              <div class="btn-icon-group btn-inline text-center pb-2">
                <div class="product-countdown justify-content-center hover-hide"
                  data-start_date="{{ $firstItem->start_date }}" data-end_time="{{ $firstItem->end_time }}"
                  data-end_date="{{ $firstItem->end_date }}" data-item_id="{{ $firstItem->item_id }}">
                  <div class="count rounded-pill days">
                    <span class="count-value_{{ $firstItem->item_id }}"></span>
                    <span class="count-period">{{ $keywords['Days'] ?? __('Days') }}</span>
                  </div>
                  <div class="count rounded-pill hours">
                    <span class="count-value_{{ $firstItem->item_id }}"></span>
                    <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
                  </div>
                  <div id="" class="count rounded-pill minutes">
                    <span class="count-value_{{ $firstItem->item_id }}"></span>
                    <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
                  </div>
                  <div id="" class="count rounded-pill seconds">
                    <span class="count-value_{{ $firstItem->item_id }}"></span>
                    <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
                  </div>
                </div>

                <div class="hover-show">
                  @if ($shop_settings->catalog_mode != 1)
                    @php
                      $current_pricce =
                          $firstItem->flash == 1
                              ? $firstItem->current_price - $firstItem->current_price * ($firstItem->flash_amount / 100)
                              : $firstItem->current_price;
                    @endphp
                    <a href="#" class="btn btn-icon rounded-pill color-primary cart-link" data-bs-toggle="tooltip"
                      data-bs-placement="top" data-title="{{ $firstItem->title }}"
                      data-current_price="{{ currency_converter($current_pricce) }}"
                      data-item_id="{{ $firstItem->item_id }}" data-language_id="{{ $uLang }}"
                      data-totalVari="{{ check_variation($firstItem->item_id) }}"
                      data-variations="{{ check_variation($firstItem->item_id) > 0 ? 'yes' : null }}"
                      data-href="{{ route('front.user.add.cart', ['id' => $firstItem->item_id, getParam()]) }}"
                      title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i class="fal fa-shopping-bag"></i>
                    </a>
                  @endif
                  <a href="javascript:void(0)"
                    onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $firstItem->item_id, getParam()]) }}')"
                    class="btn btn-icon rounded-pill color-primary ms-0" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                      class="fal fa-random"></i>
                  </a>
                  <button type="button" class="btn btn-icon rounded-pill color-primary quick-view-link"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $firstItem->item_id }}"
                    data-url="{{ route('front.user.productDetails.quickview', ['slug' => $firstItem->slug, getParam()]) }}"
                    title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                    <i class="fal fa-eye"></i>
                  </button>
                  @php
                    $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                    $checkWishList = $customer_id ? checkWishList($firstItem->item_id, $customer_id) : false;
                  @endphp
                  <a href="#"
                    class="btn btn-icon rounded-pill color-primary {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"
                    data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $firstItem->item_id, getParam()]) }}"
                    data-href="{{ route('front.user.add.wishlist', ['id' => $firstItem->item_id, getParam()]) }}"><i
                      class="fal fa-heart"></i>
                  </a>
                </div>

              </div>
              <span class="label-discount-percentage">
                <x-flash-icon></x-flash-icon>{{ $firstItem->flash_amount }}%
              </span>
            </div>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="section-title title-inline mb-30">
            <h2 class="title mb-0">
              {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}
            </h2>
            <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
              class="btn btn-md btn-primary radius-sm">{{ $keywords['Shop More'] ?? __('Shop More') }}</a>
          </div>
          <div class="row">
            @foreach ($flash_items->skip(1) as $item)
              <div class="col-lg-4 col-md-6">
                <!-- product-default -->
                <div class="product-default product-default-2 radius-md mb-30">
                  <figure class="product-img mb-20">
                    <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                      class="lazy-container ratio ratio-1-1" tabindex="0">
                      <img class="default-img ls-is-cached lazyload"
                        src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                        alt="Product">
                      <img class="hover-img ls-is-cached lazyload"
                        src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                        alt="Product">
                    </a>
                  </figure>
                  <div class="product-details">
                    <div class="btn-icon-group btn-inline text-center">
                      <div class="product-countdown justify-content-center hover-hide"
                        data-start_date="{{ $item->start_date }}" data-end_time="{{ $item->end_time }}"
                        data-end_date="{{ $item->end_date }}" data-item_id="{{ $item->item_id }}">
                        <div id="" class="count rounded-pill days">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Days'] ?? __('Days') }}</span>
                        </div>
                        <div id="" class="count rounded-pill hours">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
                        </div>
                        <div id="" class="count rounded-pill minutes">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
                        </div>
                        <div id="" class="count rounded-pill seconds">
                          <span class="count-value_{{ $item->item_id }}"></span>
                          <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
                        </div>
                      </div>

                      <div class="hover-show">

                        @if ($shop_settings->catalog_mode != 1)
                          @php
                            $current_pricce =
                                $item->flash == 1
                                    ? $item->current_price - $item->current_price * ($item->flash_amount / 100)
                                    : $item->current_price;
                          @endphp
                          <a href="#" class="btn btn-icon rounded-pill color-primary cart-link"
                            data-title="{{ $item->title }}"
                            data-current_price="{{ currency_converter($current_pricce) }}"
                            data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                            data-totalVari="{{ check_variation($item->item_id) }}"
                            data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                            data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                            data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                              class="fal fa-shopping-bag"></i>
                          </a>
                        @endif

                        <a href="javascript:void(0)"
                          onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                          class="btn btn-icon rounded-pill color-primary ms-0" data-bs-toggle="tooltip"
                          data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                            class="fal fa-random"></i>
                        </a>

                        <button type="button" class="btn btn-icon rounded-pill color-primary quick-view-link"
                          data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                          data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                          title="{{ $keywords['Quick View'] ?? __('Quick View') }}" data-bs-toggle="modal"
                          data-bs-target="#quickViewModal">
                          <i class="fal fa-eye"></i>
                        </button>

                        @php
                          $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                          $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                        @endphp
                        <a href="#"
                          class="btn btn-icon rounded-pill color-primary {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                          data-item_id="{{ $item->item_id }}"
                          data-href="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                          data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                          data-bs-original-title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                            class="fal fa-heart"></i>
                        </a>

                      </div>
                    </div>

                    <a href="{{ route('front.user.shop', ['category' => $item->category_slug, getParam()]) }}"
                      class="product-category text-sm">{{ $item->category }}</a>
                    <h4 class="product-title lc-2">
                      <a
                        href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ $item->title }}</a>
                    </h4>

                    @if ($shop_settings->item_rating_system == 1)
                      <div class="d-flex align-items-center">
                        <div class="product-ratings rate text-xsm">
                          <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                        </div>
                        <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                      </div>
                    @endif

                    <div class="product-price mb-0">
                      <span class="new-price">
                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price - $item->current_price * ($item->flash_amount / 100))) }}
                      </span>
                      <span class="old-price text-decoration-line-through">
                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                      </span>
                    </div>
                  </div>
                  @php
                    $item_label = DB::table('labels')->where('id', $item->label_id)->first();
                    $label = $item_label->name ?? null;
                    $color = $item_label->color ?? null;
                  @endphp

                  @if ($item_label)
                    <span class="label label-2 label-blue"
                      style="background-color: #{{ $color }}">{{ $label }}</span>
                  @endif

                  <span class="label-discount-percentage">
                    <x-flash-icon></x-flash-icon>{{ $item->flash_amount }}%
                  </span>

                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>
</section>

