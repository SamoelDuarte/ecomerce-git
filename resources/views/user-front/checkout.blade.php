@extends('user-front.layout')
@section('meta-description', !empty($seo) ? $seo->checkout_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->checkout_meta_keywords : '')
@section('breadcrumb_title', $pageHeading->checkout_page ?? __('Checkout'))
@section('page-title', $pageHeading->checkout_page ?? __('Checkout'))
@section('content')
@php
$user_currency = user_currency(Session::get('user_curr'));
@endphp

<!-- Checkout Start -->
<div class="shopping-area pt-10 pb-70">
    <form action="{{ route('item.payment.submit', getParam()) }}" method="POST" id="userOrderForm"
        enctype="multipart/form-data">
        @csrf
        @if (Session::has('stock_error'))
        <p class="text-danger text-center my-3">{{ Session::get('stock_error') }}</p>
        @endif

        <input type="hidden" name="shipping_service_price" id="shipping_service_price">
        <input type="hidden" name="shipping_service_name" id="shipping_service_name">
        <div class="container">
            @if (Session::has('st_errors'))
            <div class="alert alert-warning">
                <ul>
                    @foreach (Session::get('st_errors') as $sterr)
                    <li class=" text-muted">{{ $sterr }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="row gx-xl-5">
                <div class="col-lg-8">
                    @if (session()->has('stock_out_error'))
                    @foreach (session()->get('stock_out_error') as $error)
                    <div class="alert alert-danger" role="alert">{{ $error }}</div>
                    @endforeach
                    @endif

                    <div class="billing-details">
                        <h3 class="mb-20">{{ $keywords['Billing Details'] ?? __('Billing Details') }}</h3>
                        <div class="row">
                            <!-- Nome -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="firstName">{{ $keywords['First_Name'] ?? __('First Name') }} *</label>
                                    <input id="firstName" type="text" class="form-control"
                                        placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}"
                                        name="billing_fname"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_fname) : old('billing_fname') }}">
                                    @error('billing_fname')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sobrenome -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="lastName">{{ $keywords['Last_Name'] ?? __('Last Name') }} *</label>
                                    <input id="lastName" type="text" class="form-control"
                                        placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}"
                                        name="billing_lname"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_lname) : old('billing_lname') }}">
                                    @error('billing_lname')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Telefone -->
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="phone">{{ $keywords['Phone_Number'] ?? __('Phone Number') }}
                                        *</label>
                                    <input id="phone" type="text" class="form-control"
                                        placeholder="{{ $keywords['Phone_Number'] ?? __('Phone Number') }}"
                                        name="billing_number"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_number) : old('billing_number') }}">
                                    @error('billing_number')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="email">{{ $keywords['Email_Address'] ?? __('Email Address') }}
                                        *</label>
                                    <input class="form-control" id="email" type="email"
                                        placeholder="{{ $keywords['Email_Address'] ?? __('Email Address') }}"
                                        name="billing_email"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_email) : old('billing_email') }}">
                                    @error('billing_email')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- CEP -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="zipcode">CEP *</label>
                                    <input id="billing_zip" type="text" class="form-control" placeholder="CEP"
                                        name="billing_zip"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_zip) : old('billing_zip') }}">

                                    @error('billing_zip')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Rua / Logradouro -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="billing_street">Rua / Logradouro *</label>
                                    <input id="billing_street" type="text" class="form-control"
                                        placeholder="Rua / Avenida" name="billing_street"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_street) : old('billing_street') }}">
                                    @error('billing_street')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Número da casa -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="billing_number_home">Número *</label>
                                    <input id="billing_number_home" type="text" class="form-control"
                                        placeholder="Número da casa / prédio" name="billing_number_home"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_number_home) : old('billing_number_home') }}">
                                    @error('billing_number_home')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Bairro -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="billing_neighborhood">Bairro *</label>
                                    <input id="billing_neighborhood" type="text" class="form-control"
                                        placeholder="Bairro" name="billing_neighborhood"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_neighborhood) : old('billing_neighborhood') }}">
                                    @error('billing_neighborhood')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cidade -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="city">{{ $keywords['City'] ?? __('City') }} *</label>
                                    <input id="city" type="text" class="form-control"
                                        placeholder="{{ $keywords['City'] ?? __('City') }}" name="billing_city"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_city) : old('billing_city') }}">
                                    @error('billing_city')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="district">{{ $keywords['State'] ?? __('State') }} *</label>
                                    <input id="district" type="text" class="form-control"
                                        placeholder="{{ $keywords['State'] ?? __('State') }}" name="billing_state"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_state) : old('billing_state') }}">
                                    @error('billing_state')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Complemento / Referência -->
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="reference">Referência / Complemento</label>
                                    <input id="reference" type="text" class="form-control"
                                        placeholder="referência / complemento" name="billing_reference"
                                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_reference) : old('billing_reference') }}">
                                    @error('billing_reference')
                                    <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ship-details">
                        <div class="form-group mb-20">
                            <div class="custom-checkbox">
                                <input class="input-checkbox" type="checkbox" name="checkbox"
                                    @if (old('checkbox')) checked @endif id="differentaddress">
                                <label class="form-check-label" data-bs-toggle="collapse"
                                    data-target="#collapseAddress" href="#collapseAddress"
                                    aria-controls="collapseAddress"
                                    for="differentaddress"><span>{{ $keywords['Ship to a different address'] ?? __('Ship to a different address?') }}
                                        *</span></label>
                            </div>
                        </div>

                        <div id="collapseAddress" class="collapse @if (old('checkbox')) show @endif">
                            <h3 class="mb-20">{{ $keywords['Shipping Details'] ?? __('Shipping Details') }}</h3>
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="firstName">{{ $keywords['First_Name'] ?? __('First Name') }}
                                            *</label>
                                        <input id="firstName" type="text" class="form-control"
                                            name="shipping_fname"
                                            placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_fname) : old('shipping_fname') }}">
                                        @error('shipping_fname')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="lastName">{{ $keywords['Last_Name'] ?? __('Last Name') }}
                                            *</label>
                                        <input id="lastName" type="text" class="form-control"
                                            name="shipping_lname"
                                            placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_lname) : old('shipping_lname') }}">
                                        @error('shipping_lname')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label for="phone">{{ $keywords['Phone_Number'] ?? __('Phone Number') }}
                                            *</label>
                                        <input id="phone" type="text" class="form-control"
                                            name="shipping_number"
                                            placeholder="{{ $keywords['Phone_Number'] ?? __('Phone Number') }}"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_number) : old('shipping_number') }}">
                                        @error('shipping_number')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="email">{{ $keywords['Email_Address'] ?? __('Email Address') }}
                                            *</label>
                                        <input id="email" type="email" class="form-control"
                                            name="shipping_email"
                                            placeholder="{{ $keywords['Email_Address'] ?? __('Email Address') }}"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_email) : old('shipping_email') }}">
                                        @error('shipping_email')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- NOVO: Campo CEP -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="shipping_zip">CEP</label>
                                        <input id="shipping_zip" type="text" class="form-control"
                                            name="shipping_zip" placeholder="cep"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_zip) : old('shipping_zip') }}">
                                        @error('shipping_zip')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- ... tudo que já estava acima permanece igual -->

                                <!-- RUA -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="shipping_street">Rua *</label>
                                        <input id="shipping_street" type="text" class="form-control"
                                            name="shipping_street" placeholder="Rua"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_street) : old('shipping_street') }}">
                                        @error('shipping_street')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- NÚMERO -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="shipping_number_address">Número *</label>
                                        <input id="shipping_number_address" type="text" class="form-control"
                                            name="shipping_number_address" placeholder="Número"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_number_address) : old('shipping_number_address') }}">
                                        @error('shipping_number_address')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- BAIRRO -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="shipping_neighborhood">Bairro *</label>
                                        <input id="shipping_neighborhood" type="text" class="form-control"
                                            name="shipping_neighborhood" placeholder="Bairro"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_neighborhood) : old('shipping_neighborhood') }}">
                                        @error('shipping_neighborhood')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="shipping_city">{{ $keywords['City'] ?? __('City') }} *</label>
                                        <input id="shipping_city" type="text" class="form-control"
                                            name="shipping_city" placeholder="{{ $keywords['City'] ?? __('City') }}"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_city) : old('shipping_city') }}">
                                        @error('shipping_city')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="shipping_state">{{ $keywords['State'] ?? __('State') }} *</label>
                                        <input id="shipping_state" type="text" class="form-control"
                                            name="shipping_state"
                                            placeholder="{{ $keywords['State'] ?? __('State') }}"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_state) : old('shipping_state') }}">
                                        @error('shipping_state')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- NOVO: Campo de referência -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="shipping_reference">Referência / Complemento</label>
                                        <input id="shipping_reference" type="text" class="form-control"
                                            name="shipping_reference" placeholder="referência / complemento"
                                            value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_reference) : old('shipping_reference') }}">
                                        @error('shipping_reference')
                                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="order-summery radius-md border mb-30">
                        <h3 class="p-20 title mb-0">{{ $keywords['Cart Items'] ?? __('Cart Items') }}</h3>
                        @php
                        $total = 0;
                        @endphp
                        <div class="order-summery-list-wrapper">
                            @if ($cart)
                            @foreach ($cart as $key => $item)
                            @php
                            $itemPrincipal = \App\Models\User\UserItem::find($item['id']);
                            @endphp
                            @php
                            $total += $item['product_price'] * $item['qty'];
                            $prd = \App\Models\User\UserItem::with([
                            'itemContents' => function ($query) use ($userCurrentLang) {
                            $query->where('language_id', $userCurrentLang->id);
                            },
                            ])->findOrFail($item['id']);

                            $content = $prd->itemContents->first();
                            @endphp
                            <input type="hidden" name="product_id[]" value="{{ $item['id'] }}">
                            <div class="order-summery-list-item">
                                <div class="product-item">
                                    <div class="product-img">
                                        <div class="image">
                                            @if (!is_null($content))
                                            <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $content->slug]) }}"
                                                target="_blank" class="lazy-container ratio ratio-1-1">
                                                <img class=" ls-is-cached lazyload"
                                                    src="{{ asset('assets/front/images/placeholder.png') }}"
                                                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $prd->thumbnail) }}"
                                                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $prd->thumbnail) }}"
                                                    alt="Product">
                                            </a>
                                            @else
                                            <a href="" class="lazy-container ratio ratio-1-1">
                                                <img class=" ls-is-cached lazyload"
                                                    src="{{ asset('assets/front/images/placeholder.png') }}"
                                                    data-src="{{ asset('assets/user-front/images/placeholder.png') }}"
                                                    data-src="{{ asset('assets/user-front/images/placeholder.png') }}"
                                                    alt="Product">
                                            </a>
                                            @endif
                                        </div>
                                        <span class="product-qty">{{ $item['qty'] }}</span>
                                    </div>
                                    <div class="product-desc">
                                        <h5 class="product-title lc-1 mb-1">
                                            @if (!is_null($content))
                                            <a target="_blank"
                                                href="{{ route('front.user.productDetails', [getParam(), 'slug' => $content->slug]) }}">{{ convertUtf8($content->title) }}</a>
                                            @endif
                                        </h5>

                                        <div class="product-price">
                                            <span
                                                class="text-dark fw-medium">{{ $keywords['Item Price'] ?? __('Item Price') }}
                                                :</span>
                                            <span>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $item['product_price']) }}</span>
                                        </div>

                                        @if ($prd->type == 'digital')
                                        <div class="product-type">
                                            <span class="badge bg-info text-dark"> {{ $keywords['Digital Product'] ?? __('Produto Digital') }}</span>
                                        </div>
                                        @endif

                                        @if ($item['variations'] && !$itemPrincipal->hasCode())
                                        <div class="variation-area">
                                            <h5 class="text-dark fw-bold mb-0">
                                                {{ $keywords['Variations'] ?? __('Variations') }}:
                                            </h5>
                                            @foreach ($item['variations'] as $key => $variation)
                                            @php
                                            //show variations name
                                            $vNameId = App\Models\User\ProductVariationContent::where(
                                            'product_variation_id',
                                            $variation['variation_id'],
                                            )
                                            ->pluck('variation_name')
                                            ->first();

                                            $variant_id = App\Models\VariantContent::where(
                                            'id',
                                            $vNameId,
                                            )
                                            ->pluck('variant_id')
                                            ->first();
                                            $variation_name = App\Models\VariantContent::where([
                                            ['variant_id', $variant_id],
                                            ['language_id', $userCurrentLang->id],
                                            ])
                                            ->pluck('name')
                                            ->first();

                                            //show variation options name
                                            $vOptionId = App\Models\User\ProductVariantOptionContent::where(
                                            [
                                            ['language_id', $userCurrentLang->id],
                                            [
                                            'product_variant_option_id',
                                            $variation['option_id'],
                                            ],
                                            ],
                                            )
                                            ->pluck('option_name')
                                            ->first();
                                            $vOptionName = App\Models\VariantOptionContent::where(
                                            [
                                            ['language_id', $userCurrentLang->id],
                                            ['id', $vOptionId],
                                            ],
                                            )
                                            ->pluck('option_name')
                                            ->first();
                                            @endphp

                                            <div class="variation-item">
                                                <span
                                                    class="text-dark fw-medium">{{ $variation_name }}
                                                    :</span>
                                                <span class="cart_variants_price"> {{ $vOptionName }}
                                                    (<i
                                                        class="fas fa-plus"></i>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $variation['price']) }})
                                                </span>
                                            </div>
                                            @endforeach
                                        </div>
                                        <span
                                            class="show-variation">{{ $keywords['View Variations'] ?? __('View Variations') }}</span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="4">{{ __('Cart is empty') }}</td>
                            </tr>
                            @endif
                        </div>
                    </div>


                    {{-- Seção de Métodos de Entrega via Frenet --}}
                    @php
                        $onlyDigital = onlyDigitalItemsInCart();
                        \Log::info('Checkout - onlyDigitalItemsInCart resultado:', ['onlyDigital' => $onlyDigital]);
                    @endphp
                    @if (!$onlyDigital)
                    <div class="col-12 mb-5">
                        <div class="order-summery form-block border radius-md">
                            <div class="shop-title-box">
                                <h3 class="pb-1">
                                    {{ $keywords['Shipping Methods'] ?? __('Métodos de Entrega') }}
                                </h3>
                            </div>
                            <div id="frenetShippingMethods">
                                <p class="text-muted small">Digite seu CEP para calcular o frete</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div id="cartTotal">
                        <div class="order-summary form-block border radius-md mb-30">
                            <h3 class="pb-10 mb-20 border-bottom">
                                {{ $keywords['Order Summary'] ?? __('Order Summary') }}
                            </h3>
                            <div class="sub-total d-flex justify-content-between mb-2">
                                <h5 class="mb-0">{{ $keywords['Cart Total'] ?? __('Cart Total') }}</h5>
                                <span class="price"><span data="cartTotal() }}"
                                        class="price">{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, cartTotal()) }}</span>
                                </span>
                            </div>
                            <ul class="service-charge-list">
                                <li class="d-flex justify-content-between">

                                    <h5 class="mb-0">{{ $keywords['Discount'] ?? __('Discount') }}</h5>
                                    <span class="price"><span id="discount" data="{{ $discount }}">
                                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $discount) }}</span>
                                    </span>

                                </li>

                                <hr />

                                <div class="sub-total d-flex justify-content-between">
                                    <h5>{{ $keywords['Subtotal'] ?? __('Subtotal') }} </h5>
                                    <span class="price"><span data="{{ cartSubTotal() }}" class="subtotal"
                                            id="subtotal">{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, cartSubTotal()) }}</span>
                                    </span>
                                </div>
                                <hr />


                                {{-- Fretes dinâmicos via Frenet --}}
                                <div id="frenetShippingDetails"></div>

                                @if ($userShop->tax != 0)
                                <li class="d-flex justify-content-between">
                                    <h5 class="mb-0">{{ $keywords['Tax'] ?? __('Tax') }}
                                        ({{ $userShop->tax }}%)</h5>
                                    <span class="price">
                                        <span data-tax="{{ tax() }}" id="tax">
                                            {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, tax()) }}
                                        </span>
                                    </span>
                                </li>
                                @endif

                            </ul>
                            <hr>
                            <div class="total d-flex justify-content-between">
                                <h5> {{ $keywords['Order Total'] ?? __('Order Total') }} {{ __('') }}</h5>

                                @php
                                if (count($shippings) > 0) {
                                $scharge = round($shippings[0]->charge, 2);
                                $sh_id = $shippings[0]->id;
                                } else {
                                $sh_id = 0;
                                }
                                @endphp

                                <span class="price">
                                    <span
                                        data="{{ cartSubTotal() + ($sh_id > 0 ? currency_converter_shipping($scharge, $shippings[0]->id) : 0) + tax() }}"
                                        class="grandTotal">
                                        {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, cartSubTotal() + tax()) }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    @if (!session()->has('coupon'))
                    <div class="form-inline mb-30">
                        <div class="input-group radius-sm border">
                            <input class="form-control"
                                placeholder="{{ $keywords['Enter Coupon Code'] ?? __('Enter Coupon Code') }}"
                                type="text" name="coupon" autocomplete="off">
                            <button
                                class="btn btn-lg btn-primary radius-sm couponBtn">{{ $keywords['Apply'] ?? __('Apply') }}</button>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-success">
                        {{ $keywords['Coupon_already_applied'] ?? __('Coupon already applied') }}
                    </div>
                    @endif


                    <div class="order-payment form-block border radius-md mb-30">
                        @include('user-front.payment-gateways')

                        {{-- START: Offline Gateways Information & Receipt Area --}}
                        <div class="mt-3">
                            <div id="instructions"></div>
                            <input type="hidden" name="is_receipt" value="0" id="is_receipt">
                            @error('receipt')
                            <span class="error">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        {{-- END: Offline Gateways Information & Receipt Area --}}

                        <div class="text-center mt-30">
                            <button {{ $cart ? '' : 'disabled' }} class="btn btn-lg btn-primary radius-md w-100"
                                type="submit">Finalizar compra</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>
