    <!-- Best Sale Start -->
    <section class="products pt-70 pb-100 lazy" >
      <!-- Background Image -->
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-center mb-40">
              <h2 class="title mb-20">
                {{ $userSec->tab_section_title ?? ($keywords['Tab Section'] ?? __('Tab Section')) }} </h2>
              <div class="tabs-navigation tabs-navigation-scroll d-flex">
                <!-- data-hover="fancyHover" -->
                <ul class="nav nav-tabs" data-hover="fancyHover">
                  @foreach ($tabs as $key => $tab)
                    <li class="nav-item">
                      <button class="nav-link hover-effect rounded-pill {{ $key == 0 ? 'active' : '' }}"
                        data-bs-toggle="tab" data-bs-target="#{{ $tab->slug }}"
                        type="button">{{ $tab->name }}</button>
                    </li>
                  @endforeach

                </ul>
              </div>
            </div>
          </div>
          <div class="col-12">

            <div class="tab-content">

              <div class="tab-pane fade show active">
                <div class="row justify-content-center">

                  <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="product-default product-default-2 radius-md mb-30">
                      <figure class="product-img skeleton skeleton-big-img"></figure>
                      <div class="product-details">
                        <div class="d-flex btn-icon-group btn-inline mb-10">
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                        </div>
                        <div class="skeleton skeleton-category"></div>
                        <div class="skeleton skeleton-title"></div>
                        <div class="skeleton skeleton-ratings"></div>
                        <div class="product-price mb-0">
                          <span class="new-price skeleton skeleton-price"></span>
                          <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="product-default product-default-2 radius-md mb-30">
                      <figure class="product-img skeleton skeleton-big-img"></figure>
                      <div class="product-details">
                        <div class="d-flex btn-icon-group btn-inline mb-10">
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                        </div>
                        <div class="skeleton skeleton-category"></div>
                        <div class="skeleton skeleton-title"></div>
                        <div class="skeleton skeleton-ratings"></div>
                        <div class="product-price mb-0">
                          <span class="new-price skeleton skeleton-price"></span>
                          <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="product-default product-default-2 radius-md mb-30">
                      <figure class="product-img skeleton skeleton-big-img"></figure>
                      <div class="product-details">
                        <div class="d-flex btn-icon-group btn-inline mb-10">
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                        </div>
                        <div class="skeleton skeleton-category"></div>
                        <div class="skeleton skeleton-title"></div>
                        <div class="skeleton skeleton-ratings"></div>
                        <div class="product-price mb-0">
                          <span class="new-price skeleton skeleton-price"></span>
                          <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="product-default product-default-2 radius-md mb-30">
                      <figure class="product-img skeleton skeleton-big-img"></figure>
                      <div class="product-details">
                        <div class="d-flex btn-icon-group btn-inline mb-10">
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                          <span class="count-period skeleton skeleton-btn-icon"></span>
                        </div>
                        <div class="skeleton skeleton-category"></div>
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
              </div>


            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- Best Sale End -->


   <!-- Best Sale Start -->
    <section class="products pt-70 pb-100 actual-content">
      <!-- Background Image -->
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-center mb-40">
              <h2 class="title mb-20">
                {{ $userSec->tab_section_title ?? ($keywords['Tab Section'] ?? __('Tab Section')) }} </h2>
              <div class="tabs-navigation tabs-navigation-scroll d-flex">
                <!-- data-hover="fancyHover" -->
                <ul class="nav nav-tabs" data-hover="fancyHover">
                  @foreach ($tabs as $key => $tab)
                    <li class="nav-item">
                      <button class="nav-link hover-effect rounded-pill {{ $key == 0 ? 'active' : '' }}"
                        data-bs-toggle="tab" data-bs-target="#{{ $tab->slug }}"
                        type="button">{{ $tab->name }}</button>
                    </li>
                  @endforeach

                </ul>
              </div>
            </div>
          </div>
          <div class="col-12">
            @if (count($tabs) == 0)
              <h5 class="title text-center mb-20">
                {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
              </h5>
            @else
              <div class="tab-content">

                @for ($i = 0; $i < count($tabs); $i++)
                  <div class="tab-pane fade show {{ $i == 0 ? 'active' : '' }}" id="{{ $tabs[$i]->slug }}">
                    <div class="row justify-content-center">
                      @php
                        $products = json_decode($tabs[$i]->products, true);
                      @endphp
                      @if (!is_null($products))
                        @for ($j = 0; $j < count($products); $j++)
                          <div class="col-lg-3 col-md-6 col-sm-6">
                            @php
                              $product_details = \App\Models\User\UserItem::where('id', $products[$j])
                                  ->with([
                                      'itemContents' => function ($q) use ($uLang) {
                                          $q->where('language_id', '=', $uLang);
                                      },
                                      'sliders',
                                  ])
                                  ->first();
                            @endphp
                            @if (!is_null(@$product_details->itemContents[0]->slug))
                              <div class="product-default product-default-2 radius-md mb-30">
                                <figure class="product-img">
                                  <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}"
                                    class="lazy-container ratio ratio-1-1">
                                    <img class="lazyload default-img"
                                      src="{{ asset('assets/front/images/placeholder.png') }}"
                                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                                      alt="Product">
                                    <img class="lazyload hover-img"
                                      src="{{ asset('assets/front/images/placeholder.png') }}"
                                      data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                                      alt="Product">
                                  </a>
                                </figure>
                                @php
                                  $flash_info = flashAmountStatus(
                                      $product_details->id,
                                      $product_details->current_price,
                                  );
                                  $product_current_price = $flash_info['amount'];
                                  $flash_status = $flash_info['status'];

                                @endphp
                                <div class="product-details">
                                  <div class="btn-icon-group btn-inline mb-10">

                                    @if ($shop_settings->catalog_mode != 1)
                                      <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                                        data-title="{{ $product_details->itemContents[0]->title }}"
                                        data-current_price="{{ currency_converter($product_current_price) }}"
                                        data-item_id="{{ $product_details->id }}"
                                        data-language_id="{{ $uLang }}"
                                        data-totalVari="{{ check_variation($product_details->id) }}"
                                        data-variations="{{ check_variation($product_details->id) > 0 ? 'yes' : null }}"
                                        data-href="{{ route('front.user.add.cart', ['id' => $product_details->id, getParam()]) }}"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                          class="fal fa-shopping-cart "></i></a>
                                    @endif

                                    <a class="btn btn-icon rounded-pill quick-view-link" data-bs-toggle="tooltip"
                                      data-bs-placement="top" data-slug="{{ $product_details->itemContents[0]->slug }}"
                                      data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product_details->itemContents[0]->slug, getParam()]) }}"
                                      title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                        class="fal fa-eye"></i></a>

                                    <a class="btn btn-icon rounded-pill ms-0" data-bs-toggle="tooltip"
                                      data-bs-placement="top"
                                      onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details->id, getParam()]) }}')"
                                      title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                        class="fal fa-random"></i></a>

                                    @php
                                      $customer_id = Auth::guard('customer')->check()
                                          ? Auth::guard('customer')->user()->id
                                          : null;
                                      $checkWishList = $customer_id
                                          ? checkWishList($product_details->id, $customer_id)
                                          : false;
                                    @endphp
                                    <a href="#"
                                      class="btn btn-icon rounded-pill ms-0 btn-wish {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                      data-bs-toggle="tooltip" data-bs-placement="top"
                                      data-url="{{ route('front.user.add.wishlist', ['id' => $product_details->id, getParam()]) }}"
                                      data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details->id, getParam()]) }}"
                                      title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                                        class="fal fa-heart"></i></a>


                                  </div>
                                  <span class="product-category text-sm">
                                    <a
                                      href="{{ route('front.user.shop', ['category' => $product_details->itemContents[0]->category->slug, getParam()]) }}">{{ $product_details->itemContents[0]->category->name }}</a>

                                  </span>
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
                                      <span class="old-price line_through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_details->current_price)) }}
                                      </span>
                                    @else
                                      <span class="new-price">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_details->current_price)) }}
                                      </span>
                                      <span class="old-price line_through">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_details->previous_price)) }}
                                      </span>
                                    @endif
                                  </div>
                                </div>
                                @php
                                  $item_label = DB::table('labels')
                                      ->where('id', @$product_details->itemContents[0]->label_id)
                                      ->first();
                                  $label = $item_label->name ?? null;
                                  $color = $item_label->color ?? null;
                                @endphp
                                <span class="label label-3"
                                  style="background-color: #{{ $color }}">{{ $label }}</span>

                                @if ($flash_status == true)
                                  <span
                                    class="label-discount-percentage"><x-flash-icon></x-flash-icon>{{ $product_details->flash_amount }}%</span>
                                @endif
                              </div>
                            @endif
                          </div>
                        @endfor
                      @endif
                    </div>
                  </div>
                @endfor

              </div>
              <div class="text-center mt-10">
                <a href="{{ route('front.user.shop', getParam()) }}"
                  class="btn btn-lg btn-primary rounded-pill icon-end shadow">{{ $keywords['View More'] ?? __('View More') }}
                  <span class="icon"><i class="fal fa-arrow-right"></i></span>
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>
    <!-- Best Sale End -->


