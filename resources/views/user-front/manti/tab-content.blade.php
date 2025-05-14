 <section class="section pb-100 lazy">
   <div class="container">
     <div class="row">
       <div class="col-12">
         <div class="section-title title-inline mb-30">
           <h2 class="title mb-0">{{ $tabs[$i]->name }}</h2>
           <a href="{{ route('front.user.shop', getParam()) }}"
             class="btn btn-md btn-primary radius-sm">{{ $keywords['Shop More'] ?? __('Shop More') }}</a>
         </div>
       </div>
     </div>
     <div class="row">
       <div class="col-xl-3 col-lg-4 col-md-6">
         <div class="product-default radius-md mb-30">
           <figure class="product-img mb-20 skeleton skeleton-img"></figure>
           <div class="product-details">
             <div class="btn-icon-group btn-inline text-center">
               <div class="product-countdown justify-content-center hover-hide">
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
               </div>
             </div>

             <div class="skeleton skeleton-category"></div>
             <h4 class="skeleton skeleton-title"></h4>
             <div class="skeleton skeleton-ratings"></div>

             <div class="product-price mb-0">
               <span class="new-price skeleton skeleton-price"></span>
               <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
             </div>
           </div>
           <span class="skeleton skeleton-price"></span>
         </div>
       </div>

       <div class="col-xl-3 col-lg-4 col-md-6">
         <div class="product-default radius-md mb-30">
           <figure class="product-img mb-20 skeleton skeleton-img"></figure>
           <div class="product-details">
             <div class="btn-icon-group btn-inline text-center">
               <div class="product-countdown justify-content-center hover-hide">
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
               </div>
             </div>

             <div class="skeleton skeleton-category"></div>
             <h4 class="skeleton skeleton-title"></h4>
             <div class="skeleton skeleton-ratings"></div>

             <div class="product-price mb-0">
               <span class="new-price skeleton skeleton-price"></span>
               <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
             </div>
           </div>
           <span class="skeleton skeleton-price"></span>
         </div>
       </div>

       <div class="col-xl-3 col-lg-4 col-md-6">
         <div class="product-default radius-md mb-30">
           <figure class="product-img mb-20 skeleton skeleton-img"></figure>
           <div class="product-details">
             <div class="btn-icon-group btn-inline text-center">
               <div class="product-countdown justify-content-center hover-hide">
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
               </div>
             </div>

             <div class="skeleton skeleton-category"></div>
             <h4 class="skeleton skeleton-title"></h4>
             <div class="skeleton skeleton-ratings"></div>

             <div class="product-price mb-0">
               <span class="new-price skeleton skeleton-price"></span>
               <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
             </div>
           </div>
           <span class="skeleton skeleton-price"></span>
         </div>
       </div>

       <div class="col-xl-3 col-lg-4 col-md-6">
         <div class="product-default radius-md mb-30">
           <figure class="product-img mb-20 skeleton skeleton-img"></figure>
           <div class="product-details">
             <div class="btn-icon-group btn-inline text-center">
               <div class="product-countdown justify-content-center hover-hide">
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
                 <span class="count-period skeleton skeleton-btn-icon"></span>
               </div>
             </div>

             <div class="skeleton skeleton-category"></div>
             <h4 class="skeleton skeleton-title"></h4>
             <div class="skeleton skeleton-ratings"></div>

             <div class="product-price mb-0">
               <span class="new-price skeleton skeleton-price"></span>
               <span class="old-price text-decoration-line-through skeleton skeleton-price"></span>
             </div>
           </div>
           <span class="skeleton skeleton-price"></span>
         </div>
       </div>
     </div>


   </div>
 </section>


