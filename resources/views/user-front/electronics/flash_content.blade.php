 <section class="product-with-border pb-100 mt-20 lazy" >
   <div class="container">
     <div class="product-inner position-relative radius-xl">
       <div class="row">
         <div class="col-12">
           <div class="section-title title-inline title-floating bg-white">
             <h2 class="title mb-0">
               {{ $userSec->flash_section_title ?? ($keywords['Flash Section'] ?? __('Flash Section')) }} </h2>
             <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
               class="btn btn-md btn-primary rounded-pill icon-end shadow">{{ $keywords['Explore More'] ?? __('Explore More') }}
               <span class="icon"><i class="fal fa-arrow-right"></i></span>
             </a>
           </div>
         </div>
         <div class="col-12">

           <div class="mb-30">
             <div class="row">
               <div class="col-lg-3">
                 <div class="product-default product-default-2 radius-md mb-30">
                   <figure class="product-img radius-md skeleton skeleton-img"></figure>

                   <div class="product-details">
                     <div class="btn-icon-group btn-inline">
                       <div class="product-countdown">
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                       </div>
                     </div>
                     <div class="skeleton skeleton-category"></div>
                     <h3 class="product-title skeleton skeleton-title"></h3>
                     <div class="skeleton skeleton-ratings"></div>

                     <div class="product-price mb-0">
                       <span class="new-price skeleton skeleton-price"></span>
                       <span class="old-price line_through skeleton skeleton-price"></span>
                     </div>
                   </div>
                 </div>
               </div>
               <div class="col-lg-3">
                 <div class="product-default product-default-2 radius-md mb-30">
                   <figure class="product-img radius-md skeleton skeleton-img"></figure>

                   <div class="product-details">
                     <div class="btn-icon-group btn-inline">
                       <div class="product-countdown">
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                       </div>
                     </div>
                     <div class="skeleton skeleton-category"></div>
                     <h3 class="product-title skeleton skeleton-title"></h3>
                     <div class="skeleton skeleton-ratings"></div>

                     <div class="product-price mb-0">
                       <span class="new-price skeleton skeleton-price"></span>
                       <span class="old-price line_through skeleton skeleton-price"></span>
                     </div>
                   </div>
                 </div>
               </div>
               <div class="col-lg-3">
                 <div class="product-default product-default-2 radius-md mb-30">
                   <figure class="product-img radius-md skeleton skeleton-img"></figure>

                   <div class="product-details">
                     <div class="btn-icon-group btn-inline">
                       <div class="product-countdown">
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                       </div>
                     </div>
                     <div class="skeleton skeleton-category"></div>
                     <h3 class="product-title skeleton skeleton-title"></h3>
                     <div class="skeleton skeleton-ratings"></div>

                     <div class="product-price mb-0">
                       <span class="new-price skeleton skeleton-price"></span>
                       <span class="old-price line_through skeleton skeleton-price"></span>
                     </div>
                   </div>
                 </div>
               </div>
               <div class="col-lg-3">
                 <div class="product-default product-default-2 radius-md mb-30">
                   <figure class="product-img radius-md skeleton skeleton-img"></figure>

                   <div class="product-details">
                     <div class="btn-icon-group btn-inline">
                       <div class="product-countdown">
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                         <span class="count-period skeleton skeleton-btn-icon"></span>
                       </div>
                     </div>
                     <div class="skeleton skeleton-category"></div>
                     <h3 class="product-title skeleton skeleton-title"></h3>
                     <div class="skeleton skeleton-ratings"></div>

                     <div class="product-price mb-0">
                       <span class="new-price skeleton skeleton-price"></span>
                       <span class="old-price line_through skeleton skeleton-price"></span>
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


 <!-- Best Sale Product Start -->
 <section class="product-with-border pb-100 mt-20 actual-content">
   <div class="container">
     <div class="product-inner position-relative radius-xl">
       <div class="row">
         <div class="col-12">
           <div class="section-title title-inline title-floating bg-white">
             <h2 class="title mb-0">
               {{ $userSec->flash_section_title ?? ($keywords['Flash Section'] ?? __('Flash Section')) }} </h2>
             <a href="{{ route('front.user.shop', [getParam(), 'on_sale' => 'flash_sale']) }}"
               class="btn btn-md btn-primary rounded-pill icon-end shadow">{{ $keywords['Explore More'] ?? __('Explore More') }}
               <span class="icon"><i class="fal fa-arrow-right"></i></span>
             </a>
           </div>
         </div>
         <div class="col-12">
           @if (count($flash_items) == 0)
             <h5 class="title text-center mb-20">
               {{ $userSec->category_section_title ?? ($keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND')) }}
             </h5>
           @else
             <div class="product-slider mb-30" id="pro-slider-electronics-132131231"
               data-slick='{"dots": true, "slidesToShow": 4}'>
               @foreach ($flash_items as $item)
                 <div class="product-default product-default-2 radius-md mb-30">
                   <figure class="product-img">
                     <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                       class="lazy-container ratio ratio-1-1">
                       <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                         data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                         alt="Product">
                       <img class="lazyload hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                         data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                         alt="Product">
                     </a>
                   </figure>

                   <div class="product-details">
                     <div class="btn-icon-group btn-inline">
                       <div class="product-countdown hover-hide" data-start_date="{{ $item->start_date }}"
                         data-end_time="{{ $item->end_time }}" data-end_date="{{ $item->end_date }}"
                         data-item_id="{{ $item->item_id }}">
                         <div id="" class="count rounded-pill days">
                           <span class="count-value_{{ $item->item_id }}"></span>
                           <span class="count-period">{{ $keywords['Days'] ?? __('Days') }} </span>
                         </div>
                         <div id="" class="count rounded-pill hours">
                           <span class="count-value_{{ $item->item_id }}"></span>
                           <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
                         </div>
                         <div id="" class="count rounded-pill minutes">
                           <span class="count-value_{{ $item->item_id }}"></span>
                           <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
                         </div>
                         <div id="" class="count rounded-pill seconds">
                           <span class="count-value_{{ $item->item_id }}"></span>
                           <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
                         </div>
                       </div>

                       <div class="hover-show">
                         @if ($shop_settings->catalog_mode != 1)
                           @php
                             $current_pricce =
                                 $item->flash == 1
                                     ? $item->current_price - $item->current_price * ($item->flash_amount / 100)
                                     : $item->current_price;
                           @endphp
                           <a class="btn btn-icon rounded-pill color-primary cart-link cursor-pointer"
                             data-title="{{ $item->title }}"
                             data-current_price="{{ currency_converter($current_pricce) }}"
                             data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                             data-totalVari="{{ check_variation($item->item_id) }}"
                             data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                             data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                             data-bs-toggle="tooltip" data-bs-placement="top"
                             title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                               class="fal fa-shopping-cart "></i></a>
                         @endif
                         <a onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                           class="btn btn-icon rounded-pill color-primary ms-0" data-bs-toggle="tooltip"
                           data-bs-placement="top" title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                             class="fal fa-random"></i></a>
                         <a href="javascript:void(0)" class="btn btn-icon rounded-pill color-primary quick-view-link"
                           data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $item->item_id }}"
                           data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                           title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i></a>

                         @php
                           $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                           $checkWishList = $customer_id ? checkWishList($item->id, $customer_id) : false;
                         @endphp
                         <a href="#"
                           class="btn btn-icon rounded-pill color-primary {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                           data-bs-toggle="tooltip" data-bs-placement="top" data-item_id="{{ $item->id }}"
                           data-href="{{ route('front.user.add.wishlist', ['id' => $item->id, getParam()]) }}"
                           data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->id, getParam()]) }}"
                           title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                             class="fal fa-heart"></i></a>


                       </div>
                     </div>
                     <a href="{{ route('front.user.shop', ['category' => $item->category_slug, getParam()]) }}">
                       <span class="product-category text-sm">
                         {{ $item->category }}
                       </span>
                     </a>
                     <h3 class="product-title lc-2">
                       <a
                         href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ $item->title }}</a>
                     </h3>

                     @if ($shop_settings->item_rating_system == 1)
                       <div class="d-flex align-items-center">
                         <div class="product-ratings rate text-xsm">
                           <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                         </div>
                         <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                       </div>
                     @endif

                     <div class="product-price mb-0">
                       @if ($item->flash == 1)
                         <span class="new-price">
                           {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price - $item->current_price * ($item->flash_amount / 100))) }}
                         </span>
                         <span class="old-price text-decoration-line-through">
                           {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                         </span>
                       @else
                         <span class="new-price">
                           {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                         </span>
                         <span class="old-price text-decoration-line-through">
                           {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->previous_price)) }}
                         </span>
                       @endif

                     </div>
                   </div>
                   @php
                     $item_label = DB::table('labels')->where('id', $item->label_id)->first();
                     $label = $item_label->name ?? null;
                     $color = $item_label->color ?? null;
                   @endphp
                   <span
                     class="label label-2"style="background-color: #{{ $color }}">{{ $label }}</span>

                   @if ($item->flash == 1)
                     <span
                       class="label-discount-percentage "><x-flash-icon></x-flash-icon>{{ $item->flash_amount }}%</span>
                   @endif
                 </div>
               @endforeach
             </div>
           @endif
         </div>
       </div>
     </div>
   </div>
 </section>
 <!-- Best Sale Product End -->

