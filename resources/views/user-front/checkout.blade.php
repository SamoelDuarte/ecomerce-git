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
  <div class="shopping-area pt-100 pb-70">
    <form action="{{ route('item.payment.submit', getParam()) }}" method="POST" id="userOrderForm"
      enctype="multipart/form-data">
      @csrf
      @if (Session::has('stock_error'))
        <p class="text-danger text-center my-3">{{ Session::get('stock_error') }}</p>
      @endif
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
              <h3 class="mb-20">{{ $keywords['Billing Details'] ?? __('Billing Details') }} </h3>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="firstName">{{ $keywords['First_Name'] ?? __('First Name') }} *</label>
                    <input id="firstName" type="text" class="form-control"
                      placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}" name="billing_fname"
                      value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_fname) : old('billing_fname') }}">
                    @error('billing_fname')
                      <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="lastName">{{ $keywords['Last_Name'] ?? __('Last Name') }} *</label>
                    <input id="lastName" type="text" class="form-control"
                      placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}" name="billing_lname"
                      value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_lname) : old('billing_lname') }}">
                    @error('billing_lname')
                      <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group mb-3">
                    <label for="phone">{{ $keywords['Phone_Number'] ?? __('Phone Number') }} *</label>
                    <input id="phone" type="text" class="form-control"
                      placeholder="{{ $keywords['Phone_Number'] ?? __('Phone Number') }}" name="billing_number"
                      value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_number) : old('billing_number') }}">
                    @error('billing_number')
                      <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-lg-6">

                  <div class="form-group mb-3">
                    <label for="email">{{ $keywords['Email_Address'] ?? __('Email Address') }} *</label>
                    <input class="form-control" id="email" type="email"
                      placeholder="{{ $keywords['Email_Address'] ?? __('Email Address') }}" name="billing_email"
                      value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_email) : old('billing_email') }}">
                    @error('billing_email')
                      <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                    @enderror
                  </div>
                </div>

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

                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="district">{{ $keywords['State'] ?? __('State') }} </label>
                    <input id="district" type="text" class="form-control"
                      placeholder="{{ $keywords['State'] ?? __('State') }}" name="billing_state"
                      value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_state) : old('billing_state') }}">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="country">{{ $keywords['Country'] ?? __('Country') }} *</label>
                    <input type="text" class="form-control" placeholder="{{ $keywords['Country'] ?? __('Country') }}"
                      name="billing_country"
                      value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_country) : old('billing_country') }}">
                    @error('billing_country')
                      <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group mb-3">
                    <label for="country">{{ $keywords['Address'] ?? __('Address') }} *</label>
                    <textarea name="billing_address" class="form-control max_height_100"
                      placeholder="{{ $keywords['Address'] ?? __('Address') }}">{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->billing_address) : old('billing_address') }}</textarea>
                    @error('billing_address')
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
                  <label class="form-check-label" data-bs-toggle="collapse" data-target="#collapseAddress"
                    href="#collapseAddress" aria-controls="collapseAddress"
                    for="differentaddress"><span>{{ $keywords['Ship to a different address'] ?? __('Ship to a different address?') }}
                      * </span></label>
                </div>
              </div>
              <div id="collapseAddress" class="collapse @if (old('checkbox')) show @endif">
                <h3 class="mb-20">{{ $keywords['Shipping Details'] ?? __('Shipping Details') }} </h3>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="firstName">{{ $keywords['First_Name'] ?? __('First Name') }} *</label>
                      <input id="firstName" type="text" class="form-control" name="shipping_fname"
                        placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}"
                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_fname) : old('shipping_fname') }}">
                      @error('shipping_fname')
                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="lastName">{{ $keywords['Last_Name'] ?? __('Last Name') }} *</label>
                      <input id="lastName" type="text" class="form-control" name="shipping_lname"
                        placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}"
                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_lname) : old('shipping_lname') }}">
                      @error('shipping_lname')
                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group mb-3">
                      <label for="phone">{{ $keywords['Phone_Number'] ?? __('Phone Number') }} *</label>
                      <input id="phone" type="text" class="form-control" name="shipping_number"
                        placeholder="{{ $keywords['Phone_Number'] ?? __('Phone Number') }}"
                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_number) : old('shipping_number') }}">
                      @error('shipping_number')
                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="email">{{ $keywords['Email_Address'] ?? __('Email Address') }} *</label>
                      <input id="email" type="email" class="form-control" name="shipping_email"
                        placeholder="{{ $keywords['Email_Address'] ?? __('Email Address') }}"
                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_email) : old('shipping_email') }}">
                      @error('shipping_email')
                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="shipping_city">{{ $keywords['City'] ?? __('City') }}* </label>
                      <input id="shipping_city" type="text" class="form-control" name="shipping_city"
                        placeholder="{{ $keywords['City'] ?? __('City') }}"
                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_city) : old('shipping_city') }}">
                      @error('shipping_city')
                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="shipping_state">{{ $keywords['State'] ?? __('State') }}* </label>
                      <input id="shipping_state" type="text" class="form-control" name="shipping_state"
                        placeholder="{{ $keywords['State'] ?? __('State') }}"
                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_state) : old('shipping_state') }}">
                      @error('shipping_state')
                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="country">{{ $keywords['Country'] ?? __('Country') }} </label>
                      <input id="shipping_country" type="text" class="form-control" name="shipping_country"
                        placeholder="{{ $keywords['Country'] ?? __('Country') }}"
                        value="{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_country) : old('shipping_country') }}">
                      @error('shipping_country')
                        <p class="text-danger mb-2">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="form-group mb-3">
                      <label for="shipping_address">{{ $keywords['Address'] ?? __('Address') }} *</label>
                      <textarea name="shipping_address" class="form-control max_height_100"
                        placeholder="{{ $keywords['Address'] ?? __('Address') }}">{{ Auth::guard('customer')->user() ? convertUtf8(Auth::guard('customer')->user()->shipping_address) : old('shipping_address') }}</textarea>
                      @error('shipping_address')
                        <p class="text-danger mt-2">{{ convertUtf8($message) }}</p>
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
                                  data-src="{{ asset('assets/user-front/images/placeholder.png') }}" alt="Product">
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
                            <span class="text-dark fw-medium">{{ $keywords['Item Price'] ?? __('Item Price') }} :</span>
                            <span>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $item['product_price']) }}</span>
                          </div>

                          @if ($item['variations'])
                            <div class="variation-area">
                              <h5 class="text-dark fw-bold mb-0">{{ $keywords['Variations'] ?? __('Variations') }}:</h5>
                              @foreach ($item['variations'] as $key => $variation)
                                @php
                                  //show variations name
                                  $vNameId = App\Models\User\ProductVariationContent::where(
                                      'product_variation_id',
                                      $variation['variation_id'],
                                  )
                                      ->pluck('variation_name')
                                      ->first();

                                  $variant_id = App\Models\VariantContent::where('id', $vNameId)
                                      ->pluck('variant_id')
                                      ->first();
                                  $variation_name = App\Models\VariantContent::where([
                                      ['variant_id', $variant_id],
                                      ['language_id', $userCurrentLang->id],
                                  ])
                                      ->pluck('name')
                                      ->first();

                                  //show variation options name
                                  $vOptionId = App\Models\User\ProductVariantOptionContent::where([
                                      ['language_id', $userCurrentLang->id],
                                      ['product_variant_option_id', $variation['option_id']],
                                  ])
                                      ->pluck('option_name')
                                      ->first();
                                  $vOptionName = App\Models\VariantOptionContent::where([
                                      ['language_id', $userCurrentLang->id],
                                      ['id', $vOptionId],
                                  ])
                                      ->pluck('option_name')
                                      ->first();
                                @endphp

                                <div class="variation-item">
                                  <span class="text-dark fw-medium">{{ $variation_name }} :</span>
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


            @if (!onlyDigitalItemsInCart() && sizeof($shippings) > 0)
              @if (count($shippings) > 0)
                <div class="col-12 mb-5">
                  <div class="order-summery form-block border radius-md">
                    <div class="shop-title-box">
                      <h3 class="pb-20 border-bottom">{{ $keywords['Shipping Methods'] ?? __('Shipping Methods') }}</h3>
                    </div>
                    <table class=" w-100">
                      <thead class="cart-header">
                        <tr class="height-50">
                          <th>#</th>
                          <th>{{ $keywords['Method'] ?? __('Method') }} *</th>
                          <th class="price">{{ $keywords['Cost'] ?? __('Cost') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($shippings as $key => $charge)
                          <tr>
                            <td>
                              <input type="radio" {{ $key == 0 ? 'checked' : '' }} name="shipping_charge"
                                {{ $cart == null ? 'disabled' : '' }}
                                data="{{ currency_converter_shipping($charge->charge, $charge->id) }}"
                                value="{{ $charge->id }}" class="shipping shipping-charge"
                                id="shipping-charge-{{ $charge->id }}">
                            </td>
                            <td>
                              <p class="mb-0">
                                <strong><label
                                    for="shipping-charge-{{ $charge->id }}">{{ convertUtf8($charge->title) }}</label></strong>
                              </p>
                              <p class="mb-0"><small><label
                                    for="shipping-charge-{{ $charge->id }}">{{ convertUtf8($charge->text) }}</label></small>
                              </p>
                            </td>
                            <td class="d-flex">
                              {{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
                              <span>{{ currency_converter_shipping($charge->charge, $charge->id) }}</span>
                              {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }}
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              @else
                @php
                  $hidden = 'hidden';
                @endphp
                <div class="col-12">
                  <input style="visibility: {{ $hidden }}" type="radio" checked name="shipping_charge"
                    {{ $cart == null ? 'disabled' : '' }} data="0" class="shipping-charge" value="0">
                </div>
              @endif
            @endif

            <div id="cartTotal">
              <div class="order-summary form-block border radius-md mb-30">
                <h3 class="pb-10 mb-20 border-bottom">{{ $keywords['Order Summary'] ?? __('Order Summary') }}</h3>
                <div class="sub-total d-flex justify-content-between mb-2">
                  <h5 class="mb-0">{{ $keywords['Cart Total'] ?? __('Cart Total') }}</h5>
                  <span class="price"><span data="cartTotal() }}"
                      class="price">{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, cartTotal()) }}</span>
                  </span>
                </div>
                <ul class="service-charge-list">
                  <li class="d-flex justify-content-between">

                    <h5 class="mb-0">{{ $keywords['Discount'] ?? __('Discount') }}</h5>
                    <span class="price"><span data="{{ $discount }}">
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

                  @if (!onlyDigitalItemsInCart() && sizeof($shippings) > 0)
                    @php
                      $scharge = round($shippings[0]->charge, 2);
                      if (count($shippings) > 0) {
                          $sh_id = $shippings[0]->id;
                      } else {
                          $sh_id = 0;
                      }
                    @endphp

                    <li class="d-flex justify-content-between">
                      <h5>{{ $keywords['Delivery Charge'] ?? __('Delivery Charge') }}</h5>
                      <span class="price">
                        <span data="{{ $sh_id > 0 ? currency_converter_shipping($scharge, $shippings[0]->id) : 0 }}"
                          class="shipping">
                          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $sh_id > 0 ? currency_converter_shipping($scharge, $shippings[0]->id) : 0) }}
                        </span>
                      </span>

                    </li>
                  @else
                    @php
                      $scharge = 0;
                    @endphp
                  @endif

                  <li class="d-flex justify-content-between">
                    <h5 class="mb-0">{{ $keywords['Tax'] ?? __('Tax') }}({{ $userShop->tax }}%)</h5>
                    <span class="price">
                      <span data-tax="{{ tax() }}"
                        id="tax">{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, tax()) }}</span>
                    </span>

                  </li>
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
                      {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, cartSubTotal() + ($sh_id > 0 ? currency_converter_shipping($scharge, $shippings[0]->id) : 0) + tax()) }}
                    </span>
                  </span>
                </div>
              </div>
            </div>

            @if (!session()->has('coupon'))
              <div class="form-inline mb-30">
                <div class="input-group radius-sm border">
                  <input class="form-control"
                    placeholder="{{ $keywords['Enter Coupon Code'] ?? __('Enter Coupon Code') }}" type="text"
                    name="coupon" autocomplete="off">
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
              <h3 class="mb-20">{{ $keywords['Payment Method'] ?? __('Payment Method') }}</h3>
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
                  type="submit">{{ $keywords['Place Order'] ?? __('Place Order') }} </button>
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

  <script src="{{ asset('assets/user-front/js/user-checkout.js') }}"></script>
@endsection
