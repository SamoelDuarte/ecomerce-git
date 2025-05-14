@extends('user-front.layout')
@section('breadcrumb_title', $pageHeading->orders_page ?? __('My Orders'))
@section('page-title', $pageHeading->orders_page ?? __('My Orders'))
@section('content')

  <!-- Dashboard Start -->
  <section class="user-dashboard pt-100 pb-70">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('user-front.customer.side-navbar')
        <div class="col-lg-9">
          <div class="account-info radius-md mb-30">
            <div class="title">
              <h3>{{ $keywords['My Orders'] ?? __('My Orders') }}</h3>
            </div>
            <div class="main-info">
              <div class="main-table">
                <div class="table-responsiv overflow-x-scroll-mobile">
                  <table id="myTable" class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                    <thead>
                      <tr>
                        <th>{{ $keywords['Order number'] ?? __('Order number') }} </th>
                        <th>{{ $keywords['Date'] ?? __('Date') }} </th>
                        <th>{{ $keywords['Amount'] ?? __('Amount') }} </th>
                        <th>{{ $keywords['Order Status'] ?? __('Order Status') }} </th>
                        <th>{{ $keywords['Action'] ?? __('Action') }} </th>
                      </tr>
                    </thead>
                    <tbody>
                      @if (count($orders) > 0)
                        @foreach ($orders as $order)
                          <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d-m-Y') }}</td>
                            <td>
                              {{ userSymbolPrice($order->total, $order->currency_position, $order->currency_sign) }}
                            </td>
                            <td>
                              @if ($order->order_status == 'pending')
                                <span class="pending">
                                  {{ $keywords[ucfirst($order->order_status)] ?? __('Pending') }}
                                </span>
                              @elseif ($order->order_status == 'rejected')
                                <span class="rejected">
                                  {{ $keywords[ucfirst($order->order_status)] ?? __('Rejected') }}
                                </span>
                              @elseif ($order->order_status == 'processing')
                                <span class="processing">
                                  {{ $keywords[ucfirst($order->order_status)] ?? __('Processing') }}
                                </span>
                              @elseif ($order->order_status == 'completed')
                                <span class="completed">
                                  {{ $keywords[ucfirst($order->order_status)] ?? __('Completed') }}
                                </span>
                              @endif
                            </td>
                            <td><a target="_blank"
                                href="{{ route('customer.orders-details', ['id' => $order->id, getParam()]) }}"
                                class="btn base-bg">{{ $keywords['Details'] ?? __('Details') }}</a>
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                          <td colspan="5" class="text-center pt-3">
                            {{ $keywords['No Orders'] ?? __('No Orders') }}
                          </td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
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
