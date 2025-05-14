  <!-- Product List Start -->
  <section class="list-product pt-100 pb-70 lazy" >
    <div class="container">
      <div class="row gx-xl-5">
        @if ($ubs->top_rated_section == 1)
          <div class="col-lg-4">
            <div class="section-title title-inline title-bottom-line mb-10">
              <h2 class="title title-sm mb-0">{{ $userSec->top_rated_product_section_title ?? __('Top Rated') }}</h2>
            </div>
            <div class="product-list mb-30">
              <div>
                <div class="product-default product-inline product-inline-style-2 mt-20">
                  <figure class="product-img radius-md skeleton skeleton-img"></figure>
                  <div class="product-details">
                    <h4 class="skeleton skeleton-title"></h4>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mt-2 mb-10">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price line_through skeleton skeleton-price"></span>
                    </div>

                    <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex">
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                    </div>
                  </div>
                </div>
                <div class="product-default product-inline product-inline-style-2 mt-20">
                  <figure class="product-img radius-md skeleton skeleton-img"></figure>
                  <div class="product-details">
                    <h4 class="skeleton skeleton-title"></h4>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mt-2 mb-10">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price line_through skeleton skeleton-price"></span>
                    </div>

                    <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex">
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                    </div>
                  </div>
                </div>
                <div class="product-default product-inline product-inline-style-2 mt-20">
                  <figure class="product-img radius-md skeleton skeleton-img"></figure>
                  <div class="product-details">
                    <h4 class="skeleton skeleton-title"></h4>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mt-2 mb-10">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price line_through skeleton skeleton-price"></span>
                    </div>

                    <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex">
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

        @if ($ubs->bottom_middle_banner_section == 1)
          <div class="col-lg-4">
            @if ($banners)
              @for ($i = 4; $i < count($banners); $i++)
                @if ($banners[$i]->position == 'bottom_middle')
                  <div class="banner-sm banner-vertical content-top mb-30 ratio">
                    <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                      alt="Banner">
                    <div class="banner-content justify-content-center">
                      <div class="content-inner text-center">
                        <span class="sub-title text-white">{{ $banners[$i]->title }}</span>
                        <h2 class="title-md">{{ $banners[$i]->subtitle }}</h2>
                        @if ($banners[$i]->button_text)
                          <a href="{{ $banners[$i]->banner_url }}"
                            class="btn btn-md btn-outline">{{ $banners[$i]->button_text }}</a>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif
              @endfor
            @endif

          </div>
        @endif

        @if ($ubs->top_selling_section == 1)
          <div class="col-lg-4">
            <div class="section-title title-inline title-bottom-line mb-10">
              <h2 class="title title-sm mb-0">{{ $userSec->top_selling_product_section_title ?? __('Top Selling') }}
              </h2>
            </div>
            <div class="product-list mb-30">
              <div>
                <div class="product-default product-inline product-inline-style-2 mt-20">
                  <figure class="product-img radius-md skeleton skeleton-img"></figure>
                  <div class="product-details">
                    <h4 class="skeleton skeleton-title"></h4>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mt-2 mb-10">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price line_through skeleton skeleton-price"></span>
                    </div>

                    <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex">
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                    </div>
                  </div>
                </div>
                <div class="product-default product-inline product-inline-style-2 mt-20">
                  <figure class="product-img radius-md skeleton skeleton-img"></figure>
                  <div class="product-details">
                    <h4 class="skeleton skeleton-title"></h4>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mt-2 mb-10">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price line_through skeleton skeleton-price"></span>
                    </div>

                    <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex">
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                    </div>
                  </div>
                </div>
                <div class="product-default product-inline product-inline-style-2 mt-20">
                  <figure class="product-img radius-md skeleton skeleton-img"></figure>
                  <div class="product-details">
                    <h4 class="skeleton skeleton-title"></h4>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mt-2 mb-10">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price line_through skeleton skeleton-price"></span>
                    </div>

                    <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex">
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                      <span class="skeleton skeleton-btn-icon"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
  <!-- Product List End -->


  <!-- Product List Start -->
  <section class="list-product pt-100 pb-70 actual-content">
    <div class="container">
      <div class="row gx-xl-5">
        @if ($ubs->top_rated_section == 1)
          <div class="col-lg-4">
            <div class="section-title title-inline title-bottom-line mb-10">
              <h2 class="title title-sm mb-0">{{ $userSec->top_rated_product_section_title ?? __('Top Rated') }}</h2>
              <div class="slider-arrow-inline" id="product-list-slider-1-arrows">
              </div>
            </div>
            <div class="product-list mb-30">
              @if (count($top_rated) == 0)
                <h5 class="title text-center mb-20">
                  {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
                </h5>
              @else
                <div class="product-list-slider" id="product-list-slider-1">
                  @for ($k = 0; $k <= count($top_rated); $k += 4)
                    @if ($k < count($top_rated) - 1)
                      @if (count($top_rated[$k]->itemContents) > 0)
                        <div>
                          @if (isset($top_rated[$k]))
                            <div class="product-default product-inline product-inline-style-2 mt-20">
                              <figure class="product-img">
                                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k]->itemContents[0]->slug]) }}"
                                  class="lazy-container ratio ratio-1-1">
                                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_rated[$k]->thumbnail) }}"
                                    alt="">
                                </a>
                              </figure>
                              <div class="product-details">
                                <h4 class="product-title lc-1">
                                  <a
                                    href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k]->itemContents[0]->slug]) }}">{{ $top_rated[$k]->itemContents[0]->title }}</a>
                                </h4>

                                @if ($shop_settings->item_rating_system == 1)
                                  <div class="d-flex align-items-center">
                                    <div class="product-ratings rate text-xsm">
                                      <div class="rating" style="width:{{ $top_rated[$k]->rating * 20 }}%"></div>
                                    </div>
                                    <span class="ratings-total">({{ reviewCount($top_rated[$k]->id) }})</span>
                                  </div>
                                @endif

                                @php
                                  $flash_info = flashAmountStatus($top_rated[$k]->id, $top_rated[$k]->current_price);
                                  $product_current_price = $flash_info['amount'];
                                  $flash_status = $flash_info['status'];
                                @endphp
                                <div class="product-price mt-2 mb-10">
                                  @if ($flash_status == true)
                                    <span class="new-price">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                    </span>
                                    <span class="old-price text-decoration-line-through">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k]->current_price)) }}
                                    </span>
                                  @else
                                    <span class="new-price">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k]->current_price)) }}
                                    </span>
                                    <span class="old-price text-decoration-line-through">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k]->previous_price)) }}
                                    </span>
                                  @endif
                                </div>

                                <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                                  @if ($shop_settings->catalog_mode != 1)
                                    <a class="btn btn-icon radius-0 cart-link cursor-pointer"
                                      data-title="{{ $top_rated[$k]->itemContents[0]->title }}"
                                      data-current_price="{{ currency_converter($product_current_price) }}"
                                      data-item_id="{{ $top_rated[$k]->id }}" data-language_id="{{ $uLang }}"
                                      data-totalVari="{{ check_variation($top_rated[$k]->id) }}"
                                      data-variations="{{ check_variation($top_rated[$k]->id) > 0 ? 'yes' : null }}"
                                      data-href="{{ route('front.user.add.cart', ['id' => $top_rated[$k]->id, getParam()]) }}"
                                      data-bs-toggle="tooltip" data-placement="top"
                                      title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                        class="far fa-shopping-cart "></i></a>
                                  @endif

                                  <a href="javascript:void(0)" class="btn btn-icon radius-0 quick-view-link"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-item_id="{{ $top_rated[$k]->id }}"
                                    data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_rated[$k]->itemContents[0]->slug, getParam()]) }}"
                                    title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                      class="fal fa-eye"></i>
                                  </a>

                                  <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                    onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_rated[$k]->id, getParam()]) }}')"
                                    data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                      class="fal fa-random"></i></a>
                                  @php
                                    $customer_id = Auth::guard('customer')->check()
                                        ? Auth::guard('customer')->user()->id
                                        : null;
                                    $checkWishList = $customer_id
                                        ? checkWishList($top_rated[$k]->id, $customer_id)
                                        : false;
                                  @endphp
                                  <a href="#"
                                    class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-item_id="{{ $top_rated[$k]->id }}"
                                    data-href="{{ route('front.user.add.wishlist', ['id' => $top_rated[$k]->id, getParam()]) }}"
                                    data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_rated[$k]->id, getParam()]) }}"
                                    title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                      class="fal fa-heart"></i>
                                  </a>
                                </div>
                              </div>
                            </div>
                          @endif
                          @if (isset($top_rated[$k + 1]))
                            @if (!is_null(@$top_rated[$k + 1]->itemContents[0]->slug))
                              <div class="product-default product-inline product-inline-style-2 mt-20">
                                <figure class="product-img">
                                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k + 1]->itemContents[0]->slug]) }}"
                                    class="lazy-container ratio ratio-1-1">
                                    <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_rated[$k + 1]->thumbnail) }}"
                                      alt="">
                                  </a>
                                </figure>
                                <div class="product-details">
                                  <h4 class="product-title lc-1">
                                    <a
                                      href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k + 1]->itemContents[0]->slug]) }}">{{ $top_rated[$k + 1]->itemContents[0]->title }}</a>
                                  </h4>
                                  @if ($shop_settings->item_rating_system == 1)
                                    <div class="d-flex align-items-center">
                                      <div class="product-ratings rate text-xsm">
                                        <div class="rating" style="width:{{ $top_rated[$k + 1]->rating * 20 }}%">
                                        </div>
                                      </div>
                                      <span class="ratings-total">({{ reviewCount($top_rated[$k + 1]->id) }})</span>
                                    </div>
                                  @endif
                                  @php
                                    $flash_info = flashAmountStatus(
                                        $top_rated[$k + 1]->id,
                                        $top_rated[$k + 1]->current_price,
                                    );
                                    $product_current_price = $flash_info['amount'];
                                    $flash_status = $flash_info['status'];
                                  @endphp

                                  <div class="product-price mt-2 mb-10">
                                    @if ($flash_status == true)
                                      <span class="new-price">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                      </span>
                                      <span class="old-price text-decoration-line-through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k + 1]->current_price)) }}
                                      </span>
                                    @else
                                      <span class="new-price">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k + 1]->current_price)) }}
                                      </span>
                                      <span class="old-price text-decoration-line-through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k + 1]->previous_price)) }}
                                      </span>
                                    @endif
                                  </div>

                                  <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                                    @if ($shop_settings->catalog_mode != 1)
                                      <a class="btn btn-icon radius-0 cart-link cursor-pointer"
                                        data-title="{{ $top_rated[$k + 1]->itemContents[0]->title }}"
                                        data-current_price="{{ currency_converter($product_current_price) }}"
                                        data-item_id="{{ $top_rated[$k + 1]->id }}"
                                        data-language_id="{{ $uLang }}"
                                        data-totalVari="{{ check_variation($top_rated[$k + 1]->id) }}"
                                        data-variations="{{ check_variation($top_rated[$k + 1]->id) > 0 ? 'yes' : null }}"
                                        data-href="{{ route('front.user.add.cart', ['id' => $top_rated[$k + 1]->id, getParam()]) }}"
                                        data-bs-toggle="tooltip" data-placement="top"
                                        title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                          class="far fa-shopping-cart "></i></a>
                                    @endif

                                    <a href="javascript:void(0)" class="btn btn-icon radius-0 quick-view-link"
                                      data-bs-toggle="tooltip" data-bs-placement="top"
                                      data-slug="{{ $top_rated[$k + 1]->itemContents[0]->slug }}"
                                      data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_rated[$k + 1]->itemContents[0]->slug, getParam()]) }}"
                                      title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                        class="fal fa-eye"></i>
                                    </a>

                                    <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                      onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_rated[$k + 1]->id, getParam()]) }}')"
                                      data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                        class="fal fa-random"></i></a>
                                    @php
                                      $customer_id = Auth::guard('customer')->check()
                                          ? Auth::guard('customer')->user()->id
                                          : null;
                                      $checkWishList = $customer_id
                                          ? checkWishList($top_rated[$k + 1]->id, $customer_id)
                                          : false;
                                    @endphp
                                    <a href="#"
                                      class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                      data-bs-toggle="tooltip" data-bs-placement="top"
                                      data-item_id="{{ $top_rated[$k + 1]->id }}"
                                      data-href="{{ route('front.user.add.wishlist', ['id' => $top_rated[$k + 1]->id, getParam()]) }}"
                                      data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_rated[$k + 1]->id, getParam()]) }}"
                                      title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                        class="fal fa-heart"></i>
                                    </a>
                                  </div>
                                </div>
                              </div>
                            @endif
                          @endif
                          @if (isset($top_rated[$k + 2]))
                            @if (!is_null(@$top_rated[$k + 2]->itemContents[0]->slug))
                              <div class="product-default product-inline product-inline-style-2 mt-20">
                                <figure class="product-img">
                                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k + 2]->itemContents[0]->slug]) }}"
                                    class="lazy-container ratio ratio-1-1">
                                    <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_rated[$k + 2]->thumbnail) }}"
                                      alt="Product">
                                  </a>
                                </figure>
                                <div class="product-details">
                                  <h4 class="product-title lc-1">
                                    <a
                                      href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k + 2]->itemContents[0]->slug]) }}">{{ $top_rated[$k + 2]->itemContents[0]->title }}</a>
                                  </h4>

                                  @if ($shop_settings->item_rating_system == 1)
                                    <div class="d-flex align-items-center">
                                      <div class="product-ratings rate text-xsm">
                                        <div class="rating" style="width:{{ @$top_rated[$k + 2]->rating * 20 }}%">
                                        </div>
                                      </div>
                                      <span class="ratings-total">({{ reviewCount($top_rated[$k + 2]->id) }})</span>
                                    </div>
                                  @endif

                                  @php
                                    $flash_info = flashAmountStatus(
                                        $top_rated[$k + 2]->id,
                                        $top_rated[$k + 2]->current_price,
                                    );
                                    $product_current_price = $flash_info['amount'];
                                    $flash_status = $flash_info['status'];
                                  @endphp

                                  <div class="product-price mt-2 mb-10">
                                    @if ($flash_status == true)
                                      <span class="new-price">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                      </span>
                                      <span class="old-price text-decoration-line-through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k + 2]->current_price)) }}
                                      </span>
                                    @else
                                      <span class="new-price">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k + 2]->current_price)) }}
                                      </span>
                                      <span class="old-price text-decoration-line-through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k + 2]->previous_price)) }}
                                      </span>
                                    @endif
                                  </div>

                                  <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                                    @if ($shop_settings->catalog_mode != 1)
                                      <a class="btn btn-icon radius-0 cart-link cursor-pointer"
                                        data-title="{{ $top_rated[$k + 2]->itemContents[0]->title }}"
                                        data-current_price="{{ currency_converter($product_current_price) }}"
                                        data-item_id="{{ $top_rated[$k + 2]->id }}"
                                        data-language_id="{{ $uLang }}"
                                        data-totalVari="{{ check_variation($top_rated[$k + 2]->id) }}"
                                        data-variations="{{ check_variation($top_rated[$k + 2]->id) > 0 ? 'yes' : null }}"
                                        data-href="{{ route('front.user.add.cart', ['id' => $top_rated[$k + 2]->id, getParam()]) }}"
                                        data-bs-toggle="tooltip" data-placement="top"
                                        title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                          class="far fa-shopping-cart "></i></a>
                                    @endif

                                    <a href="javascript:void(0)" class="btn btn-icon radius-0 quick-view-link"
                                      data-bs-toggle="tooltip" data-bs-placement="top"
                                      data-slug="{{ $top_rated[$k + 2]->itemContents[0]->slug }}"
                                      data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_rated[$k + 2]->itemContents[0]->slug, getParam()]) }}"
                                      title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                        class="fal fa-eye"></i>
                                    </a>

                                    <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                      onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_rated[$k + 2]->id, getParam()]) }}')"
                                      data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                        class="fal fa-random"></i></a>
                                    @php
                                      $customer_id = Auth::guard('customer')->check()
                                          ? Auth::guard('customer')->user()->id
                                          : null;
                                      $checkWishList = $customer_id
                                          ? checkWishList($top_rated[$k + 2]->id, $customer_id)
                                          : false;
                                    @endphp
                                    <a href="#"
                                      class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                      data-bs-toggle="tooltip" data-bs-placement="top"
                                      data-item_id="{{ $top_rated[$k + 2]->id }}"
                                      data-href="{{ route('front.user.add.wishlist', ['id' => $top_rated[$k + 2]->id, getParam()]) }}"
                                      data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_rated[$k + 2]->id, getParam()]) }}"
                                      title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                        class="fal fa-heart"></i>
                                    </a>

                                  </div>

                                </div>
                              </div>
                            @endif
                          @endif
                          @if (isset($top_rated[$k + 3]))
                            @if (!is_null(@$top_rated[$k + 3]->itemContents[0]->slug))
                              <div class="product-default product-inline product-inline-style-2 mt-20">
                                <figure class="product-img">
                                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k + 3]->itemContents[0]->slug]) }}"
                                    class="lazy-container ratio ratio-1-1">
                                    <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_rated[$k + 3]->thumbnail) }}"
                                      alt="Product">
                                  </a>
                                </figure>
                                <div class="product-details">
                                  <h4 class="product-title lc-1">
                                    <a
                                      href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_rated[$k + 3]->itemContents[0]->slug]) }}">{{ $top_rated[$k + 3]->itemContents[0]->title }}</a>
                                  </h4>

                                  @if ($shop_settings->item_rating_system == 1)
                                    <div class="d-flex align-items-center">
                                      <div class="product-ratings rate text-xsm">
                                        <div class="rating" style="width:{{ @$top_rated[$k + 3]->rating * 20 }}%">
                                        </div>
                                      </div>
                                      <span class="ratings-total">({{ reviewCount($top_rated[$k + 3]->id) }})</span>
                                    </div>
                                  @endif

                                  @php
                                    $flash_info = flashAmountStatus(
                                        $top_rated[$k + 3]->id,
                                        $top_rated[$k + 3]->current_price,
                                    );
                                    $product_current_price = $flash_info['amount'];
                                    $flash_status = $flash_info['status'];
                                  @endphp

                                  <div class="product-price mt-2 mb-10">
                                    @if ($flash_status == true)
                                      <span class="new-price">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                      </span>
                                      <span class="old-price text-decoration-line-through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k + 3]->current_price)) }}
                                      </span>
                                    @else
                                      <span class="new-price">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k + 3]->current_price)) }}
                                      </span>
                                      <span class="old-price text-decoration-line-through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_rated[$k + 3]->previous_price)) }}
                                      </span>
                                    @endif
                                  </div>

                                  <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                                    @if ($shop_settings->catalog_mode != 1)
                                      <a class="btn btn-icon radius-0 cart-link cursor-pointer"
                                        data-title="{{ $top_rated[$k + 3]->itemContents[0]->title }}"
                                        data-current_price="{{ currency_converter($product_current_price) }}"
                                        data-item_id="{{ $top_rated[$k + 3]->id }}"
                                        data-language_id="{{ $uLang }}"
                                        data-totalVari="{{ check_variation($top_rated[$k + 3]->id) }}"
                                        data-variations="{{ check_variation($top_rated[$k + 3]->id) > 0 ? 'yes' : null }}"
                                        data-href="{{ route('front.user.add.cart', ['id' => $top_rated[$k + 3]->id, getParam()]) }}"
                                        data-bs-toggle="tooltip" data-placement="top"
                                        title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                          class="far fa-shopping-cart "></i></a>
                                    @endif

                                    <a href="javascript:void(0)" class="btn btn-icon radius-0 quick-view-link"
                                      data-bs-toggle="tooltip" data-bs-placement="top"
                                      data-slug="{{ $top_rated[$k + 3]->itemContents[0]->slug }}"
                                      data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_rated[$k + 3]->itemContents[0]->slug, getParam()]) }}"
                                      title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                        class="fal fa-eye"></i>
                                    </a>

                                    <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                      onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_rated[$k + 3]->id, getParam()]) }}')"
                                      data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                        class="fal fa-random"></i></a>
                                    @php
                                      $customer_id = Auth::guard('customer')->check()
                                          ? Auth::guard('customer')->user()->id
                                          : null;
                                      $checkWishList = $customer_id
                                          ? checkWishList($top_rated[$k + 3]->id, $customer_id)
                                          : false;
                                    @endphp
                                    <a href="#"
                                      class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                      data-bs-toggle="tooltip" data-bs-placement="top"
                                      data-item_id="{{ $top_rated[$k + 3]->id }}"
                                      data-href="{{ route('front.user.add.wishlist', ['id' => $top_rated[$k + 3]->id, getParam()]) }}"
                                      data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_rated[$k + 3]->id, getParam()]) }}"
                                      title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                        class="fal fa-heart"></i>
                                    </a>
                                  </div>
                                </div>
                              </div>
                            @endif
                          @endif
                        </div>
                      @endif
                    @endif
                  @endfor
                </div>
              @endif
            </div>
          </div>
        @endif

        @if ($ubs->bottom_middle_banner_section == 1)
          <div class="col-lg-4">
            @if ($banners)
              @for ($i = 4; $i < count($banners); $i++)
                @if ($banners[$i]->position == 'bottom_middle')
                  <div class="banner-sm banner-vertical content-top mb-30 ratio">
                    <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                      alt="Banner">
                    <div class="banner-content justify-content-center">
                      <div class="content-inner text-center">
                        <span class="sub-title text-white">{{ $banners[$i]->title }}</span>
                        <h2 class="title-md">{{ $banners[$i]->subtitle }}</h2>
                        @if ($banners[$i]->button_text)
                          <a href="{{ $banners[$i]->banner_url }}"
                            class="btn btn-md btn-outline">{{ $banners[$i]->button_text }}</a>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif
              @endfor
            @endif

          </div>
        @endif

        @if ($ubs->top_selling_section == 1)
          <div class="col-lg-4">
            <div class="section-title title-inline title-bottom-line mb-10">
              <h2 class="title title-sm mb-0">{{ $userSec->top_selling_product_section_title ?? __('Top Selling') }}
              </h2>
              <div class="slider-arrow-inline" id="product-list-slider-2-arrows">
              </div>
            </div>
            <div class="product-list mb-30">

              @if (count($top_selling) == 0)
                <h5 class="text-center mb-20">
                  {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
                </h5>
              @else
                <div class="product-list-slider" id="product-list-slider-2">
                  @if (!empty($top_selling))
                    @for ($k = 0; $k <= count($top_selling); $k += 4)
                      @if ($k < count($top_selling) - 1)
                        @if (!is_null(@$top_selling[$k]->item->itemContents[0]->slug))
                          @if (@$top_selling[$k]->item->status == 1)
                            <div>
                              @if (!is_null(@$top_selling[$k]->item->itemContents[0]->slug))
                                <div class="product-default product-inline mt-20">
                                  <figure class="product-img">
                                    <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k]->item->itemContents[0]->slug]) }}"
                                      class="lazy-container ratio ratio-1-1">
                                      <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_selling[$k]->item->thumbnail) }}"
                                        alt="Product">
                                    </a>
                                  </figure>
                                  <div class="product-details">
                                    <h4 class="product-title lc-1">
                                      <a
                                        href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k]->item->itemContents[0]->slug]) }}">{{ $top_selling[$k]->item->itemContents[0]->title }}</a>({{ $top_selling[$k]->quantity }})
                                    </h4>

                                    @if ($shop_settings->item_rating_system == 1)
                                      <div class="d-flex  align-items-center">
                                        <div class="product-ratings rate text-xsm">
                                          <div class="rating"
                                            style="width:{{ $top_selling[$k]->item->rating * 20 }}%">
                                          </div>
                                        </div>
                                        <span
                                          class="ratings-total">({{ reviewCount($top_selling[$k]->item->id) }})</span>
                                      </div>
                                    @endif

                                    @php
                                      $flash_info = flashAmountStatus(
                                          $top_selling[$k]->item->id,
                                          $top_selling[$k]->item->current_price,
                                      );
                                      $product_current_price = $flash_info['amount'];
                                      $flash_status = $flash_info['status'];
                                    @endphp

                                    <div class="product-price mt-2 mb-10">
                                      @if ($flash_status == true)
                                        <span class="new-price">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                        </span>
                                        <span class="old-price  text-decoration-line-through">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k]->item->current_price)) }}
                                        </span>
                                      @else
                                        <span class="new-price">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k]->item->current_price)) }}
                                        </span>
                                        <span class="old-price  text-decoration-line-through">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k]->item->previous_price)) }}
                                        </span>
                                      @endif
                                    </div>

                                    <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                                      @if ($shop_settings->catalog_mode != 1)
                                        <a class="btn btn-icon radius-0 cart-link cursor-pointer"
                                          data-title="{{ $top_selling[$k]->item->itemContents[0]->title }}"
                                          data-current_price="{{ currency_converter($product_current_price) }}"
                                          data-item_id="{{ $top_selling[$k]->item->id }}"
                                          data-language_id="{{ $uLang }}"
                                          data-totalVari="{{ check_variation($top_selling[$k]->item->id) }}"
                                          data-variations="{{ check_variation($top_selling[$k]->item->id) > 0 ? 'yes' : null }}"
                                          data-href="{{ route('front.user.add.cart', ['id' => $top_selling[$k]->item->itemContents[0], getParam()]) }}"
                                          data-bs-toggle="tooltip" data-placement="top"
                                          title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                            class="far fa-shopping-cart "></i></a>
                                      @endif

                                      <a href="javascript:void(0)" class="btn btn-icon radius-0 quick-view-link"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-slug="{{ $top_selling[$k]->item->itemContents[0]->slug }}"
                                        data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_selling[$k]->item->itemContents[0]->slug, getParam()]) }}"
                                        title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                          class="fal fa-eye"></i>
                                      </a>

                                      <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                        onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_selling[$k]->item->id, getParam()]) }}')"
                                        data-bs-placement="top"
                                        title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                          class="fal fa-random"></i></a>
                                      @php
                                        $customer_id = Auth::guard('customer')->check()
                                            ? Auth::guard('customer')->user()->id
                                            : null;
                                        $checkWishList = $customer_id
                                            ? checkWishList($top_selling[$k]->item->id, $customer_id)
                                            : false;
                                      @endphp
                                      <a href="#"
                                        class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-item_id="{{ $top_selling[$k]->item->id }}"
                                        data-href="{{ route('front.user.add.wishlist', ['id' => $top_selling[$k]->item->id, getParam()]) }}"
                                        data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_selling[$k]->item->id, getParam()]) }}"
                                        title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                          class="fal fa-heart"></i>
                                      </a>
                                    </div>
                                  </div>
                                </div>
                              @endif

                              @if (!is_null(@$top_selling[$k + 1]->item->itemContents[0]->slug))
                                <div class="product-default product-inline mt-20">
                                  <figure class="product-img">
                                    <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k + 1]->item->itemContents[0]->slug]) }}"
                                      class="lazy-container ratio ratio-1-1">
                                      <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_selling[$k + 1]->item->thumbnail) }}"
                                        alt="">
                                    </a>
                                  </figure>
                                  <div class="product-details">
                                    <h4 class="product-title lc-1">
                                      <a
                                        href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k + 1]->item->itemContents[0]->slug]) }}">{{ $top_selling[$k + 1]->item->itemContents[0]->title }}</a>({{ $top_selling[$k + 1]->quantity }})
                                    </h4>
                                    @if ($shop_settings->item_rating_system == 1)
                                      <div class="d-flex  align-items-center">
                                        <div class="product-ratings rate text-xsm">
                                          <div class="rating"
                                            style="width:{{ $top_selling[$k + 1]->item->rating * 20 }}%">
                                          </div>
                                        </div>
                                        <span
                                          class="ratings-total">({{ reviewCount($top_selling[$k + 1]->item->id) }})</span>
                                      </div>
                                    @endif
                                    @php
                                      $flash_info = flashAmountStatus(
                                          $top_selling[$k + 1]->item->id,
                                          $top_selling[$k + 1]->item->current_price,
                                      );
                                      $product_current_price = $flash_info['amount'];
                                      $flash_status = $flash_info['status'];
                                    @endphp

                                    <div class="product-price mt-2 mb-10">
                                      @if ($flash_status == true)
                                        <span class="new-price">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                        </span>
                                        <span class="old-price  text-decoration-line-through">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 1]->item->current_price)) }}
                                        </span>
                                      @else
                                        <span class="new-price">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 1]->item->current_price)) }}
                                        </span>
                                        <span class="old-price  text-decoration-line-through">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 1]->item->previous_price)) }}
                                        </span>
                                      @endif
                                    </div>

                                    <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                                      @if ($shop_settings->catalog_mode != 1)
                                        <a class="btn btn-icon radius-0 cart-link cursor-pointer"
                                          data-title="{{ $top_selling[$k + 1]->item->itemContents[0]->title }}"
                                          data-current_price="{{ currency_converter($product_current_price) }}"
                                          data-item_id="{{ $top_selling[$k + 1]->item->id }}"
                                          data-language_id="{{ $uLang }}"
                                          data-totalVari="{{ check_variation($top_selling[$k + 1]->item->id) }}"
                                          data-variations="{{ check_variation($top_selling[$k + 1]->item->id) > 0 ? 'yes' : null }}"
                                          data-href="{{ route('front.user.add.cart', ['id' => $top_selling[$k + 1]->item->id, getParam()]) }}"
                                          data-bs-toggle="tooltip" data-placement="top"
                                          title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                            class="far fa-shopping-cart "></i></a>
                                      @endif

                                      <a href="javascript:void(0)" class="btn btn-icon radius-0 quick-view-link"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-slug="{{ $top_selling[$k + 1]->item->itemContents[0]->slug }}"
                                        data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_selling[$k + 1]->item->itemContents[0]->slug, getParam()]) }}"
                                        title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                          class="fal fa-eye"></i>
                                      </a>

                                      <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                        onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_selling[$k + 1]->item->id, getParam()]) }}')"
                                        data-bs-placement="top"
                                        title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                          class="fal fa-random"></i></a>
                                      @php
                                        $customer_id = Auth::guard('customer')->check()
                                            ? Auth::guard('customer')->user()->id
                                            : null;
                                        $checkWishList = $customer_id
                                            ? checkWishList($top_selling[$k + 1]->item->id, $customer_id)
                                            : false;
                                      @endphp
                                      <a href="#"
                                        class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-item_id="{{ $top_selling[$k + 1]->item->id }}"
                                        data-href="{{ route('front.user.add.wishlist', ['id' => $top_selling[$k + 1]->item->id, getParam()]) }}"
                                        data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_selling[$k + 1]->item->id, getParam()]) }}"
                                        title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                          class="fal fa-heart"></i>
                                      </a>
                                    </div>
                                  </div>
                                </div>
                              @endif
                              @if (!is_null(@$top_selling[$k + 2]->item->itemContents[0]->slug))
                                <div class="product-default product-inline mt-20">
                                  <figure class="product-img">
                                    <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k + 2]->item->itemContents[0]->slug]) }}"
                                      class="lazy-container ratio ratio-1-1">
                                      <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_selling[$k + 2]->item->thumbnail) }}"
                                        alt="">
                                    </a>
                                  </figure>
                                  <div class="product-details">
                                    <h4 class="product-title lc-1">
                                      <a
                                        href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k + 2]->item->itemContents[0]->slug]) }}">{{ $top_selling[$k + 2]->item->itemContents[0]->title }}</a>({{ $top_selling[$k + 2]->quantity }})
                                    </h4>
                                    @if ($shop_settings->item_rating_system == 1)
                                      <div class="d-flex  align-items-center">
                                        <div class="product-ratings rate text-xsm">
                                          <div class="rating"
                                            style="width:{{ $top_selling[$k + 2]->item->rating * 20 }}%">
                                          </div>
                                        </div>
                                        <span
                                          class="ratings-total">({{ reviewCount($top_selling[$k + 2]->item->id) }})</span>
                                      </div>
                                    @endif
                                    @php
                                      $flash_info = flashAmountStatus(
                                          $top_selling[$k + 2]->item->id,
                                          $top_selling[$k + 2]->item->current_price,
                                      );
                                      $product_current_price = $flash_info['amount'];
                                      $flash_status = $flash_info['status'];
                                    @endphp

                                    <div class="product-price mt-2 mb-10">
                                      @if ($flash_status == true)
                                        <span class="new-price">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                        </span>
                                        <span class="old-price  text-decoration-line-through">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 2]->item->current_price)) }}
                                        </span>
                                      @else
                                        <span class="new-price">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 2]->item->current_price)) }}
                                        </span>
                                        <span class="old-price  text-decoration-line-through">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 2]->item->previous_price)) }}
                                        </span>
                                      @endif
                                    </div>

                                    <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                                      @if ($shop_settings->catalog_mode != 1)
                                        <a class="btn btn-icon radius-0 cart-link cursor-pointer"
                                          data-title="{{ $top_selling[$k + 2]->item->itemContents[0]->title }}"
                                          data-current_price="{{ currency_converter($product_current_price) }}"
                                          data-item_id="{{ $top_selling[$k + 2]->item->id }}"
                                          data-language_id="{{ $uLang }}"
                                          data-totalVari="{{ check_variation($top_selling[$k + 2]->item->id) }}"
                                          data-variations="{{ check_variation($top_selling[$k + 2]->item->id) > 0 ? 'yes' : null }}"
                                          data-href="{{ route('front.user.add.cart', ['id' => $top_selling[$k + 2]->item->id, getParam()]) }}"
                                          data-bs-toggle="tooltip" data-placement="top"
                                          title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                            class="far fa-shopping-cart "></i></a>
                                      @endif

                                      <a href="javascript:void(0)" class="btn btn-icon radius-0 quick-view-link"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-slug="{{ $top_selling[$k + 2]->item->itemContents[0]->slug }}"
                                        data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_selling[$k + 2]->item->itemContents[0]->slug, getParam()]) }}"
                                        title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                          class="fal fa-eye"></i>
                                      </a>

                                      <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                        onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_selling[$k + 2]->item->id, getParam()]) }}')"
                                        data-bs-placement="top"
                                        title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                          class="fal fa-random"></i></a>
                                      @php
                                        $customer_id = Auth::guard('customer')->check()
                                            ? Auth::guard('customer')->user()->id
                                            : null;
                                        $checkWishList = $customer_id
                                            ? checkWishList($top_selling[$k + 2]->item->id, $customer_id)
                                            : false;
                                      @endphp
                                      <a href="#"
                                        class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-item_id="{{ $top_selling[$k + 2]->item->id }}"
                                        data-href="{{ route('front.user.add.wishlist', ['id' => $top_selling[$k + 2]->item->id, getParam()]) }}"
                                        data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_selling[$k + 2]->item->id, getParam()]) }}"
                                        title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                          class="fal fa-heart"></i>
                                      </a>
                                    </div>
                                  </div>
                                </div>
                              @endif
                              @if (!is_null(@$top_selling[$k + 3]->item->itemContents[0]->slug))
                                <div class="product-default product-inline mt-20">
                                  <figure class="product-img">
                                    <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k + 3]->item->itemContents[0]->slug]) }}"
                                      class="lazy-container ratio ratio-1-1">
                                      <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                        data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_selling[$k + 3]->item->thumbnail) }}"
                                        alt="">
                                    </a>
                                  </figure>
                                  <div class="product-details">
                                    <h4 class="product-title lc-1">
                                      <a
                                        href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k + 3]->item->itemContents[0]->slug]) }}">{{ $top_selling[$k + 3]->item->itemContents[0]->title }}</a>({{ $top_selling[$k + 3]->quantity }})
                                    </h4>
                                    @if ($shop_settings->item_rating_system == 1)
                                      <div class="d-flex  align-items-center">
                                        <div class="product-ratings rate text-xsm">
                                          <div class="rating"
                                            style="width:{{ $top_selling[$k + 3]->item->rating * 20 }}%">
                                          </div>
                                        </div>
                                        <span
                                          class="ratings-total">({{ reviewCount($top_selling[$k + 3]->item->id) }})</span>
                                      </div>
                                    @endif
                                    @php
                                      $flash_info = flashAmountStatus(
                                          $top_selling[$k + 3]->item->id,
                                          $top_selling[$k + 3]->item->current_price,
                                      );
                                      $product_current_price = $flash_info['amount'];
                                      $flash_status = $flash_info['status'];
                                    @endphp

                                    <div class="product-price mt-2 mb-10">
                                      @if ($flash_status == true)
                                        <span class="new-price">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                        </span>
                                        <span class="old-price  text-decoration-line-through">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 3]->item->current_price)) }}
                                        </span>
                                      @else
                                        <span class="new-price">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 3]->item->current_price)) }}
                                        </span>
                                        <span class="old-price  text-decoration-line-through">
                                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 3]->item->previous_price)) }}
                                        </span>
                                      @endif
                                    </div>

                                    <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                                      @if ($shop_settings->catalog_mode != 1)
                                        <a class="btn btn-icon radius-0 cart-link cursor-pointer"
                                          data-title="{{ $top_selling[$k + 3]->item->itemContents[0]->title }}"
                                          data-current_price="{{ currency_converter($product_current_price) }}"
                                          data-item_id="{{ $top_selling[$k + 3]->item->id }}"
                                          data-language_id="{{ $uLang }}"
                                          data-totalVari="{{ check_variation($top_selling[$k + 3]->item->id) }}"
                                          data-variations="{{ check_variation($top_selling[$k + 3]->item->id) > 0 ? 'yes' : null }}"
                                          data-href="{{ route('front.user.add.cart', ['id' => $top_selling[$k + 3]->item->id, getParam()]) }}"
                                          data-bs-toggle="tooltip" data-placement="top"
                                          title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                            class="far fa-shopping-cart "></i></a>
                                      @endif

                                      <a href="javascript:void(0)" class="btn btn-icon radius-0 quick-view-link"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-slug="{{ $top_selling[$k + 3]->item->itemContents[0]->slug }}"
                                        data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_selling[$k + 3]->item->itemContents[0]->slug, getParam()]) }}"
                                        title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                          class="fal fa-eye"></i>
                                      </a>

                                      <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                        onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_selling[$k + 3]->item->id, getParam()]) }}')"
                                        data-bs-placement="top"
                                        title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                          class="fal fa-random"></i></a>
                                      @php
                                        $customer_id = Auth::guard('customer')->check()
                                            ? Auth::guard('customer')->user()->id
                                            : null;
                                        $checkWishList = $customer_id
                                            ? checkWishList($top_selling[$k + 3]->item->id, $customer_id)
                                            : false;
                                      @endphp
                                      <a href="#"
                                        class="btn btn-icon {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-item_id="{{ $top_selling[$k + 3]->item->id }}"
                                        data-href="{{ route('front.user.add.wishlist', ['id' => $top_selling[$k + 3]->item->id, getParam()]) }}"
                                        data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $top_selling[$k + 3]->item->id, getParam()]) }}"
                                        title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                          class="fal fa-heart"></i>
                                      </a>
                                    </div>
                                  </div>
                                </div>
                              @endif
                            </div>
                          @endif
                        @endif
                      @endif
                    @endfor
                  @endif
                </div>
              @endif
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>
  <!-- Product List End -->


