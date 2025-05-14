
    <!-- Product List Start -->
    <section class="products pb-100 lazy" >
      <div class="container">
        <div class="row gx-xl-4">
          <div class="col-lg-5">

            @if ($ubs->bottom_left_banner_section == 1)
              @if ($banners)
                @php
                  $leftBanners = $banners->where('position', 'bottom_left')->take(2);
                @endphp
                @if ($leftBanners)
                  @foreach ($leftBanners as $banner)
                    <div class="banner-sm lazy-container radius-lg mb-30 ratio ratio-2-3">
                      <img class="lazyload h-100 blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}"
                        alt="Banner">
                      <div class="banner-content mw-80">
                        <div class="content-inner">
                          <span class="sub-title">{{ $banner->title }}</span>
                          <h2 class="title-md">{{ $banner->subtitle }}
                          </h2>
                          @if ($banner->banner_url && $banner->button_text)
                            <a href="{{ $banner->banner_url }}"
                              class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banner->button_text }}
                              <span class="icon"><i class="fal fa-arrow-right"></i></span>
                            </a>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endforeach
                @endif
              @endif
            @endif

          </div>

          <div class="col-lg-7">
            <div class="section-title title-inline title-bottom-line mb-10">
              <h2 class="title title-sm mb-0">
                {{ $userSec->latest_product_section_title ?? ($keywords['Latest Item'] ?? __('Latest Item')) }}</h2>
              <div class="slider-arrow-inline style-2" id="product-list-slider-skeleton-1-arrows">
              </div>
            </div>
            <div class="product-list mb-30">

              <div >

                <div class="mb-30">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="product-default product-inline product-inline-2 mt-20">
                        <figure class="product-img skeleton skeleton-img"></figure>
                        <div class="product-details">
                          <div class="skeleton skeleton-category"></div>
                          <div class="skeleton skeleton-title"></div>

                          <div class="skeleton skeleton-ratings"></div>

                          <div class="product-price mt-1 mb-10">
                            <span class="new-price skeleton skeleton-price"></span>
                            <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                          </div>

                          <div class="d-flex">
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="product-default product-inline product-inline-2 mt-20">
                        <figure class="product-img skeleton skeleton-img"></figure>
                        <div class="product-details">
                          <div class="skeleton skeleton-category"></div>
                          <div class="skeleton skeleton-title"></div>

                          <div class="skeleton skeleton-ratings"></div>

                          <div class="product-price mt-1 mb-10">
                            <span class="new-price skeleton skeleton-price"></span>
                            <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                          </div>

                          <div class="d-flex">
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="product-default product-inline product-inline-2 mt-20">
                        <figure class="product-img skeleton skeleton-img"></figure>
                        <div class="product-details">
                          <div class="skeleton skeleton-category"></div>
                          <div class="skeleton skeleton-title"></div>

                          <div class="skeleton skeleton-ratings"></div>

                          <div class="product-price mt-1 mb-10">
                            <span class="new-price skeleton skeleton-price"></span>
                            <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                          </div>

                          <div class="d-flex">
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="product-default product-inline product-inline-2 mt-20">
                        <figure class="product-img skeleton skeleton-img"></figure>
                        <div class="product-details">
                          <div class="skeleton skeleton-category"></div>
                          <div class="skeleton skeleton-title"></div>

                          <div class="skeleton skeleton-ratings"></div>

                          <div class="product-price mt-1 mb-10">
                            <span class="new-price skeleton skeleton-price"></span>
                            <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                          </div>

                          <div class="d-flex">
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="product-default product-inline product-inline-2 mt-20">
                        <figure class="product-img skeleton skeleton-img"></figure>
                        <div class="product-details">
                          <div class="skeleton skeleton-category"></div>
                          <div class="skeleton skeleton-title"></div>

                          <div class="skeleton skeleton-ratings"></div>

                          <div class="product-price mt-1 mb-10">
                            <span class="new-price skeleton skeleton-price"></span>
                            <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                          </div>

                          <div class="d-flex">
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="product-default product-inline product-inline-2 mt-20">
                        <figure class="product-img skeleton skeleton-img"></figure>
                        <div class="product-details">
                          <div class="skeleton skeleton-category"></div>
                          <div class="skeleton skeleton-title"></div>

                          <div class="skeleton skeleton-ratings"></div>

                          <div class="product-price mt-1 mb-10">
                            <span class="new-price skeleton skeleton-price"></span>
                            <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                          </div>

                          <div class="d-flex">
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
      </div>
    </section>
    <!-- Product List End -->


    <!-- Product List Start -->
    <section class="products pb-100 actual-content">
      <div class="container">
        <div class="row gx-xl-4">
          <div class="col-lg-5">

            @if ($ubs->bottom_left_banner_section == 1)
              @if ($banners)
                @php
                  $leftBanners = $banners->where('position', 'bottom_left')->take(2);
                @endphp
                @if ($leftBanners)
                  @foreach ($leftBanners as $banner)
                    <div class="banner-sm lazy-container radius-lg mb-30 ratio ratio-2-3">
                      <img class="lazyload h-100 blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                      <div class="banner-content mw-80">
                        <div class="content-inner">
                          <span class="sub-title">{{ $banner->title }}</span>
                          <h2 class="title-md">{{ $banner->subtitle }}
                          </h2>
                          @if ($banner->banner_url && $banner->button_text)
                            <a href="{{ $banner->banner_url }}"
                              class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banner->button_text }}
                              <span class="icon"><i class="fal fa-arrow-right"></i></span>
                            </a>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endforeach
                @endif
              @endif
            @endif

          </div>
          <div class="col-lg-7">
            <div class="section-title title-inline title-bottom-line mb-10">
              <h2 class="title title-sm mb-0">
                {{ $userSec->latest_product_section_title ?? ($keywords['Latest Item'] ?? __('Latest Item')) }}</h2>
              <div class="slider-arrow-inline style-2" id="product-list-slider-1-arrows">
              </div>
            </div>
            <div class="product-list mb-30">
              @if (count($latest_items) == 0)
                <h5 class="title text-center mt-30">
                  {{ $userSec->category_section_title ?? ($keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND')) }}
                </h5>
              @else
                <div class="product-list-slider" id="product-list-slider-1" data-slick='{"slidesToShow": 2}'>
                  @for ($k = 0; $k <= count($latest_items); $k = $k + 4)
                    @if ($k < count($latest_items) - 1)
                      @if (count(@$latest_items[$k]->itemContents) > 0)
                        <div class="mb-30">
                          <div class="product-default product-inline product-inline-2 mt-20">
                            <figure class="product-img radius-md">
                              <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k]->itemContents[0]->slug]) }}"
                                class="lazy-container ratio ratio-1-1">
                                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                  data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $latest_items[$k]->thumbnail) }}"
                                  alt="Product">
                              </a>
                            </figure>
                            <div class="product-details">
                              <a
                                href="{{ route('front.user.shop', ['category' => $latest_items[$k]->itemContents[0]->category->slug, getParam()]) }}"><span
                                  class="product-category text-sm">{{ $latest_items[$k]->itemContents[0]->category->name }}</span></a>
                              <h3 class="product-title lc-1">
                                <a
                                  href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k]->itemContents[0]->slug]) }}">{{ $latest_items[$k]->itemContents[0]->title }}</a>
                              </h3>

                              @if ($shop_settings->item_rating_system == 1)
                                <div class="d-flex align-items-center">
                                  <div class="product-ratings rate text-xsm">
                                    <div class="rating" style="width:{{ $latest_items[$k]->rating * 20 }}%"></div>
                                  </div>
                                  <span class="ratings-total">({{ reviewCount($latest_items[$k]->id) }})</span>
                                </div>
                              @endif

                              <div class="product-price mt-1 mb-10">
                                @php
                                  $flash_info = flashAmountStatus(
                                      $latest_items[$k]->id,
                                      $latest_items[$k]->current_price,
                                  );
                                  $product_current_price = $flash_info['amount'];
                                  $flash_status = $flash_info['status'];
                                @endphp
                                @if ($flash_status == true)
                                  <span class="new-price">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                  </span>
                                  <span class="old-price text-decoration-line-through">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k]->current_price)) }}
                                  </span>
                                @else
                                  <span class="new-price">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k]->current_price)) }}
                                  </span>
                                  @if ($latest_items[$k]->previous_price > 0)
                                    <span class="old-price text-decoration-line-through">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k]->previous_price)) }}
                                    </span>
                                  @endif
                                @endif
                              </div>

                              <div class="btn-icon-group btn-inline btn-icon-group-sm">

                                @if ($shop_settings->catalog_mode != 1)
                                  <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                                    data-title="{{ $latest_items[$k]->itemContents[0]->title }}"
                                    data-current_price="{{ currency_converter($product_current_price) }}"
                                    data-item_id="{{ $latest_items[$k]->id }}" data-language_id="{{ $uLang }}"
                                    data-totalVari="{{ check_variation($latest_items[$k]->id) }}"
                                    data-variations="{{ check_variation($latest_items[$k]->id) > 0 ? 'yes' : null }}"
                                    data-href="{{ route('front.user.add.cart', ['id' => $latest_items[$k]->id, getParam()]) }}"
                                    data-bs-toggle="tooltip" data-placement="top"
                                    title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                      class="far fa-shopping-cart "></i></a>
                                @endif

                                <a href="javascript:void(0)" class="btn btn-icon rounded-pill quick-view-link"
                                  data-bs-toggle="tooltip" data-bs-placement="top"
                                  data-slug="{{ $latest_items[$k]->itemContents[0]->slug }}"
                                  data-url="{{ route('front.user.productDetails.quickview', ['slug' => $latest_items[$k]->itemContents[0]->slug, getParam()]) }}"
                                  title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i>
                                </a>

                                <a class="btn btn-icon rounded-pill" data-bs-toggle="tooltip"
                                  onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $latest_items[$k]->id, getParam()]) }}')"
                                  data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                    class="fal fa-random"></i></a>
                                @php
                                  $customer_id = Auth::guard('customer')->check()
                                      ? Auth::guard('customer')->user()->id
                                      : null;
                                  $checkWishList = $customer_id
                                      ? checkWishList($latest_items[$k]->id, $customer_id)
                                      : false;
                                @endphp
                                <a href="#"
                                  class="btn btn-icon rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                  data-bs-toggle="tooltip" data-bs-placement="top"
                                  data-item_id="{{ $latest_items[$k]->id }}"
                                  data-href="{{ route('front.user.add.wishlist', ['id' => $latest_items[$k]->id, getParam()]) }}"
                                  data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $latest_items[$k]->id, getParam()]) }}"
                                  title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                    class="fal fa-heart"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                          @if (!is_null(@$latest_items[$k + 1]))
                            <div class="product-default product-inline product-inline-2 mt-20">
                              <figure class="product-img radius-md">
                                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k + 1]->itemContents[0]->slug]) }}"
                                  class="lazy-container ratio ratio-1-1">
                                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $latest_items[$k + 1]->thumbnail) }}"
                                    alt="Product">
                                </a>
                              </figure>
                              <div class="product-details">
                                <a
                                  href="{{ route('front.user.shop', ['category' => $latest_items[$k + 1]->itemContents[0]->category->slug, getParam()]) }}"><span
                                    class="product-category text-sm">{{ $latest_items[$k + 1]->itemContents[0]->category->name }}</span></a>
                                <h4 class="product-title lc-1">
                                  <a
                                    href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k + 1]->itemContents[0]->slug]) }}">{{ $latest_items[$k + 1]->itemContents[0]->title }}</a>
                                </h4>

                                @if ($shop_settings->item_rating_system == 1)
                                  <div class="d-flex align-items-center">
                                    <div class="product-ratings rate text-xsm">
                                      <div class="rating" style="width:{{ $latest_items[$k + 1]->rating * 20 }}%">
                                      </div>
                                    </div>
                                    <span class="ratings-total">({{ reviewCount($latest_items[$k + 1]->id) }})</span>
                                  </div>
                                @endif


                                <div class="product-price mt-1 mb-10">
                                  @php
                                    $flash_info = flashAmountStatus(
                                        $latest_items[$k + 1]->id,
                                        $latest_items[$k + 1]->current_price,
                                    );
                                    $product_current_price = $flash_info['amount'];
                                    $flash_status = $flash_info['status'];
                                  @endphp

                                  @if ($flash_status == true)
                                    <span class="new-price">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                    </span>
                                    <span class="old-price text-decoration-line-through">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 1]->current_price)) }}
                                    </span>
                                  @else
                                    <span class="new-price">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 1]->current_price)) }}
                                    </span>
                                    @if ($latest_items[$k + 1]->previous_price > 0)
                                      <span class="old-price text-decoration-line-through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 1]->previous_price)) }}
                                      </span>
                                    @endif
                                  @endif
                                </div>

                                <div class="btn-icon-group btn-inline btn-icon-group-sm">

                                  @if ($shop_settings->catalog_mode != 1)
                                    <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                                      data-title="{{ $latest_items[$k + 1]->itemContents[0]->title }}"
                                      data-current_price="{{ currency_converter($product_current_price) }}"
                                      data-item_id="{{ $latest_items[$k + 1]->id }}"
                                      data-language_id="{{ $uLang }}"
                                      data-totalVari="{{ check_variation($latest_items[$k + 1]->id) }}"
                                      data-variations="{{ check_variation($latest_items[$k + 1]->id) > 0 ? 'yes' : null }}"
                                      data-href="{{ route('front.user.add.cart', ['id' => $latest_items[$k + 1]->id, getParam()]) }}"
                                      data-bs-toggle="tooltip" data-placement="top"
                                      title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                        class="far fa-shopping-cart "></i></a>
                                  @endif

                                  <a href="javascript:void(0)" class="btn btn-icon rounded-pill quick-view-link"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-slug="{{ $latest_items[$k + 1]->itemContents[0]->slug }}"
                                    data-url="{{ route('front.user.productDetails.quickview', ['slug' => $latest_items[$k + 1]->itemContents[0]->slug, getParam()]) }}"
                                    title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                      class="fal fa-eye"></i>
                                  </a>

                                  <a class="btn btn-icon rounded-pill" data-bs-toggle="tooltip"
                                    onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $latest_items[$k + 1]->id, getParam()]) }}')"
                                    data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                      class="fal fa-random"></i></a>
                                  @php
                                    $customer_id = Auth::guard('customer')->check()
                                        ? Auth::guard('customer')->user()->id
                                        : null;
                                    $checkWishList = $customer_id
                                        ? checkWishList($latest_items[$k + 1]->id, $customer_id)
                                        : false;
                                  @endphp
                                  <a href="#"
                                    class="btn btn-icon rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-item_id="{{ $latest_items[$k + 1]->id }}"
                                    data-href="{{ route('front.user.add.wishlist', ['id' => $latest_items[$k + 1]->id, getParam()]) }}"
                                    data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $latest_items[$k + 1]->id, getParam()]) }}"
                                    title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                      class="fal fa-heart"></i>
                                  </a>

                                </div>

                              </div>
                            </div>
                          @endif
                          @if (!is_null(@$latest_items[$k + 2]))
                            <div class="product-default product-inline product-inline-2 mt-20">
                              <figure class="product-img radius-md">
                                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k + 2]->itemContents[0]->slug]) }}"
                                  class="lazy-container ratio ratio-1-1">
                                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $latest_items[$k + 2]->thumbnail) }}"
                                    alt="Product">
                                </a>
                              </figure>
                              <div class="product-details">
                                <a
                                  href="{{ route('front.user.shop', ['category' => $latest_items[$k + 2]->itemContents[0]->category->slug, getParam()]) }}"><span
                                    class="product-category text-sm">{{ $latest_items[$k + 2]->itemContents[0]->category->name }}</span></a>
                                <h4 class="product-title lc-1">
                                  <a
                                    href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k + 2]->itemContents[0]->slug]) }}">{{ $latest_items[$k + 2]->itemContents[0]->title }}</a>
                                </h4>

                                @if ($shop_settings->item_rating_system == 1)
                                  <div class="d-flex align-items-center">
                                    <div class="product-ratings rate text-xsm">
                                      <div class="rating" style="width:{{ $latest_items[$k + 2]->rating * 20 }}%">
                                      </div>
                                    </div>
                                    <span class="ratings-total">({{ reviewCount($latest_items[$k + 2]->id) }})</span>
                                  </div>
                                @endif

                                <div class="product-price mt-1 mb-10">
                                  @php
                                    $flash_info = flashAmountStatus(
                                        $latest_items[$k + 2]->id,
                                        $latest_items[$k + 2]->current_price,
                                    );
                                    $product_current_price = $flash_info['amount'];
                                    $flash_status = $flash_info['status'];
                                  @endphp

                                  @if ($flash_status == true)
                                    <span class="new-price">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                    </span>
                                    <span class="old-price text-decoration-line-through">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 2]->current_price)) }}
                                    </span>
                                  @else
                                    <span class="new-price">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 2]->current_price)) }}
                                    </span>
                                    @if ($latest_items[$k + 2]->previous_price > 0)
                                      <span class="old-price text-decoration-line-through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 2]->previous_price)) }}
                                      </span>
                                    @endif
                                  @endif
                                </div>

                                <div class="btn-icon-group btn-inline btn-icon-group-sm">

                                  @if ($shop_settings->catalog_mode != 1)
                                    <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                                      data-title="{{ $latest_items[$k + 2]->itemContents[0]->title }}"
                                      data-current_price="{{ currency_converter($product_current_price) }}"
                                      data-item_id="{{ $latest_items[$k + 2]->id }}"
                                      data-language_id="{{ $uLang }}"
                                      data-totalVari="{{ check_variation($latest_items[$k + 2]->id) }}"
                                      data-variations="{{ check_variation($latest_items[$k + 2]->id) > 0 ? 'yes' : null }}"
                                      data-href="{{ route('front.user.add.cart', ['id' => $latest_items[$k + 2]->id, getParam()]) }}"
                                      data-bs-toggle="tooltip" data-placement="top"
                                      title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                        class="far fa-shopping-cart "></i></a>
                                  @endif

                                  <a href="javascript:void(0)" class="btn btn-icon rounded-pill quick-view-link"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-slug="{{ $latest_items[$k + 2]->itemContents[0]->slug }}"
                                    data-url="{{ route('front.user.productDetails.quickview', ['slug' => $latest_items[$k + 2]->itemContents[0]->slug, getParam()]) }}"
                                    title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                      class="fal fa-eye"></i>
                                  </a>

                                  <a class="btn btn-icon rounded-pill" data-bs-toggle="tooltip"
                                    onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $latest_items[$k + 2]->id, getParam()]) }}')"
                                    data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                      class="fal fa-random"></i></a>
                                  @php
                                    $customer_id = Auth::guard('customer')->check()
                                        ? Auth::guard('customer')->user()->id
                                        : null;
                                    $checkWishList = $customer_id
                                        ? checkWishList($latest_items[$k + 2]->id, $customer_id)
                                        : false;
                                  @endphp
                                  <a href="#"
                                    class="btn btn-icon rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-item_id="{{ $latest_items[$k + 2]->id }}"
                                    data-href="{{ route('front.user.add.wishlist', ['id' => $latest_items[$k + 2]->id, getParam()]) }}"
                                    data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $latest_items[$k + 2]->id, getParam()]) }}"
                                    title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                                      class="fal fa-heart"></i>
                                  </a>

                                </div>

                              </div>
                            </div>
                          @endif
                          @if (!is_null(@$latest_items[$k + 3]))
                            <div class="product-default product-inline product-inline-2 mt-20">
                              <figure class="product-img radius-md">
                                <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k + 3]->itemContents[0]->slug]) }}"
                                  class="lazy-container ratio ratio-1-1">
                                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $latest_items[$k + 3]->thumbnail) }}"
                                    alt="Product">
                                </a>
                              </figure>
                              <div class="product-details">
                                <a
                                  href="{{ route('front.user.shop', ['category' => $latest_items[$k + 3]->itemContents[0]->category->slug, getParam()]) }}"><span
                                    class="product-category text-sm">{{ $latest_items[$k + 3]->itemContents[0]->category->name }}</span></a>
                                <h4 class="product-title lc-1">
                                  <a
                                    href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k + 3]->itemContents[0]->slug]) }}">{{ $latest_items[$k + 3]->itemContents[0]->title }}</a>
                                </h4>

                                @if ($shop_settings->item_rating_system == 1)
                                  <div class="d-flex align-items-center">
                                    <div class="product-ratings rate text-xsm">
                                      <div class="rating" style="width:{{ $latest_items[$k + 3]->rating * 20 }}%">
                                      </div>
                                    </div>
                                    <span class="ratings-total">({{ reviewCount($latest_items[$k + 3]->id) }})</span>
                                  </div>
                                @endif

                                <div class="product-price mt-1 mb-10">
                                  @php
                                    $flash_info = flashAmountStatus(
                                        $latest_items[$k + 3]->id,
                                        $latest_items[$k + 3]->current_price,
                                    );
                                    $product_current_price = $flash_info['amount'];
                                    $flash_status = $flash_info['status'];
                                  @endphp

                                  @if ($flash_status == true)
                                    <span class="new-price">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                    </span>
                                    <span class="old-price text-decoration-line-through">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 3]->current_price)) }}
                                    </span>
                                  @else
                                    <span class="new-price">
                                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 3]->current_price)) }}
                                    </span>
                                    @if ($latest_items[$k + 3]->previous_price > 0)
                                      <span class="old-price text-decoration-line-through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 3]->previous_price)) }}
                                      </span>
                                    @endif
                                  @endif
                                </div>

                                <div class="btn-icon-group btn-inline btn-icon-group-sm">

                                  @if ($shop_settings->catalog_mode != 1)
                                    <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                                      data-title="{{ $latest_items[$k + 3]->itemContents[0]->title }}"
                                      data-current_price="{{ currency_converter($product_current_price) }}"
                                      data-item_id="{{ $latest_items[$k + 3]->id }}"
                                      data-language_id="{{ $uLang }}"
                                      data-totalVari="{{ check_variation($latest_items[$k + 3]->id) }}"
                                      data-totalVari="{{ check_variation($latest_items[$k + 3]->id) }}"
                                      data-variations="{{ check_variation($latest_items[$k + 3]->id) > 0 ? 'yes' : null }}"
                                      data-href="{{ route('front.user.add.cart', ['id' => $latest_items[$k + 3]->id, getParam()]) }}"
                                      data-bs-toggle="tooltip" data-placement="top"
                                      title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                        class="far fa-shopping-cart "></i></a>
                                  @endif

                                  <a href="javascript:void(0)" class="btn btn-icon rounded-pill quick-view-link"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-slug="{{ $latest_items[$k + 3]->itemContents[0]->slug }}"
                                    data-url="{{ route('front.user.productDetails.quickview', ['slug' => $latest_items[$k + 3]->itemContents[0]->slug, getParam()]) }}"
                                    title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                      class="fal fa-eye"></i>
                                  </a>

                                  <a class="btn btn-icon rounded-pill" data-bs-toggle="tooltip"
                                    onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $latest_items[$k + 3]->id, getParam()]) }}')"
                                    data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                      class="fal fa-random"></i></a>
                                  @php
                                    $customer_id = Auth::guard('customer')->check()
                                        ? Auth::guard('customer')->user()->id
                                        : null;
                                    $checkWishList = $customer_id
                                        ? checkWishList($latest_items[$k + 3]->id, $customer_id)
                                        : false;
                                  @endphp
                                  <a href="#"
                                    class="btn btn-icon rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    data-item_id="{{ $latest_items[$k + 3]->id }}"
                                    data-href="{{ route('front.user.add.wishlist', ['id' => $latest_items[$k + 3]->id, getParam()]) }}"
                                    data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $latest_items[$k + 3]->id, getParam()]) }}"
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
                  @endfor
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Product List End -->