<!-- Checkout End -->
@endsection
@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    "use strict";
    var instruction_url = "{{ route('product.payment.paymentInstruction', getParam()) }}";
    var offline_gateways = @php echo json_encode($offlines) @endphp;
    var coupon_url = "{{ route('front.coupon', getParam()) }}";
    var anet_public_key = "{{ @$anerInfo['public_key'] }}";
    var anet_login_id = "{{ @$anerInfo['login_id'] }}";
    var stripe_key = "{{ @$stripeInfo['key'] }}";
    var processing_text = "{{ $keywords['Processing'] ?? __('Processing') }}";
    var place_order = "{{ $keywords['Place Order'] ?? __('Place Order') }}";
    var ucurrency_position = "{{ $user_currency->symbol_position }}";
    var ucurrency_symbol = "{{ $user_currency->symbol }}";
</script>
{{-- START: Authorize.net Scripts --}}
@if (!is_null(@$anerInfo))
@php
if (@$anerInfo['sandbox_check'] == 1) {
$anetSrc = 'https://jstest.authorize.net/v1/Accept.js';
} else {
$anetSrc = 'https://js.authorize.net/v1/Accept.js';
}
@endphp
<script type="text/javascript" src="{{ $anetSrc }}" charset="utf-8"></script>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    // IMPORTANTE: Executar ANTES do user-checkout.js para garantir que os dados sejam copiados
    $(document).ready(function() {
        // Usar captura de evento para executar ANTES de outros listeners
        var form = document.getElementById('userOrderForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                // Se o checkbox "Ship to different address" NÃO estiver marcado,
                // copiar dados de billing para shipping antes de enviar
                if (!$('#differentaddress').is(':checked')) {
                    console.log('Copiando dados de billing para shipping...');
                    
                    // Tornar o collapse visível temporariamente para garantir que os campos sejam enviados
                    $('#collapseAddress').addClass('show');
                    
                    // Copiar todos os dados
                    $('input[name="shipping_fname"]').val($('input[name="billing_fname"]').val());
                    $('input[name="shipping_lname"]').val($('input[name="billing_lname"]').val());
                    $('input[name="shipping_email"]').val($('input[name="billing_email"]').val());
                    $('input[name="shipping_number"]').val($('input[name="billing_number"]').val());
                    $('input[name="shipping_city"]').val($('input[name="billing_city"]').val());
                    $('input[name="shipping_state"]').val($('input[name="billing_state"]').val());
                    $('input[name="shipping_zip"]').val($('input[name="billing_zip"]').val());
                    $('input[name="shipping_street"]').val($('input[name="billing_street"]').val());
                    $('input[name="shipping_number_address"]').val($('input[name="billing_number_home"]').val());
                    $('input[name="shipping_neighborhood"]').val($('input[name="billing_neighborhood"]').val());
                    $('input[name="shipping_reference"]').val($('input[name="billing_reference"]').val());
                    $('input[name="shipping_country"]').val('BR');
                    
                    console.log('Dados copiados com sucesso!');
                    console.log('Billing Nome:', $('input[name="billing_fname"]').val());
                    console.log('Shipping Nome (copiado):', $('input[name="shipping_fname"]').val());
                    console.log('Billing CEP:', $('input[name="billing_zip"]').val());
                    console.log('Shipping CEP (copiado):', $('input[name="shipping_zip"]').val());
                }
            }, true); // true = usar capture phase (executa antes)
        }
    });
</script>

<script src="{{ asset('assets/user-front/js/user-checkout.js?v=1.0.5') }}"></script>
@endsection