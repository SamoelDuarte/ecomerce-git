@extends('front.layout')

@section('meta-description', !empty($seo) ? $seo->login_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->login_meta_keywords : '')

@section('pagename')
  - {{ $pageHeading ?? __('Login') }}
@endsection
@section('breadcrumb-title')
  {{ $pageHeading ?? __('Login') }}
@endsection
@section('breadcrumb-link')
  {{ $pageHeading ?? __('Login') }}
@endsection

@section('content')
  <!--====== Start user-form-section ======-->

  <div class="authentication-area pt-90 pb-120">
    <div class="container">
      <div class="main-form">
        <form id="#authForm" action="{{ route('user.login') }}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="title">
            <h3>{{ __('login') }}</h3>
          </div>
          <div class="form-group mb-30">
            <input type="email" name="email" class="form-control" placeholder="{{ __('Email Address') }}" required>
            @if (Session::has('err'))
              <p class="text-danger mb-2 mt-2">{{ Session::get('err') }}</p>
            @endif
            @error('email')
              <p class="text-danger mb-2 mt-2">{{ $message }}</p>
            @enderror
          </div>

          <div class="form-group mb-30">
            <input type="password" name="password" class="form-control" placeholder="{{ __('Password') }}" required>
            @error('password')
              <p class="text-danger mb-2 mt-2">{{ $message }}</p>
            @enderror
          </div>
          <div class="form_group">
            @if ($bs->is_recaptcha == 1)
              <div class="d-block mb-4">
                {!! NoCaptcha::renderJs() !!}
                {!! NoCaptcha::display() !!}
                @if ($errors->has('g-recaptcha-response'))
                  @php
                    $errmsg = $errors->first('g-recaptcha-response');
                  @endphp
                  <p class="text-danger mb-0 mt-2">{{ __("$errmsg") }}</p>
                @endif
              </div>
            @endif
          </div>
          <div class="row align-items-center">
            <div class="col-4 col-xs-12">
              <div class="link">
                <a href="{{ route('user.forgot.password.form') }}">{{ __('Lost your password') }}?</a>
              </div>
            </div>
            <div class="col-8 col-xs-12">
              <div class="link go-signup">
                {{ __("Don't have an account?") }} <a href="{{ route('front.pricing') }}">{{ __('Click Here') }}</a>
                {{ __('to Signup') }}
              </div>
            </div>
          </div>
          <button type="submit" class="btn primary-btn w-100"> {{ __('LOG IN') }} </button>
        </form>
      </div>
    </div>
  </div>
  <!-- Authentication End -->



@endsection
