@extends('user-front.layout')

@section('breadcrumb_title', $keywords['Order Details'] ?? __('Order Details'))
@section('page-title', $keywords['Order Details'] ?? __('Order Details'))

@section('content')
  @php
    $width_40 = '40%';
    $width_60 = '60%';
    $width_30 = '30%';
    $width_70 = '70%';
  @endphp
  <!-- Dashboard Start -->
  <section class="user-dashboard pt-100 pb-70">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('user-front.customer.side-navbar')
        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="order-details radius-md">
                  <div class="progress-area-step">
                    <ul class="progress-steps">
                      <li class="{{ $data->order_status == 'pending' ? 'active' : '' }}">
                        <div class="icon">{{ __('1') }}</div>
                        <div class="progress-title">{{ $keywords['Pending'] ?? __('Pending') }} </div>
                      </li>
                      <li class="{{ $data->order_status == 'processing' ? 'active' : '' }}">
                        <div class="icon">{{ __('2') }}</div>
                        <div class="progress-title">{{ $keywords['Processing'] ?? __('Processing') }} </div>
                      </li>

                      @if ($data->order_status != 'rejected')
                        <li class="{{ $data->order_status == 'completed' ? 'active' : '' }}">
                          <div class="icon">{{ __('3') }}</div>
                          <div class="progress-title">{{ $keywords['Completed'] ?? __('Completed') }} </div>
                        </li>
                      @else
                        <li class="{{ $data->order_status == 'rejected' ? 'active' : '' }}">
                          <div class="icon">{{ __('3') }}</div>
                          <div class="progress-title">{{ $keywords['Rejected'] ?? __('Rejected') }} </div>
                        </li>
                      @endif

                    </ul>
                  </div>
                  <div class="view-order-page pb-70">
                    <div class="order-info-area">
                      <div class="row align-items-center">
                        <div class="col-lg-8">
                          <div class="order-info mb-20">
                            <h4>{{ $keywords['Order'] ?? __('Order') }}{{ $data->order_id }}
                              [{{ $data->order_number }}] </h4>
                            <p class="m-0">
                              {{ $keywords['Order Date'] ?? __('Order Date') }} :
                              {{ $data->created_at->format('jS, M Y') }}
                            </p>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="prinit mb-20">
                            <a href="{{ asset('assets/front/invoices/' . $data->invoice_number) }}"
                              download="{{ $data->invoice_number }}.pdf" id="print-click" class="btn btn-md radius-sm"><i
                                class="fas fa-download"></i>{{ $keywords['Download Invoice'] ?? __('Download Invoice') }}
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="billing-add-area mb-40">
                    <div class="row">
                      <!--  Order Details-->
                      <div class="col-lg-6">
                        <div class="main-info mb-30">
                          <div class="table_component" role="region" tabindex="0">
                            <table>
                              <colgroup>
                                <col style="width: {{ $width_40 }}">
                                <col style="width: {{ $width_60 }}">
                              </colgroup>
                              <thead>
                                <tr>
                                  <th class="text-center" colspan="2">
                                    {{ $keywords['Order Details'] ?? __('Order Details') }}</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>
                                    <p class="mb-0">{{ $keywords['Payment Status'] ?? __('Payment Status') }} :</p>
                                  </td>
                                  <td>
                                    @if ($data->payment_status == 'Pending')
                                      <span class="pending">{{ $keywords[$data->payment_status] ?? __('Pending') }}
                                      </span>
                                    @elseif ($data->payment_status == 'rejected')
                                      <span class="pending">{{ $keywords[$data->payment_status] ?? __('Rejected') }}
                                      </span>
                                    @else
                                      <span class="completed">
                                        {{ $keywords[$data->payment_status] ?? __($data->payment_status) }}
                                      </span>
                                    @endif
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <p class="mb-0">{{ $keywords['Shipping Method'] ?? __('Shipping Method') }}:</p>
                                  </td>
                                  <td> {{ $data->shipping_method ?? '-' }}</td>
                                </tr>
                                <tr>
                                  <td>
                                    <p class="mb-0">{{ $keywords['Cart Total'] ?? __('Cart Total') }} :</p>
                                  </td>
                                  <td>
                                    <p class="mb-0">
                                      {{ userSymbolPrice($data->cart_total, $data->currency_position, $data->currency_sign) }}
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <p class="mb-0">{{ $keywords['Discount'] ?? __('Discount') }}
                                      <small class="text-success font-10">(<i
                                          class="fas fa-minus text-success"></i>)</small> :
                                    </p>
                                  </td>
                                  <td>
                                    <p class="mb-0">
                                      {{ userSymbolPrice($data->discount, $data->currency_position, $data->currency_sign) }}
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <p class="mb-0">{{ $keywords['Subtotal'] ?? __('Subtotal') }} :</p>
                                  </td>
                                  <td>
                                    <p class="mb-0">
                                      {{ userSymbolPrice($data->cart_total - $data->discount, $data->currency_position, $data->currency_sign) }}
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <p class="mb-0">{{ $keywords['Shipping Charge'] ?? __('Shipping Charge') }}<small
                                        class="text-dark font-10">(<i class="fas fa-plus"></i>) </small> : </p>
                                  </td>
                                  <td>
                                    <p class="mb-0">
                                      {{ userSymbolPrice($data->shipping_charge, $data->currency_position, $data->currency_sign) }}
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <p class="mb-0"> {{ $keywords['Tax'] ?? __('Tax') }}
                                      ({{ !is_null($data->tax_percentage) ? $data->tax_percentage : 0 }}%)<small
                                        class="text-danger font-10">(<i class="fas fa-plus"></i>) </small> : </p>
                                  </td>
                                  <td>
                                    <p class="mb-0">
                                      {{ userSymbolPrice($data->tax, $data->currency_position, $data->currency_sign) }}
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <p class="mb-0"> {{ $keywords['Paid Amount'] ?? __('Paid Amount') }} : </p>
                                  </td>
                                  <td>
                                    <p class="mb-0">
                                      {{ userSymbolPrice($data->total, $data->currency_position, $data->currency_sign) }}
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <p class="mb-0">{{ $keywords['Order Status'] ?? __('Order Status') }} : </p>
                                  </td>
                                  <td>
                                    <p class="mb-0"> {{ $keywords[$data->order_status] ?? __($data->order_status) }}
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <p class="mb-0">{{ $keywords['Payment Method'] ?? __('Payment Method') }} : </p>
                                  </td>
                                  <td>
                                    <p class="mb-0"> {{ $keywords[$data->method] ?? __($data->method) }} </p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="row">
                          <!--  Shipping Details-->
                          <div class="col-md-12 mb-4">
                            <div class="main-info">
                              <div class="table_component" role="region" tabindex="0">
                                <table>
                                  <colgroup>
                                    <col style="width: {{ $width_30 }}">
                                    <col style="width: {{ $width_70 }}">
                                  </colgroup>
                                  <thead>
                                    <tr>
                                      <th class="text-center" colspan="2">
                                        {{ $keywords['Shipping Details'] ?? __('Shipping Details') }}</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['Email'] ?? __('Email') }} :</p>
                                      </td>
                                      <td>
                                        <span class="mb-0">{{ $data->shipping_email }}
                                        </span>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['Phone'] ?? __('Phone') }}:</p>
                                      </td>
                                      <td> {{ $data->shipping_number }}</td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['City'] ?? __('City') }}:</p>
                                      </td>
                                      <td> {{ $data->shipping_city }}</td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['Address'] ?? __('Address') }}:</p>
                                      </td>
                                      <td> {{ $data->shipping_address }}</td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['Country'] ?? __('Country') }}:</p>
                                      </td>
                                      <td> {{ $data->shipping_country }}</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                          <!--  Billing Details-->
                          <div class="col-md-12">
                            <div class="main-info">
                              <div class="table_component" role="region" tabindex="0">
                                <table>
                                  <colgroup>
                                    <col style="width: {{ $width_30 }}">
                                    <col style="width: {{ $width_70 }}">
                                  </colgroup>
                                  <thead>
                                    <tr>
                                      <th class="text-center" colspan="2">
                                        {{ $keywords['Billing Details'] ?? __('Billing Details') }}</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['Email'] ?? __('Email') }} :</p>
                                      </td>
                                      <td>
                                        <span class="mb-0">{{ $data->billing_email }}
                                        </span>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['Phone'] ?? __('Phone') }}:</p>
                                      </td>
                                      <td> {{ $data->billing_number }}</td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['City'] ?? __('City') }}:</p>
                                      </td>
                                      <td> {{ $data->billing_city }}</td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['Address'] ?? __('Address') }}:</p>
                                      </td>
                                      <td> {{ $data->billing_address }}</td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <p class="mb-0">{{ $keywords['Country'] ?? __('Country') }}:</p>
                                      </td>
                                      <td> {{ $data->billing_country }}</td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive product-list">
                    <h3>{{ $keywords['Ordered Products'] ?? __('Ordered Products') }}</h3>
                    <table class="table table-bordered table_ordered mb-30">
                      <thead>
                        <tr>
                          <th>{{ $keywords['Image'] ?? __('Image') }} </th>
                          <th>{{ $keywords['Product'] ?? __('Product') }} </th>
                          <th>{{ $keywords['Quantity'] ?? __('Quantity') }} </th>
                          <th>{{ $keywords['Item Price'] ?? __('Item Price') }} </th>
                          <th>{{ $keywords['Paid Amount'] ?? __('Paid Amount') }} </th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($data->orderitems as $key => $order)
                          @php
                            $itemcontent = App\Models\User\UserItemContent::where('item_id', $order->item_id)
                                ->where('language_id', $currentLanguage->id)
                                ->first();
                            $ser = 0;
                          @endphp

                          <tr>
                            <td class="table_image_td">
                              @if (@$itemcontent->slug)
                                <a target="_blank" class="d-block"
                                  href="{{ route('front.user.productDetails', ['slug' => $itemcontent->slug, getParam()]) }}">
                                  <img
                                  src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $order->item->thumbnail) }}"alt="product"
                                    class="table-image lazyload">
                                </a>
                              @endif
                            </td>
                            <td>
                              @if (!is_null(@$itemcontent->slug))
                                <a target="_blank" class="d-block"
                                  href="{{ route('front.user.productDetails', ['slug' => $itemcontent->slug, getParam()]) }}">{{ truncateString($order->title, 40) }}</a>
                              @endif
                              @if (!empty($order->variations))
                                @php
                                  $variatons = json_decode($order->variations);
                                  $variant_total = 0;
                                @endphp
                                @if (!empty($variatons))
                                  <p class="mb-0 mt-2">
                                    <strong>{{ $keywords['Variations'] ?? __('Variations') }} :</strong>
                                  </p>
                                  @foreach ($variatons as $k => $itm)
                                    @php
                                      $variant_total = $variant_total + $itm->price;
                                    @endphp
                                    <p class="mb-0 order_variants_price">{{ $k }}
                                      (<small>{{ $itm->name }}</small>)
                                      :
                                      <i
                                        class="fas fa-plus"></i>{{ userSymbolPrice($itm->price, $data->currency_position, $data->currency_sign) }}
                                    </p>
                                  @endforeach
                                @endif
                              @endif

                              <!--- donwload file or link for digtal product -->
                              @if ($order->item->type == 'digital' && $order->item->download_link != null)
                                <a href="{{ $order->item->download_link }}"
                                  class="digital-donwload-btn btn btn-primary btn-sm border-0">{{ $keywords['Download'] ?? __('Download') }}</a>
                              @else
                                <form action="{{ route('user-digital-download') }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="product_id" value="{{ $order->item->id }}">
                                  @if ($order->item->type == 'digital')
                                    <button type="submit"
                                      class="digital-donwload-btn btn btn-primary btn-sm border-0">{{ $keywords['Download'] ?? __('Download') }}</button>
                                  @endif
                                </form>
                              @endif
                            </td>
                            <td class="text-center">
                              <p class="mb-0">{{ $order->qty }}</p>
                            </td>
                            <td class="text-center">
                              <p class="mb-0">
                                {{ userSymbolPrice($order->price, $data->currency_position, $data->currency_sign) }}</p>
                            </td>
                            <td class="text-center">
                              <p class="mb-0">
                                {{ userSymbolPrice($order->price * $order->qty + @$variant_total * $order->qty, $data->currency_position, $data->currency_sign) }}
                              </p>
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
        </div>
      </div>
    </div>
  </section>
  <!-- Dashboard End -->
@endsection
