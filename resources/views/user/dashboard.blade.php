@extends('user.layout')
@php
  $default = \App\Models\User\Language::where('is_default', 1)->first();
  $user = Auth::guard('web')->user();
  $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
  if (!empty($user)) {
      $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
      $permissions = json_decode($permissions, true);
  }
@endphp

@section('content')
  <div class="mt-2 mb-4">
    <h2 class="pb-2">{{ __('Welcome back') }},
      {{ Auth::guard('web')->user()->shop_name ?? Auth::guard('web')->user()->username }}!</h2>
  </div>
  @if (is_null($package))
    @php
      $pendingMemb = \App\Models\Membership::query()
          ->where([['user_id', '=', Auth::id()], ['status', 0]])
          ->whereYear('start_date', '<>', '9999')
          ->orderBy('id', 'DESC')
          ->first();
      $pendingPackage = isset($pendingMemb) ? \App\Models\Package::query()->findOrFail($pendingMemb->package_id) : null;
    @endphp

    @if ($pendingPackage)
      <div class="alert alert-warning">
        {{ __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.') }}
      </div>
      <div class="alert alert-warning">
        <strong>{{ __('Pending Package:') }} </strong> {{ $pendingPackage->title }}
        <span class="badge badge-secondary">{{ $pendingPackage->term }}</span>
        <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
      </div>
    @else
      <div class="alert alert-warning">
        {{ __('Your membership is expired. Please purchase a new package / extend the current package.') }}
      </div>
    @endif
  @else
    <div class="row justify-content-center align-items-center mb-1">
      <div class="col-12">
        <div class="alert border-left border-primary text-dark">
          @if ($package_count >= 2)
            @if ($next_membership->status == 0)
              <strong
                class="text-danger">{{ __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.') }}</strong><br>
            @elseif ($next_membership->status == 1)
              <strong
                class="text-danger">{{ __('You have another package to activate after the current package expires. You cannot purchase / extend any package, until the next package is activated') }}</strong><br>
            @endif
          @endif

          <strong>{{ __('Current Package:') }} </strong> {{ __($current_package->title) }}
          <span class="badge badge-secondary">{{ __($current_package->term) }}</span>
          @if ($current_membership->is_trial == 1)
            ({{ __('Expire Date:') }}
            {{ Carbon\Carbon::parse($current_membership->expire_date)->format('jS M ,Y') }})
            <span class="badge badge-primary">{{ __('Trial') }}</span>
          @else
            ({{ __('Expire Date:') }}
            {{ $current_package->term === 'lifetime' ? __('Lifetime') : Carbon\Carbon::parse($current_membership->expire_date)->format('jS M ,Y') }})
          @endif

          @if ($package_count >= 2)
            <div>
              <strong>{{ __('Next Package To Activate:') }} </strong>
              {{ $next_package->title }} <span class="badge badge-secondary">{{ $next_package->term }}</span>
              @if ($current_package->term != 'lifetime' && $current_membership->is_trial != 1)
                ({{ __('Activation Date:') }}
                {{ Carbon\Carbon::parse($next_membership->start_date)->format('jS M ,Y') }},
                {{ __('Expire Date:') }}
                {{ $next_package->term === 'lifetime' ? __('Lifetime') : Carbon\Carbon::parse($next_membership->expire_date)->format('jS M ,Y') }})
              @endif
              @if ($next_membership->status == 0)
                <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
              @endif
            </div>
          @endif
        </div>
      </div>
    </div>
  @endif
  <div class="row">
    @if (!is_null($package))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-primary card-round"
          href="{{ route('user.item.index', ['language' => $default->code]) }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-store-alt"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Items') }}</p>
                  <h4 class="card-title">{{ $total_items }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-secondary card-round" href="{{ route('user.all.item.orders') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-shopping-cart"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Orders') }}</p>
                  <h4 class="card-title">{{ $total_orders }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-info card-round" href="{{ route('user.register.user') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-users"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Registered Customers') }}</p>
                  <h4 class="card-title">{{ $total_customers }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-warning card-round" href="{{ route('user.subscriber.index') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-envelope-open"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Subscribers') }}</p>
                  <h4 class="card-title">{{ $total_subscribers }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif


    @if (!empty($permissions) && in_array('Blog', $permissions))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-success card-round" href="{{ route('user.blog.index') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-blog"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Blogs') }}</p>
                  <h4 class="card-title">{{ $blogs }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif


    @if (!empty($permissions) && in_array('Custom Page', $permissions))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-danger card-round" href="{{ route('user.blog.index') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="la flaticon-file"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Custom Pages') }}</p>
                  <h4 class="card-title">{{ $total_custom_pages }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif
  </div>

  <div class="row">
    <div class="col-lg-6">
      <div class="row row-card-no-pd">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="card-head-row">
                <h4 class="card-title">{{ __('Latest Product Orders') }}</h4>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  @if (count($orders) == 0)
                    <h3 class="text-center">{{ __('NO PRODUCT ORDER FOUND') }}
                    </h3>
                  @else
                    <div class="table-responsive">
                      <table class="table table-striped mt-3">
                        <thead>
                          <tr>
                            <th scope="col">{{ __('Order Number') }}</th>
                            <th scope="col">{{ __('Total') }}</th>
                            <th scope="col">{{ __('Order Status') }}</th>
                            <th scope="col">{{ __('Payment Status') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($orders as $key => $order)
                            <tr>
                              <td>
                                #{{ $order->order_number }}
                              </td>

                              <td>
                                {{ round($order->total, 2) }}
                                ({{ $order->currency_code }})
                              </td>
                              <td>
                                @if ($order->order_status != 'rejected')
                                  <form id="statusForm{{ $order->id }}" class="d-inline-block"
                                    action="{{ route('user.item.orders.status') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <select
                                      class="w-min-max-100 form-control form-control-sm
                              @if ($order->order_status == 'pending') bg-warning
                              @elseif ($order->order_status == 'processing')
                                bg-primary
                              @elseif ($order->order_status == 'completed')
                                bg-success
                              @elseif ($order->order_status == 'rejected')
                                bg-danger @endif
                              "
                                      name="order_status"
                                      onchange="document.getElementById('statusForm{{ $order->id }}').submit();">
                                      <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                        {{ __('Pending') }}</option>
                                      <option value="processing"
                                        {{ $order->order_status == 'processing' ? 'selected' : '' }}>
                                        {{ __('Processing') }}</option>
                                      <option value="completed"
                                        {{ $order->order_status == 'completed' ? 'selected' : '' }}>
                                        {{ __('Completed') }}</option>
                                      <option value="rejected"
                                        {{ $order->order_status == 'rejected' ? 'selected' : '' }}>
                                        {{ __('Rejected') }}</option>
                                    </select>
                                  </form>
                                @else
                                  <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                @endif

                              </td>
                              <td>
                                @if ($order->gateway_type != 'offline')
                                  @if ($order->payment_status == 'Completed')
                                    <span class="badge badge-success">{{ __('Completed') }}</span>
                                  @elseif($order->payment_status == 'Pending')
                                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                                  @elseif($order->payment_status == 'Rejected')
                                    <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                  @endif
                                @elseif ($order->gateway_type == 'offline')
                                  @if ($order->payment_status == 'Rejected')
                                    <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                  @else
                                    <form action="{{ route('user.item.paymentStatus') }}"
                                      id="paymentStatusForm{{ $order->id }}" method="POST">
                                      @csrf
                                      <input type="hidden" name="order_id" value="{{ $order->id }}">
                                      <select
                                        class="form-control-sm text-white border-0
                                    @if ($order->payment_status == 'Completed') bg-success
                                    @elseif($order->payment_status == 'Pending')
                                        bg-warning @endif
                                    "
                                        name="payment_status"
                                        onchange="document.getElementById('paymentStatusForm{{ $order->id }}').submit();">
                                        <option value="Pending"
                                          {{ $order->payment_status == 'Pending' ? 'selected' : '' }}>
                                          {{ __('Pending') }}</option>
                                        <option value="Completed"
                                          {{ $order->payment_status == 'Completed' ? 'selected' : '' }}>
                                          {{ __('Completed') }}</option>
                                        <option value="Rejected"
                                          {{ $order->payment_status == 'Rejected' ? 'selected' : '' }}>
                                          {{ __('Rejected') }}</option>
                                      </select>
                                    </form>
                                  @endif
                                @endif
                              </td>
                              <td>
                                <div class="dropdown">
                                  <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    {{ __('Actions') }}
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('user.item.details', $order->id) }}"
                                      target="_blank">{{ __('Details') }}</a>
                                    <a class="dropdown-item"
                                      href="{{ asset('assets/front/invoices/' . $order->invoice_number) }}"
                                      target="_blank">{{ __('Invoice') }}</a>
                                    <form class="deleteform d-block" action="{{ route('user.item.order.delete') }}"
                                      method="post">
                                      @csrf
                                      <input type="hidden" name="order_id" value="{{ $order->id }}">
                                      <button type="submit" class="deletebtn">
                                        {{ __('Delete') }}
                                      </button>
                                    </form>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="row row-card-no-pd">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="card-head-row">
                <h4 class="card-title">{{ __('Recent Payment Logs') }}</h4>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  @if (count($memberships) == 0)
                    <h3 class="text-center">{{ __('NO PAYMENT LOG FOUND') }}</h3>
                  @else
                    <div class="table-responsive">
                      <table class="table table-striped mt-3">
                        <thead>
                          <tr>
                            <th scope="col">{{ __('Transaction Id') }}</th>
                            <th scope="col">{{ __('Amount') }}</th>
                            <th scope="col">{{ __('Payment Status') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($memberships as $key => $membership)
                            <tr>
                              <td>
                                {{ strlen($membership->transaction_id) > 30 ? mb_substr($membership->transaction_id, 0, 30, 'UTF-8') . '...' : $membership->transaction_id }}
                              </td>
                              @php
                                $bex = json_decode($membership->settings);
                              @endphp
                              <td>
                                @if ($membership->price == 0)
                                  Free
                                @else
                                  {{ format_price($membership->price) }}
                                @endif
                              </td>
                              <td>
                                @if ($membership->status == 1)
                                  <h3 class="d-inline-block badge badge-success">
                                    {{ __('Success') }}
                                  </h3>
                                @elseif ($membership->status == 0)
                                  <h3 class="d-inline-block badge badge-warning">
                                    {{ __('Pending') }}
                                  </h3>
                                @elseif ($membership->status == 2)
                                  <h3 class="d-inline-block badge badge-danger">
                                    {{ __('Rejected') }}
                                  </h3>
                                @endif
                              </td>
                              <td>
                                @if (!empty($membership->name !== 'anonymous'))
                                  <a class="btn btn-sm btn-info" href="#" data-toggle="modal"
                                    data-target="#detailsModal{{ $membership->id }}">{{ __('Detail') }}</a>
                                @else
                                  -
                                @endif
                              </td>
                            </tr>
                            <div class="modal fade" id="detailsModal{{ $membership->id }}" tabindex="-1"
                              role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                      {{ __('Details') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <h3 class="text-warning">{{ __('Member details') }}
                                    </h3>
                                    <label>{{ __('Name') }}</label>
                                    <p>{{ $membership->user->shop_name }}</p>
                                    <label>{{ __('Email') }}</label>
                                    <p>{{ $membership->user->email }}</p>
                                    <label>{{ __('Phone') }}</label>
                                    <p>{{ $membership->user->phone }}</p>
                                    <h3 class="text-warning">
                                      {{ __('Payment details') }}</h3>
                                    <p><strong>{{ __('Cost') . ':' }} </strong>
                                      {{ $membership->price == 0 ? __('Free') : $membership->price }}
                                    </p>
                                    <p><strong>{{ __('Currency:') }} </strong>
                                      {{ $membership->currency }}
                                    </p>
                                    <p><strong>{{ __('Method') }}: </strong>
                                      {{ __($membership->payment_method) }}
                                    </p>
                                    <h3 class="text-warning">
                                      {{ __('Package Details') }}</h3>
                                    <p><strong>{{ __('Title') }}:
                                      </strong>{{ __($membership->package->title) }}
                                    </p>
                                    <p><strong>{{ __('Term') }}: </strong>
                                      {{ __($membership->package->term) }}
                                    </p>
                                    <p><strong>{{ __('Start Date') }}: </strong>
                                      @if (\Illuminate\Support\Carbon::parse($membership->start_date)->format('Y') == '9999')
                                        <span class="badge badge-danger">{{ __('Never Activated') }}</span>
                                      @else
                                        {{ \Illuminate\Support\Carbon::parse($membership->start_date)->format('jS M ,Y') }}
                                      @endif
                                    </p>
                                    <p><strong>{{ __('Expire Date') }}: </strong>

                                      @if (\Illuminate\Support\Carbon::parse($membership->start_date)->format('Y') == '9999')
                                        -
                                      @else
                                        @if ($membership->modified == 1)
                                          {{ \Illuminate\Support\Carbon::parse($membership->expire_date)->addDay()->format('jS M ,Y') }}
                                          <span
                                            class="badge badge-primary btn-xs">{{ __('modified  by Admin') }}</span>
                                        @else
                                          {{ $membership->package->term == 'lifetime' ? __('Lifetime') : \Illuminate\Support\Carbon::parse($membership->expire_date)->format('jS M ,Y') }}
                                        @endif
                                      @endif
                                    </p>
                                    <p>
                                      <strong>{{ __('Purchase Type') }}: </strong>
                                      @if ($membership->is_trial == 1)
                                        {{ __('Trial') }}
                                      @else
                                        {{ $membership->price == 0 ? __('Free') : __('Regular') }}
                                      @endif
                                    </p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
