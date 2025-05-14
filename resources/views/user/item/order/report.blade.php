@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Sales Report') }}
    </h4>
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
        <a href="#">
          {{ __('Sales Report') }}
        </a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header p-1">
          <div class="row">
            <div class="col-lg-10">
              <form action="{{ url()->full() }}" class="form-inline">
                <div class="form-group">
                  <label for="">{{ __('From') }}</label>
                  <input class="form-control datepicker" type="text" name="from_date" placeholder="{{ __('From') }}"
                    value="{{ request()->input('from_date') ? request()->input('from_date') : '' }}" required
                    autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="">{{ __('To') }}</label>
                  <input class="form-control datepicker ml-1" type="text" name="to_date"
                    placeholder="{{ __('To') }}"
                    value="{{ request()->input('to_date') ? request()->input('to_date') : '' }}" required
                    autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="">{{ __('Payment Method') }}</label>
                  <select name="payment_method" class="form-control ml-1">
                    <option value="" selected>{{ __('All') }}</option>
                    @if (!empty($onPms))
                      @foreach ($onPms as $onPm)
                        <option value="{{ $onPm->keyword }}"
                          {{ request()->input('payment_method') == $onPm->keyword ? 'selected' : '' }}>
                          {{ $onPm->name }}</option>
                      @endforeach
                    @endif
                    @if (!empty($offPms))
                      @foreach ($offPms as $offPm)
                        <option value="{{ $offPm->name }}"
                          {{ request()->input('payment_method') == $offPm->name ? 'selected' : '' }}>{{ $offPm->name }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Payment Status') }}</label>
                  <select name="payment_status" class="form-control ml-1">
                    <option value="" selected>{{ __('All') }}</option>
                    <option value="Pending" {{ request()->input('payment_status') == 'Pending' ? 'selected' : '' }}>
                      {{ __('Pending') }}</option>
                    <option value="Completed" {{ request()->input('payment_status') == 'Completed' ? 'selected' : '' }}>
                      {{ __('Completed') }}</option>
                  </select>
                </div>


                <div class="form-group">
                  <label for="">{{ __('Order Status') }}</label>
                  <select name="order_status" class="form-control ml-1">
                    <option value="" selected>{{ __('All') }}</option>
                    <option value="pending" {{ request()->input('order_status') == 'pending' ? 'selected' : '' }}>
                      {{ __('Pending') }}</option>
                    <option value="processing" {{ request()->input('order_status') == 'processing' ? 'selected' : '' }}>
                      {{ __('Processing') }}</option>
                    <option value="completed" {{ request()->input('order_status') == 'completed' ? 'selected' : '' }}>
                      {{ __('Completed') }}</option>
                    <option value="rejected" {{ request()->input('order_status') == 'rejected' ? 'selected' : '' }}>
                      {{ __('Rejected') }}</option>
                  </select>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-sm ml-1">{{ __('Submit') }}</button>
                </div>
              </form>
            </div>
            <div class="col-lg-2">
              <form action="{{ route('user.orders.export') }}" class="form-inline justify-content-end">
                <div class="form-group">
                  <button type="submit" class="btn btn-success btn-sm ml-1"
                    title="{{ __('CSV Format') }}">{{ __('Export') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-body">
          @if (count($orders) > 0)
            <div class="row">
              <!-- Total Card -->
              <div class="col-sm-6 col-md-3">
                <a class="card card-stats card-info card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-dollar-sign"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">{{ __('Total') }}</p>
                          <h4 class="card-title">
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ $total }}
                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Discount Card -->
              <div class="col-sm-6 col-md-3">
                <a class="card card-stats card-success card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-percent"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">{{ __('Discount') }}</p>
                          <h4 class="card-title">
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ $discount }}
                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Shipping Charge Card -->
              <div class="col-sm-6 col-md-3">
                <a class="card card-stats card-warning card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-shipping-fast"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">{{ __('Shipping Charge') }}</p>
                          <h4 class="card-title">
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ $shipping_charge }}
                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Tax Card -->
              <div class="col-sm-6 col-md-3">
                <a class="card card-stats card-danger card-round">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-5">
                        <div class="icon-big text-center">
                          <i class="fas fa-balance-scale"></i>
                        </div>
                      </div>
                      <div class="col-7 col-stats">
                        <div class="numbers">
                          <p class="card-category">{{ __('Tax') }}</p>
                          <h4 class="card-title">
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                            {{ $tax }}
                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          @endif

          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) > 0)
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Order Number') }}</th>
                        <th scope="col">{{ __('Billing Name') }}</th>
                        <th scope="col">{{ __('Billing Email') }}</th>
                        <th scope="col">{{ __('Billing Phone') }}</th>
                        <th scope="col">{{ __('Billing City') }}</th>
                        <th scope="col">{{ __('Billing Country') }}</th>
                        <th scope="col">{{ __('Shipping Name') }}</th>
                        <th scope="col">{{ __('Shipping Email') }}</th>
                        <th scope="col">{{ __('Shipping Phone') }}</th>
                        <th scope="col">{{ __('Shipping City') }}</th>
                        <th scope="col">{{ __('Shipping Country') }}</th>
                        <th scope="col">{{ __('Gateway') }}</th>
                        <th scope="col">{{ __('Shipping Method') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Order Status') }}</th>
                        <th scope="col">{{ __('Cart Total') }}</th>
                        <th scope="col">{{ __('Discount') }}</th>
                        <th scope="col">{{ __('Tax') }}</th>
                        <th scope="col">{{ __('Shipping Charge') }}</th>
                        <th scope="col">{{ __('Total') }}</th>
                        <th scope="col">{{ __('Date') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $key => $order)
                        <tr>
                          <td>#{{ $order->order_number }}</td>
                          <td>{{ $order->billing_fname }}</td>
                          <td>{{ $order->billing_email }}</td>
                          <td>{{ $order->billing_number }}</td>
                          <td>{{ $order->billing_city }}</td>
                          <td>{{ $order->billing_country }}</td>
                          <td>{{ $order->shipping_fname }}</td>
                          <td>{{ $order->shipping_email }}</td>
                          <td>{{ $order->shipping_number }}</td>
                          <td>{{ $order->shipping_city }}</td>
                          <td>{{ $order->shipping_country }}</td>
                          <td>{{ ucfirst($order->method) }}</td>
                          <td>{{ $order->shipping_method ? $order->shipping_method : '-' }}</td>
                          <td>
                            @if ($order->payment_status == 'Pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @elseif ($order->payment_status == 'Completed')
                              <span class="badge badge-success">{{ __('Completed') }}</span>
                            @endif
                          </td>
                          <td>
                            @if ($order->order_status == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @elseif ($order->order_status == 'processing')
                              <span class="badge badge-primary">{{ __('Processing') }}</span>
                            @elseif ($order->order_status == 'completed')
                              <span class="badge badge-success">{{ __('Completed') }}</span>
                            @elseif ($order->order_status == 'rejected')
                              <span class="badge badge-danger">{{ __('Rejected') }}</span>
                            @endif
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ round($order->cart_total, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ round($order->discount, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ round($order->tax, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}
                            {{ round($order->shipping_charge, 2) }}
                            {{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $userBs->base_currency_symbol_position == 'left' ? $userBs->base_currency_symbol : '' }}{{ round($order->total, 2) }}{{ $userBs->base_currency_symbol_position == 'right' ? $userBs->base_currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $order->created_at }}
                          </td>
                        </tr>


                        {{-- Receipt Modal --}}
                        <div class="modal fade" id="receiptModal{{ $order->id }}" tabindex="-1" role="dialog"
                          aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                  {{ __('Receipt Image') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <img src="{{ asset('assets/front/receipt/' . $order->receipt) }}" alt="Receipt"
                                  class="w-100">
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                  data-dismiss="modal">{{ __('Close') }}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <!-- Send Mail Modal -->
                <div class="modal fade" id="mailModal" tabindex="-1" role="dialog"
                  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                          {{ __('Send Mail') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="ajaxEditForm" class="" action="{{ route('user.orders.mail') }}"
                          method="POST">
                          @csrf
                          <div class="form-group">
                            <label for="">{{ __('Client Mail') }} <span class="text-danger">**</span></label>
                            <input id="inemail" type="text" class="form-control" name="email" value=""
                              placeholder="{{ __('Enter email') }}">
                            <p id="eerremail" class="mb-0 text-danger em"></p>
                          </div>
                          <div class="form-group">
                            <label for="">{{ __('Subject') }} <span class="text-danger">**</span></label>
                            <input id="insubject" type="text" class="form-control" name="subject" value=""
                              placeholder="{{ __('Enter subject') }}">
                            <p id="eerrsubject" class="mb-0 text-danger em"></p>
                          </div>
                          <div class="form-group">
                            <label for="">{{ __('Message') }} <span class="text-danger">**</span></label>
                            <textarea id="inmessage" class="form-control summernote" name="message" placeholder="{{ __('Enter message') }}"
                              data-height="150"></textarea>
                            <p id="eerrmessage" class="mb-0 text-danger em"></p>
                          </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                          data-dismiss="modal">{{ __('Close') }}</button>
                        <button id="updateBtn" type="button" class="btn btn-primary">{{ __('Send Mail') }}</button>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
        </div>

        @if (!empty($orders))
          <div class="card-footer">
            <div class="row">
              <div class="d-inline-block mx-auto">
                {{ $orders->links() }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
