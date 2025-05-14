  <div class="col-xl-4 col-lg-6 actual-content">
    <div class="section-title title-inline title-bottom-line mb-10">
      <h2 class="title title-sm mb-0">{{ $userSec->top_selling_product_section_title ?? __('Top Selling') }}
      </h2>
      <div class="slider-arrow-inline product-list-slider-arrows" id="product-list-slider-2-arrows">
      </div>
    </div>

    <div class="product-list mb-20">
      @if (count($top_selling) == 0)
        <h5 class="title mb-20">
          {{ $keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND') }}
        </h5>
      @else
        <div class="product-list-slider" id="product-list-slider-2">
          @if (!empty($top_selling))
            @for ($k = 0; $k <= count($top_selling); $k += 4)
              @if ($k < count($top_selling) - 1)
                @if ($top_selling[$k]->item->itemContents != '[]')
                  @if (@$top_selling[$k]->item->status == 1)
                    <div>
                      @if (isset($top_selling[$k]))
                        <div class="product-default product-inline product-inline-3 mt-20">
                          <figure class="product-img">
                            <a href="#" class="lazy-container ratio ratio-1-1">
                              <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_selling[$k]->item->thumbnail) }}"
                                alt="Product">
                            </a>
                          </figure>
                          <div class="product-details">
                            @if (count($top_selling[$k]->item->itemContents) > 0)
                              <span class="product-category text-sm"><a
                                  href="{{ route('front.user.shop', ['category' => $top_selling[$k]->item->itemContents[0]->category->slug, getParam()]) }}">{{ @$top_selling[$k]->item->itemContents[0]->category->name }}</a></span>
                              <h4 class="product-title lc-1 fw-bold">
                                <a
                                  href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k]->item->itemContents[0]->slug]) }}">{{ $top_selling[$k]->item->itemContents[0]->title }}</a>({{ $top_selling[$k]->quantity }})
                              </h4>
                            @endif

                            @if ($shop_settings->item_rating_system == 1)
                              <div class="d-flex align-items-center">
                                <div class="product-ratings rate text-xsm">
                                  <div class="rating" style="width:{{ $top_selling[$k]->item->rating * 20 }}%">
                                  </div>
                                </div>
                                <span class="ratings-total">({{ reviewCount($top_selling[$k]->item->id) }})</span>
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

                            <div class="product-price mt-1 mb-10">
                              @if ($flash_status == true)
                                <span class="new-price">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                </span>
                                <span class="old-price line_through">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k]->item->current_price)) }}
                                </span>
                              @else
                                <span class="new-price">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k]->item->current_price)) }}
                                </span>
                                <span class="old-price line_through">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k]->item->previous_price)) }}
                                </span>
                              @endif
                            </div>

                            <div class="btn-icon-group btn-inline btn-icon-group-sm">

                              @if ($shop_settings->catalog_mode != 1)
                                <a class="btn btn-icon radius-0 cart-link cursor-pointer"
                                  data-title="{{ $top_selling[$k]->item->itemContents[0]->title }}"
                                  data-current_price="{{ currency_converter($product_current_price) }}"
                                  data-item_id="{{ $top_selling[$k]->item->id }}"
                                  data-language_id="{{ $uLang }}"
                                  data-totalVari="{{ check_variation($top_selling[$k]->item->id) }}"
                                  data-variations="{{ check_variation($top_selling[$k]->item->id) > 0 ? 'yes' : null }}"
                                  data-href="{{ route('front.user.add.cart', ['id' => $top_selling[$k]->item->id, getParam()]) }}"
                                  data-bs-toggle="tooltip" data-placement="top"
                                  title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                                    class="far fa-shopping-cart "></i></a>
                              @endif

                              <a href="javascript:void(0)" class="btn btn-icon radius-0 quick-view-link"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                data-slug="{{ $top_selling[$k]->item->itemContents[0]->slug }}"
                                data-url="{{ route('front.user.productDetails.quickview', ['slug' => $top_selling[$k]->item->itemContents[0]->slug, getParam()]) }}"
                                title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i>
                              </a>

                              <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_selling[$k]->item->id, getParam()]) }}')"
                                data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
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

                      @if (isset($top_selling[$k + 1]))
                        <div class="product-default product-inline product-inline-3 mt-20">
                          <figure class="product-img">
                            <a href="#" class="lazy-container ratio ratio-1-1">
                              <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_selling[$k + 1]->item->thumbnail) }}"
                                alt="Product">
                            </a>
                          </figure>
                          <div class="product-details">
                            @if (count($top_selling[$k + 1]->item->itemContents) > 0)
                              <span class="product-category text-sm"><a
                                  href="{{ route('front.user.shop', ['category' => $top_selling[$k + 1]->item->itemContents[0]->category->slug, getParam()]) }}">{{ @$top_selling[$k + 1]->item->itemContents[0]->category->name }}</a></span>
                              <h4 class="product-title lc-1 fw-bold">
                                <a
                                  href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k + 1]->item->itemContents[0]->slug]) }}">{{ $top_selling[$k + 1]->item->itemContents[0]->title }}</a>({{ $top_selling[$k + 1]->quantity }})
                              </h4>
                            @endif

                            @if ($shop_settings->item_rating_system == 1)
                              <div class="d-flex align-items-center">
                                <div class="product-ratings rate text-xsm">
                                  <div class="rating" style="width:{{ $top_selling[$k + 1]->item->rating * 20 }}%">
                                  </div>
                                </div>
                                <span class="ratings-total">({{ reviewCount($top_selling[$k + 1]->item->id) }})</span>
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

                            <div class="product-price mt-1 mb-10">
                              @if ($flash_status == true)
                                <span class="new-price">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                </span>
                                <span class="old-price line_through">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 1]->item->current_price)) }}
                                </span>
                              @else
                                <span class="new-price">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 1]->item->current_price)) }}
                                </span>
                                <span class="old-price line_through">
                                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 1]->item->previous_price)) }}
                                </span>
                              @endif
                            </div>

                            <div class="btn-icon-group btn-inline btn-icon-group-sm">

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
                                title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i>
                              </a>

                              <a class="btn btn-icon radius-0" data-bs-toggle="tooltip"
                                onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $top_selling[$k + 1]->item->id, getParam()]) }}')"
                                data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
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

                      @if (isset($top_selling[$k + 2]))
                        @if (!is_null($top_selling[$k + 2]->item))
                          <div class="product-default product-inline product-inline-3 mt-20">
                            <figure class="product-img">
                              <a href="#" class="lazy-container ratio ratio-1-1">
                                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                  data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_selling[$k + 2]->item->thumbnail) }}"
                                  alt="Product">
                              </a>
                            </figure>
                            <div class="product-details">
                              @if (count($top_selling[$k + 2]->item->itemContents) > 0)
                                <span class="product-category text-sm"><a
                                    href="{{ route('front.user.shop', ['category' => $top_selling[$k + 2]->item->itemContents[0]->category->slug, getParam()]) }}">{{ @$top_selling[$k + 2]->item->itemContents[0]->category->name }}</a></span>
                                <h4 class="product-title lc-1 fw-bold">
                                  <a
                                    href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k + 2]->item->itemContents[0]->slug]) }}">{{ $top_selling[$k + 2]->item->itemContents[0]->title }}</a>({{ $top_selling[$k + 2]->quantity }})
                                </h4>
                              @endif

                              @if ($shop_settings->item_rating_system == 1)
                                <div class="d-flex align-items-center">
                                  <div class="product-ratings rate text-xsm">
                                    <div class="rating" style="width:{{ $top_selling[$k + 2]->item->rating * 20 }}%">
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

                              <div class="product-price mt-1 mb-10">
                                @if ($flash_status == true)
                                  <span class="new-price">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                  </span>
                                  <span class="old-price line_through">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 2]->item->current_price)) }}
                                  </span>
                                @else
                                  <span class="new-price">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 2]->item->current_price)) }}
                                  </span>
                                  <span class="old-price line_through">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 2]->item->previous_price)) }}
                                  </span>
                                @endif
                              </div>

                              <div class="btn-icon-group btn-inline btn-icon-group-sm">

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
                                  data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
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
                      @endif

                      @if (isset($top_selling[$k + 3]))
                        @if (!is_null($top_selling[$k + 3]->item))
                          <div class="product-default product-inline product-inline-3 mt-20">
                            <figure class="product-img">
                              <a href="#" class="lazy-container ratio ratio-1-1">
                                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                                  data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $top_selling[$k + 3]->item->thumbnail) }}"
                                  alt="Product">
                              </a>
                            </figure>
                            <div class="product-details">
                              @if (count($top_selling[$k + 3]->item->itemContents) > 0)
                                <span class="product-category text-sm"><a
                                    href="{{ route('front.user.shop', ['category' => $top_selling[$k + 3]->item->itemContents[0]->category->slug, getParam()]) }}">{{ @$top_selling[$k + 3]->item->itemContents[0]->category->name }}</a></span>
                                <h4 class="product-title lc-1 fw-bold">
                                  <a
                                    href="{{ route('front.user.productDetails', [getParam(), 'slug' => $top_selling[$k + 3]->item->itemContents[0]->slug]) }}">{{ $top_selling[$k + 3]->item->itemContents[0]->title }}</a>({{ $top_selling[$k + 3]->quantity }})
                                </h4>
                              @endif

                              @if ($shop_settings->item_rating_system == 1)
                                <div class="d-flex align-items-center">
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

                              <div class="product-price mt-1 mb-10">
                                @if ($flash_status == true)
                                  <span class="new-price">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                                  </span>
                                  <span class="old-price line_through">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 3]->item->current_price)) }}
                                  </span>
                                @else
                                  <span class="new-price">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 3]->item->current_price)) }}
                                  </span>
                                  <span class="old-price line_through">
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($top_selling[$k + 3]->item->previous_price)) }}
                                  </span>
                                @endif
                              </div>

                              <div class="btn-icon-group btn-inline btn-icon-group-sm">

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
                                  data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
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
  <div class="col-xl-4 col-lg-6 lazy" >
    <div class="section-title title-inline title-bottom-line mb-10">
      <h2 class="title title-sm mb-0">{{ $userSec->top_selling_product_section_title ?? __('Top Selling') }}
      </h2>
      <div class="slider-arrow-inline product-list-slider-arrows" id="product-list-slider-skeleton-arrows">
      </div>
    </div>
    <div class="product-list mb-20">

      <div class="product-list-slider" id="product-list-slider-skeleton">
        <div>
          <div class="product-default product-inline product-inline-3 mt-20">
            <figure class="product-img radius-md skeleton skeleton-img"></figure>
            <div class="product-details">
              <div class="skeleton skeleton-category"></div> 
              <h4 class="skeleton skeleton-title"></h4>
              <div class="skeleton skeleton-ratings"></div>
              <div class="product-price mt-1 mb-10">
                <span class="new-price skeleton skeleton-price"></span>
                <span class="old-price line_through skeleton skeleton-price"></span>
              </div>
              <div class="d-flex">
                <span class="skeleton skeleton-btn-icon"></span>
                <span class="skeleton skeleton-btn-icon"></span>
                <span class="skeleton skeleton-btn-icon"></span>
                <span class="skeleton skeleton-btn-icon"></span>
              </div>
            </div>
          </div>
          <div class="product-default product-inline product-inline-3 mt-20">
            <figure class="product-img radius-md skeleton skeleton-img"></figure>
            <div class="product-details">
              <div class="skeleton skeleton-category"></div>
              <h4 class="skeleton skeleton-title"></h4>
              <div class="skeleton skeleton-ratings"></div>
              <div class="product-price mt-1 mb-10">
                <span class="new-price skeleton skeleton-price"></span>
                <span class="old-price line_through skeleton skeleton-price"></span>
              </div>
              <div class="d-flex">
                <span class="skeleton skeleton-btn-icon"></span>
                <span class="skeleton skeleton-btn-icon"></span>
                <span class="skeleton skeleton-btn-icon"></span>
                <span class="skeleton skeleton-btn-icon"></span>
              </div>
            </div>
          </div>
          <div class="product-default product-inline product-inline-3 mt-20">
            <figure class="product-img radius-md skeleton skeleton-img"></figure>
            <div class="product-details">
              <div class="skeleton skeleton-category"></div>
              <h4 class="skeleton skeleton-title"></h4>
              <div class="skeleton skeleton-ratings"></div>
              <div class="product-price mt-1 mb-10">
                <span class="new-price skeleton skeleton-price"></span>
                <span class="old-price line_through skeleton skeleton-price"></span>
              </div>
              <div class="d-flex">
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
  </div>
