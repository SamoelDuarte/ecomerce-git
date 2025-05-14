@extends('user.layout')

@includeIf('user.partials.rtl-style')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Counter') }}</h4>
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
        <a
          href="{{ route('user.pages.counter_section.index', ['language' => $d_lang->code]) }}">{{ __('Counter Section') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Counter') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title">{{ __('Edit Counter') }}</div>
            </div>
            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="{{ route('user.pages.counter_section.index', ['language' => $d_lang->code]) }}"
                class="btn btn-info btn-sm {{ $dashboard_language->rtl == 1 ? 'float-left ' : 'float-right' }}"><i
                  class="fas fa-backward"></i>
                {{ __('Back') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mx-auto">
              <form id="counterForm"
                action="{{ route('user.pages.counter_section.counter.update', ['id' => $data->id]) }}" method="post">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Icon') }} <span class="text-danger">**</span></label>
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
                  <label for="">{{ __('Amount') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="amount" placeholder="{{ __('Enter Amount') }}"
                    id="inamount" value="{{ $data->amount }}">
                  @error('amount')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
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
                  <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
                  <input id="incolor" class="form-control jscolor" name="color" value="{{ $data->color }}">
                  @error('color')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success" form="counterForm">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
