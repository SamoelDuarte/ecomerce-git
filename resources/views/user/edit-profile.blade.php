@extends('user.layout')

@php
  $selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/cropper.css') }}">
@endsection
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Profile') }}</h4>
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
        <a href="#">{{ __('Edit Profile') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Profile') }}</div>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-10 mx-auto">
              <form id="ajaxForm" class="" action="{{ route('user-profile-update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="mb-2">
                        <label for="image"><strong>{{ __('Profile Image') }}</strong></label>
                      </div>
                      <div class="showImage mb-3">
                        <img
                          src="{{ $user->photo ? asset('assets/front/img/user/' . $user->photo) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <br>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="photo">
                      </div>
                      <p class="text-warning">{{ __('Image Size') }} {{ __('100x100') }}</p>
                      <p id="errphoto" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">{{ __('Shop Name') }} <span class="text-danger">**</span></label>
                      <input type="text" class="form-control" name="shop_name" value="{{ $user->shop_name }}"
                        placeholder="{{ __('Enter shop name') }}">
                      <p id="errshop_name" class="mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">{{ __('Username') }} <span class="text-danger">**</span></label>
                      <input type="text" class="form-control" name="username" value="{{ $user->username }}"
                        placeholder="{{ __('Enter username') }}">
                      <p id="errusername" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">{{ __('Phone') }} <span class="text-danger">**</span></label>
                      <input type="text" class="form-control" name="phone" value="{{ $user->phone }}"
                        placeholder="{{ __('Enter phone') }}">
                      <p id="errphone" class="mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">{{ __('City') }} <span class="text-danger">**</span></label>
                      <input type="text" class="form-control" name="city" rows="5"
                        value="{{ $user->city }}">
                      <p id="errcity" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">{{ __('State') }} </label>
                      <input type="text" class="form-control" name="state" rows="5"
                        value="{{ $user->state }}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">{{ __('Country') }} <span class="text-danger">**</span></label>
                      <input type="text" class="form-control" name="country" rows="5"
                        value="{{ $user->country }}">
                      <p id="errcountry" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">{{ __('Category') }}</label>
                      <select name="category_id" class="form-control select2">
                        <option value="">{{ __('Select Shop Category') }}</option>
                        @foreach ($categories as $category)
                          <option value="{{ $category->unique_id }}" @selected($category->unique_id == $user->category_id)>{{ $category->name }}
                          </option>
                        @endforeach
                      </select>

                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Address') }} <span class="text-danger">**</span></label>
                      <textarea type="text" class="form-control" name="address" rows="5">{{ $user->address }}</textarea>
                      <p id="erraddress" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
