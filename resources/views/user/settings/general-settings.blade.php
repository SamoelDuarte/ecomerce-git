@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('General Settings') }}</h4>
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
        <a href="#">{{ __('Site Settings') }}</a>
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
        <form id="ajaxForm" action="{{ route('user.general_settings.update_info') }}" method="post"
          enctype="multipart/form-data">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update Information') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body py-5">
            <div class="row">
              <div class="col-lg-10 mx-auto">
                <h3 class="text-warning">{{ __('Information') }}</h3>
                <hr class="divider">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0">
                        <label for="image"><strong>{{ __('Favicon') }} </strong></label>
                      </div>
                      <div class="col-md-12 showImage2 mb-3 pl-0 pr-0">
                        <img
                          src="{{ !is_null(@$data->favicon) ? asset('assets/front/img/user/' . $data->favicon) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image2">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="favicon">
                      </div>
                      @if ($errors->has('favicon'))
                        <p id="errfavicon" class="mb-0 text-danger em">{{ $errors->first('favicon') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0">
                        <label for="image"><strong>{{ __('Logo') }} </strong></label>
                      </div>
                      <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                        <img
                          src="{{ isset($data->logo) ? asset('assets/front/img/user/' . $data->logo) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="logo">
                      </div>
                      <p class="text-warning">
                        {{ __('Only JPG, JPEG, PNG images are allowed') }}
                      </p>
                      @if ($errors->has('logo'))
                        <p id="errlogo" class="mb-0 text-danger em">{{ $errors->first('logo') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0">
                        <label for="image"><strong>{{ __('Preloader') }} </strong></label>
                      </div>
                      <div class="col-md-12 showImage3 mb-3 pl-0 pr-0">
                        <img
                          src="{{ isset($data->preloader) ? asset('assets/front/img/user/' . $data->preloader) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image3">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="preloader">
                      </div>

                      <p class="text-warning">
                        {{ __('Only JPG, JPEG, PNG, GIF images are allowed') }}
                      </p>
                      @if ($errors->has('preloader'))
                        <p id="errpreloader" class="mb-0 text-danger em">{{ $errors->first('preloader') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>{{ __('Preloader Status') }} <span class="text-danger">**</span></label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="preloader_status" value="1" class="selectgroup-input"
                            {{ $data->preloader_status == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="preloader_status" value="0" class="selectgroup-input"
                            {{ $data->preloader_status == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-10 mx-auto">
                <h3 class="text-warning">{{ __('Set Timezone') }}</h3>
                <hr class="divider">
                <div class="form-group">
                  <label>{{ __('Timezone') }}
                    <span class="text-danger">**</span></label>
                  <select name="timezone" class="form-control select2">
                    @foreach ($timezones as $timezone)
                      <option value="{{ $timezone->timezone }}" @selected($timezone->timezone == $data->timezone)>{{ $timezone->timezone }}
                      </option>
                    @endforeach
                  </select>
                  <p id="errtimezone" class="em text-danger mb-0"></p>
                </div>
              </div>

              <div class="col-lg-10 mx-auto">
                <h3 class="text-warning">{{ __('Currency Settings') }}</h3>
                <hr class="divider">
                <div class="form-group">
                  <label>{{ __('Base Currency Symbol Position') }}
                    <span class="text-danger">**</span></label>
                  <select name="base_currency_symbol_position" class="form-control">
                    <option value="left" {{ $data->base_currency_symbol_position == 'left' ? 'selected' : '' }}>
                      {{ __('Left') }}
                    </option>
                    <option value="right" {{ $data->base_currency_symbol_position == 'right' ? 'selected' : '' }}>
                      {{ __('Right') }}
                    </option>
                  </select>
                  <p id="errbase_currency_symbol_position" class="em text-danger mb-0"></p>
                </div>
              </div>

              <div class="col-lg-10 mx-auto">
                <h3 class="text-warning">{{ __('Website Appearance') }}</h3>
                <hr class="divider">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Base Color') }}</label>
                      <input type="text" class="form-control jscolor" name="base_color"
                        value="{{ $data->base_color }}">
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" id="submitBtn" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
