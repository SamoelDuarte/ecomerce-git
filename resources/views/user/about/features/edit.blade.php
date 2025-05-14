@extends('user.layout')

@includeIf('user.partials.rtl-style')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Features') }}</h4>
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
        <a href="#">{{ __('Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('About Us') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('user.pages.aboutus.about', ['language' => $d_lang->code]) }}">{{ __('About') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Features') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title">{{ __('Edit Features') }}</div>
            </div>
            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="{{ route('user.pages.aboutus.about', ['language' => $d_lang->code]) }}"
                class="btn btn-info btn-sm {{ $dashboard_language->rtl == 1 ? 'float-left ' : 'float-right' }}"><i
                  class="fas fa-backward"></i>
                {{ __('Back') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mx-auto">
              <form id="featueUpdateForm"
                action="{{ route('user.pages.about_us.features.update', ['id' => $data->id]) }}" method="post">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Features Icon') }} <span class="text-danger">**</span></label>
                  <div class="btn-group d-block">
                    <button type="button" class="btn btn-primary iconpicker-component edit-iconpicker-component">
                      <i class="{{ $data->icon }}" id="in_icon"></i>
                    </button>
                    <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car"
                      data-toggle="dropdown"></button>
                    <div class="dropdown-menu"></div>
                  </div>

                  <input type="hidden" id="inputIcon" name="icon" value="{{ $data->icon }}">
                  @error('icon')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror

                  <div class="text-warning mt-2">
                    <small>{{ __('Click on the dropdown icon to select an icon.') }}</small>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="title" placeholder="{{ __('Enter Title') }}"
                    id="intitle" value="{{ $data->title }}">
                  @error('title')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="">{{ __('Subtitle') }} <span class="text-danger">**</span></label>
                  <input type="text" name="subtitle" class="form-control" placeholder="{{ __('Enter Subtitle') }}"
                    id="insubtitle" value="{{ $data->subtitle }}">
                  @error('subtitle')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
                  <input id="incolor" class="form-control jscolor" name="color" value="{{ $data->color }}">
                  @error('color')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                  <input type="number" class="form-control" name="serial_number"
                    placeholder="{{ __('Enter Serial Number') }}" id="in_serial_number"
                    value="{{ $data->serial_number }}">
                  @error('serial_number')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                  <p class="text-warning mt-2 mb-0">
                    <small>{{ __('The higher the serial number is, the later the features will be shown.') }}</small>
                  </p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
                  <select class="form-control" name="status">
                    <option value="" selected disabled>{{ __('Enter name') }}
                    </option>
                    <option @selected($data->status == 1) value="1" selected>
                      {{ __('Active') }}</option>
                    <option @selected($data->status == 0) value="0">{{ __('Deactive') }}
                    </option>
                  </select>
                  @error('status')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                  <p id="errstatus" class="mb-0 text-danger em"></p>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success" form="featueUpdateForm">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
