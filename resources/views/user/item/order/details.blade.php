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
                                <strong>{{ __('Order Status') . ':' }}</strong>
                            </div>
                            <div class="col-lg-6">
                                @switch($order->order_status)
                                    @case('Pedido Realizado')
                                        <span class="badge bg-secondary">{{ $order->order_status }}</span>
                                    @break

                                    @case('Pedido Separação')
                                        <span class="badge bg-info">{{ $order->order_status }}</span>
                                    @break

                                    @case('Pedido Faturado')
                                        <span class="badge bg-warning">{{ $order->order_status }}</span>
                                    @break

                                    @case('Pedido em transporte')
                                        <span class="badge bg-primary">{{ $order->order_status }}</span>
                                    @break

                                    @case('Entregue')
                                        <span class="badge bg-success">{{ $order->order_status }}</span>
                                    @break

                                    @case('Pedido Cancelado')
                                        <span class="badge bg-danger">{{ $order->order_status }}</span>
                                    @break

                                    @default
                                        <span class="badge bg-light text-dark">{{ $order->order_status }}</span>
                                @endswitch
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ __('Shipping Method') . ':' }}</strong>
                            </div>
                            <div class="col-lg-6">
                                {{ $order->shipping_service }}
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
                                @if (!empty($order->shipping_price))
                                    {{ textPrice($order->currency_text_position, $order->currency_code, $order->shipping_price) }}
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

                        {{-- REMOVIDO País --}}

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ __('Address') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{-- Monta endereço completo com rua + número + bairro --}}
                                {{ convertUtf8(
                                    $order->shipping_street . ', Nº ' . $order->shipping_number_address . ' - ' . $order->shipping_neighborhood,
                                ) }}
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

                        {{-- REMOVIDO País --}}

                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <strong>{{ __('Address') }} :</strong>
                            </div>
                            <div class="col-lg-6">
                                {{-- Monta endereço completo com rua + número + bairro --}}
                                {{ convertUtf8(
                                    $order->billing_street . ', Nº ' . $order->billing_number_home . ' - ' . $order->billing_neighborhood,
                                ) }}
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
                                        // dd($itemLang->id);
                                        $item_variant = json_decode($item->variations);
                                        $variant_total = 0;
                                        $item_price = $item->price;
                                        $slug = App\Models\User\UserItemContent::where([['item_id', $item->item_id]])
                                            ->pluck('slug')
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <img src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->image) }}"
                                                alt="product" class="table-image">
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
                                            {{ textPrice($order->currency_text_position, $order->currency_code, $item_price) }}
                                        </td>
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
