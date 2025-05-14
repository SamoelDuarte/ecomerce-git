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
    $keywords = App\Http\Helpers\Common::get_keywords($order->user_id);
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
      background: rgba({{ hexToRgba($userBs->base_color) }}, 0.2);
      padding: 20px 25px;
    }

    .tm_invoice_info_table {
      background: rgba({{ hexToRgba($userBs->base_color) }}, 0.2);
    }

    .package-info-table thead {
      background: #{{ $userBs->base_color }};
    }

    .bg-primary {
      background: #{{ $userBs->base_color }};
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
              @if ($userBs->logo)
                <img src="{{ asset('/assets/front/img/user/' . $userBs->logo) }}" height="40"
                  class="d-inline-block ">
              @else
                <img src="{{ asset('assets/admin/img/noimage.jpg') }}" height="40" class="d-inline-block">
              @endif
            </div>
            <div class="text-right strong invoice-heading float-right">{{ $keywords['INVOICE'] ?? __('INVOICE') }}
            </div>

          </div>

          <div class="px-25 mb-15 clearfix tm_invoice_info_table">
            <table>
              <tbody>
                <tr>
                  <td>
                    <span><b> {{ $keywords['Payment Method'] ?? __('Payment Method') }}: </b>
                      {{ $keywords[$order->method] ?? $order->method }}</span>
                  </td>
                  <td>
                    <span><b> {{ $keywords['Invoice No'] ?? __('Invoice No') }}:</b> #{{ $order->order_number }}
                    </span>
                  </td>
                  <td class="text-right">
                    <span><b> {{ $keywords['Date'] ?? __('Date') }}:</b>
                      {{ \Illuminate\Support\Carbon::now()->format('jS, M Y') }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="header clearfix px-25 mb-15">
            <div class="text-left float-left">
              <div class="strong">{{ $keywords['Bill to'] ?? __('Bill to') }}:</div>
              <div class="small">
                <strong>{{ $keywords['Name'] ?? __('Name') }}: </strong>
                <span class="{{ detectTextDirection($order->billing_fname . ' ' . $order->billing_lname) }}"
                  dir="{{ detectTextDirection($order->billing_fname . ' ' . $order->billing_lname) }}">{{ ucfirst($order->billing_fname) }}
                  {{ ucfirst($order->billing_lname) }}</span>
              </div>

              <div class="small">
                <strong>{{ $keywords['Address'] ?? __('Address') }}: </strong>
                <span class="{{ detectTextDirection($order->billing_address) }}"
                  dir="{{ detectTextDirection($order->billing_address) }}">{{ $order->billing_address }}</span>
              </div>

              <div class="small">
                <strong>{{ $keywords['City'] ?? __('City') }}: </strong>
                <span class="{{ detectTextDirection($order->billing_city . ' ' . $order->billing_state) }}"
                  dir="{{ detectTextDirection($order->billing_city . ' ' . $order->billing_state) }}">{{ $order->billing_city }},
                  {{ $order->billing_state }}</span>
              </div>

              <div class="small">
                <strong>{{ $keywords['Country'] ?? __('Country') }}: </strong>
                <span class="{{ detectTextDirection($order->billing_country) }}"
                  dir="{{ detectTextDirection($order->billing_country) }}">{{ $order->billing_country }}</span>
              </div>

              <div class="small">
                <strong>{{ $keywords['Email'] ?? __('Email') }}: </strong>
                <span class="{{ detectTextDirection($order->billing_email) }}"
                  dir="{{ detectTextDirection($order->billing_email) }}">{{ $order->billing_email }}</span>
              </div>

            </div>
            <div class="order-details float-right">
              <div class="text-right">

                <div class="strong">{{ $keywords['Order Details'] ?? __('Order Details') }}:</div>
                @if (!is_null($order->discount))
                  <div class="small"><strong>{{ $keywords['Discount'] ?? __('Discount') }}: </strong>
                    {{ currencyTextPrice($order->currency_id, $order->discount) }} </div>
                @endif

                <div class=" small"><strong>{{ $keywords['Tax'] ?? __('Tax') }}: </strong>
                  {{ currencyTextPrice($order->currency_id, $order->tax) }} </div>

                <div class=" small"><strong>{{ $keywords['Paid Amount'] ?? __('Paid Amount') }}: </strong>
                  {{ currencyTextPrice($order->currency_id, $order->total) }} </div>

                <div class="small">
                  <strong>{{ $keywords['Payment Status'] ?? __('Payment Status') }}:
                  </strong>{{ $keywords[$order->payment_status] ?? $order->payment_status }}
                </div>

                <div class="small">
                  <strong>{{ $keywords['Order Status'] ?? __('Order Status') }}:
                  </strong>{{ $keywords[ucfirst($order->order_status)] ?? ucfirst($order->order_status) }}
                </div>
              </div>
            </div>
          </div>

          <div class="package-info px-25">
            <table class="text-left package-info-table">
              <thead>
                <tr>
                  <td class="text-center text-white small">
                    <strong>{{ $keywords['Title'] ?? __('Title') }}</strong>
                  </td>
                  <td class="tm_border_left text-center text-white small">
                    <strong> {{ $keywords['Quantity'] ?? __('Quantity') }}</strong>
                  </td>
                  <td class="tm_border_left text-center text-white small">
                    <strong> {{ $keywords['Price'] ?? __('Price') }}</strong>
                  </td>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->orderitems as $item)
                  <tr>
                    <td>
                      <span class="{{ detectTextDirection($item->title) }}"
                        dir="{{ detectTextDirection($item->title) }}">{{ $item->title }}</span>
                    </td>
                    <td class="tm_border_left text-center">{{ $item->qty }}</td>
                    <td class="tm_border_left text-right">
                      {{ currencyTextPrice($order->currency_id, round($item->price, 2)) }}
                      <br>
                      @php
                        $variations = json_decode($item->variations);
                      @endphp

                      @if (!is_null($variations))
                        @foreach ($variations as $k => $vitm)
                          @php
                            $name = isset($vitm->name) ? $vitm->name : '';
                            $key = is_string($k) ? $k : '';
                          @endphp
                          <span class="{{ detectTextDirection($name) }}" dir="{{ detectTextDirection($name) }}">
                            {{ $name }} <small class="{{ detectTextDirection($key) }}"
                              dir="{{ detectTextDirection($key) }}">({{ $key }})</small> :
                            {{ currencyTextPrice($order->currency_id, round($vitm->price, 2)) }}</span>
                        @endforeach
                      @endif

                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="tm_invoice_footer clearfix px-25">

            <div class="tm_right_footer float-right">
              <table>
                <tbody>
                  <tr>
                    <td class="fw-bold">{{ $keywords['Subtotal'] ?? __('Subtotal') }}</td>
                    <td class="text-right fw-bold"> {{ currencyTextPrice($order->currency_id, $order->cart_total) }}
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-bold">{{ $keywords['Tax'] ?? __('Tax') }}</td>
                    <td class="text-right fw-bold">{{ currencyTextPrice($order->currency_id, $order->tax) }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">{{ $keywords['Shipping Charge'] ?? __('Shipping Charge') }}</td>
                    <td class="text-right fw-bold">
                      {{ currencyTextPrice($order->currency_id, $order->shipping_charge) }}
                    </td>
                  </tr>
                  <tr class="bg-primary paid-tr">
                    <td class="fw-bold text-white">{{ $keywords['Paid Amount'] ?? __('Paid Amount') }}</td>
                    <td class="text-right fw-bold text-white">
                      {{ currencyTextPrice($order->currency_id, $order->total) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>

          <div class="mt-50">
            <div class="text-right regards">{{ $keywords['Thanks & Regards'] ?? __('Thanks & Regards') }},</div>
            <div class="text-right strong regards">
              @php
                $user = \App\Models\User::where('id', $order->user_id)->first();
                $website_title = $user->shop_name ?? $user->username;
              @endphp
              <span class="{{ detectTextDirection($website_title) }}"
                dir="{{ detectTextDirection($website_title) }}">{{ $website_title }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</body>

</html>
