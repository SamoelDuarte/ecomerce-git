      <!-- Product List Start -->
    <section class="products pt-100 lazy" >
      <div class="container">
        <div class="row gx-xl-4">
          <div class="col-lg-9">
            <div class="section-title title-inline title-bottom-line mb-10">
              <h2 class="title title-sm mb-0">
                {{ $userSec->latest_product_section_title ?? ($keywords['Latest_Toys_Collection'] ?? __('Latest Toys Collection')) }}
              </h2>
              <div class="slider-arrow-inline style-2" id="product-list-slider-skeleton-1-arrows">
              </div>
            </div>

            <div class="product-list mb-30">
              <div class="product-list-slider">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="mb-30">
                      <div class="product-default product-inline product-inline-2 mt-20">
                        <figure class="product-img skeleton skeleton-img"></figure>
                        <div class="product-details">
                          <div class="skeleton skeleton-title"></div>
                          <div class="skeleton skeleton-ratings"></div>

                          <div class="product-price mt-2 mb-10">
                            <span class="new-price skeleton skeleton-price"></span>
                            <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                          </div>

                          <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex">
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="mb-30">
                      <div class="product-default product-inline product-inline-2 mt-20">
                        <figure class="product-img skeleton skeleton-img"></figure>
                        <div class="product-details">
                          <div class="skeleton skeleton-title"></div>
                          <div class="skeleton skeleton-ratings"></div>

                          <div class="product-price mt-2 mb-10">
                            <span class="new-price skeleton skeleton-price"></span>
                            <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                          </div>

                          <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex">
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                            <span class="count-period skeleton skeleton-btn-icon"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="mb-30">
                      <div class="product-default product-inline product-inline-2 mt-20">
                        <figure class="product-img skeleton skeleton-img"></figure>
                        <div class="product-details">
                          <div class="skeleton skeleton-title"></div>
                          <div class="skeleton skeleton-ratings"></div>

                          <div class="product-price mt-2 mb-10">
                            <span class="new-price skeleton skeleton-price"></span>
                            <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                          </div>

                          <div class="btn-icon-group btn-inline btn-icon-group-sm d-flex">
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

          <div class="col-lg-3">
            @if ($ubs->left_banner_section == 1)
              @if ($banners)
                @for ($i = 0; $i < count($banners); $i++)
                  @if ($banners[$i]->position == 'middle')
                    <div class="banner-sm banner-vertical content-top radius-lg mb-30">
                      <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                        alt="Banner">
                      <div class="banner-content mx-auto px-5 mw-100">
                        <div class="content-inner">
                          <span class="sub-title">{{ $banners[$i]->title }}</span>
                          <h3 class="title">{{ $banners[$i]->subtitle }}</h3>
                          @if ($banners[$i]->button_text)
                            <a href="{{ $banners[$i]->banner_url }}"
                              class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banners[$i]->button_text }}
                              <span class="icon"><i class="fal fa-arrow-right"></i></span>
                            </a>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endif
                @endfor
              @endif
            @endif
          </div>
        </div>
      </div>
    </section>
    <!-- Product List End -->




   <!-- Product List Start -->
    <section class="products pt-100 actual-content">
      <div class="container">
        <div class="row gx-xl-4">
          <div class="col-lg-9">
            <div class="section-title title-inline title-bottom-line mb-10">
              <h2 class="title title-sm mb-0">
                {{ $userSec->latest_product_section_title ?? ($keywords['Latest_Toys_Collection'] ?? __('Latest Toys Collection')) }}
              </h2>
              <div class="slider-arrow-inline style-2" id="product-list-slider-1-arrows">
              </div>
            </div>
            @if (count($latest_items) == 0)
              <h5 class="title text-center mb-20">
                {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
              </h5>
            @else
              <div class="product-list mb-30">
                <div class="product-list-slider" id="product-list-slider-1"
                  data-slick='{"dots": true, "autoplay": false, "slidesToShow": 3}'>

                  @for ($k = 1; $k < count($latest_items); $k += 2)
                    @if (isset($latest_items[$k]) && count($latest_items[$k]->itemContents) > 0)
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
                            <h4 class="product-title lc-1">
                              <a
                                href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k]->itemContents[0]->slug]) }}">{{ $latest_items[$k]->itemContents[0]->title }}</a>
                            </h4>

                            @if ($shop_settings->item_rating_system == 1)
                              <div class="d-flex align-items-center">
                                <div class="product-ratings rate text-xsm">
                                  <div class="rating" style="width:{{ $latest_items[$k]->rating * 20 }}%"></div>
                                </div>
                                <span class="ratings-total">({{ reviewCount($latest_items[$k]->id) }})</span>
                              </div>
                            @endif

                            @php
                              $flash_info = flashAmountStatus($latest_items[$k]->id, $latest_items[$k]->current_price);
                              $product_current_price = $flash_info['amount'];
                              $flash_status = $flash_info['status'];
                            @endphp

                            <div class="product-price mt-2 mb-10">
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
                                <span class="old-price text-decoration-line-through">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k]->previous_price)) }}
                                </span>
                              @endif
                            </div>

                            <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                              @if ($shop_settings->catalog_mode != 1)
                                <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                                  data-title="{{ $latest_items[$k]->itemContents[0]->title }}"
                                  data-current_price="{{ currency_converter($product_current_price) }}"
                                  data-item_id="{{ $latest_items[$k]->id }}" data-language_id="{{ $uLang }}"
                                  data-totalVari="{{ check_variation($latest_items[$k]->id) }}"
                                  data-variations="{{ check_variation($latest_items[$k]->id) > 0 ? 'yes' : null }}"
                                  data-href="{{ route('front.user.add.cart', ['id' => $latest_items[$k]->id, getParam()]) }}"
                                  data-bs-toggle="tooltip" data-bs-placement="top"
                                  title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                    class="fal fa-shopping-cart "></i></a>
                              @endif

                              <a class="btn btn-icon rounded-pill quick-view-link" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-slug="{{ $latest_items[$k]->itemContents[0]->slug }}"
                                data-url="{{ route('front.user.productDetails.quickview', ['slug' => $latest_items[$k]->itemContents[0]->slug, getParam()]) }}"
                                title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                  class="fal fa-eye"></i></a>

                              <a class="btn btn-icon rounded-pill ms-0" data-bs-toggle="tooltip" data-bs-placement="top"
                                onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $latest_items[$k]->id, getParam()]) }}')"
                                title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i></a>

                              @php
                                $customer_id = Auth::guard('customer')->check()
                                    ? Auth::guard('customer')->user()->id
                                    : null;
                                $checkWishList = $customer_id
                                    ? checkWishList($latest_items[$k]->id, $customer_id)
                                    : false;
                              @endphp
                              <a href="#"
                                class="btn btn-icon rounded-pill ms-0 btn-wish {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                data-url="{{ route('front.user.add.wishlist', ['id' => $latest_items[$k]->id, getParam()]) }}"
                                data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $latest_items[$k]->id, getParam()]) }}"
                                title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                                  class="fal fa-heart"></i></a>
                            </div>
                          </div>
                        </div>
                        @if (isset($latest_items[$k + 1]) && !empty($latest_items[$k + 1]->itemContents))
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
                              <h4 class="product-title lc-1">
                                <a
                                  href="{{ route('front.user.productDetails', [getParam(), 'slug' => $latest_items[$k + 1]->itemContents[0]->slug]) }}">{{ $latest_items[$k + 1]->itemContents[0]->title }}</a>
                              </h4>

                              @if ($shop_settings->item_rating_system == 1)
                                <div class="d-flex align-items-center">
                                  <div class="product-ratings rate text-xsm">
                                    <div class="rating" style="width:{{ $latest_items[$k + 1]->rating * 20 }}%"></div>
                                  </div>
                                  <span class="ratings-total">({{ reviewCount($latest_items[$k + 1]->id) }})</span>
                                </div>
                              @endif

                              @php
                                $flash_info = flashAmountStatus(
                                    $latest_items[$k + 1]->id,
                                    $latest_items[$k + 1]->current_price,
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
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 1]->current_price)) }}
                                  </span>
                                @else
                                  <span class="new-price">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($latest_items[$k + 1]->current_price)) }}
                                  </span>
                                  <span
                                    class="old-price text-decoration-line-through">{{ currency_converter($latest_items[$k + 1]->previous_price) }}</span>
                                @endif
                              </div>

                              <div class="btn-icon-group btn-inline btn-icon-group-sm ">

                                @if ($shop_settings->catalog_mode != 1)
                                  <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                                    data-title="{{ $latest_items[$k + 1]->itemContents[0]->title }}"
                                    data-current_price="{{ currency_converter($product_current_price) }}"
                                    data-totalVari="{{ check_variation($latest_items[$k + 1]->id) }}"
                                    data-item_id="{{ $latest_items[$k + 1]->id }}"
                                    data-language_id="{{ $uLang }}"
                                    data-variations="{{ check_variation($latest_items[$k + 1]->id) > 0 ? 'yes' : null }}"
                                    data-href="{{ route('front.user.add.cart', ['id' => $latest_items[$k + 1]->id, getParam()]) }}"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                      class="fal fa-shopping-cart "></i></a>
                                @endif

                                <a class="btn btn-icon rounded-pill quick-view-link" data-bs-toggle="tooltip"
                                  data-bs-placement="top"
                                  data-slug="{{ $latest_items[$k + 1]->itemContents[0]->slug }}"
                                  data-url="{{ route('front.user.productDetails.quickview', ['slug' => $latest_items[$k + 1]->itemContents[0]->slug, getParam()]) }}"
                                  title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                    class="fal fa-eye"></i></a>

                                <a class="btn btn-icon rounded-pill ms-0" data-bs-toggle="tooltip"
                                  data-bs-placement="top"
                                  onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $latest_items[$k + 1]->id, getParam()]) }}')"
                                  title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
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
                                  class="btn btn-icon rounded-pill ms-0 btn-wish {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                  data-bs-toggle="tooltip" data-bs-placement="top"
                                  data-url="{{ route('front.user.add.wishlist', ['id' => $latest_items[$k + 1]->id, getParam()]) }}"
                                  data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $latest_items[$k + 1]->id, getParam()]) }}"
                                  title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                                    class="fal fa-heart"></i></a>


                              </div>

                            </div>
                          </div>
                        @endif
                      </div>
                    @endif
                  @endfor
                </div>
              </div>
            @endif
          </div>

          <div class="col-lg-3">
            @if ($ubs->left_banner_section == 1)
              @if ($banners)
                @for ($i = 0; $i < count($banners); $i++)
                  @if ($banners[$i]->position == 'middle')
                    <div class="banner-sm banner-vertical content-top radius-lg mb-30">
                      <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                        alt="Banner">
                      <div class="banner-content mx-auto px-5 mw-100">
                        <div class="content-inner">
                          <span class="sub-title">{{ $banners[$i]->title }}</span>
                          <h3 class="title">{{ $banners[$i]->subtitle }}</h3>
                          @if ($banners[$i]->button_text)
                            <a href="{{ $banners[$i]->banner_url }}"
                              class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banners[$i]->button_text }}
                              <span class="icon"><i class="fal fa-arrow-right"></i></span>
                            </a>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endif
                @endfor
              @endif
            @endif
          </div>
        </div>
      </div>
    </section>
    <!-- Product List End -->


