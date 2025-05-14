@extends('user-front.layout')

@section('breadcrumb_title', $keywords['Reset password'] ?? __('Reset password'))
@section('page-title', $keywords['Reset password'] ?? __('Reset password'))

@section('content')
  <!--====== PROFILE PART ENDS ======-->

  <div class="authentication-area ptb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">
          <div class="auth-form p-30 border radius-md">
            @if (Session::has('error'))
              <div class="alert alert-danger text-danger">{{ Session::get('error') }}</div>
            @endif

            <form id="#authForm" action="{{ route('customer.reset_password_submit', getParam()) }}" method="POST">
              @csrf
              <input type="hidden" name="user_id" value="{{ $user->id }}">
              <input type="hidden" name="token" value="{{ request()->input('token') }}">
              <div class="title">
                <h3 class="mb-20">{{ $keywords['Reset password'] ?? __('Reset password') }}</h3>
              </div>
              <div class="form-group mb-30">
                <input type="password" placeholder="{{ $keywords['New Password'] ?? __('New Password') }}"
                  class="form-control" name="new_password" value="{{ old('new_password') }}" required>
                @error('new_password')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
              <div class="form-group mb-30">
                <input type="password" placeholder="{{ $keywords['Confirm New Password'] ?? __('Confirm New Password') }}"
                  class="form-control" name="new_password_confirmation" value="{{ old('new_password_confirmation') }}"
                  required>
                @error('new_password_confirmation')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>

              <button type="submit" class="btn btn-lg btn-primary radius-md w-100">
                {{ $keywords['Update'] ?? __('Update') }}
              </button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
