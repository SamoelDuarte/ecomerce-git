@extends('admin.layout')

@if (!empty($feature->language) && $feature->language->rtl == 1)
  <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Feature') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a
          href="{{ route('admin.feature.index') . '?language=' . request()->input('language') }}">{{ __('Features') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Feature') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.feature.update') }}" method="post" enctype="multipart/form-data">
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Edit Feature') }}</div>
            <a class="btn btn-info btn-sm float-right d-inline-block"
              href="{{ route('admin.feature.index') . '?language=' . request()->input('language') }}">
              <span class="btn-label">
                <i class="fas fa-backward"></i>
              </span>
              {{ __('Back') }}
            </a>
          </div>
          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-6 m-auto">
                @csrf
                <input type="hidden" name="feature_id" value="{{ $feature->id }}">
                <div class="form-group">
                  <div class="mb-2">
                    <label for="image"><strong> {{ __('Feature Image') }}</strong></label>
                  </div>

                  <div class="showImage mb-3 pl-0 pr-0">
                    <img
                      src="{{ !empty($feature->icon) ? asset('assets/front/img/feature/' . $feature->icon) : asset('assets/admin/img/noimage.jpg') }}"
                      alt="..." class="img-thumbnail">
                  </div>
                  <br>
                  <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                    {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="image">
                  </div>
                  <p id="errimage" class="mb-0 text-danger em"></p>
                  <p class="p-0 text-warning">
                    {{ __('Recommended Image size : 62X62') }}
                  </p>
                </div>
                <div class="form-group">
                  <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
                  <input class="form-control" name="title" placeholder="{{ __('Enter title') }}"
                    value="{{ $feature->title }}">
                  @error('title')
                    <p class="mb-0 text-danger">{{ $message }}</p>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="">{{ __('Text') }} <span class="text-danger">**</span></label>
                  <input class="form-control" name="text" placeholder="{{ __('Enter text') }}"
                    value="{{ $feature->text }}">
                  @error('text')
                    <p class="mb-0 text-danger">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                  <input type="number" class="form-control ltr" name="serial_number"
                    value="{{ $feature->serial_number }}" placeholder="{{ __('Enter Serial Number') }}">
                  @error('serial_number')
                    <p class="mb-0 text-danger">{{ $message }}</p>
                  @enderror
                  <p class="text-warning">
                    <small>{{ __('The higher the serial number is, the later the feature will be shown.') }}</small>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer pt-3">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
