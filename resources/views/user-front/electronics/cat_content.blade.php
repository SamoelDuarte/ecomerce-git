@foreach ($categories as $cat)
  @if ($cat->is_feature == 1)
    <!-- New Product Start -->
    <section class="products pb-70 lazy" >
      <div class="container">
        <div class="row gx-xl-5">
          @if ($ubs->left_banner_section == 1)
            @if ($banners)
              @php
                $leftBanners = $banners->where('position', 'left')->take(1);
              @endphp
              @if ($leftBanners)
                @foreach ($leftBanners as $banner)
                  <div class="col-lg-4">
                    <div class="banner-sm banner-vertical content-top radius-lg mb-30 ratio">
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
                @endforeach
              @endif
            @endif
          @endif

          <div class="col-lg-8">

            <div class="row">
              <div class="col-lg-4">
                <div class="product-default product-default-2 radius-md mb-30">
                  <figure class="product-img skeleton skeleton-big-img"></figure>

                  <div class="product-details">
                    <div class="btn-icon-group btn-inline pb-10">
                      <div class="skeleton skeleton-category"></div>
                    </div>
                    <div class="skeleton skeleton-category"></div>
                    <h4 class="skeleton skeleton-title"></h4>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mb-0">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                    </div>
                  </div>

                </div>
              </div>
              <div class="col-lg-4">
                <div class="product-default product-default-2 radius-md mb-30">
                  <figure class="product-img skeleton skeleton-big-img"></figure>

                  <div class="product-details">
                    <div class="btn-icon-group btn-inline pb-10">
                      <div class="skeleton skeleton-category"></div>
                    </div>
                    <div class="skeleton skeleton-category"></div>
                    <h4 class="skeleton skeleton-title"></h4>
                    <div class="skeleton skeleton-ratings"></div>

                    <div class="product-price mb-0">
                      <span class="new-price skeleton skeleton-price"></span>
                      <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                    </div>
                  </div>

                </div>
              </div>
              <div class="col-lg-4">
                <div class="product-default product-default-2 radius-md mb-30">
                  <figure class="product-img skeleton skeleton-big-img"></figure>

                  <div class="product-details">
                    <div class="btn-icon-group btn-inline pb-10">
                      <div class="skeleton skeleton-category"></div>
                    </div>
                    <div class="skeleton skeleton-category"></div>
                    <h4 class="skeleton skeleton-title"></h4>
                    <div class="skeleton skeleton-ratings"></div>

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
      </div>
    </section>
  @endif
@endforeach


@foreach ($categories as $cat)
  @if ($cat->is_feature == 1)
    <!-- New Product Start -->
    <section class="products pb-70 actual-content">
      <div class="container">
        <div class="row gx-xl-5">
          @if ($ubs->left_banner_section == 1)
            @if ($banners)
              @php
                $leftBanners = $banners->where('position', 'left')->take(1);
              @endphp
              @if ($leftBanners)
                @foreach ($leftBanners as $banner)
                  <div class="col-lg-4">
                    <div class="banner-sm banner-vertical content-top radius-lg mb-30 ratio">
                      <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
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
                @endforeach
              @endif
            @endif
          @endif

          <div class="col-lg-8">
            <div class="section-title title-inline mb-40 border-bottom">
              <h2 class="title title-sm mb-20">{{ $cat->name }}</h2>
              <div class="slider-arrow-inline style-2 mb-20" id="pro-slider-electronics-{{ $cat->id }}-arrows">
              </div>
            </div>
            <div class="product-slider" id="pro-slider-electronics-{{ $cat->id }}"
              data-slick='{"arrows": true, "slidesToShow": 3}'>


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
                      <div class="btn-icon-group btn-inline pb-10">
                        <a href="#"
                          class="btn btn-sm btn-icon color-primary ms-0 rounded-pill w-auto icon-start hover-hide"
                          data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Cart"><i
                            class="fal fa-shopping-bag"></i>{{ $keywords['Add_to_Cart'] ?? __(' Add to Cart') }}
                        </a>
                        <div class="hover-show">

                          <a onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details->id, getParam()]) }}')"
                            class="btn btn-icon rounded-pill ms-0" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i></a>

                          <a href="javascript:void(0)" class="btn btn-icon rounded-pill quick-view-link"
                            data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $product_details->id }}"
                            data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product_details->itemContents[0]->slug, getParam()]) }}"
                            title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i></a>

                          @php
                            $customer_id = Auth::guard('customer')->check()
                                ? Auth::guard('customer')->user()->id
                                : null;
                            $checkWishList = $customer_id ? checkWishList($product_details->id, $customer_id) : false;
                          @endphp
                          <a href="#"
                            class="btn btn-icon rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $product_details->id }}"
                            data-href="{{ route('front.user.add.wishlist', ['id' => $product_details->id, getParam()]) }}"
                            data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details->id, getParam()]) }}"
                            title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                              class="fal fa-heart"></i></a>
                          @if ($shop_settings->catalog_mode != 1)
                            <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                              data-title="{{ $product_details->itemContents[0]->title }}"
                              data-current_price="{{ currency_converter($product_current_price) }}"
                              data-item_id="{{ $product_details->id }}"
                              data-totalVari="{{ check_variation($product_details->id) }}"
                              data-variations="{{ check_variation($product_details->id) > 0 ? 'yes' : null }}"
                              data-href="{{ route('front.user.add.cart', ['id' => $product_details->id, getParam()]) }}"
                              data-bs-toggle="tooltip" data-bs-placement="top"
                              title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                class="far fa-shopping-cart "></i></a>
                          @endif

                        </div>
                      </div>
                      <a
                        href="{{ route('front.user.shop', ['category' => $product_details->itemContents[0]->category->slug, getParam()]) }}">
                        <span
                          class="product-category text-sm">{{ $product_details->itemContents[0]->category->name }}</span>
                      </a>

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
                          @if ($product_details->previous_price > 0)
                            <span class="old-price text-decoration-line-through">
                              {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_details->previous_price)) }}
                            </span>
                          @endif
                        @endif
                      </div>
                    </div>

                    @php
                      $item_label = DB::table('labels')
                          ->where('id', $product_details->itemContents[0]->label_id)
                          ->first();
                      $label = $item_label->name ?? null;
                      $color = $item_label->color ?? null;
                    @endphp
                    <span class="label label-2"
                      style="background-color: #{{ $color }}">{{ $label }}</span>

                    @if ($product_details->flash == 1)
                      <span
                        class="label-discount-percentage"><x-flash-icon></x-flash-icon>{{ $product_details->flash_amount }}%</span>
                    @endif
                  </div>
                @endif
              @endforeach

            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
@endforeach


