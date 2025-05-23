@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Change Password') }}</h4>
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
        <a href="#">{{ __('Change Password') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('user.updatePassword') }}" method="post" role="form">
          <div class="card-header">
            <div class="card-title">{{ __('Change Password') }}</div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 m-auto">
                @csrf
                <div class="form-body">
                  <div class="form-group">
                    <label>{{ __('Current Password') }} <span class="text-danger">**</span></label>
                    <div class="">
                      <input class="form-control" name="old_password" placeholder="{{ __('Your Current Password') }}"
                        type="password">
                      @if ($errors->has('old_password'))
                        <span class="text-danger">
                          {{ $errors->first('old_password') }}
                        </span>
                      @else
                        @if ($errors->first('oldPassMatch'))
                          <span class="text-danger">
                            {{ __("Old password doesn't match with the existing password!") }}
                          </span>
                        @endif
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label>{{ __('New Password') }} <span class="text-danger">**</span></label>
                    <div class="">
                      <input class="form-control" name="password" placeholder="{{ __('New Password') }}" type="password">
                      @if ($errors->has('password'))
                        <span class="text-danger">
                          {{ $errors->first('password') }}
                        </span>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label>{{ __('New Password Again') }} <span class="text-danger">**</span></label>
                    <div class="">
                      <input class="form-control" name="password_confirmation"
                        placeholder="{{ __('New Password Again') }}" type="password">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
