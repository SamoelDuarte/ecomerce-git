@extends('user-front.layout')

@section('content')
@section('breadcrumb_title', $pageHeading->dashboard_page ?? __('Dashboard'))
@section('page-title', $pageHeading->dashboard_page ?? __('Dashboard'))

<!-- Dashboard Start -->
<section class="user-dashboard pt-100 pb-70">
  <div class="container">
    <div class="row gx-xl-5">
      @includeIf('user-front.customer.side-navbar')

      <div class="col-lg-9">
        <div class="row">
          <div class="col-lg-12">
            <div class="user-profile-details mb-30">
              <div class="account-info radius-md">
                <div class="title">
                  <h3>{{ $keywords['Account Information'] ?? __('Account Information') }}</h3>
                </div>
                <div class="main-info">
                  <ul class="list">
                    <li><span>{{ $keywords['Name'] ?? __('Name') }} :</span> <span>
                        {{ Auth::guard('customer')->user()->first_name . ' ' . Auth::guard('customer')->user()->last_name }}</span>
                    </li>
                    <li><span>{{ $keywords['Email'] ?? __('Email') }} :</span> <span>
                        {{ Auth::guard('customer')->user()->email }}</span></li>
                    <li><span>{{ $keywords['Phone'] ?? __('Phone') }} :</span> <span>
                        {{ Auth::guard('customer')->user()->contact_number }}</span></li>
                    <li><span>{{ $keywords['City'] ?? __('City') }} :</span>
                      <span>{{ Auth::guard('customer')->user()->billing_city }}</span>
                    </li>
                    <li><span>{{ $keywords['Country'] ?? __('Country') }} :</span>
                      <span>{{ Auth::guard('customer')->user()->billing_country }}</span>
                    </li>
                    <li><span>{{ $keywords['Address'] ?? __('Address') }} :</span>
                      <span>{{ Auth::guard('customer')->user()->billing_address }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        @if ($shop_settings->catalog_mode != 1)
          <div class="row gx-lg-4">
            <div class="col-md-4">
              <div class=" card-box radius-md mb-30 color-1">
                <div class="card-icon">
                  <i class="far fa-clock"></i>
                </div>
                <div class="card-info">
                  <h4>{{ $keywords['Pending Orders'] ?? __('Pending Orders') }} </h4>
                  <p class="fs-4 mb-0">{{ $pending_orders }} </p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card-box radius-md mb-30 color-2">
                <div class="card-icon">
                  <i class="fas fa-sync-alt"></i>
                </div>
                <div class="card-info">
                  <h4>{{ $keywords['Processing Orders'] ?? __('Processing Orders') }} </h4>
                  <p class="fs-4 mb-0">{{ $processing_orders }}</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card-box color-3 radius-md mb-30">
                <div class="card-icon">
                  <i class="far fa-check-circle"></i>
                </div>
                <div class="card-info">
                  <h4>{{ $keywords['Completed Orders'] ?? __('Completed Orders') }} </h4>
                  <p class="fs-4 mb-0">{{ $completed_orders }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="account-info radius-md mb-30">
                <div class="title">
                  <h3>{{ $keywords['Recent Orders'] ?? __('Recent Orders') }} </h3>
                </div>
                <div class="main-info">
                  <div class="main-table">
                    <div class="table-responsiv overflow-x-scroll-mobile">
                      <table class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
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
                                    <span
                                      class="pending">{{ $keywords[ucfirst($order->order_status)] ?? __('Pending') }}
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
                                <td><a href="{{ route('customer.orders-details', ['id' => $order->id, getParam()]) }}"
                                    class="btn base-bg" class="btn">{{ $keywords['Details'] ?? __('Details') }} </a>
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
        @endif
      </div>
    </div>
  </div>
</section>
<!-- Dashboard End -->
@endsection
