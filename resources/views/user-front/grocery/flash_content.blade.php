<!-- Flash Sale Start -->
<section class="flash-sale pb-70 actual-content">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title title-inline mb-10">
          <h2 class="title mb-20">
            {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}</h2>
          @if (count($flash_items) > 0)
            <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
              class="btn btn-md btn-primary radius-sm mb-20">{{ $keywords['Shop More'] ?? __('Shop More') }}</a>
          @endif
        </div>
      </div>
      <div class="col-12">
        @if (count($flash_items) == 0)
          <h5 class="title text-center mb-20">
            {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
          </h5>
        @else
          <div class="row">

            @foreach ($flash_items as $item)
              <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="product-box product-default radius-xl mb-30">
                  @php
                    $item_label = DB::table('labels')->where('id', $item->label_id)->first();
                    $label = $item_label->name ?? null;
                    $color = $item_label->color ?? null;
                  @endphp
                  <span class="label label-2" style="background-color: #{{ $color }}">{{ $label }}</span>
                  <figure class="product-img">
                    <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                      class="lazy-container ratio ratio-1-1">
                      <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                        alt="Product">
                      <img class="lazyload hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                        alt="Product">
                    </a>
                    <div class="btn-icon-group btn-inline btn-center-position">
                      @if ($shop_settings->catalog_mode != 1)
                        @php
                          $current_price =
                              $item->flash == 1
                                  ? $item->current_price - $item->current_price * ($item->flash_amount / 100)
                                  : $item->current_price;
                        @endphp
                        <a class=" btn btn-icon hover-show radius-sm cart-link cursor-pointer"
                          data-title="{{ $item->title }}"
                          data-current_price="{{ currency_converter($current_price) }}"
                          data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                          data-totalVari="{{ check_variation($item->item_id) }}"
                          data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                          data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                          data-bs-toggle="tooltip" data-bs-placement="top"
                          title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                            class="far fa-shopping-cart "></i></a>
                      @endif

                      <a href="javascript:void(0)" class="btn btn-icon hover-show radius-sm quick-view-link"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                        data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                        title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i></a>

                      @php
                        $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                        $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                      @endphp
                      <a href="#"
                        class="btn btn-icon hover-show radius-sm {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-url="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                        data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                        title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                          class="fal fa-heart"></i></a>

                      <a onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                        class="btn btn-icon hover-show radius-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i></a>
                    </div>
                  </figure>

                  <div class="product-details">
                    <span class="product-category text-sm"><a
                        href="{{ route('front.user.shop', ['category' => $item->category_slug, getParam()]) }}">{{ $item->category }}</a></span>
                    <h3 class="product-title lc-2"><a
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


                    <div class="product-price">
                      @if ($item->flash == 1)
                        <span class="new-price">
                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price - $item->current_price * ($item->flash_amount / 100))) }}
                        </span>

                        <span class="old-price line_through">
                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                        </span>
                      @else
                        <span class="new-price">
                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                        </span>

                        <span class="old-price line_through">
                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->previous_price)) }}
                        </span>
                      @endif
                    </div>


                    <div class="product-countdown justify-content-start" data-start_date="{{ $item->start_date }}"
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
<!-- Flash Sale End -->


<!-- Flash Sale Start -->
<section class="flash-sale pb-70 lazy" >
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title title-inline mb-10">
          <h2 class="title mb-20">
            {{ $userSec->flash_section_title ?? ($keywords['Super Flash Sale'] ?? __('Super Flash Sale')) }}</h2>
          @if (count($flash_items) > 0)
            <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
              class="btn btn-md btn-primary radius-sm mb-20">{{ $keywords['Shop More'] ?? __('Shop More') }}</a>
          @endif
        </div>
      </div>
      <div class="col-12">
        <div class="row">
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="product-box product-default radius-xl mb-30">
              <figure class="product-img skeleton skeleton-big-img"></figure>

              <div class="product-details">
                <div class="skeleton skeleton-category"></div>
                <h4 class="skeleton skeleton-title"></h4>
                <div class="skeleton skeleton-ratings"></div>


                <div class="product-price">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>


                <div class="product-countdown justify-content-start">
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="product-box product-default radius-xl mb-30">
              <figure class="product-img skeleton skeleton-big-img"></figure>

              <div class="product-details">
                <div class="skeleton skeleton-category"></div>
                <h4 class="skeleton skeleton-title"></h4>
                <div class="skeleton skeleton-ratings"></div>


                <div class="product-price">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>


                <div class="product-countdown justify-content-start">
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="product-box product-default radius-xl mb-30">
              <figure class="product-img skeleton skeleton-big-img"></figure>

              <div class="product-details">
                <div class="skeleton skeleton-category"></div>
                <h4 class="skeleton skeleton-title"></h4>
                <div class="skeleton skeleton-ratings"></div>


                <div class="product-price">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>


                <div class="product-countdown justify-content-start">
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                  <span class="count-period skeleton skeleton-btn-icon"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="product-box product-default radius-xl mb-30">
              <figure class="product-img skeleton skeleton-big-img"></figure>

              <div class="product-details">
                <div class="skeleton skeleton-category"></div>
                <h4 class="skeleton skeleton-title"></h4>
                <div class="skeleton skeleton-ratings"></div>


                <div class="product-price">
                  <span class="new-price skeleton skeleton-price"></span>
                  <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                </div>


                <div class="product-countdown justify-content-start">
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
</section>
<!-- Flash Sale End -->
