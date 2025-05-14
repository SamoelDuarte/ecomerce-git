@extends('user-front.layout')
@php
  $user_currency = user_currency(Session::get('user_curr'));
@endphp
@section('page-title', $product->title ?? ($keywords['Product_Details'] ?? __('Product Details')))
@section('og-meta')
  <!--- For Social Media Share Thumbnail --->
  <meta property="og:title" content="{{ $product->title . ' | ' . $user->username }}">
  <meta property="og:description" content="{{ $product->summary }}">
  <meta property="og:image" content="{{ asset('assets/front/img/user/items/thumbnail/' . $product->item->thumbnail) }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
  <meta name="twitter:card" content="summary_large_image">
@endsection
@section('content')
  <div class="page-title-area header-next">
    @if (@$userBe->breadcrumb)
      <img class=" bg-img" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/front/img/user/' . $userBe->breadcrumb) }}"
        alt="Banner">
    @endif
    <div class="container">
      <div class="content text-start">
        <h2>
          {{ truncateString($product->title, 40) ?? ($keywords['Product_Details'] ?? __('Product Details')) }}
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb justify-content-start">
            <li class="breadcrumb-item"><a
                href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">
              {{ $keywords['Product_Details'] ?? __('Product Details') }} </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <!-- Shop Start -->
  <div class="product-single pt-100 pb-70 overflow-hidden">
    <div class="container">
      <div class="product-single-default">
        <div class="row ">
          <div class="col-lg-6">
            @if ($product->item->sliders)
              <input type="hidden" id="details_item_id" value="{{ $product->item->id }}">
              <div class="product-single-gallery">
                <div class="slider-thumbnails2">

                  @foreach ($product->item->sliders as $slide)
                    <div class="thumbnail-img radius-md lazy-container ratio ratio-1-1">
                      <img class="lazyload"
                      src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/items/slider-images/' . $slide->image) }}"
                        alt="product image" />
                    </div>
                  @endforeach

                </div>
                <div class="product-single-slider2">
                  @foreach ($product->item->sliders as $slide)
                    <div class="product-single-single-item">
                      <figure class="radius-lg lazy-container ratio ratio-1-1">
                        <a src="{{ asset('assets/front/img/user/items/slider-images/' . $slide->image) }}">
                          <img class="lazyload"
                          src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/items/slider-images/' . $slide->image) }}"
                            alt="product image" />
                        </a>
                      </figure>
                    </div>
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

              <h2 class="product-title">{{ $product->title }} <span class="label label-2"
                  style="background-color: #{{ $color }}">{{ $label }}</span> </h2>
              <ul>
                @php
                  $avgreview = \App\Models\User\UserItemReview::where('item_id', $product->item->id)->avg('review');
                @endphp

                <div class="d-flex align-items-center gap-10 mb-2">
                  @if ($shop_settings->item_rating_system == 1)
                    <div class="rating-wrapper d-flex gap-2 align-items-center">
                      <div class="rate-icon mt-n3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="23" viewBox="0 0 24 23"
                          fill="none">
                          <path
                            d="M12 0.5L14.6942 8.7918H23.4127L16.3593 13.9164L19.0534 22.2082L12 17.0836L4.94658 22.2082L7.64074 13.9164L0.587322 8.7918H9.30583L12 0.5Z"
                            fill="#EEAE0B"></path>
                        </svg>
                      </div>

                      <span>
                        {{ number_format($avgreview, 1) }}
                        ({{ reviewCount($product->item->id) }}
                        {{ reviewCount($product->item->id) == 1 ? $keywords['Review'] ?? __('Review') : $keywords['Reviews'] ?? __('Reviews') }})
                      </span>

                    </div>
                  @endif
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
                $flash_info = flashAmountStatus($product->item_id, $product->item->current_price);
                $product_current_price = $flash_info['amount'];
                $flash_status = $flash_info['status'];
              @endphp

              <div class="product-price">
                @if ($flash_status == true)
                  <div class="new-price-area d-flex color-primary">
                    <span
                      class="new-price-currency">{{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}</span>
                    <span class="new-price" id="details_new-price"
                      data-base_price="{{ currency_converter($product_current_price) }}">{{ currency_converter($product_current_price) }}</span>
                    {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }}
                  </div>

                  <div class="old-price-area d-flex">
                    {{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
                    <span class="old-price" id="details_old-price"
                      data-old_price="{{ currency_converter($product->item->current_price) }}">
                      {{ currency_converter($product->item->current_price) }}</span>
                    {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }}
                  </div>

                  <span class="discountoff">{{ $product->item->flash_amount }}%
                    {{ $keywords['OFF'] ?? __('OFF') }}</span>
                @else
                  <div class="new-price-area d-flex color-primary ">
                    {{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
                    <span class="new-price" id="details_new-price"
                      data-base_price="{{ currency_converter($product->item->current_price) }}">{{ currency_converter($product->item->current_price) }}</span>
                    {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }}
                  </div>

                  <div class="old-price-area d-flex">
                    @if ($product->item->previous_price > 0)
                      {{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
                      <span class="old-price" id="details_old-price"
                        data-old_price="{{ currency_converter($product->item->previous_price) }}">
                        {{ currency_converter($product->item->previous_price) }}</span>
                      {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }}
                    @endif
                  </div>
                @endif
              </div>
              <p class="product-text mb-3 lc-3">
                {{ $product->summary }}
              </p>
              @if ($product->item->sku)
                <div class="sku-code mb-10">
                  <span class="text-dark fw-semibold">{{ $keywords['SKU'] ?? __('SKU') }} :</span>
                  <span class="text-dark">{{ $product->item->sku }}</span>
                </div>
              @endif


              @if ($flash_status == true)
                <div class="product-countdown mt-3" data-end_time="{{ $product->item->end_time }}"
                  data-end_date="{{ $product->item->end_date }}" data-item_id="{{ $product->id }}">
                  <div id="" class="count radius-sm days">
                    <span class="count-value_{{ $product->id }}"></span>
                    <span class="count-period">{{ $keywords['Days'] ?? __('Days') }} </span>
                  </div>
                  <div id="" class="count radius-sm hours">
                    <span class="count-value_{{ $product->id }}"></span>
                    <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
                  </div>
                  <div id="" class="count radius-sm minutes">
                    <span class="count-value_{{ $product->id }}"></span>
                    <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
                  </div>
                  <div id="" class="count radius-sm seconds">
                    <span class="count-value_{{ $product->id }}"></span>
                    <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
                  </div>
                  <div data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ $keywords['Flash_sale'] ?? __('Flash sale') }}">
                    <div class="details-label-discount-percentage">
                      <div class="percentage-text">
                        <x-flash-icon></x-flash-icon> <span>{{ $product->flash_amount }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
              <div class="product-action">
                @if (count($product_variations) > 0)
                  <ul class="product-list-group mb-20" id="variantListULDetails">
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
                          <ul class="custom-radio variantUL" id="variantUL">
                            @foreach ($variant_content_options as $variant_content_option)
                              @php
                                $variant_option_contents = App\Models\User\ProductVariantOptionContent::where([
                                    ['product_variant_option_id', $variant_content_option->id],
                                    ['language_id', $uLang],
                                ])->first();

                                $sup_option_content = make_input_name(
                                    @$variant_option_contents->option_content->option_name,
                                );

                                $id_name = make_input_name(@$variant_option_contents->option_content->option_name);
                                $main_id = 'detail_' . $sup_option_content . '_' . $id_name;
                              @endphp
                              <li>
                                <input id="radio_{{ $main_id }}" type="radio"
                                  name="{{ make_input_name(@$variant_content->name) }}[]"
                                  class="{{ $main_id }} product-variant input-radio"
                                  value="{{ $variant_option_contents->option_content->option_name }}:{{ currency_converter($variant_content_option->price) }}:{{ $variant_content_option->stock }}:{{ $variant_content_option->id }}:{{ $product_variation->id }}">
                                <label class="form-radio-label" for="radio_{{ $main_id }}"><span
                                    class="details_view_variants_price">{{ $variant_option_contents->option_content->option_name }}
                                    (<i
                                      class="fas fa-plus"></i>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($variant_content_option->price)) }})
                                  </span></label>
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
                    <div class="quantity-input d-flex item_quantity_details">
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
                        title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i>
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
                        title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                          class="fal fa-heart"></i>
                      </a>
                    </div>

                  </div>

                  <input type="hidden" name="final-price" id="details_final-price" class="form-control final-price">

                  <button class="btn btn-sm btn-primary radius-md" type="button" aria-label="Add to cart"
                    data-bs-toggle="tooltip" data-placement="top"
                    title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}" onclick="addToCartDetails2()">
                    <i class="fas fa-cart-plus"></i><span>{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }} </span>
                  </button>

                  <div class="d-flex align-items-center flex-wrap gap-10 mt-20">
                    <span class="text-dark fw-semibold">{{ $keywords['Share Now'] ?? __('Share Now') }} :</span>
                    <div class="social-link ">
                      <a href="https://www.instagram.com/stories/create/?text={{ urlencode('Check out this product: ' . url()->current()) }}"
                        target="_blank" title="Share on Instagram">
                        <i class="fab fa-instagram"></i>
                      </a>
                      <a href="//x.com/intent/tweet?text={{ urlencode('Check this out! ' . url()->current()) }}"
                        target="_blank" title="Share on Twitter">
                        <i class="fab fa-twitter"></i>
                      </a>
                      <a href="//www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&src=sdkpreparse"
                        target="_blank" title="Share on Facebook">
                        <i class="fab fa-facebook-f"></i>
                      </a>
                      <a href="https://wa.me/?text={{ urlencode('Check this out: ' . url()->current()) }}"
                        target="_blank" title="Share on WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                      </a>
                    </div>
                  </div>
                @endif

              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="product-single-tab pt-70">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc"
              type="button">{{ $keywords['Description'] ?? __('Description') }} </button>
          </li>

          @if ($shop_settings->item_rating_system == 1)
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews"
                type="button">{{ $keywords['Reviews'] ?? __('Reviews') }} </button>
            </li>
          @endif
          @if ($shop_settings->disqus_comment_system == 1)
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Disqus"
                type="button">{{ $keywords['Comments'] ?? __('Comments') }} </button>
            </li>
          @endif
        </ul>
        <div class="tab-content radius-lg">
          <div class="tab-pane fade active show" id="desc">
            <div class="tab-description">
              <div class="row align-items-center">
                <div class="col-md-12">
                  <div class="content mb-30 tinymce-content">
                    {!! replaceBaseUrl($product->description ?? null) !!}
                  </div>
                </div>
              </div>

            </div>
          </div>
          @if ($shop_settings->item_rating_system == 1)
            <div class="tab-pane fade" id="reviews">
              <div class="tav-review mb-30">
                <h4 class="mb-15">{{ $keywords['Customer Reviews'] ?? __('Customer Reviews') }}</h4>
                <ul class="comment-list">
                  @foreach ($reviews as $review)
                    <ul class="comment-list">
                      <li class="comment mb-20">
                        <div class="comment-body">
                          <div class="author">
                            <img class="radius-sm lazyload"
                            src="{{ asset('assets/front/images/placeholder.png') }}"
                              data-src="{{ is_null(@$review->customer->image) ? asset('assets/user-front/images/avatar-1.jpg') : asset('assets/user-front/images/users/' . @$review->customer->image) }}"
                              alt="">
                          </div>
                          <div class="content">
                            <h6>
                              {{ !empty(convertUtf8(@$review->customer)) ? convertUtf8(@$review->customer->username) : '' }}
                              <span>( {{ @$review->created_at->format('F j, Y') }} )</span>
                            </h6>
                            <div class="rate text-xsm">
                              <div class="rating" style="width:{{ @$review->review * 20 }}%"></div>
                            </div>
                            <p>{{ nl2br(convertUtf8(@$review->comment)) }}
                            </p>
                          </div>
                        </div>
                      </li>


                    </ul>
                  @endforeach
                </ul>

                @if (Auth::guard('customer')->user())
                  @if (App\Models\User\UserOrderItem::where('customer_id', Auth::guard('customer')->user()->id)->where('item_id', $product->item->id)->exists())
                    <div class="comment-form">
                      @error('error')
                        <p class="text-danger my-2">{{ Session::get('error') }}</p>
                      @enderror
                      <h4 class="mb-10">{{ $keywords['Add Your review'] ?? __('Add Your review') }}</h4>
                      <form action="{{ route('item.review.submit', getParam()) }}" method="POST">
                        @csrf
                        <input type="hidden" value="" id="reviewValue" name="review">
                        <input type="hidden" value="{{ $product->item->id }}" name="item_id">

                        <div class="input-box mb-3">
                          <div class="review-content mt-3">
                            <ul class="review-value review-1">
                              <li><a class="cursor-pointer" data-href="1"><i class="far fa-star"></i></a></li>
                            </ul>
                            <ul class="review-value review-2">
                              <li><a class="cursor-pointer" data-href="2"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="2"><i class="far fa-star"></i></a></li>
                            </ul>
                            <ul class="review-value review-3">
                              <li><a class="cursor-pointer" data-href="3"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="3"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="3"><i class="far fa-star"></i></a></li>
                            </ul>
                            <ul class="review-value review-4">
                              <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                            </ul>
                            <ul class="review-value review-5">
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                              <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                            </ul>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-12">
                            <div class="form-group mb-30">
                              <textarea class="form-control" name="comment" id="comment" cols="30" rows="9"
                                placeholder="{{ $keywords['Comment'] ?? __('Comment') }} *"></textarea>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <button type="submit"
                            class="btn btn-lg btn-primary radius-md">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                        </div>
                      </form>
                    </div>
                  @endif
                @else
                  <div class="review-login">
                    <a class="boxed-btn d-inline-block mr-2"
                      href="{{ route('customer.login', getParam()) }}">{{ $keywords['Login'] ?? __('Login') }}</a>
                    {{ $keywords['to leave a rating'] ?? __('to leave a rating') }}
                  </div>
                @endif
              </div>
            </div>
          @endif
          @if ($shop_settings->disqus_comment_system == 1)
            <div class="tab-pane fade" id="Disqus">
              <div id="disqus_thread"></div>
            </div>
          @endif
        </div>
      </div>
      <!-- Flash Sale Start -->
      <div class="pt-100">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-inline mb-10">
              <h2 class="title fs-1 mb-20">{{ $keywords['Related Products'] ?? __('Related Products') }} </h2>
              @if (count($related_product) > 0)
                <a href="{{ route('front.user.shop', [getParam(), 'category' => $category_slug]) }}"
                  class="btn btn-md btn-primary radius-sm mb-20">{{ $keywords['More_Items'] ?? __('More Items') }} </a>
              @endif
            </div>
          </div>
          <div class="col-12">
            @if (count($related_product) > 0)
              <div class="product-slider mb-30 pb-10" id="product-details-slider"
                data-slick='{"dots": true, "slidesToShow": 4}'>
                @foreach ($related_product as $item)
                  <div class="product-default product-center radius-xl">
                    <figure class="product-img">
                      <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                        class="lazy-container ratio ratio-1-1">
                        <img class="lazyload default-img"
                    src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->item->thumbnail) }}"
                          alt="Product">
                        <img class="lazyload hover-img"
                  src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->item->thumbnail) }}"
                          alt="Product">
                      </a>
                    </figure>
                    <div class="product-details">
                      <a href="{{ route('front.user.shop', ['category' => $item->category->slug, getParam()]) }}">
                        <span class="product-category text-sm">{{ $item->category->name }}</span>
                      </a>
                      <h3 class="product-title">
                        <a
                          href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ truncateString($item->title, 30) }}</a>
                      </h3>

                      @if ($shop_settings->item_rating_system == 1)
                        <div class="d-flex justify-content-center align-items-center">
                          <div class="product-ratings rate text-xsm">
                            <div class="rating" style="width:{{ $item->item->rating * 20 }}%"></div>
                          </div>
                          <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                        </div>
                      @endif

                      @php
                        $flash_info = flashAmountStatus($item->item->id, $item->item->current_price);
                        $product_current_price = $flash_info['amount'];
                        $flash_status = $flash_info['status'];
                      @endphp

                      <div class="product-price">
                        @if ($flash_status == true)
                          <span class="new-price">
                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                          </span>

                          <span class="old-price ms-1 line_through">
                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->item->current_price)) }}
                          </span>

                          <span class="old-price">{{ $item->item->flash_amount }}%
                            {{ $keywords['OFF'] ?? __('OFF') }}</span>
                        @else
                          <span class="new-price">
                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->item->current_price)) }}
                          </span>
                          <span class="old-price line_through">
                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->item->previous_price)) }}
                          </span>
                        @endif
                      </div>
                    </div>

                    <div class="btn-icon-group btn-inline">

                      @if ($shop_settings->catalog_mode != 1)
                        <a class=" btn btn-icon radius-sm cart-link cursor-pointer" data-title="{{ $item->title }}"
                          data-current_price="{{ currency_converter($product_current_price) }}"
                          data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                          data-totalVari="{{ check_variation($item->item_id) }}"
                          data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                          data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                          data-bs-toggle="tooltip" data-bs-placement="top"
                          title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                            class="far fa-shopping-cart "></i></a>
                      @endif

                      @php
                        $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                        $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                      @endphp
                      <a href="#"
                        class="btn btn-icon radius-sm {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                        data-href="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                        data-removeUrl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                        title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                          class="fal fa-heart"></i>
                      </a>

                      <a class="btn btn-icon radius-sm quick-view-link" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                        data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                        title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i>
                      </a>

                      <a onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                        class="btn btn-icon radius-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i>
                      </a>


                    </div>

                  </div> <!-- product-default -->
                @endforeach
              </div>
            @else
              <h3 class="text-center">{{ $keywords['No related product found'] ?? __('No related product found') }}
              </h3>
            @endif
          </div>
        </div>
      </div>
      <!-- Flash Sale End -->
    </div>
  </div>
  <!-- Shop End -->
  {{-- Variation Modal Starts --}}
  @include('user-front.partials.variation-modal')
  {{-- Variation Modal Ends --}}

  <!-- Quick View Modal Start -->
  <div class="modal custom-modal quick-view-modal fade" id="quickViewModal" tabindex="-1"
    aria-labelledby="quickViewModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content radius-sm">
        <button type="button" class="close_modal_btn" data-bs-dismiss="modal" aria-label="Close"><i
            class="fal fa-times"></i></button>
        <div class="modal-body">
          <div class="product-single-default">
            <div class="row" id="quickViewModalContent">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Quick View Modal End -->
@endsection
@if ($userBs->is_disqus == 1 && in_array('Disqus', $packagePermissions) && $shop_settings->disqus_comment_system == 1)
  @section('scripts')
    <script>
      "use strict";
      (function() {
        var d = document,
          s = d.createElement('script');
        s.src = 'https://{{ $userBs->disqus_shortname }}.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
      })();
    </script>
  @endsection
@endif
