@extends('user.layout')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Registered Customers') }}</h4>
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
        <a href="#">{{ __('Registered Customers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Customer Details') }}</a>
      </li>
    </ul>

    <a href="{{ route('user.register.user') }}" class="btn-md btn btn-primary ml-auto">{{ __('Back') }}</a>
  </div>
  <div class="row">

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ __('Profile') }}</h4>
        </div>
        <div class="card-body p-4">
          <div class="text-center">
            <img
              src="{{ !empty($user->image) ? asset('assets/user-front/images/users/' . $user->image) : asset('assets/user/img/profile.png') }}"
              alt="" class="rounded-circle profile-image">
          </div>

          <div class="row mt-4 mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Username') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->username ?? '-' }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('First Name') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->first_name ?? '-' }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Last Name') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->last_name ?? '-' }}
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Email') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->email ?? '-' }}
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Phone') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->contact_number ?? '-' }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Address') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->address ?? '-' }}
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Email Status') }} :</strong>
            </div>
            <div class="col-lg-6">
              @if ($user->email_verified == 1)
                <span class="badge badge-success">{{ __('Verified') }}</span>
              @elseif ($user->email_verified == 0)
                <span class="badge badge-danger">{{ __('Unverified') }}</span>
              @endif
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Account Status') }} :</strong>
            </div>
            <div class="col-lg-6">
              @if ($user->status == 1)
                <span class="badge badge-success">{{ __('Active') }}</span>
              @elseif ($user->status == 0)
                <span class="badge badge-danger">{{ __('Banned') }}</span>
              @endif
            </div>
          </div>


        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ __('Billing Details') }}</h4>
        </div>
        <div class="card-body">

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Billing First Name') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->billing_fname ?? '-' }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Billing Last Name') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->billing_lname }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Billing Email') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->billing_email }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Billing Number') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->billing_number }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Billing City') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->billing_city }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Billing State') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->billing_state }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Billing Address') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->billing_address }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Billing Country') }} :</strong>
            </div>
            <div class="col-lg-6">
              {{ $user->billing_country }}
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ __('Shipping Details') }}</h4>
        </div>
        <div class="card-body">

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Shipping First Name') }} : </strong>
            </div>
            <div class="col-lg-6">
              {{ $user->shipping_fname ?? '-' }}
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Shipping Last Name') }} : </strong>
            </div>
            <div class="col-lg-6">
              {{ $user->shipping_lname }}
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Shipping Email') }} : </strong>
            </div>
            <div class="col-lg-6">
              {{ $user->shipping_email }}
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Shipping Number') }} : </strong>
            </div>
            <div class="col-lg-6">
              {{ $user->shipping_number }}
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Shipping City') }} : </strong>
            </div>
            <div class="col-lg-6">
              {{ $user->shipping_city }}
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Shipping State') }} : </strong>
            </div>
            <div class="col-lg-6">
              {{ $user->shipping_state }}
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Shipping Country') }} : </strong>
            </div>
            <div class="col-lg-6">
              {{ $user->shipping_country }}
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-lg-6">
              <strong>{{ __('Shipping Address') }} : </strong>
            </div>
            <div class="col-lg-6">
              {{ $user->shipping_address }}
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