<section class="section pb-100 actual-content">
   <div class="container">
     <div class="row">
       <div class="col-12">
         <div class="section-title title-inline mb-30">
           <h2 class="title mb-0">{{ $tabs[$i]->name }}</h2>
           <a href="{{ route('front.user.shop', getParam()) }}"
             class="btn btn-md btn-primary radius-sm">{{ $keywords['Shop More'] ?? __('Shop More') }}</a>
         </div>
       </div>
     </div>
     <div class="row">
       @php
         $products = json_decode(@$tabs[$i]->products, true);
       @endphp
       @if (!empty($products) && is_array($products))
         @for ($j = 0; $j < count($products); $j++)
           @php
             $product_details = \App\Models\User\UserItem::where('id', $products[$j])
                 ->with([
                     'itemContents' => function ($q) use ($uLang) {
                         $q->where('language_id', '=', $uLang);
                     },
                     'sliders',
                 ])
                 ->first();
             $flash_info = flashAmountStatus($product_details->id, $product_details->current_price);
             $product_current_price = $flash_info['amount'];
             $flash_status = $flash_info['status'];
           @endphp

           @if (!is_null(@$product_details->itemContents[0]->slug))
             <div class="col-lg-3 col-md-6">
               <div class="product-default product-default-2 radius-md mb-20">
                 <figure class="product-img">
                   <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}"
                     class="lazy-container ratio ratio-1-1" tabindex="0">
                     <img class="default-img ls-is-cached lazyload"
                       src="{{ asset('assets/front/images/placeholder.png') }}"
                       data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                       alt="Product">
                     <img class="hover-img ls-is-cached lazyload"
                       src="{{ asset('assets/front/images/placeholder.png') }}"
                       data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product_details->thumbnail) }}"
                       alt="Product">
                   </a>
                 </figure>
                 <div class="product-details mt-3">
                   <div class="btn-icon-group btn-inline text-start mb-10">

                     @if ($shop_settings->catalog_mode != 1)
                       <a href="javascript:void(0)" class="btn btn-icon rounded-pill cart-link"
                         data-title="{{ $product_details->itemContents[0]->title }}"
                         data-current_price="{{ currency_converter($product_current_price) }}"
                         data-item_id="{{ $product_details->id }}" data-language_id="{{ $uLang }}"
                         data-totalVari="{{ check_variation($product_details->id) }}"
                         data-variations="{{ check_variation($product_details->id) > 0 ? 'yes' : null }}"
                         data-href="{{ route('front.user.add.cart', ['id' => $product_details->id, getParam()]) }}"
                         data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                         data-bs-original-title="{{ $keywords['Add to Cart'] ?? __('Add to Cart') }}"><i
                           class="fal fa-shopping-bag"></i>
                       </a>
                     @endif

                     <a href="javascript:void(0)" class="btn btn-icon rounded-pill ms-0"
                       onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $product_details->id, getParam()]) }}')"
                       data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                       data-bs-original-title="{{ $keywords['Compare'] ?? __('Compare') }}"><i
                         class="fal fa-random"></i>
                     </a>

                     <button type="button" class="btn btn-icon rounded-pill quick-view-link" data-bs-toggle="tooltip"
                       data-bs-placement="top" title="" data-slug="{{ $product_details->itemContents[0]->slug }}"
                       data-url="{{ route('front.user.productDetails.quickview', ['slug' => $product_details->itemContents[0]->slug, getParam()]) }}"
                       tabindex="0" data-bs-original-title="{{ $keywords['Quick View'] ?? __('Quick View') }}">
                       <i class="fal fa-eye"></i>
                     </button>

                     @php
                       $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                       $checkWishList = $customer_id ? checkWishList($product_details->id, $customer_id) : false;
                     @endphp
                     <a href="#"
                       class="btn btn-icon rounded-pill {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                       data-item_id="{{ $product_details->id }}"
                       data-href="{{ route('front.user.add.wishlist', ['id' => $product_details->id, getParam()]) }}"
                       data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $product_details->id, getParam()]) }}"
                       data-bs-toggle="tooltip" data-bs-placement="top" title="" tabindex="0"
                       data-bs-original-title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                         class="fal fa-heart"></i>
                     </a>


                   </div>
                   <a href="{{ route('front.user.shop', ['category' => $product_details->itemContents[0]->category->slug, getParam()]) }}"
                     class="product-category text-sm">{{ $product_details->itemContents[0]->category->name }}</a>
                   <h4 class="product-title lc-2">
                     <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $product_details->itemContents[0]->slug]) }}"
                       tabindex="0">{{ $product_details->itemContents[0]->title }}</a>
                   </h4>
                   @if ($shop_settings->item_rating_system == 1)
                     <div class="d-flex align-items-center">
                       <div class="product-ratings rate text-xsm">
                         <div class="rating" style="width:{{ $product_details->rating * 20 }}%">
                         </div>
                       </div>
                       <span class="ratings-total">({{ reviewCount($product_details->id) }})</span>
                     </div>
                   @endif
                   <div class="product-price mb-0">
                     @if ($flash_status == true)
                       <span class="new-price">
                         {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                       </span>

                       <span class="old-price ms-1 text-decoration-line-through">
                         {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_details->current_price)) }}
                       </span>
                     @else
                       <span class="new-price">
                         {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_details->current_price)) }}
                       </span>

                       <span class="old-price ms-1 text-decoration-line-through">
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
                 @if ($item_label)
                   <span class="label label-2 label-pink"
                     style="background-color: #{{ $color }}">{{ $label }}</span>
                 @endif

                 @if ($flash_status == true)
                   <span class="label-discount-percentage">
                     <x-flash-icon></x-flash-icon>{{ $product_details->flash_amount }}%
                   </span>
                 @endif

               </div>
             </div>
           @endif
         @endfor
       @endif

     </div>
   </div>
 </section>


