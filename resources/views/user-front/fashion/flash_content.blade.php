 <!-- Best Product Start -->
 <section class="new-product  pt-100 pb-70 bg-color lazy" >
   <div class="container">
     <div class="row gx-xl-5 align-items-center flex-lg-nowrap">
       <div class="col-lg-4">
         <div class="content mb-30">
           <span class="sub-title color-primary mb-10">{{ $userSec->flash_section_subtitle ?? '' }} </span>
           <h2 class="title mb-10">
             {{ $userSec->flash_section_title ?? ($keywords['Flash Section'] ?? __('Flash Section')) }} </h2>
           <span class="line line-2"></span>
         </div>
       </div>
       <div class="col-lg-8">

         <div class="row">
           <div class="col-lg-3">
             <div class="product-default product-default-3 radius-md border-0 mb-30">
               <figure class="product-img skeleton skeleton-img"></figure>
               <div class="product-details">
                 <div class="product-countdown justify-content-left">
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                 </div>
                 <span class="count-period skeleton skeleton-title"></span>
                 <div class="product-price mt-0 mb-10 lh-1">
                   <span class="new-price skeleton skeleton-price"></span>
                   <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                 </div>
                 <div class="skeleton skeleton-ratings"></div>
               </div>
             </div>
           </div>
           <div class="col-lg-3">
             <div class="product-default product-default-3 radius-md border-0 mb-30">
               <figure class="product-img skeleton skeleton-img"></figure>
               <div class="product-details">
                 <div class="product-countdown justify-content-left">
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                 </div>
                 <span class="count-period skeleton skeleton-title"></span>
                 <div class="product-price mt-0 mb-10 lh-1">
                   <span class="new-price skeleton skeleton-price"></span>
                   <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                 </div>
                 <div class="skeleton skeleton-ratings"></div>
               </div>
             </div>
           </div>
           <div class="col-lg-3">
             <div class="product-default product-default-3 radius-md border-0 mb-30">
               <figure class="product-img skeleton skeleton-img"></figure>
               <div class="product-details">
                 <div class="product-countdown justify-content-left">
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                 </div>
                 <span class="count-period skeleton skeleton-title"></span>
                 <div class="product-price mt-0 mb-10 lh-1">
                   <span class="new-price skeleton skeleton-price"></span>
                   <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                 </div>
                 <div class="skeleton skeleton-ratings"></div>
               </div>
             </div>
           </div>
           <div class="col-lg-3">
             <div class="product-default product-default-3 radius-md border-0 mb-30">
               <figure class="product-img skeleton skeleton-img"></figure>
               <div class="product-details">
                 <div class="product-countdown justify-content-left">
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                   <span class="count-period skeleton skeleton-btn-icon"></span>
                 </div>
                 <span class="count-period skeleton skeleton-title"></span>
                 <div class="product-price mt-0 mb-10 lh-1">
                   <span class="new-price skeleton skeleton-price"></span>
                   <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
                 </div>
                 <div class="skeleton skeleton-ratings"></div>
               </div>
             </div>
           </div>
         </div>

       </div>
     </div>
   </div>
 </section>
 <!-- Best Product Start -->



 <!-- Best Product Start -->
 <section class="new-product  pt-100 pb-70 bg-color actual-content">
   <div class="container">
     <div class="row gx-xl-5 align-items-center flex-lg-nowrap">
       <div class="col-lg-4">
         <div class="content mb-30">
           <span class="sub-title color-primary mb-10">{{ $userSec->flash_section_subtitle ?? '' }} </span>
           <h2 class="title mb-10">
             {{ $userSec->flash_section_title ?? ($keywords['Flash Section'] ?? __('Flash Section')) }} </h2>
           <span class="line line-2"></span>

           <div class="slider-arrow style-2 mt-40" id="pro-slider-fashion-arrows"></div>
         </div>
       </div>
       <div class="col-lg-auto">
         @if (count($flash_items) == 0)
           <h5 class="title mb-20">
             {{ $userSec->category_section_title ?? ($keywords['NO PRODUCTS FOUND'] ?? __('NO PRODUCTS FOUND')) }}
           </h5>
         @else
           <div class="product-slider" id="pro-slider-fashion"
             data-slick='{"arrows": true, "infinite": true, "slidesToShow": 4}'>

             @foreach ($flash_items as $item)
               <div class="product-default product-default-3 radius-md border-0 mb-30">
                 <figure class="product-img">
                   <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}"
                     class="ratio ratio-1-1">
                     <img class="lazyload blur-up default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                       data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                       alt="Product">
                     <img class="lazyload blur-up hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                       data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                       alt="Product">
                   </a>
                   <span class="line"></span>

                   <div class="btn-icon-group btn-inline btn-center-position">
                     @if ($shop_settings->catalog_mode != 1)
                       @php
                         $current_price =
                             $item->flash == 1
                                 ? $item->current_price - $item->current_price * ($item->flash_amount / 100)
                                 : $item->current_price;
                       @endphp
                       <a class=" btn btn-icon hover-show rounded-pill cart-link cursor-pointer"
                         data-title="{{ $item->title }}" data-current_price="{{ currency_converter($current_price) }}"
                         data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                         data-totalVari="{{ check_variation($item->item_id) }}"
                         data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                         data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                         data-bs-toggle="tooltip" data-bs-placement="top"
                         title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i
                           class="far fa-shopping-cart "></i></a>
                     @endif
                     <a class="btn btn-icon hover-show rounded-pill quick-view-link" data-bs-toggle="tooltip"
                       data-bs-placement="top" data-slug="{{ $item->slug }}"
                       data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->slug, getParam()]) }}"
                       title="{{ $keywords['Quick View'] ?? __('Quick View') }}"><i class="fal fa-eye"></i></a>

                     @php
                       $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                       $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                     @endphp
                     <a class="btn btn-icon hover-show rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                       data-item_id="{{ $item->item_id }}"
                       data-href="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                       data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                       data-bs-toggle="tooltip" data-bs-placement="top"
                       title="{{ $keywords['Add to wishlist'] ?? __('Add to wishlist') }}"><i
                         class="fal fa-heart"></i></a>

                     <a onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                       class="btn btn-icon hover-show rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top"
                       title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i></a>
                   </div>


                 </figure>
                 <div class="product-details">
                   <div class="product-countdown justify-content-left" data-start_date="{{ $item->start_date }}"
                     data-end_time="{{ $item->end_time }}" data-end_date="{{ $item->end_date }}"
                     data-item_id="{{ $item->item_id }}">
                     <div id="" class="count radius-sm days">
                       <span class="count-value_{{ $item->item_id }}"></span>
                       <span class="count-period">{{ $keywords['Days'] ?? __('Days') }} </span>
                     </div>
                     <div id="" class="count radius-sm hours">
                       <span class="count-value_{{ $item->item_id }}"></span>
                       <span class="count-period">{{ $keywords['Hours'] ?? __('Hours') }}</span>
                     </div>
                     <div id="" class="count radius-sm minutes">
                       <span class="count-value_{{ $item->item_id }}"></span>
                       <span class="count-period">{{ $keywords['Mins'] ?? __('Mins') }}</span>
                     </div>
                     <div id="" class="count radius-sm seconds">
                       <span class="count-value_{{ $item->item_id }}"></span>
                       <span class="count-period">{{ $keywords['Sec'] ?? __('Sec') }}</span>
                     </div>
                   </div>
                   <h3 class="product-title lc-2">
                     <a
                       href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->slug]) }}">{{ $item->title }}</a>
                   </h3>
                   <div class="product-price mt-0 mb-10 lh-1">
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

                   @if ($shop_settings->item_rating_system == 1)
                     <div class="d-flex align-items-center">
                       <div class="product-ratings rate text-xsm">
                         <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                       </div>
                       <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                     </div>
                   @endif
                 </div>



                 @if ($item->flash == 1)
                   <span
                     class="label-discount-percentage"><x-flash-icon></x-flash-icon>{{ $item->flash_amount }}%</span>
                 @endif
               </div>
             @endforeach

           </div>
         @endif
       </div>
     </div>
   </div>
 </section>
 <!-- Best Product Start -->


