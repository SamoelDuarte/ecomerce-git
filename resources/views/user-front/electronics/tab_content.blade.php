    <!-- Best Sale Start -->
    <section class="products pb-100 lazy">
      <!-- Background Image -->
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="title text-center mb-10">
              <h2 class="title mb-20">
                {{ $userSec->tab_section_title ?? ($keywords['Tab Section'] ?? __('Tab Section')) }} </h2>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="tabs-navigation tabs-navigation-scroll d-flex mb-20">
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
        <div class="row">
          <div class="col-12">
            <div class="row gx-xl-4">
              <div class="col-lg-12">
                <div class="tab-content">
                  <div class="tab-pane fade active show">
                    <div class="row">
                      <div class="col-lg-3 col-md-6">
                        <div class="product-default product-default-2 radius-md mb-30">
                          <figure class="product-img skeleton skeleton-big-img"></figure>

                          <div class="product-details">

                            <div class="btn-icon-group btn-inline mb-10">
                              <div class="skeleton skeleton-category"></div>
                            </div>
                            <div class="skeleton skeleton-category"></div>
                            <h4 class="skeleton skeleton-title"></h4>
                            <div class="product-price mb-0">
                              <span class="new-price skeleton skeleton-price"></span>
                              <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-6">
                        <div class="product-default product-default-2 radius-md mb-30">
                          <figure class="product-img skeleton skeleton-big-img"></figure>

                          <div class="product-details">

                            <div class="btn-icon-group btn-inline mb-10">
                              <div class="skeleton skeleton-category"></div>
                            </div>
                            <div class="skeleton skeleton-category"></div>
                            <h4 class="skeleton skeleton-title"></h4>
                            <div class="product-price mb-0">
                              <span class="new-price skeleton skeleton-price"></span>
                              <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-6">
                        <div class="product-default product-default-2 radius-md mb-30">
                          <figure class="product-img skeleton skeleton-big-img"></figure>

                          <div class="product-details">

                            <div class="btn-icon-group btn-inline mb-10">
                              <div class="skeleton skeleton-category"></div>
                            </div>
                            <div class="skeleton skeleton-category"></div>
                            <h4 class="skeleton skeleton-title"></h4>
                            <div class="product-price mb-0">
                              <span class="new-price skeleton skeleton-price"></span>
                              <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 col-md-6">
                        <div class="product-default product-default-2 radius-md mb-30">
                          <figure class="product-img skeleton skeleton-big-img"></figure>

                          <div class="product-details">

                            <div class="btn-icon-group btn-inline mb-10">
                              <div class="skeleton skeleton-category"></div>
                            </div>
                            <div class="skeleton skeleton-category"></div>
                            <h4 class="skeleton skeleton-title"></h4>
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
        </div>
      </div>
    </section>
    <!-- Best Sale End -->


    <!-- Best Sale Start -->
    <section class="products pb-100 actual-content">
      <!-- Background Image -->
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="title text-center mb-10">
              <h2 class="title mb-20">
                {{ $userSec->tab_section_title ?? ($keywords['Tab Section'] ?? __('Tab Section')) }} </h2>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="tabs-navigation tabs-navigation-scroll d-flex mb-20">
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
        <div class="row">
          <div class="col-12">
            <div class="row gx-xl-4">
              <div class="col-lg-12">
                <div class="tab-content">
                  @if (count($tabs) == 0)
                    <h5 class="title text-center mb-20">
                      {{ $userSec->category_section_title ?? ($keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND')) }}
                    </h5>
                  @else
                    @for ($i = 0; $i < count($tabs); $i++)
                      <div class="tab-pane fade {{ $i == 0 ? 'active show' : '' }}" id="{{ $tabs[$i]->slug }}">
                        <div class="row">
                          @php
                            $products = json_decode($tabs[$i]->products, true);
                          @endphp

                          @if (!is_null($products))
                            @for ($j = 0; $j < count($products); $j++)
                              @if ($j < 8)
                                <div class="col-lg-3 col-md-6">
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
                                          <a href="#"
                                            class="btn btn-sm btn-icon color-primary ms-0 rounded-pill w-auto icon-start hover-hide"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Add to Cart"><i
                                              class="fal fa-shopping-bag"></i>{{ $keywords['Add_to_Cart'] ?? __(' Add to Cart') }}
                                          </a>
                                          <div class="hover-show">
                                            @if ($shop_settings->catalog_mode != 1)
                                              <a class="btn btn-icon rounded-pill cart-link cursor-pointer"
                                                data-title="{{ $product_details->itemContents[0]->title }}"
                                                data-current_price="{{ currency_converter($product_current_price) }}"
                                                data-item_id="{{ $product_details->id }}"
                                                data-variations="{{ check_variation($product_details->id) > 0 ? 'yes' : null }}"
                                                data-totalVari="{{ check_variation($product_details->id) }}"
                                                data-href="{{ route('front.user.add.cart', ['id' => $product_details->id, getParam()]) }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                                  class="far fa-shopping-cart "></i>
                                              </a>
                                            @endif

                                            <a class="btn btn-icon rounded-pill quick-view-link"
                                              data-bs-toggle="tooltip" data-bs-placement="top"
                                              data-slug="{{ $product_details->itemContents[0]->slug }}"
                                              data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product_details->itemContents[0]->slug, getParam()]) }}"
                                              title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i
                                                class="fal fa-eye"></i></a>

                                            @php
                                              $customer_id = Auth::guard('customer')->check()
                                                  ? Auth::guard('customer')->user()->id
                                                  : null;
                                              $checkWishList = $customer_id
                                                  ? checkWishList($product_details->id, $customer_id)
                                                  : false;
                                            @endphp
                                            <a href="#"
                                              class="btn btn-icon rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                                              data-bs-toggle="tooltip" data-bs-placement="top"
                                              data-item_id="{{ $product_details->id }}"
                                              data-href="{{ route('front.user.add.wishlist', ['id' => $product_details->id, getParam()]) }}"
                                              data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details->id, getParam()]) }}"
                                              title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                                                class="fal fa-heart"></i></a>


                                            <a class="btn btn-icon rounded-pill ms-0" data-bs-toggle="tooltip"
                                              data-bs-placement="top"
                                              onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details->id, getParam()]) }}')"
                                              title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                                                class="fal fa-random"></i></a>

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

                                      @if ($flash_status == true)
                                        <span
                                          class="label-discount-percentage"><x-flash-icon></x-flash-icon>{{ $product_details->flash_amount }}%</span>
                                      @endif
                                    </div>
                                  @endif
                                </div>
                              @endif
                            @endfor
                          @else
                            <h5 class="title text-center mb-20">
                              {{ $userSec->category_section_title ?? ($keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND')) }}
                            </h5>
                          @endif
                        </div>
                      </div>
                    @endfor
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Best Sale End -->
