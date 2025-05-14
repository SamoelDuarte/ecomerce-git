@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where('code', request()->input('language'))
      ->where('user_id', Auth::guard('web')->user()->id)
      ->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@if (!empty($header->language) && $header->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Top Header Section') }}</h4>
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
        <a href="#">{{ __('Top Header Section') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Top Header Section') }}
              </div>
            </div>
            <div class="col-lg-2">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          </div>
        </div>
        <div class="card-body pt-5 pb-4">
          <div class="row">
            <div class="col-lg-6 m-auto">

              <form id="ajaxForm" action="{{ route('user.header.update', $lang_id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Support Icon') }} <span class="text-danger">**</span></label>
                      <div class="btn-group d-block">
                        <button type="button" class="btn btn-primary iconpicker-component">
                          <i class="{{ $header != null ? $header->header_logo : '' }}"></i>
                        </button>
                        <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car"
                          data-toggle="dropdown">
                        </button>
                        <div class="dropdown-menu"></div>
                      </div>
                      <input id="inputIcon" type="hidden" name="icon"
                        value="{{ $header != null ? $header->header_logo : 'fas fa-heart' }}">
                      @if ($errors->has('icon'))
                        <p class="mb-0 text-danger">{{ $errors->first('icon') }}</p>
                      @endif
                      <div class="mt-2">
                        <small>{{ __('NB: click on the dropdown sign to select a icon.') }}</small>
                      </div>
                      <p id="erricon" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Support Text') }}</label>
                  <input type="text" class="form-control" name="header_text"
                    value="{{ $header != null ? $header->header_text : '' }}">
                  <p id="errheader_text" class="em text-danger mb-0"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Header Middle Text') }}</label>
                  <input type="text" class="form-control" name="header_middle_text"
                    value="{{ $header != null ? $header->header_middle_text : '' }}">
                  <p id="errheader_middle_text" class="em text-danger mb-0"></p>
                </div>
              </form>

            </div>
          </div>
        </div>

        <div class="card-header">
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
