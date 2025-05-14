@extends('admin.layout')

@section('pagename')
  - {{ __('Edit Profile') }}
@endsection

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Profile') }}</h4>
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
        <a href="#">{{ __('Edit Profile') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Update Profile') }}</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 m-auto">

              <form action="{{ route('admin.updateProfile') }}" method="post" role="form"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-body">
                  <div class="form-group">
                    <div class="col-md-12">
                      <label for="image"><strong>{{ __('Profile Image') }}</strong></label>
                    </div>
                    <div class="col-md-12">
                      <div class="showImage mb-3 pl-0 pr-0">
                        <img
                          src="{{ !empty(Auth::guard('admin')->user()->image) ? asset('assets/admin/img/propics/' . Auth::guard('admin')->user()->image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <br>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="profile_image">
                      </div>
                      <p id="errimage" class="mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-12">
                      <label>{{ __('Username') }} <span class="text-danger">**</span></label>
                    </div>
                    <div class="col-md-12">
                      <input class="form-control input-lg" name="username" value="{{ $admin->username }}"
                        placeholder="{{ __('Your Username') }}" type="text">
                      @if ($errors->has('username'))
                        <p class="text-danger mb-0">{{ $errors->first('username') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <label>{{ __('Email') }} <span class="text-danger">**</span></label>
                    </div>
                    <div class="col-md-12">
                      <input class="form-control input-lg" name="email" value="{{ $admin->email }}"
                        placeholder="{{ __('Enter Your Email') }}" type="text">
                      @if ($errors->has('email'))
                        <p class="text-danger mb-0">{{ $errors->first('email') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <label>{{ __('First Name') }} <span class="text-danger">**</span></label>
                    </div>
                    <div class="col-md-12">
                      <input class="form-control input-lg" name="first_name" value="{{ $admin->first_name }}"
                        placeholder="{{ __('Enter Your First Name') }}" type="text">
                      @if ($errors->has('first_name'))
                        <p class="text-danger mb-0">{{ $errors->first('first_name') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <label>{{ __('Last Name') }} <span class="text-danger">**</span></label>
                    </div>
                    <div class="col-md-12">
                      <input class="form-control input-lg" name="last_name" value="{{ $admin->last_name }}"
                        placeholder="{{ __('Enter Your Last Name') }}" type="last_name">
                      @if ($errors->has('last_name'))
                        <p class="text-danger mb-0">{{ $errors->first('last_name') }}</p>
                      @endif
                    </div>
                  </div>
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

      </div>
    </div>
  </div>
@endsection
