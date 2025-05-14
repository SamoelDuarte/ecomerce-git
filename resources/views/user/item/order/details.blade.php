@extends('user.layout')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Order Details') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('user-dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ url()->previous() }}">{{ __('All Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Order Details') }}</a>
      </li>
    </ul>
    <a href="{{ route('user.all.item.orders') }}" class="btn-md btn btn-primary ml-auto">{{ __('Back') }}</a>
  </div>



  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Order') }} [ {{ $order->order_number }} ]
          </div>
        </div>
        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Payment Status') . ':' }}</strong>
              </div>
              <div class="col-lg-6">
                @if ($order->payment_status == 'Pending' || $order->payment_status == 'pending')
                  <span class="badge badge-warning">{{ convertUtf8($order->payment_status) }} </span>
                @elseif ($order->payment_status == 'Completed')
                  <span class="badge badge-success">{{ convertUtf8($order->payment_status) }} </span>
                @elseif ($order->payment_status == 'Rejected')
                  <span class="badge badge-danger">{{ convertUtf8($order->payment_status) }} </span>
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Order Status') . ':' }}</strong>
              </div>
              <div class="col-lg-6">
                @if ($order->order_status == 'pending')
                  <span class="badge badge-warning">{{ ucfirst($order->order_status) }} </span>
                @elseif ($order->order_status == 'processing')
                  <span class="badge badge-primary">{{ ucfirst($order->order_status) }} </span>
                @elseif ($order->order_status == 'completed')
                  <span class="badge badge-success">{{ ucfirst($order->order_status) }} </span>
                @elseif ($order->order_status == 'rejected')
                  <span class="badge badge-danger">{{ ucfirst($order->order_status) }} </span>
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Shipping Method') . ':' }}</strong>
              </div>
              <div class="col-lg-6">
                {{ $order->shipping_method }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Cart Total') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ textPrice($order->currency_text_position, $order->currency_code, $order->cart_total) }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong class="text-success">{{ __('Discount') }}
                  <span class="font-10">(<i class="fas fa-minus"></i>)</span> :</strong>
              </div>
              <div class="col-lg-6">
                @if (!empty($order->discount))
                  {{ textPrice($order->currency_text_position, $order->currency_code, $order->discount) }}
                @else
                  {{ textPrice($order->currency_text_position, $order->currency_code, 0) }}
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Subtotal') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ textPrice($order->currency_text_position, $order->currency_code, round($order->cart_total - $order->discount, 2)) }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong class="text-danger">{{ __('Shipping Charge') }}
                  <span class="">(<i class="fas fa-plus font-10"></i>)</span> :</strong>
              </div>
              <div class="col-lg-6">
                @if (!empty($order->shipping_charge))
                  {{ textPrice($order->currency_text_position, $order->currency_code, $order->shipping_charge) }}
                @else
                  {{ textPrice($order->currency_text_position, $order->currency_code, 0) }}
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong class="text-danger">{{ __('Tax') }}
                  ({{ is_null($order->tax_percentage) ? 0 : $order->tax_percentage }}%)
                  <span class="">(<i class="fas fa-plus font-10"></i>)</span> :</strong>
              </div>
              <div class="col-lg-6">
                @if (!empty($order->tax))
                  {{ textPrice($order->currency_text_position, $order->currency_code, $order->tax) }}
                @else
                  {{ textPrice($order->currency_text_position, $order->currency_code, 0) }}
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Total') }} :</strong>
              </div>
              <div class="col-lg-6">
                @if (!empty($order->total))
                  {{ textPrice($order->currency_text_position, $order->currency_code, $order->total) }}
                @else
                  {{ textPrice($order->currency_text_position, $order->currency_code, 0) }}
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Payment Method') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->method) }}
              </div>
            </div>


            <div class="row mb-0">
              <div class="col-lg-6">
                <strong>{{ __('Order Date') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->created_at->format('jS, M Y')) }}
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Shipping Details') }}</div>

        </div>
        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Name') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->shipping_fname . ' ' . $order->shipping_lname) }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Email') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->shipping_email) }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Phone') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ $order->shipping_number }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('City') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->shipping_city) }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('State') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ !is_null($order->shipping_state) ? convertUtf8($order->shipping_state) : '-' }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Country') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->shipping_country) }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Address') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->shipping_address) }}
              </div>
            </div>


          </div>
        </div>
      </div>

    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Billing Details') }}</div>

        </div>
        <div class="card-body">
          <div class="payment-information">
            @if (!is_null(@$order->customer->username))
              <div class="row mb-2">
                <div class="col-lg-6">
                  <strong>{{ __('Username') }} :</strong>
                </div>
                <div class="col-lg-6">
                  <a target="_blank"
                    href="{{ route('user.register.user.view', $order->customer->id) }}">{{ convertUtf8(@$order->customer->username) }}</a>
                </div>
              </div>
            @endif
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Name') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->billing_fname . ' ' . $order->billing_lname) }}
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Email') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->billing_email) }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Phone') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ $order->billing_number }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('City') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->billing_city) }}
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('State') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ !is_null($order->billing_state) ? convertUtf8($order->billing_state) : '-' }}
              </div>
            </div>

            <div class="row mb-0">
              <div class="col-lg-6">
                <strong>{{ __('Country') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->billing_country) }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Address') }} :</strong>
              </div>
              <div class="col-lg-6">
                {{ convertUtf8($order->billing_address) }}
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ __('Order Item(s)') }}</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive product-list">
            <table class="table table-bordered product-list-table mt-3">
              <thead>
                <tr class="border_top_1px">
                  <th>#</th>
                  <th>{{ __('Image') }}</th>
                  <th>{{ __('Name') }}</th>
                  <th class="text-center">{{ __('Quantity') }}</th>
                  <th class="text-center">{{ __('Price') }}</th>
                  <th class="text-center">{{ __('Total') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->orderitems as $key => $item)
                  @php
                    $item_variant = json_decode($item->variations);
                    $variant_total = 0;
                    $item_price = $item->price;
                    $slug = App\Models\User\UserItemContent::where([
                        ['item_id', $item->item_id],
                        ['language_id', $itemLang->id],
                    ])
                        ->pluck('slug')
                        ->first();
                  @endphp
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                      <img src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->image) }}" alt="product"
                        class="table-image">
                    </td>
                    <td>
                      <a class="d-block product-title"
                        href="{{ route('front.user.productDetails', [Auth::user('web')->username, 'slug' => $slug]) }}"
                        target="_blank">
                        {{ truncateString(convertUtf8($item->title), 50) }}
                      </a>
                      @if (!empty($item_variant))
                        <p class="mb-0 mt-0"><strong>{{ __('Variations') . ':' }}</strong>
                        </p>
                        <ul class="variation-list">
                          @foreach ($item_variant as $variant)
                            @php
                              $variant_total = $variant_total + $variant->price;
                            @endphp
                            <li>{{ $variant->name }} :
                              {{ textPrice($order->currency_text_position, $order->currency_code, $variant->price) }}
                            </li>
                          @endforeach
                        </ul>
                      @endif
                    </td>
                    <td class="text-center">
                      <span>{{ $item->qty }}</span>
                    </td>
                    <td class="text-center">
                      {{ textPrice($order->currency_text_position, $order->currency_code, $item_price) }}</td>
                    <td class="text-center">
                      {{ textPrice($order->currency_text_position, $order->currency_code, round($item_price * $item->qty + $variant_total * $item->qty, 2)) }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
