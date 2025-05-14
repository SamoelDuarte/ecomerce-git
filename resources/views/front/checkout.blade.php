@extends('front.layout')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/front/css/checkout.css') }}">
@endsection

@section('pagename')
  - {{ $pageHeading ?? __('Checkout') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->checkout_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->checkout_meta_keywords : '')
@php
  $d_none = 'none';
  $d_block = 'block';
@endphp
@section('breadcrumb-title')
  {{ $pageHeading ?? __('Checkout') }}
@endsection
@section('breadcrumb-link')
  {{ $pageHeading ?? __('Checkout') }}
@endsection


@section('content')
  <!--====== Start saas_checkout ======-->
  <!-- Checkout Start -->
  <section class="checkout-area pt-90 pb-90">
    <div class="container">
      <form action="{{ route('front.membership.checkout') }}" method="POST" enctype="multipart/form-data"
        id="my-checkout-form">
        @csrf
        <div class="row">

          <div class="col-lg-6">
            <div class="billing-form form-block mb-30">
              <div class="title">
                <h3>{{ __('Billing Details') }}</h3>
              </div>
              <div class="row">
                {{-- @dd($data) --}}
                <input type="hidden" name="category" value="{{ $data['category'] }}">
                <input type="hidden" name="username" value="{{ $data['username'] }}">
                <input type="hidden" name="password" value="{{ $data['password'] }}">
                <input type="hidden" name="package_type" value="{{ $data['status'] }}">
                <input type="hidden" name="email" value="{{ $data['email'] }}">
                <input type="hidden" name="price"
                  value="{{ $data['status'] == 'trial' ? 0 : $data['package']->price }}">
                <input type="hidden" name="package_id" value="{{ $data['id'] }}">
                <input type="hidden" name="payment_method" id="payment" value="{{ old('payment_method') }}">
                <input type="hidden" name="trial_days" id="trial_days" value="{{ $data['package']->trial_days }}">
                <input type="hidden" name="start_date" value="{{ \Carbon\Carbon::today()->format('d-m-Y') }}">
                @if ($data['status'] === 'trial')
                  <input type="hidden" name="expire_date"
                    value="{{ \Carbon\Carbon::today()->addDay($data['package']->trial_days)->format('d-m-Y') }}">
                @else
                  @if ($data['package']->term === 'daily')
                    <input type="hidden" name="expire_date"
                      value="{{ \Carbon\Carbon::today()->addDay()->format('d-m-Y') }}">
                  @elseif($data['package']->term === 'weekly')
                    <input type="hidden" name="expire_date"
                      value="{{ \Carbon\Carbon::today()->addWeek()->format('d-m-Y') }}">
                  @elseif($data['package']->term === 'monthly')
                    <input type="hidden" name="expire_date"
                      value="{{ \Carbon\Carbon::today()->addMonth()->format('d-m-Y') }}">
                  @elseif($data['package']->term === 'lifetime')
                    <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::maxValue()->format('d-m-Y') }}">
                  @else
                    <input type="hidden" name="expire_date"
                      value="{{ \Carbon\Carbon::today()->addYear()->format('d-m-Y') }}">
                  @endif
                @endif

                <div class="col-lg-6">
                  <div class="form-group mb-30">
                    <label for="lastName">{{ __('Shop Name') }}*</label>
                    <input id="shop_name" type="text" class="form-control" name="shop_name"
                      placeholder="{{ __('Shop Name') }}" value="{{ old('shop_name') }}" required>
                    @if ($errors->has('shop_name'))
                      <p class="text-danger">{{ $errors->first('shop_name') }}</p>
                    @endif
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group mb-30">
                    <label for="phone">{{ __('Phone Number') }}*</label>
                    <input id="phone" type="number" class="form-control" name="phone"
                      placeholder="{{ __('Phone Number') }}" value="{{ old('phone') }}" required>
                    @if ($errors->has('phone'))
                      <p class="text-danger">{{ $errors->first('phone') }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group mb-30">
                    <label for="email">{{ __('Email Address') }}*</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ $data['email'] }}"
                      disabled>
                    @if ($errors->has('email'))
                      <p class="text-danger">{{ $errors->first('email') }}</p>
                    @endif
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group mb-30">
                    <label for="address">{{ __('Street Address') }}</label>
                    <input id="address" type="text" class="form-control" name="address"
                      placeholder="{{ __('Street Address') }}" value="{{ old('address') }}">
                    @if ($errors->has('address'))
                      <p class="text-danger">
                        {{ $errors->first('address') }}
                        </span>
                    @endif
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group mb-30">
                    <label for="city">{{ __('City') }} *</label>
                    <input id="city" type="text" class="form-control" name="city"
                      placeholder="{{ __('City') }}" value="{{ old('city') }}" required>
                    @if ($errors->has('city'))
                      <p class="text-danger">{{ $errors->first('city') }}</p>
                    @endif
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group mb-30">
                    <label for="district">{{ __('State') }}</label>
                    <input id="district" type="text" class="form-control" name="district"
                      placeholder="{{ __('State') }}" value="{{ old('district') }}">
                    @if ($errors->has('district'))
                      <p class="text-danger">
                        {{ $errors->first('district') }}
                      </p>
                    @endif
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group mb-30">
                    <label for="post_code">{{ __('Postcode/Zip') }}</label>
                    <input id="post_code" type="text" class="form-control" name="post_code"
                      placeholder="{{ __('Post Code') }}" value="{{ old('post_code') }}">
                    @if ($errors->has('post_code'))
                      <p class="text-danger">
                        {{ $errors->first('post_code') }}
                      </p>
                    @endif
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group mb-30">
                    <label for="country">{{ __('Country') }} *</label>
                    <input id="country" type="text" class="form-control" name="country"
                      placeholder="{{ __('Country') }}" value="{{ old('country') }}" required>
                    @if ($errors->has('country'))
                      <span class="error">
                        <strong>{{ $errors->first('country') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="order-summery form-block mb-30">
              <div class="title">
                <h3>{{ __('Package Summary') }}</h3>
              </div>
              <div class="order-list-info">
                <ul class="summery-list">
                  <li>{{ __('Package') }} <span>{{ $data['package']->title }}</span></li>
                  <li>{{ __('Start Date') }} <span>{{ \Carbon\Carbon::today()->format('d-m-Y') }}</span>
                  </li>
                  @if ($data['status'] === 'trial')
                    <li>
                      {{ __('Expiry Date') }}
                      <span>
                        {{ \Carbon\Carbon::today()->addDay($data['package']->trial_days)->format('d-m-Y') }}
                      </span>
                    </li>
                  @else
                    <li>
                      {{ __('Expiry Date') }}
                      <span>
                        @if ($data['package']->term === 'daily')
                          {{ \Carbon\Carbon::today()->addDay()->format('d-m-Y') }}
                        @elseif($data['package']->term === 'weekly')
                          {{ \Carbon\Carbon::today()->addWeek()->format('d-m-Y') }}
                        @elseif($data['package']->term === 'monthly')
                          {{ \Carbon\Carbon::today()->addMonth()->format('d-m-Y') }}
                        @elseif($data['package']->term === 'lifetime')
                          {{ __('Lifetime') }}
                        @else
                          {{ \Carbon\Carbon::today()->addYear()->format('d-m-Y') }}
                        @endif
                      </span>
                    </li>
                  @endif
                </ul>
              </div>
              <div class="order-price">
                <ul class="summery-list">
                  <li>{{ __('Total') }}
                    <span class="price">
                      @if ($data['status'] === 'trial')
                        {{ __('Free') }} ({{ $data['package']->trial_days . ' days' }})
                      @elseif($data['package']->price == 0)
                        {{ __('Free') }}
                      @else
                        {{ format_price($data['package']->price) }}
                      @endif
                    </span>
                  </li>
                </ul>
              </div>
            </div>
            @if ($data['package']->price == 0 || $data['status'] == 'trial')
            @else
              <div class="order-payment form-block mb-30">
                <div class="title">
                  <h3>{{ __('Payment Method') }}</h3>
                </div>
                <div class="form-group mb-30">
                  <select id="payment-gateway" name="payment_method">
                    <option value="" selected="" disabled="">
                      {{ __('Choose an option') }}</option>
                    @foreach ($data['payment_methods'] as $payment_method)
                      <option value="{{ $payment_method->name }}"
                        {{ old('payment_method') == $payment_method->name ? 'selected' : '' }}>
                        {{ __($payment_method->name) }}
                      </option>
                    @endforeach
                  </select>
                  @if ($errors->has('payment_method'))
                    <p class="text-danger">
                      <strong>{{ $errors->first('payment_method') }}</strong>
                    </p>
                  @endif
                </div>

                <div class="iyzico-element {{ old('payment_method') == 'Iyzico' ? '' : 'd-none' }}">
                  <input type="text" name="identity_number" class="form-control mb-2"
                    placeholder="{{ __('Identity Number') }}" value="{{ old('identity_number') }}">
                  @error('identity_number')
                    <p class="text-danger text-left">{{ $message }}</p>
                  @enderror
                </div>

                <div class="row gateway-details py-3" id="tab-stripe"
                  style="display: {{ old('payment_method') == 'Stripe' ? $d_block : $d_none }};">

                  <div class="col-md-12">
                    <div id="stripe-element" class="mb-2">
                      <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <!-- Used to display form errors -->
                    <div id="stripe-errors" class="text-danger pb-2" role="alert"></div>
                  </div>
                </div>
              </div>
            @endif
            {{-- END: Stripe Card Details Form --}}

            {{-- START: Authorize.net Card Details Form --}}
            <div class="row gateway-details py-3" id="tab-anet"
              style="display: {{ old('payment_method') == 'Authorize.net' ? $d_block : $d_none }}">
              <div class="col-lg-6">
                <div class="form_group mb-3">
                  <input class="form-control" type="text" id="anetCardNumber"
                    placeholder="{{ __('Card Number') }}" disabled />
                </div>
              </div>
              <div class="col-lg-6 mb-3">
                <div class="form_group">
                  <input class="form-control" type="text" id="anetExpMonth" placeholder="{{ __('Expire Month') }}"
                    disabled />
                </div>
              </div>
              <div class="col-lg-6 ">
                <div class="form_group">
                  <input class="form-control" type="text" id="anetExpYear" placeholder="{{ __('Expire Year') }}"
                    disabled />
                </div>
              </div>
              <div class="col-lg-6 ">
                <div class="form_group">
                  <input class="form-control" type="text" id="anetCardCode" placeholder="{{ __('Card Code') }}"
                    disabled />
                </div>
              </div>
              <input type="hidden" name="opaqueDataValue" id="opaqueDataValue" disabled />
              <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor" disabled />
              @php
                $d_none = 'none';
              @endphp
              <ul id="anetErrors" style="display: {{ $d_none }}"></ul>
            </div>
            {{-- END: Authorize.net Card Details Form --}}

            {{-- START: Offline Gateways Information & Receipt Area --}}
            <div>
              <div id="instructions"></div>
              <input type="hidden" name="is_receipt" value="0" id="is_receipt">
              @if ($errors->has('receipt'))
                <span class="error">
                  <strong>{{ $errors->first('receipt') }}</strong>
                </span>
              @endif
            </div>
            {{-- END: Offline Gateways Information & Receipt Area --}}

            <div class="text-center">
              <button id="confirmBtn" form="my-checkout-form" class="btn primary-btn w-100"
                type="submit">{{ __('Confirm') }} </button>
            </div>
          </div>
        </div>
    </div>
    </form>
    </div>
  </section>
  <!-- Checkout End -->

  <!--====== End saas_checkout ======-->
@endsection

@section('scripts')
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    @php
      $stripe = App\Models\PaymentGateway::where('keyword', 'stripe')->first();
      $stripe_info = $stripe->information ? $stripe->convertAutoData() : [];

      $anet = App\Models\PaymentGateway::find(20);
      $anerInfo = $anet ? $anet->convertAutoData() : [];

      $anetTest = $anerInfo['sandbox_check'] ?? 0;
      $anetSrc = $anetTest == 1 ? 'https://jstest.authorize.net/v1/Accept.js' : 'https://js.authorize.net/v1/Accept.js';

      $authorize_public_key = $anerInfo['public_key'] ?? '';
      $authorize_login_id = $anerInfo['login_id'] ?? '';
      $stripe_key = $stripe_info['key'] ?? '';
    @endphp

    let stripe_key = "{{ $stripe_key }}";
    let offline = {!! json_encode($data['offline']) !!};
    var instruction_url = "{{ route('front.payment.instructions') }}";
    var processing_text = "{{ __('Processing') }}";
    var confirm_text = "{{ __('Confirm') }}";
  </script>

  {{-- START: Authorize.net Scripts --}}

  <script type="text/javascript" src="{{ $anetSrc }}" charset="utf-8"></script>
  <script src="{{ asset('assets/front/js/membership-checkout.js') }}"></script>
  <script type="text/javascript"></script>
  {{-- END: Authorize.net Scripts --}}
@endsection
