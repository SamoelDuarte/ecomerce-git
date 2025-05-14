@extends('user.layout')

@section('content')
  @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <strong>{{ $message }}</strong>
    </div>
  @endif
  @if (!empty($membership) && ($membership->package->term == 'lifetime' || $membership->is_trial == 1))
    <div class="alert bg-warning alert-warning text-white text-center">
      <h3>{{ __('If you purchase this package') }} <strong class="text-dark">({{ $checkout_package->title }})</strong>,
        {{ __('then your current package') }} <strong class="text-dark">({{ $membership->package->title }}
          @if ($membership->is_trial == 1)
            <span class="badge badge-secondary">{{ __('Trial') }}</span>
          @endif)
        </strong> {{ __('will be replaced immediately') }}</h3>
    </div>
  @endif
  <div class="row justify-content-center align-items-center mb-1">
    <div class="col-md-1 pl-md-0">
    </div>

    <div class="col-md-6 pl-md-0 pr-md-0">
      <div class="card card-pricing card-pricing-focus card-secondary">
        <form id="my-checkout-form" action="{{ route('user.plan.checkout') }}" method="post"
          enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="package_id" value="{{ $checkout_package->id }}">
          <input type="hidden" name="user_id" value="{{ auth()->id() }}">
          <input type="hidden" name="payment_method" id="payment" value="{{ old('payment_method') }}">
          <div class="card-header">
            <h4 class="card-title">{{ __($checkout_package->title) }}</h4>
            <div class="card-price">
              <span
                class="price">{{ $checkout_package->price == 0 ? __('Free') : format_price($checkout_package->price) }}</span>
              <span class="text">/{{ __($checkout_package->term) }}</span>
            </div>
          </div>
          <div class="card-body">
            <ul class="specification-list">
              <li>
                <span class="name-specification">{{ __('Membership') }}</span>
                <span class="status-specification">{{ __('Yes') }}</span>
              </li>
              <li>
                <span class="name-specification">{{ __('Start Date') }}</span>
                @if (
                    (!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                        (!empty($membership) && $membership->is_trial == 1))
                  <input type="hidden" name="start_date" value="{{ \Carbon\Carbon::today()->format('d-m-Y') }}">
                  <span class="status-specification">{{ \Carbon\Carbon::today()->format('d-m-Y') }}</span>
                @else
                  <input type="hidden" name="start_date"
                    value="{{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d-m-Y') }}">
                  <span
                    class="status-specification">{{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d-m-Y') }}</span>
                @endif
              </li>
              <li>
                <span class="name-specification">{{ __('Expire Date') }}</span>
                <span class="status-specification">
                  @if ($checkout_package->term == 'monthly')
                    @if (
                        (!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                            (!empty($membership) && $membership->is_trial == 1))
                      {{ \Carbon\Carbon::parse(now())->addMonth()->format('d-m-Y') }}
                      <input type="hidden" name="expire_date"
                        value="{{ \Carbon\Carbon::parse(now())->addMonth()->format('d-m-Y') }}">
                    @else
                      {{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->addMonth()->format('d-m-Y') }}
                      <input type="hidden" name="expire_date"
                        value="{{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->addMonth()->format('d-m-Y') }}">
                    @endif
                  @elseif($checkout_package->term == 'lifetime')
                    {{ __('Lifetime') }}
                    <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::maxValue()->format('d-m-Y') }}">
                  @else
                    @if (
                        (!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                            (!empty($membership) && $membership->is_trial == 1))
                      {{ \Carbon\Carbon::parse(now())->addYear()->format('d-m-Y') }}
                      <input type="hidden" name="expire_date"
                        value="{{ \Carbon\Carbon::parse(now())->addYear()->format('d-m-Y') }}">
                    @else
                      {{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->addYear()->format('d-m-Y') }}
                      <input type="hidden" name="expire_date"
                        value="{{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->addYear()->format('d-m-Y') }}">
                    @endif
                  @endif
                </span>
              </li>
              <li>
                <span class="name-specification">{{ __('Total Cost') }}</span>
                <input type="hidden" name="price" value="{{ $checkout_package->price }}">
                <span class="status-specification">
                  {{ $checkout_package->price == 0 ? __('Free') : format_price($checkout_package->price) }}
                </span>
              </li>
              @if ($checkout_package->price != 0)
                <li>
                  <div class="form-group px-0">
                    <label class="text-white">{{ __('Payment Method') }} <span class="text-danger">**</span></label>
                    <select name="payment_method" class="form-control input-solid" id="payment-gateway" required>
                      <option value="" disabled selected>
                        {{ __('Select a Payment Method') }}
                      </option>
                      @foreach ($payment_methods as $payment_method)
                        <option value="{{ $payment_method->name }}"
                          {{ old('payment_method') == $payment_method->name ? 'selected' : '' }}>
                          {{ __($payment_method->name) }}</option>
                      @endforeach
                    </select>
                  </div>
                </li>
              @endif


              <div class="iyzico-element {{ old('payment_method') == 'Iyzico' ? '' : 'd-none' }} row">
                <div class="col-md-6">
                  <input type="text" name="zip_code" class="form-control mb-2" placeholder="{{ __('Zip Code') }} "
                    value="{{ old('zip_code') }}">
                  @error('zip_code')
                    <p class="text-danger text-left">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-6">
                  <input type="text" name="identity_number" class="form-control mb-2"
                    placeholder="{{ __('Identity Number') }}" value="{{ old('identity_number') }}">
                  @error('identity_number')
                    <p class="text-danger text-left">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-6">
                  <input type="text" name="address" class="form-control mb-2"
                    placeholder="{{ __('Address') }}" value="{{ old('address') }}">
                  @error('address')
                    <p class="text-danger text-left">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-6">
                  <input type="text" name="city" class="form-control mb-2"
                    placeholder="{{ __('City') }}" value="{{ old('city') }}">
                  @error('city')
                    <p class="text-danger text-left">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-6">
                  <input type="text" name="country" class="form-control mb-2"
                    placeholder="{{ __('Country') }}" value="{{ old('country') }}">
                  @error('country')
                    <p class="text-danger text-left">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-6">
                  <input type="text" name="phone" class="form-control mb-2"
                    placeholder="{{ __('Phone') }}" value="{{ old('phone') }}">
                  @error('phone')
                    <p class="text-danger text-left">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              @php
                $d_none = 'none';
              @endphp
              <div class="row gateway-details pt-3 text-left" id="tab-stripe" style="display: {{ $d_none }}">

                <div class="col-md-12">
                  <div id="stripe-element" class="mb-2">
                    <!-- A Stripe Element will be inserted here. -->
                  </div>
                  <!-- Used to display form errors -->
                  <div id="stripe-errors" class="text-danger pb-2" role="alert"></div>
                </div>
              </div>

              {{-- START: Authorize.net Card Details Form --}}
              <div class="row gateway-details pt-3" id="tab-anet" style="display: {{ $d_none }}">
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <input class="form-control" type="text" id="anetCardNumber"
                      placeholder="{{ __('Card Number') }}" disabled />
                  </div>
                </div>
                <div class="col-lg-6 mb-3">
                  <div class="form-group">
                    <input class="form-control" type="text" id="anetExpMonth"
                      placeholder="{{ __('Expire Month') }}" disabled />
                  </div>
                </div>
                <div class="col-lg-6 ">
                  <div class="form-group">
                    <input class="form-control" type="text" id="anetExpYear" placeholder="{{ __('Expire Year') }}"
                      disabled />
                  </div>
                </div>
                <div class="col-lg-6 ">
                  <div class="form-group">
                    <input class="form-control" type="text" id="anetCardCode" placeholder="{{ __('Card Code') }}"
                      disabled />
                  </div>
                </div>
                <input type="hidden" name="opaqueDataValue" id="opaqueDataValue" disabled />
                <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor" disabled />
                <ul id="anetErrors" style="display: {{ $d_none }}"></ul>
              </div>
              {{-- END: Authorize.net Card Details Form --}}

              <div id="instructions" class="text-left"></div>
              <input type="hidden" name="is_receipt" value="0" id="is_receipt">
            </ul>

          </div>
          <div class="card-footer">
            <button class="btn btn-light btn-block" type="submit"><b>{{ __('Checkout Now') }}</b></button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-1 pr-md-0"></div>
  </div>
@endsection

@section('scripts')
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    'use strict';
    let offline = @php echo json_encode($offline) @endphp;
    let instruction_url = "{{ route('front.payment.instructions') }}";
    @php
      $stripe = App\Models\PaymentGateway::where('keyword', 'stripe')->first();
      $stripe_info = $stripe->convertAutoData();
      $anet = App\Models\PaymentGateway::find(20);
      $anerInfo = $anet->convertAutoData();
      $anetTest = $anerInfo['sandbox_check'];

      if ($anetTest == 1) {
          $anetSrc = 'https://jstest.authorize.net/v1/Accept.js';
      } else {
          $anetSrc = 'https://js.authorize.net/v1/Accept.js';
      }
    @endphp
    let stripe_key = "{{ $stripe_info['key'] }}";
    let authorize_public_key = "{{ $anerInfo['public_key'] }}";
    let authorize_login_id = "{{ $anerInfo['login_id'] }}";
  </script>

  <script type="text/javascript" src="{{ $anetSrc }}" charset="utf-8"></script>
  <script src="{{ asset('assets/front/js/membership-checkout.js') }}"></script>
@endsection
