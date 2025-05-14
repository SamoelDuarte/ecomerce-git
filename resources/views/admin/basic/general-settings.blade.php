@extends('admin.layout')

@if (!empty($abe->language) && $abe->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('General Settings') }}</h4>
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
        <a href="#">{{ __('Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('General Settings') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form class="" action="{{ route('admin.general-settings.update') }}" method="post"
          enctype="multipart/form-data">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update General Settings') }}</div>
              </div>
            </div>
          </div>
          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-10 mx-auto">
                @csrf
                <h3 class="text-warning">{{ __('Information') }}</h3>
                <hr class="divider">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>{{ __('Website Title') }} <span class="text-danger">**</span></label>
                      <input class="form-control" name="website_title" value="{{ $abs->website_title }}">
                      @if ($errors->has('website_title'))
                        <p class="mb-0 text-danger">{{ $errors->first('website_title') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0 pr-0">
                        <label for="image"><strong>{{ __('Favicon') }} <span
                              class="text-danger">**</span></strong></label>
                      </div>
                      <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                        <img
                          src="{{ $abs->favicon ? asset('assets/front/img/' . $abs->favicon) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="favicon">
                      </div>
                      @error('favicon')
                        <p class="mb-0 text-danger">{{ $errors->first('favicon') }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0 pr-0">
                        <label for="image2"><strong> {{ __('Logo') }} <span
                              class="text-danger">**</span></strong></label>
                      </div>
                      <div class="col-md-12 showImage2 mb-3 pl-0 pr-0">
                        <img
                          src="{{ $abs->logo ? asset('assets/front/img/' . $abs->logo) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>

                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image2">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="logo">
                      </div>
                      @error('logo')
                        <p class="mb-0 text-danger">{{ $errors->first('logo') }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0 pr-0">
                        <label for="image3"><strong> {{ __('Preloader') }} <span
                              class="text-danger">**</span></strong></label>
                      </div>
                      <div class="col-md-12 showImage3 mb-3 pl-0 pr-0">
                        <img
                          src="{{ $abs->preloader ? asset('assets/front/img/' . $abs->preloader) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>

                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image3">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="preloader">
                      </div>
                      @error('preloader')
                        <p class="mb-0 text-danger">{{ $errors->first('preloader') }}</p>
                      @enderror
                      <p class="text-warning mb-0">{{ __('Only GIF, JPG, JPEG, PNG file formats are allowed') }}</p>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label>{{ __('Preloader Status') }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="preloader_status" value="1" class="selectgroup-input"
                            {{ $abs->preloader_status == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="preloader_status" value="0" class="selectgroup-input"
                            {{ $abs->preloader_status == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <br>
                <h3 class="text-warning">{{ __('Regional Time Preferences') }}</h3>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Timezone') }} <span class="text-danger">**</span></label>
                      <select name="timezone" class="form-control select2">
                        @foreach ($timezones as $timezone)
                          <option value="{{ $timezone->timezone }}"
                            {{ $timezone->timezone == $abe->timezone ? 'selected' : '' }}>{{ $timezone->timezone }}
                          </option>
                        @endforeach
                      </select>
                      @if ($errors->has('timezone'))
                        <p class="mb-0 text-danger">{{ $errors->first('timezone') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ $keywords['Time Format'] ?? __('Time Format') }} <span
                          class="text-danger">**</span></label>
                      <select name="time_format" class="form-control select2">
                        <option value="12" @selected($abs->time_format == 12)>{{ $keywords['12 Hour'] ?? __('12 Hour') }}
                        </option>
                        <option value="24" @selected($abs->time_format == 24)>{{ $keywords['24 Hour'] ?? __('24 Hour') }}
                        </option>
                      </select>
                      <p id="errtop_rated_count" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>
                <br>

                <h3 class="text-warning">{{ __('Website Appearance') }}</h3>
                <hr class="divider">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Base Color Code 1') }} <span class="text-danger">**</span></label>
                      <input class="jscolor form-control ltr" name="base_color" value="{{ $abs->base_color }}">
                      @if ($errors->has('base_color'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_color') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Base Color Code 2') }} <span class="text-danger">**</span></label>
                      <input class="jscolor form-control ltr" name="base_color_2" value="{{ $abs->base_color_2 }}">
                      @if ($errors->has('base_color_2'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_color_2') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <br>
                <h3 class="text-warning">{{ __('Currency Settings') }}</h3>
                <hr class="divider">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Base Currency Symbol') }} <span class="text-danger">**</span></label>
                      <input type="text" class="form-control ltr" name="base_currency_symbol"
                        value="{{ $abe->base_currency_symbol }}">
                      @if ($errors->has('base_currency_symbol'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_symbol') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Base Currency Symbol Position') }} <span class="text-danger">**</span></label>
                      <select name="base_currency_symbol_position" class="form-control ltr">
                        <option value="left" {{ $abe->base_currency_symbol_position == 'left' ? 'selected' : '' }}>
                          {{ __('Left') }}
                        </option>
                        <option value="right" {{ $abe->base_currency_symbol_position == 'right' ? 'selected' : '' }}>
                          {{ __('Right') }}</option>
                      </select>
                      @if ($errors->has('base_currency_symbol_position'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_symbol_position') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Base Currency Text') }} <span class="text-danger">**</span></label>
                      <input type="text" class="form-control ltr" name="base_currency_text"
                        value="{{ $abe->base_currency_text }}">
                      @if ($errors->has('base_currency_text'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_text') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Base Currency Text Position') }} <span class="text-danger">**</span></label>
                      <select name="base_currency_text_position" class="form-control ltr">
                        <option value="left" {{ $abe->base_currency_text_position == 'left' ? 'selected' : '' }}>
                          {{ __('Left') }}
                        </option>
                        <option value="right" {{ $abe->base_currency_text_position == 'right' ? 'selected' : '' }}>
                          {{ __('Right') }}
                        </option>
                      </select>
                      @if ($errors->has('base_currency_text_position'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_text_position') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Base Currency Rate') }} <span class="text-danger">**</span></label>
                      <div class="input-group mb-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">{{ __('1 USD =') }}</span>
                        </div>
                        <input type="text" name="base_currency_rate" class="form-control ltr"
                          value="{{ $abe->base_currency_rate }}">
                        <div class="input-group-append">
                          <span class="input-group-text">{{ $abe->base_currency_text }}</span>
                        </div>
                      </div>

                      @if ($errors->has('base_currency_rate'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_rate') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" id="displayNotif" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
