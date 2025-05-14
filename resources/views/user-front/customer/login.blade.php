@extends('user-front.layout')
@section('meta-description', !empty($seo) ? $seo->login_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->login_meta_keywords : '')
@section('breadcrumb_title', $pageHeading->login_page ?? __('Login'))
@section('page-title', $pageHeading->login_page ?? __('Login'))

@section('content')
  @php
    $input = request()->input('redirected');

  @endphp
  <!-- Authentication Start -->
  <div class="authentication-area ptb-100">
    <div class="container">

      <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">

          @if (!onlyDigitalItemsInCart())
            @if ($input == 'checkout')
              <div class="form-group">
                <h3 class="text-center text-muted  mb-20">
                  <a href="{{ route('front.user.checkout', [getParam(), 'type' => 'guest']) }}"
                    class="underline checkout-guest">{{ $keywords['Checkout as Guest'] ?? __('Checkout as Guest') }}</a>
                </h3>
              </div>
              <div class="mt-4 mb-3 text-center">
                <h3 class="mb-0"><strong> {{ $keywords['OR'] ?? __('OR') }},</strong></h3>
              </div>
            @endif
          @endif

          <div class="auth-form p-30 border radius-md">
            @if (Session::has('error'))
              <div class="alert alert-danger text-danger">{{ Session::get('error') }}</div>
            @endif
            @if (Session::has('success'))
              <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            <form id="#authForm" action="{{ route('customer.login_submit', getParam()) }}" method="POST">
              @csrf
              <input type="hidden" name="user_id" value="{{ $user->id }}">
              <div class="title">
                <h3 class="mb-20">{{ $keywords['Login'] ?? __('Log In') }}</h3>
              </div>
              <div class="form-group mb-30">
                <input type="email" placeholder="{{ $keywords['Enter_Email_Address'] ?? __('Enter Email Address') }}"
                  class="form-control" name="email" required>
                @error('email')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group mb-30">
                <input type="password" class="form-control" name="password"
                  placeholder="{{ $keywords['Enter Password'] ?? __('Enter Password') }}" required>
                @error('password')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              @if ($userBs->is_recaptcha == 1 && in_array('Google Recaptcha', $packagePermissions))
                <div class="form-group mb-30">
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
                </div>
              @endif
              <div class="row align-items-center mb-20">
                <div class="col-5 col-xs-12">
                  <div class="link">
                    <a href="{{ route('customer.forget_password', getParam()) }}">{{ $keywords['Forgot your password'] ?? __('Forgot your password') }}
                      ?</a>
                  </div>
                </div>
                <div class="col-7 col-xs-12">
                  <div class="link go-signup">
                    {{ $keywords['Dont_have_an_account'] ?? __('Dont have an account') }} ? <a
                      href="{{ route('customer.signup', getParam()) }}">{{ $keywords['Signup'] ?? __('Signup') }}</a>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-lg btn-primary radius-md w-100 mb-2">
                {{ $keywords['Login'] ?? __('Login') }}</button>
              @if (in_array('Google Login', $packagePermissions) && $userBs->is_google_login == 1)
                <p class="text-center login_or mb-2">{{ $keywords['or'] ?? __('OR') }}</p>
                <div class="login_google_area text-center ">
                  <a href="{{ route('customer.google.login', getParam()) }}" class="login_google radius-md">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20"
                      viewBox="0 0 48 48">
                      <path fill="#fbc02d"
                        d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12	s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20	s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
                      </path>
                      <path fill="#e53935"
                        d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039	l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
                      </path>
                      <path fill="#4caf50"
                        d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36	c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
                      </path>
                      <path fill="#1565c0"
                        d="M43.611,20.083L43.595,20L42,20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571	c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
                      </path>
                    </svg>
                    {{ $keywords['Login with Google'] ?? __('Login with Google') }}</a>
                </div>
              @endif
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- Authentication End -->

@endsection
