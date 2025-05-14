<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Invoice') }}</title>
  <link rel="stylesheet" href="{{ asset('assets/front/css/design-pdf.css') }}">
  @php
    $font_family = 'DejaVu Sans, serif';

    $color = '#333542';
    $rtl = 'rtl';
    $unicode_bidi = 'bidi-override';
    $di_block = 'inline-block';
    $w_60 = '60%';
    $w_10 = '10%';
    $w_30 = '30%';
    $w_80 = '80%';
    $w_20 = '20%';
    $w_45 = '45%';
  @endphp
  <style>
    body {
      font-family: "{{ $font_family }}" !important;
    }

    .rtl {
      unicode-bidi: "{{ $unicode_bidi }}" !important;
      direction: "{{ $rtl }}" !important;
    }

    span {
      display: "{{ $di_block }}"
    }

    .w_50 {
      width: "{{ $w_60 }}" !important;
    }

    .w_10 {
      width: "{{ $w_10 }}" !important;
    }

    .w_40 {
      width: "{{ $w_30 }}" !important;
    }

    .w_80 {
      width: "{{ $w_80 }}";
    }

    .w-20 {
      width: "{{ $w_20 }}";
    }

    .w_45 {
      width: "{{ $w_45 }}";
    }


    .invoice-header {
      background: rgba({{ hexToRgba($bs->base_color) }}, 0.2);
      padding: 20px 25px;
    }

    .tm_invoice_info_table {
      background: rgba({{ hexToRgba($bs->base_color) }}, 0.2);
    }

    .package-info-table thead {
      background: #{{ $bs->base_color }};
    }

    .bg-primary {
      background: #{{ $bs->base_color }};
    }
  </style>
</head>

<body>
  <div class="main">
    <div class="invoice-container">
      <div class="invoice-wrapper">
        <div class="invoice-area pb-30">

          <div class="invoice-header clearfix mb-15 px-25">

            <div class="float-left">
              @if ($bs->logo)
                <img src="{{ asset('assets/front/img/' . $bs->logo) }}" height="40" class="d-inline-block ">
              @else
                <img src="{{ asset('assets/admin/img/noimage.jpg') }}" height="40" class="d-inline-block">
              @endif
            </div>
            <div class="text-right strong invoice-heading float-right">{{ __('INVOICE') }}</div>

          </div>

          <div class="px-25 mb-15 clearfix tm_invoice_info_table">
            <table class=" ">
              <tbody>
                <tr>
                  <td>
                    <span><b> {{ __('Payment Method') }}: </b> {{ $request['payment_method'] }}</span>
                  </td>
                  <td>
                    <span><b> {{ __('Order No') }}:</b> #{{ $order_id }} </span>
                  </td>
                  <td class="text-right">
                    <span><b> {{ __('Date') }}:</b>
                      {{ \Illuminate\Support\Carbon::now()->format('jS, M Y') }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="header clearfix px-25 mb-15">
            <div class="text-left  float-left">
              <div class="strong">{{ __('Bill to') }}:</div>
              <div class="small">

                <strong>{{ __('Name') }}: </strong>
                <span class="{{ detectTextDirection($member['shop_name']) }}"
                  dir="{{ detectTextDirection($member['shop_name']) }}">
                  {{ $member['shop_name'] }}</span>
              </div>

              <div class="small">
                <strong>{{ __('Username') }}: </strong>
                <span class="{{ detectTextDirection($member['username']) }}"
                  dir="{{ detectTextDirection($member['username']) }}">{{ $member['username'] }}</span>
              </div>

              <div class="small">
                <strong>{{ __('Email') }}: </strong>
                <span class="{{ detectTextDirection($member['email']) }}"
                  dir="{{ detectTextDirection($member['email']) }}">{{ $member['email'] }}</span>
              </div>

              @if ($phone)
                <div class="small">
                  <strong>{{ __('Phone') }}: </strong>
                  <span class="{{ detectTextDirection($phone) }}"
                    dir="{{ detectTextDirection($phone) }}">{{ $phone }}</span>
                </div>
              @endif

            </div>
            <div class="order-details float-right">
              <div class="text-right">

                <div class="strong">{{ __('Order Details') }}:</div>
                <div class=" small"><strong>{{ __('Order Id') }}: </strong>
                  #{{ $order_id }} </div>

                <div class=" small"><strong>{{ __('Order Price') }}: </strong>
                  {{ $amount == 0 ? 'Free' : textPrice($base_currency_text_position, $base_currency_text, $amount) }}
                </div>

                <div class="small">
                  <strong>{{ __('Payment Status') }}: </strong> {{ $status == 2 ? __('Rejected') : __('Completed') }}
                </div>
              </div>
            </div>
          </div>

          <div class="package-info px-25">
            <table class="text-left package-info-table">
              <thead>
                <tr>
                  <td class="text-center text-white small">{{ __('Package Title') }}</td>
                  <td class="tm_border_left text-white small text-center">{{ __('Start Date') }}</td>
                  <td class="tm_border_left text-white small text-center">{{ __('Expire Date') }}</td>
                  <td class="tm_border_left text-white small text-center">{{ __('Currency') }}</td>
                  <td class="tm_border_left text-white small text-center">{{ __('Price') }}</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <span class="{{ detectTextDirection($package_title) }}"
                      dir="{{ detectTextDirection($package_title) }}">{{ $package_title }}</span>
                  </td>

                  <td class="tm_border_left text-center">{{ $request['start_date'] }}</td>

                  <td class="tm_border_left text-center">
                    {{ \Carbon\Carbon::parse($request['expire_date'])->format('Y') == '9999' ? 'Lifetime' : $request['expire_date'] }}
                  </td>

                  <td class="tm_border_left text-center">{{ $base_currency_text }}</td>
                  <td class="tm_border_left text-center">
                    {{ $amount == 0 ? 'Free' : textPrice($base_currency_text_position, $base_currency_text, $amount) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-50">
            <div class="text-right regards">{{ __('Thanks & Regards') }},</div>
            <div class="text-right strong regards">
              <span class="{{ detectTextDirection($bs->website_title) }}"
                dir="{{ detectTextDirection($bs->website_title) }}">{{ $bs->website_title }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</body>

</html>
