@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Registered Customers') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="#">
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
        <a href="#">{{ __('Change Password') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('user.register.user.updatePassword') }}" method="post" role="form">
          <div class="card-header">
            <div class="row">
              <div class="col-6">
                <div class="card-title">{{ __('Change Password') }}
                  ({{ $user->username }})</div>
              </div>
              <div class="col-6 {{ $dashboard_language->rtl == 1 ? 'text-left' : 'text-right' }} ">
                <a href="{{ route('user.register.user') }}" class="btn btn-sm btn-primary">{{ __('Back') }}</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 m-auto">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="form-body">

                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="">{{ __('New Password') }} <span class="text-danger">**</span></label>
                        <input type="password" class="form-control" placeholder="{{ __('New Password') }}" name="npass"
                          value="{{ Request::old('npass') }}">
                        @error('npass')
                          <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="">{{ __('Confirm Password') }} <span class="text-danger">**</span></label>
                        <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}"
                          name="cfpass" value="{{ Request::old('cfpass') }}">
                        @error('cfpass')
                          <p class="text-danger mb-0">{{ $message }}</p>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-success">{{ __('Submit') }}</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
