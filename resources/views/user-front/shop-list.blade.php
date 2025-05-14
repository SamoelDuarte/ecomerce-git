@if (count($items) > 0)
  @foreach ($items as $item)
    @php
      $flash_info = flashAmountStatus($item->item_id, $item->current_price);
      $product_current_price = $flash_info['amount'];
      $flash_status = $flash_info['status'];
    @endphp
    <div class="col-12">
      <div class="product-default product-list-card product-column radius-md mb-30">
        @if ($flash_status == true)
          <span class="label-discount-percentage"><x-flash-icon></x-flash-icon>{{ $item->flash_amount }}%</span>
        @endif

        <figure class="product-img">
          <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->product_slug]) }}"
            class="lazy-container radius-md ratio ratio-1-1">
            <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}" alt="Product">
            <img class="lazyload hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}" alt="Product">
          </a>
        </figure>
        <div class="product-details">
          <a href="javascript:void(0)" class="category" data-slug="{{ $item->category_slug }}">
            <span class="product-category text-sm">{{ $item->category_name }}</span>
          </a>

          <h4 class="product-title lc-2">
            <a
              href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->product_slug]) }}">{{ $item->title }}</a>
          </h4>
          <p class="text">{!! nl2br($item->summary) !!}</p>
        </div>

        <div class="product-action">
          <div class="product-price">
            @if ($flash_status == true)
              <span class="new-price">
                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
              </span>&nbsp;
              <span class="old-price line_through">
                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
              </span>
              &nbsp;
              <span class="old-price">
                {{ $item->flash_amount }}% {{ $keywords['OFF'] ?? __('OFF') }}</span>
            @else
              <span class="new-price">
                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
              </span>
              @if ($item->previous_price > 0)
                <span class="old-price line_through ms-1">
                  {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->previous_price)) }}
                </span>
              @endif
            @endif
          </div>

          @if ($shop_settings->item_rating_system == 1)
            <div class="d-flex justify-content-center align-items-center">
              <div class="product-ratings mb-10 rate text-xsm">
                <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
              </div>
              <span class="ratings-total mb-10">({{ reviewCount($item->item_id) }})</span>
            </div>
          @endif

          <div class="btn-icon-group">
            @if ($shop_settings->catalog_mode != 1)
              <a href="#" class="btn btn-md btn-primary radius-sm mb-20 cart-link"
                data-title="{{ $item->title }}" data-current_price="{{ currency_converter($product_current_price) }}"
                data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                data-totalVari="{{ check_variation($item->item_id) }}"
                data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}">{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}</a>
            @endif
            <div>
              <button type="button" class="btn btn-icon radius-sm quick-view-link" data-bs-toggle="tooltip"
                data-bs-placement="left" title="{{ $keywords['Quick View'] ?? __('Quick View') }}"
                data-bs-toggle="modal" data-bs-target="#quickViewModal" data-item_id="{{ $item->item_id }}"
                data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->product_slug, getParam()]) }}">
                <i class="fal fa-eye"></i>
              </button>
              <a href="javascript:void(0)"
                onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                class="btn btn-icon radius-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i></a>

              @php
                $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
              @endphp
              <a href="javascript:void(0)"
                class="btn btn-icon radius-sm {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                data-href="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                data-removeUrl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                data-bs-toggle="tooltip" data-bs-placement="right"
                title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i class="fal fa-heart"></i></a>
            </div>
          </div>
        </div>
      </div> <!-- product-default -->
    </div>
  @endforeach
  <div class="col-12">
    <div class="pagination mb-30 justify-content-center">
      @if (count($items) > 0)
        {{ $items->appends([
                'type' => request()->input('type'),
                'category' => request()->input('category'),
                'min' => request()->input('min'),
                'max' => request()->input('max'),
                'keyword' => request()->input('keyword'),
                'sort' => request()->input('sort'),
                'variations' => request()->input('variations'),
            ])->links() }}
      @endif
    </div>
  </div>
@else
  <div class="card">
    <div class="card-body cart">
      <div class="col-sm-12 empty-cart-cls text-center">
        <i class="far fa-shopping-bag empty-icon"></i>
        <h3><strong>{{ $keywords['No Product Found'] ?? __('No Product Found') }}</strong></h3>
      </div>
    </div>
  </div>
@endif
