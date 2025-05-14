@extends('user-front.layout')

@section('meta-description', !empty($seo) ? $seo->signup_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->signup_meta_keywords : '')
@section('breadcrumb_title', $pageHeading->signup_page ?? __('Signup'))
@section('page-title', $pageHeading->signup_page ?? __('Signup'))
@section('content')
  <!-- Authentication Start -->
  <div class="authentication-area ptb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">
          <div class="auth-form p-30 border radius-md">
            @if (Session::has('warning'))
              <div class="alert alert-danger text-danger">{{ Session::get('warning') }}</div>
            @endif
            @if (Session::has('sendmail'))
              <div class="alert alert-success mb-4">
                <p>{{ __(Session::get('sendmail')) }}</p>
              </div>
            @endif

            <form action="{{ route('customer.signup.submit', getParam()) }}" method="POST">
              @csrf
              <div class="title">
                <h3 class="mb-20">{{ $keywords['Signup'] ?? __('Signup') }}</h3>
              </div>
              <div class="form-group mb-30">
                <input type="text" placeholder="{{ $keywords['Username'] ?? __('Username') }}" class="form-control"
                  name="username" value="{{ old('username') }}">
                @error('username')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group mb-30">
                <input type="email" placeholder="{{ $keywords['Email_Address'] ?? __('Email Address') }}"
                  class="form-control" name="email" value="{{ old('email') }}" required>
                @error('email')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group mb-30">
                <input required type="password" placeholder="{{ $keywords['Enter Password'] ?? __('Enter Password') }}" class="form-control" name="password"
                  value="{{ old('password') }}" required>
                @error('password')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group mb-30">
                <input type="password" placeholder="{{ $keywords['Enter Confirm Password'] ?? __('Enter Confirm Password') }}" class="form-control" name="password_confirmation"
                  value="{{ old('password_confirmation') }}" required>
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
                <div class="col-8 col-xs-12">
                  <div class="link go-signup">
                    {{ $keywords['Already_have_an_account'] ?? __('Already have an account') }}? <a
                      href="{{ route('customer.login', getParam()) }}">{{ $keywords['Click_here'] ?? __('Click here ') }}</a>{{ $keywords['to Login'] ?? __(' to Login') }}
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-lg btn-primary radius-md w-100">
                {{ $keywords['Signup'] ?? __('Sign up!') }} </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Authentication End -->
@endsection
