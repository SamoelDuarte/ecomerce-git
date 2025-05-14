    <!-- New Product Start -->
    @foreach ($categories as $cat)
      @if ($cat->is_feature == 1)
        <section class="products pb-70 lazy" >
          <div class="container">
            <div class="row gx-xl-5">
              <div class="col-lg-8">
                <div class="section-title title-inline mb-40 border-bottom">
                  <h2 class="title title-sm mb-20"> {{ $cat->name }}</h2>
                  <div class="slider-arrow-inline style-2 mb-20"
                    id="pro-slider-electronics-{{ $cat->id }}-arrows">
                  </div>
                </div>
                <div class="product-slider" id="pro-slider-electronics-skeleton-{{ $cat->id }}"
                  data-slick='{
                  "arrows": true,
                  "slidesToShow": 3,
                  "responsive": [
                      {
                          "breakpoint": 1200,
                          "settings": {
                              "slidesToShow": 2
                          }
                      },
                      {
                          "breakpoint": 992,
                          "settings": {
                              "slidesToShow": 2
                          }
                      },
                      {
                          "breakpoint": 575,
                          "settings": {
                              "slidesToShow": 1
                          }
                      }
                  ]

                }'>

                  <div class="product-default product-default-2 radius-md mb-30">
                    <figure class="product-img skeleton skeleton-big-img"></figure>
                    <div class="product-details">
                      <div class="d-flex btn-icon-group btn-inline mb-10">
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                      </div>

                      <div class="skeleton skeleton-category">

                      </div>

                      <div class="skeleton skeleton-title"></div>
                      <div class="skeleton skeleton-title"></div>
                      <div class="skeleton skeleton-ratings"></div>

                      <div class="product-price mb-0">
                        <span class="new-price skeleton skeleton-price"></span>
                        <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                      </div>
                    </div>

                  </div>
                  <div class="product-default product-default-2 radius-md mb-30">
                    <figure class="product-img skeleton skeleton-big-img"></figure>
                    <div class="product-details">
                      <div class="d-flex btn-icon-group btn-inline">
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                      </div>

                      <div class="skeleton skeleton-category">

                      </div>

                      <div class="skeleton skeleton-title"></div>
                      <div class="skeleton skeleton-title"></div>
                      <div class="skeleton skeleton-ratings"></div>

                      <div class="product-price mb-0">
                        <span class="new-price skeleton skeleton-price"></span>
                        <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                      </div>
                    </div>

                  </div>
                  <div class="product-default product-default-2 radius-md mb-30">
                    <figure class="product-img skeleton skeleton-big-img"></figure>
                    <div class="product-details">
                      <div class="d-flex btn-icon-group btn-inline">
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                        <span class="count-period skeleton skeleton-btn-icon"></span>
                      </div>

                      <div class="skeleton skeleton-category">

                      </div>

                      <div class="skeleton skeleton-title"></div>
                      <div class="skeleton skeleton-title"></div>
                      <div class="skeleton skeleton-ratings"></div>

                      <div class="product-price mb-0">
                        <span class="new-price skeleton skeleton-price"></span>
                        <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                      </div>
                    </div>

                  </div>



                </div>
              </div>

              @if ($ubs->middle_right_banner_section == 1)
                @if ($banners)
                  @php
                    $banner = $banners->firstWhere('position', 'right');
                  @endphp
                  @if ($banner)
                    <div class="col-lg-4">
                      <div class="banner-sm banner-vertical content-top radius-lg mb-30  ratio ratio-1-2">
                        <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}"
                          alt="Banner">
                        <div class="banner-content">
                          <div class="content-inner">
                            <h2 class="title-md">{{ $banner->title }}</h2>
                            @if (!is_null($banner->banner_url))
                              <a href="{{ $banner->banner_url }}"
                                class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banner->button_text }}
                                <span class="icon"><i class="fal fa-arrow-right"></i></span>
                              </a>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                @endif
              @endif

            </div>
          </div>
        </section>
      @endif
    @endforeach
    <!-- New Product End -->


  <!-- New Product Start -->
    @foreach ($categories as $cat)
      @if ($cat->is_feature == 1)
        <section class="products pb-70 actual-content">
          <div class="container">
            <div class="row gx-xl-5">
              <div class="col-lg-8">
                <div class="section-title title-inline mb-40 border-bottom">
                  <h2 class="title title-sm mb-20"> {{ $cat->name }}</h2>
                  <div class="slider-arrow-inline style-2 mb-20" id="pro-slider-electronics-{{ $cat->id }}-arrows">
                  </div>
                </div>
                <div class="product-slider" id="pro-slider-electronics-{{ $cat->id }}"
                  data-slick='{
                  "arrows": true,
                  "slidesToShow": 3,
                  "responsive": [
                      {
                          "breakpoint": 1200,
                          "settings": {
                              "slidesToShow": 2
                          }
                      },
                      {
                          "breakpoint": 992,
                          "settings": {
                              "slidesToShow": 2
                          }
                      },
                      {
                          "breakpoint": 575,
                          "settings": {
                              "slidesToShow": 1
                          }
                      }
                  ]

                }'>


                  @foreach ($cat->items as $single_item)
                    @php
                      $product_details = \App\Models\User\UserItem::where('id', $single_item->item_id)
                          ->with([
                              'itemContents' => function ($q) use ($uLang) {
                                  $q->where('language_id', '=', $uLang);
                              },
                              'sliders',
                          ])
                          ->where([['status', 1], ['is_feature', 1]])
                          ->first();
                    @endphp
                    @if (!is_null(@$product_details->itemContents[0]->slug))
                      <div class="product-default product-default-2 radius-md mb-30">
                        <figure class="product-img">
                          <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}"
                            class="lazy-container ratio ratio-1-1">
                            <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                              alt="Product">
                            <img class="lazyload hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                              alt="Product">
                          </a>
                        </figure>

                        @php
                          $flash_info = flashAmountStatus($product_details->id, $product_details->current_price);
                          $product_current_price = $flash_info['amount'];
                          $flash_status = $flash_info['status'];

                        @endphp

                        <div class="product-details">
                          <div class="btn-icon-group btn-inline">

                            <a onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details->id, getParam()]) }}')"
                              class="btn btn-icon rounded-pill mb-10 ms-0" data-bs-toggle="tooltip"
                              data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                class="fal fa-random"></i>
                            </a>

                            <a class="btn btn-icon mb-10 rounded-pill quick-view-link" data-bs-toggle="tooltip"
                              data-bs-placement="top" data-item_id="{{ $product_details->id }}"
                              data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product_details->itemContents[0]->slug, getParam()]) }}"
                              title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i></a>

                            @php
                              $customer_id = Auth::guard('customer')->check()
                                  ? Auth::guard('customer')->user()->id
                                  : null;
                              $checkWishList = $customer_id ? checkWishList($product_details->id, $customer_id) : false;
                            @endphp
                            <a href="#"
                              class="btn btn-icon mb-10 rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                              data-bs-toggle="tooltip" data-bs-placement="top"
                              data-item_id="{{ $product_details->id }}"
                              data-href="{{ route('front.user.add.wishlist', ['id' => $product_details->id, getParam()]) }}"
                              data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details->id, getParam()]) }}"
                              title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                                class="fal fa-heart"></i></a>
                            @if ($shop_settings->catalog_mode != 1)
                              <a class="btn btn-icon rounded-pill mb-10 cart-link cursor-pointer"
                                data-title="{{ $product_details->itemContents[0]->title }}"
                                data-current_price="{{ currency_converter($product_current_price) }}"
                                data-item_id="{{ $product_details->id }}" data-language_id="{{ $uLang }}"
                                data-totalVari="{{ check_variation($product_details->id) }}"
                                data-variations="{{ check_variation($product_details->id) > 0 ? 'yes' : null }}"
                                data-href="{{ route('front.user.add.cart', ['id' => $product_details->id, getParam()]) }}"
                                data-bs-toggle="tooltip" data-placement="top"
                                title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                  class="fal fa-shopping-cart "></i></a>
                            @endif

                          </div>
                          <span class="product-category text-sm"><a
                              href="{{ route('front.user.shop', ['category' => $product_details->itemContents[0]->category->slug, getParam()]) }}">{{ $product_details->itemContents[0]->category->name }}</a></span>
                          <h3 class="product-title lc-2">
                            <a
                              href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}">{{ $product_details->itemContents[0]->title }}</a>
                          </h3>

                          @if ($shop_settings->item_rating_system == 1)
                            <div class="d-flex align-items-center">
                              <div class="product-ratings rate text-xsm">
                                <div class="rating" style="width:{{ $product_details->rating * 20 }}%"></div>
                              </div>
                              <span class="ratings-total">({{ reviewCount($product_details->id) }})</span>
                            </div>
                          @endif

                          <div class="product-price mb-0">
                            @if ($flash_status == true)
                              <span class="new-price">
                                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                              </span>
                              <span class="old-price text-decoration-line-through">
                                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_details->current_price)) }}
                              </span>
                            @else
                              <span class="new-price">
                                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_details->current_price)) }}
                              </span>
                              <span class="old-price text-decoration-line-through">
                                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_details->previous_price)) }}
                              </span>
                            @endif
                          </div>
                        </div>

                        @if (!is_null(@$product_details->itemContents[0]->label_id))
                          @php
                            $item_label = DB::table('labels')
                                ->where('id', @$product_details->itemContents[0]->label_id)
                                ->first();
                            $label = $item_label->name ?? null;
                            $color = $item_label->color ?? null;
                          @endphp
                          <span class="label label-3 "
                            style="background-color: #{{ $color }}">{{ $label }}</span>
                        @endif

                        @if ($flash_status == true)
                          <span
                            class="label-discount-percentage"><x-flash-icon></x-flash-icon>{{ $product_details->flash_amount }}%</span>
                        @endif

                      </div>
                    @endif
                  @endforeach

                </div>
              </div>

              @if ($ubs->middle_right_banner_section == 1)
                @if ($banners)
                  @php
                    $banner = $banners->firstWhere('position', 'right');
                  @endphp
                  @if ($banner)
                    <div class="col-lg-4">
                      <div class="banner-sm banner-vertical content-top radius-lg mb-30  ratio ratio-1-2">
                        <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}"
                          alt="Banner">
                        <div class="banner-content">
                          <div class="content-inner">
                            <h2 class="title-md">{{ $banner->title }}</h2>
                            @if (!is_null($banner->banner_url))
                              <a href="{{ $banner->banner_url }}"
                                class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banner->button_text }}
                                <span class="icon"><i class="fal fa-arrow-right"></i></span>
                              </a>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                @endif
              @endif

            </div>
          </div>
        </section>
      @endif
    @endforeach
    <!-- New Product End -->


