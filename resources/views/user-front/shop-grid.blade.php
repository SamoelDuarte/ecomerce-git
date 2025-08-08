@if (count($items) > 0)
    @foreach ($items as $item)
        @php
            // Verifica se tem códigos digitais vinculados
            $codes = \App\Models\User\DigitalProductCode::where('user_item_id', $item->item_id)->get();

            $hasCodes = $codes->isNotEmpty();

            // Lógica de flash sale e preço atual
            $flash_info = flashAmountStatus($item->item_id, $item->current_price);
            $product_current_price = $flash_info['amount'];
            $flash_status = $flash_info['status'];

            // Lógica de preços para produtos digitais com códigos
            $codePrices = $codes->pluck('price')->filter(); // só preços > 0
            $minCodePrice = $codePrices->min();
            $maxCodePrice = $codePrices->max();

            // Se for digital e preço do item for 0, usar preço dos códigos
            $isDigital = $item->type === 'digital';
            $showCodePriceRange = $isDigital && $item->current_price == 0 && $hasCodes && $codePrices->count();
        @endphp

        <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6">
            <div class="product-default product-grid-card product-center radius-lg mb-30">

                <figure class="product-grid-card-image lazy-container ratio ratio-1-1">
                    <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->product_slug]) }}">
                        <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}"
                            alt="Product">
                    </a>
                </figure>

                <div class="product-details">
                    <h4 class="product-title lc-2">
                        <a
                            href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->product_slug]) }}">{{ $item->title }}</a>
                    </h4>

                    @if ($shop_settings->item_rating_system == 1)
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="product-ratings rate text-xsm">
                                <div class="rating" style="width:{{ $item->rating * 20 }}%"></div>
                            </div>
                            <span class="ratings-total">({{ reviewCount($item->item_id) }})</span>
                        </div>
                    @endif

                    <div class="product-price">
                        @if ($showCodePriceRange)
                            {{-- Se for digital, preço 0 e tem códigos, mostra faixa de preço dos códigos --}}
                            <span class="new-price">
                                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($minCodePrice)) }}
                            </span>
                            @if ($minCodePrice != $maxCodePrice)
                                <span class="new-price"> -
                                    {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($maxCodePrice)) }}
                                </span>
                            @endif
                        @elseif ($flash_status == true)
                            {{-- Flash sale ativo --}}
                            <span class="new-price">
                                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($product_current_price)) }}
                            </span>&nbsp;
                            <span class="old-price line_through">
                                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($item->current_price)) }}
                            </span>
                        @else
                            {{-- Preço normal --}}
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
                </div>

                <div class="btn-icon-group btn-inline">
                    @if ($shop_settings->catalog_mode != 1)
                        @if ($isDigital && $hasCodes)
                            {{-- Link normal que redireciona --}}
                            <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $item->product_slug]) }}"
                                class="btn btn-icon radius-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}">
                                <i class="far fa-shopping-cart"></i>
                            </a>
                        @else
                            {{-- Botão para adicionar ao carrinho via JS --}}
                            <a href="#" class="btn btn-icon radius-sm cart-link cursor-pointer"
                                data-title="{{ $item->title }}"
                                data-current_price="{{ currency_converter($product_current_price) }}"
                                data-item_id="{{ $item->item_id }}" data-language_id="{{ $uLang }}"
                                data-totalVari="{{ check_variation($item->item_id) }}"
                                data-variations="{{ check_variation($item->item_id) > 0 ? 'yes' : null }}"
                                data-href="{{ route('front.user.add.cart', ['id' => $item->item_id, getParam()]) }}"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}">
                                <i class="far fa-shopping-cart"></i>
                            </a>
                        @endif
                    @endif


                    <button type="button" class="btn btn-icon radius-sm quick-view-link" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="{{ $keywords['Quick View'] ?? __('Quick View') }}"
                        data-bs-toggle="modal" data-bs-target="#quickViewModal" data-item_id="{{ $item->item_id }}"
                        data-slug="{{ $item->product_slug }}"
                        data-url="{{ route('front.user.productDetails.quickview', ['slug' => $item->product_slug, getParam()]) }}">
                        <i class="fal fa-eye"></i>
                    </button>

                    <a class="btn btn-icon radius-sm"
                        onclick="addToCompare('{{ route('front.user.add.compare', ['id' => $item->item_id, getParam()]) }}')"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i></a>

                    @php
                        $customer_id = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
                        $checkWishList = $customer_id ? checkWishList($item->item_id, $customer_id) : false;
                    @endphp
                    <a class="btn btn-icon radius-sm btn-wish {{ $checkWishList ? 'remove-wish active' : 'add-to-wish' }}"
                        data-url="{{ route('front.user.add.wishlist', ['id' => $item->item_id, getParam()]) }}"
                        data-removeurl="{{ route('front.user.remove.wishlist', ['id' => $item->item_id, getParam()]) }}"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i
                            class="fal fa-heart"></i></a>
                </div>

                @php
                    $item_label = DB::table('labels')->where('id', $item->label_id)->first();
                    $label = $item_label->name ?? null;
                    $color = $item_label->color ?? null;
                @endphp
                <span class="label label-2" style="background-color: #{{ $color }}">{{ $label }}</span>

                @if ($flash_status == true)
                    <span
                        class="label-discount-percentage"><x-flash-icon></x-flash-icon>{{ $item->flash_amount }}%</span>
                @endif
            </div> <!-- product-default -->
        </div>
    @endforeach

    <div class="col-12">
        <div class="pagination mb-30 justify-content-center">
            {{ $items->appends([
                    'type' => request()->input('type'),
                    'category' => request()->input('category'),
                    'min' => request()->input('min'),
                    'max' => request()->input('max'),
                    'keyword' => request()->input('keyword'),
                    'sort' => request()->input('sort'),
                    'variations' => request()->input('variations'),
                ])->links() }}
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
