<select name="payment_method" id="payment-gateway" class="olima_select form-control">
  <option value="" selected disabled>{{ $keywords['Select a payment gateway'] ?? __('Select a payment gateway') }}
  </option>
  @foreach ($payment_gateways as $payment)
    <option value="{{ $payment->name }}" {{ old('payment_method') == $payment->name ? 'selected' : '' }}>
      @php
        $gateway_name = str_replace('_', ' ', $payment->name);
      @endphp
      {{ $keywords[$gateway_name] ?? __($gateway_name) }}
    </option>
  @endforeach
  @foreach ($offlines as $offline)
    <option value="{{ $offline->name }}">
      {{ $keywords["$offline->name"] ?? __($offline->name) }}
    </option>
  @endforeach
</select>
@error('payment_method')
  <p class="text-danger">{{ $message }}</p>
@enderror



{{-- START: Stripe Card Details Form --}}
@php
  $d_none = 'none';
  $d_block = 'block';
@endphp
<div class="row gateway-details py-3" id="tab-stripe" style="display: {{ $d_none }}">
  <div class="col-md-12">
    <div id="stripe-element" class="mb-2">
      <!-- A Stripe Element will be inserted here. -->
    </div>
    <!-- Used to display form errors -->
    <div id="stripe-errors" class="text-danger pb-2" role="alert"></div>
  </div>
</div>
{{-- END: Stripe Card Details Form --}}

<div class="row mt-2 iyzico-element {{ old('payment_method') == 'Iyzico' ? '' : 'd-none' }}">
  <div class="col-lg-12">
    <div class="form_group mb-3">
      <input type="text" name="identity_number" class="form-control mb-2"
        placeholder="{{ $keywords['Identity Number'] ?? __('Identity Number') }}" value="{{ old('identity_number') }}">
      @error('identity_number')
        <p class="text-danger text-left">{{ $message }}</p>
      @enderror
    </div>
    <div class="form_group mb-3">
      <input type="text" name="zip_code" class="form-control mb-2"
        placeholder="{{ $keywords['Zip Code'] ?? __('Zip Code') }}" value="{{ old('zip_code') }}">
      @error('zip_code')
        <p class="text-danger text-left">{{ $message }}</p>
      @enderror
    </div>
  </div>
</div>


{{-- START: Authorize.net Card Details Form --}}
<div class="row gateway-details py-3" id="tab-anet"
  style="display: {{ old('payment_method') == 'Authorize.net' ? $d_block : $d_none }}">
  <div class="col-lg-6">
    <div class="form_group mb-3">
      <input class="form-control" type="text" id="anetCardNumber"
        placeholder="{{ $keywords['Card Number'] ?? __('Card Number') }}" disabled />
    </div>
  </div>
  <div class="col-lg-6 mb-3">
    <div class="form_group">
      <input class="form-control" type="text" id="anetExpMonth"
        placeholder="{{ $keywords['Expire Month'] ?? __('Expire Month') }}" disabled />
    </div>
  </div>
  <div class="col-lg-6 ">
    <div class="form_group">
      <input class="form-control" type="text" id="anetExpYear"
        placeholder="{{ $keywords['Expire Year'] ?? __('Expire Year') }}" disabled />
    </div>
  </div>
  <div class="col-lg-6 ">
    <div class="form_group">
      <input class="form-control" type="text" id="anetCardCode"
        placeholder="{{ $keywords['Card Code'] ?? __('Card Code') }}" disabled />
    </div>
  </div>
  <input type="hidden" name="opaqueDataValue" id="opaqueDataValue" disabled />
  <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor" disabled />
  <ul id="anetErrors"></ul>
</div>
{{-- END: Authorize.net Card Details Form --}}


@if ($errors->has('receipt'))
  <p class="text-danger mb-4">{{ $errors->first('receipt') }}</p>
@endif
{{-- End: Offline Gateways Area --}}
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="lc" value="UK">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="ref_id" id="ref_id" value="">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest">
<input type="hidden" name="currency_sign" value="$">
