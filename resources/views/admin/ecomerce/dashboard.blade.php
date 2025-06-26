@extends('admin.layout')


@section('content')

    <div class="row">
        {{-- Total Items --}}
        @if (isset($total_items))
            <div class="col-sm-6 col-md-4">
                <a class="card card-stats card-primary card-round"
                    href="{{ route('user.item.index', ['language' => $default->code ?? 'en']) }}">
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
        @endif

        {{-- Total Orders --}}
        @if (isset($total_orders))
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
        @endif

        {{-- Total Customers --}}
        @if (isset($total_customers))
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
        @endif

        {{-- Total Subscribers --}}
        @if (isset($total_subscribers))
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

        {{-- Blogs --}}
        @if (isset($blogs))
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

        {{-- Custom Pages --}}
        @if (isset($total_custom_pages))
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
                                    @if ($orders->isEmpty())
                                        <h3 class="text-center">{{ __('NO PRODUCT ORDER FOUND') }}</h3>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped mt-3">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Order Number') }}</th>
                                                        <th>{{ __('Total') }}</th>
                                                        <th>{{ __('Order Status') }}</th>
                                                        <th>{{ __('Payment Status') }}</th>
                                                        <th>{{ __('Vendor') }}</th>
                                                        <th>{{ __('Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($orders as $order)
                                                        <tr>
                                                            <td>#{{ $order->order_number }}</td>
                                                            <td>{{ round($order->total, 2) }}
                                                                ({{ $order->currency_code }})
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge 
                              @if ($order->order_status == 'pending') badge-warning
                              @elseif ($order->order_status == 'processing') badge-primary
                              @elseif ($order->order_status == 'completed') badge-success
                              @elseif ($order->order_status == 'rejected') badge-danger @endif">
                                                                    {{ ucfirst($order->order_status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge 
                              @if ($order->payment_status == 'Completed') badge-success
                              @elseif ($order->payment_status == 'Pending') badge-warning
                              @elseif ($order->payment_status == 'Rejected') badge-danger @endif">
                                                                    {{ $order->payment_status }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $order->user->shop_name ?? '-' }}</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-info btn-sm dropdown-toggle"
                                                                        type="button" data-toggle="dropdown">
                                                                        {{ __('Actions') }}
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        @if ($order->user)
                                                                            <a class="dropdown-item"
                                                                                href="{{ route('register.user.secret_login', $order->user->id) }}?redirect={{ route('user.item.details', $order->id) }}"
                                                                                target="_blank">
                                                                                {{ __('Details') }}
                                                                            </a>
                                                                        @endif


                                                                        @if ($order->invoice_number)
                                                                            <a class="dropdown-item"
                                                                                href="{{ asset('assets/front/invoices/' . $order->invoice_number) }}"
                                                                                target="_blank">{{ __('Invoice') }}</a>
                                                                        @endif
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
                                    @if ($memberships->isEmpty())
                                        <h3 class="text-center">{{ __('NO PAYMENT LOG FOUND') }}</h3>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped mt-3">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Transaction Id') }}</th>
                                                        <th>{{ __('Amount') }}</th>
                                                        <th>{{ __('Payment Status') }}</th>
                                                        <th>{{ __('Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($memberships as $membership)
                                                        <tr>
                                                            <td>{{ Str::limit($membership->transaction_id, 30) }}</td>
                                                            <td>
                                                                {{ $membership->price == 0 ? __('Free') : format_price($membership->price) }}
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge 
                              @if ($membership->status == 1) badge-success
                              @elseif ($membership->status == 0) badge-warning
                              @elseif ($membership->status == 2) badge-danger @endif">
                                                                    {{ $membership->status == 1 ? __('Success') : ($membership->status == 0 ? __('Pending') : __('Rejected')) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-sm btn-info" data-toggle="modal"
                                                                    data-target="#detailsModal{{ $membership->id }}">
                                                                    {{ __('Detail') }}
                                                                </button>
                                                            </td>
                                                        </tr>

                                                        {{-- Modal de Detalhes --}}
                                                        <div class="modal fade" id="detailsModal{{ $membership->id }}"
                                                            tabindex="-1" role="dialog">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">{{ __('Details') }}</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h4 class="text-warning">
                                                                            {{ __('Member Details') }}</h4>
                                                                        <p><strong>{{ __('Name') }}:</strong>
                                                                            {{ $membership->user->shop_name ?? '-' }}</p>
                                                                        <p><strong>{{ __('Email') }}:</strong>
                                                                            {{ $membership->user->email ?? '-' }}</p>
                                                                        <p><strong>{{ __('Phone') }}:</strong>
                                                                            {{ $membership->user->phone ?? '-' }}</p>

                                                                        <h4 class="text-warning">
                                                                            {{ __('Payment Details') }}</h4>
                                                                        <p><strong>{{ __('Cost') }}:</strong>
                                                                            {{ $membership->price == 0 ? __('Free') : format_price($membership->price) }}
                                                                        </p>
                                                                        <p><strong>{{ __('Currency') }}:</strong>
                                                                            {{ $membership->currency }}</p>
                                                                        <p><strong>{{ __('Method') }}:</strong>
                                                                            {{ __($membership->payment_method) }}</p>

                                                                        <h4 class="text-warning">
                                                                            {{ __('Package Details') }}</h4>
                                                                        <p><strong>{{ __('Title') }}:</strong>
                                                                            {{ $membership->package->title ?? '-' }}</p>
                                                                        <p><strong>{{ __('Term') }}:</strong>
                                                                            {{ $membership->package->term ?? '-' }}</p>
                                                                        <p><strong>{{ __('Start Date') }}:</strong>
                                                                            @if (\Carbon\Carbon::parse($membership->start_date)->year == 9999)
                                                                                <span
                                                                                    class="badge badge-danger">{{ __('Never Activated') }}</span>
                                                                            @else
                                                                                {{ \Carbon\Carbon::parse($membership->start_date)->format('jS M, Y') }}
                                                                            @endif
                                                                        </p>
                                                                        <p><strong>{{ __('Expire Date') }}:</strong>
                                                                            @if (\Carbon\Carbon::parse($membership->start_date)->year == 9999)
                                                                                -
                                                                            @elseif ($membership->package->term == 'lifetime')
                                                                                {{ __('Lifetime') }}
                                                                            @else
                                                                                {{ \Carbon\Carbon::parse($membership->expire_date)->format('jS M, Y') }}
                                                                            @endif
                                                                        </p>
                                                                        <p><strong>{{ __('Purchase Type') }}:</strong>
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
