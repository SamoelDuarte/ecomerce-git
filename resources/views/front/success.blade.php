@extends('front.layout')

@section('pageHeading')
  {{ $keywords['payment_success'] ?? __('Success') }}
@endsection

@section('breadcrumb-title')
  {{ __('Success') }}
@endsection
@section('breadcrumb-link')
  {{ __('Success') }}
@endsection

@section('content')
  <!--====== Purchase Success Section Start ======-->

  <div class="purchase-message pt-120 pb-120">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="purchase-success purchase-success-card text-center">
            <div class="success-icon">
              <i class="fas fa-check"></i>
            </div>
            @auth
              <h1>{{ __('payment_success') }}</h1>
              <p class="paragraph-text px-5">
                {{ __('extend_success_msg') }}
              </p>
              <a href="{{ route('user-dashboard') }}" class="btn primary-btn">{{ __('Go to Dashboard') }}</a>
            @endauth
            @guest
              <h1>{{ __('payment_success') }}</h1>
              <p class="paragraph-text px-5">
                {{ __('payment_success_msg') }}
              </p>
              <a href="{{ route('front.index') }}" class="btn primary-btn">{{ __('Go Home') }}</a>
            @endguest
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--====== Purchase Success Section End ======-->
@endsection

@section('script')
  <script type="text/javascript"></script>
@endsection
