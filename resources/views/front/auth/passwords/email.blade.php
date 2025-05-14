@extends('front.layout')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/front/css/forgot-password.css') }}">
@endsection

@section('pagename')
  - {{ $pageHeading ?? __('Reset Password') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->forget_password_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->forget_password_meta_keywords : '')

@section('breadcrumb-title')
  {{ $pageHeading ?? __('Reset Password') }}
@endsection
@section('breadcrumb-link')
  {{ $pageHeading ?? __('Reset Password') }}
@endsection

@section('content')
  <div class="authentication-area pt-90 pb-120">
    <div class="container">
      <div class="main-form">
        <form id="#authForm" action="{{ route('user-forgot-submit') }}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="title">
            <h3>{{ __('Reset Password') }}</h3>
          </div>
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="{{ __('Email Address') }}"
              value="{{ old('email') }}" required>
            @if (Session::has('err'))
              <p class="text-danger mb-2 mt-2">{{ Session::get('err') }}</p>
            @endif
            @error('email')
              <p class="text-danger mb-2 mt-2">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-group mt-3">
            @if ($bs->is_recaptcha == 1)
              <div class="d-block">
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

          <button type="submit" class="btn primary-btn w-100"> {{ __('Send Request') }} </button>
        </form>
      </div>
    </div>
  </div>
  <!-- Authentication End -->

@endsection
