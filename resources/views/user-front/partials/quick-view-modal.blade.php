@if (!empty($product))
  @php
    $user_currency = user_currency(Session::get('user_curr'));
  @endphp
  <div class="col-lg-6">
    @if ($product->item->sliders)
      <input type="hidden" id="item_id" value="{{ $item_id }}">
      <div class="product-single-gallery">
        <div class="slider-thumbnails">
          @foreach ($product->item->sliders as $slide)
            <div class="thumbnail-img radius-sm lazy-container ratio ratio-1-1">
              <img class="lazyload" src="{{ asset('assets/front/img/user/items/slider-images/' . $slide->image) }}"
                alt="product image" />
            </div>
          @endforeach

        </div>
        <div class="product-single-slider">
          @foreach ($product->item->sliders as $slide)
            <figure class="radius-lg lazy-container ratio ratio-1-1">
              <a src="{{ asset('assets/front/img/user/items/slider-images/' . $slide->image) }}">
                <img class="lazyload"
                src="{{ asset('assets/front/images/placeholder.png') }}"
                 data-src="{{ asset('assets/front/img/user/items/slider-images/' . $slide->image) }}"
                  alt="product image" />
              </a>
            </figure>
          @endforeach
        </div>
      </div>
    @endif
  </div>
  <div class="col-lg-6">
    <div class="product-single-details">
      @php
        $item_label = DB::table('labels')->where('id', $product->label_id)->first();
        $label = $item_label->name ?? null;
        $color = $item_label->color ?? null;
      @endphp

      <h2 class="product-title lc-2">{{ $product->title }} <span class="label label-2"
          style="background-color: #{{ $color }}">{{ $label }}</span></h2>
      <ul>
        @php
          $avgreview = \App\Models\User\UserItemReview::where('item_id', $item_id)->avg('review');
        @endphp

        <div class="d-flex align-items-center gap-10 mb-2">
          <div class="rating-wrapper d-flex gap-2 align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="23" viewBox="0 0 24 23" fill="none">
              <path
                d="M12 0.5L14.6942 8.7918H23.4127L16.3593 13.9164L19.0534 22.2082L12 17.0836L4.94658 22.2082L7.64074 13.9164L0.587322 8.7918H9.30583L12 0.5Z"
                fill="#EEAE0B"></path>
            </svg>
            <span>
              {{ number_format($avgreview, 1) }}
              ({{ reviewCount($product->item->id) }}
              @php
                $review = $keywords['Review'] ?? __('Review');
                $reviews = $keywords['Reviews'] ?? __('Reviews');
              @endphp
              {{ reviewCount($product->item->id) == 1 ? $review : $reviews }})
            </span>
          </div>
          <div class="stock-status">
            @if ($product->item->type == 'physical')
              @php
                $varitaion_stock = VariationStock($product->item->id);
              @endphp
              @if ($varitaion_stock['has_variation'] == 'yes')
                @if ($varitaion_stock['stock'] == 'yes')
                  <span class="badge bg-success"><i class="fa fa-check"></i>
                    {{ $keywords['In Stock'] ?? __('In Stock') }}</span>
                @else
                  <span class="badge bg-danger"><i class="fa fa-times"></i>
                    {{ $keywords['Out of Stock'] ?? __('Out of Stock') }}</span>
                @endif
              @else
                @if ($product->item->stock > 0)
                  <span class="badge bg-success"><i class="fa fa-check"></i>
                    {{ $keywords['In Stock'] ?? __('In Stock') }}</span>
                @else
                  <span class="badge bg-danger"><i class="fa fa-times"></i>
                    {{ $keywords['Out of Stock'] ?? __('Out of Stock') }}</span>
                @endif
              @endif
            @endif
          </div>
        </div>

      </ul>
      @php
        $flash_info = flashAmountStatus($item_id, $product->item->current_price);
        $product_current_price = $flash_info['amount'];
        $flash_status = $flash_info['status'];
      @endphp

      <div class="product-price">
        @if ($flash_status == true)
          <div class="new-price-area d-flex color-primary">
            {{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
            <span class="new-price" id="new-price">
              {{ currency_converter($product_current_price) }}
            </span>
            {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }}
          </div>

          <div class="old-price-area d-flex">
            {{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
            <span class="old-price" id="old-price"
              data-old_price="{{ currency_converter($product->item->current_price) }}">
              {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product->item->current_price)) }}
            </span>
            {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }}
          </div>

          <span class="discountoff">{{ $product->item->flash_amount }}% {{ $keywords['OFF'] ?? __('OFF') }}</span>
        @else
          <div class="new-price-area d-flex color-primary ">
            {{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
            <span class="new-price" id="new-price">{{ currency_converter($product->item->current_price) }}</span>
            {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }}
          </div>

          <div class="old-price-area d-flex">
            @if ($product->item->previous_price > 0)
              {{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
              <span class="old-price" id="old-price"
                data-old_price="{{ currency_converter($product->item->previous_price) }}">
                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product->item->previous_price)) }}
              </span>
              {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }}
            @endif
          </div>

        @endif

      </div>
      <p class="product-text mb-3 lc-3">{{ $product->summary }}
      </p>

      <div class="sku-code mb-10">
        <span class="text-dark fw-semibold">{{ $keywords['SKU'] ?? __('SKU') }}:</span>
        <span class="text-dark">{{ $product->item->sku }}</span>
      </div>

      @if ($flash_status == true)
        <div class="product-countdown mt-3" data-start_date="{{ $product->item->start_date }}"
          data-end_time="{{ $product->item->end_time }}" data-end_date="{{ $product->item->end_date }}"
          data-item_id="{{ $item_id }}">
          <div class="count radius-sm days">
            <span class="count-value_{{ $item_id }}"></span>
            <span class="count-period">{{ $keywords['Days'] ?? __('Days') }} </span>
          </div>
          <div class="count radius-sm hours">
            <span class="count-value_{{ $item_id }}"></span>
            <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
          </div>
          <div class="count radius-sm minutes">
            <span class="count-value_{{ $item_id }}"></span>
            <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
          </div>
          <div class="count radius-sm seconds">
            <span class="count-value_{{ $item_id }}"></span>
            <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
          </div>

          <div data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ $keywords['Flash_sale'] ?? __('Flash sale') }}">
            <div class="details-label-discount-percentage">
              <div class="percentage-text">
                <x-flash-icon></x-flash-icon> <span>{{ $product->flash_amount }}%</span>
              </div>
            </div>
          </div>

        </div>
      @endif

      <div class="product-action">
        @if (count($product_variations) > 0)
          <ul class="product-list-group mb-20" id="variantListUL">
            @foreach ($product_variations as $product_variation)
              @php
                $product_variation_contents = App\Models\User\ProductVariationContent::where([
                    ['product_variation_id', $product_variation->id],
                    ['language_id', $uLang],
                ])->get();
                $variant_content_options = App\Models\User\ProductVariantOption::where([
                    ['product_variation_id', $product_variation->id],
                ])->get();
              @endphp

              @foreach ($product_variation_contents as $product_variation_content)
                @php
                  $variant_content = App\Models\VariantContent::where(
                      'id',
                      $product_variation_content->variation_name,
                  )->first();
                @endphp
                <li class="list-item" data-variant_name="{{ @$variant_content->name }}">
                  <h4 class="list-item-title color-primary mb-1">{{ @$variant_content->name }}:</h4>
                  <ul class="list-group custom-radio variantUL" id="variantUL">
                    @foreach ($variant_content_options as $variant_content_option)
                      @php
                        $variant_option_contents = App\Models\User\ProductVariantOptionContent::where([
                            ['product_variant_option_id', $variant_content_option->id],
                            ['language_id', $uLang],
                        ])->first();

                        $sup_option_content = make_input_name(@$variant_option_contents->option_content->option_name);

                        $id_name = make_input_name(@$variant_option_contents->option_content->option_name);
                        $main_id = $sup_option_content . '_' . $id_name;
                      @endphp
                      <li>
                        <input id="radio_{{ $main_id }}" type="radio"
                          name="{{ make_input_name(@$variant_content->name) }}[]"
                          class="{{ $main_id }} product-variant"
                          value="{{ $variant_option_contents->option_content->option_name }}:{{ currency_converter($variant_content_option->price) }}:{{ $variant_content_option->stock }}:{{ $variant_content_option->id }}:{{ $product_variation->id }}">
                        <label class="" for="radio_{{ $main_id }}">
                          <span
                            class="quick_view_variants_price">{{ $variant_option_contents->option_content->option_name }}
                            (<i
                              class="fas fa-plus"></i>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($variant_content_option->price)) }})
                          </span>
                        </label>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @endforeach
            @endforeach
          </ul>
        @endif
        @if ($shop_settings->catalog_mode != 1)
          <div class="d-flex flex-wrap align-items-center gap-10 mb-20">
            <div class="quantity-input d-flex item_quantity">
              <div class="quantity-down quantity-btn minus" data-item_id="{{ $product->id }}">
                <i class="fal fa-minus"></i>
              </div>
              <input class="quantity_field" type="number" name="cart-amount" value="1" min="1">
              <div class="quantity-up quantity-btn plus" data-item_id="{{ $product->id }}">
                <i class="fal fa-plus"></i>
              </div>
            </div>

            <div class="btn-icon-group btn-inline">
              <a class="btn btn-icon radius-sm"
                onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product->item_id, getParam()]) }}')"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ $keywords['Compare'] ?? __('Compare') }}">
                <i class="fal fa-random"></i>
              </a>
              @php
                $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                $checkWishList = $customer_id ? checkWishList($product->item_id, $customer_id) : false;
              @endphp
              <a href="#"
                class="btn btn-icon radius-sm {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                data-bs-toggle="tooltip" data-bs-placement="top"
                data-url="{{ route('front.user.add.wishlist', ['id' => $product->item_id, getParam()]) }}"
                data-removeUrl="{{ route('front.user.remove.wishlist', ['id' => $product->item_id, getParam()]) }}"
                title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i class="fal fa-heart"></i>
              </a>
            </div>

          </div>

          <input type="hidden" name="final-price" id="final-price" class="form-control final-price">

          <button class="btn btn-md btn-primary radius-md" type="button" aria-label="Add to cart"
            data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}" onclick="addToCartDetails()"> <i
              class="fas fa-cart-plus"></i><span>{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }} </span>
          </button>

          <div class="d-flex align-items-center flex-wrap gap-10 mt-20">
            <span class="text-dark fw-semibold">{{ $keywords['Share Now'] ?? __('Share Now') }} :</span>
            <div class="social-link">
              <a href="https://www.instagram.com/stories/create/?text={{ urlencode('Check out this product: ' . url(route('front.user.productDetails', ['slug' => $product->slug, getParam()]))) }}"
                target="_blank" title="Share on Instagram Story">
                <i class="fab fa-instagram"></i>
              </a>

              <a href="//x.com/intent/tweet?text={{ urlencode('Check this out! ' . route('front.user.productDetails', ['slug' => $product->slug, getParam()])) }}"
                target="_blank" title="Share on Twitter">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="//www.facebook.com/sharer/sharer.php?u={{ route('front.user.productDetails', ['slug' => $product->slug, getParam()]) }}&src=sdkpreparse"
                target="_blank" title="Share on Facebook">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="https://wa.me/?text={{ urlencode('Check this out: ' . url(route('front.user.productDetails', ['slug' => $product->slug, getParam()]))) }}"
                target="_blank" title="Share on WhatsApp">
                <i class="fab fa-whatsapp"></i>
              </a>

            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endif
