@extends('admin.layout')

@php
  $selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Contact Page') }}</h4>
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
        <a href="#">{{ __('Contact Page') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form enctype="multipart/form-data" action="{{ route('admin.contact.update', $lang_id) }}" method="POST">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Contact Page') }}</div>
              </div>
                  <div class="col-lg-2">
              @include('admin.partials.languages')
            </div>
            </div>
          </div>
          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-6 m-auto">
                @csrf
                <div class="form-group">
                  <label>{{ __('Address') }} <span class="text-danger">**</span></label>
                  <textarea class="form-control" name="contact_addresses" rows="4" placeholder="{{ __('Enter Address') }}">{{ $abe->contact_addresses }}</textarea>
                  <div class="text-warning">{{ __('Use newline to seperate multiple addresses.') }}</div>
                  @if ($errors->has('contact_addresses'))
                    <p class="mb-0 text-danger">{{ $errors->first('contact_addresses') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Phone') }} <span class="text-danger">**</span></label>
                  <input class="form-control" data-role="tagsinput" name="contact_numbers"
                    value="{{ $abe->contact_numbers }}" placeholder="{{ __('Enter Phone Number') }}">
                  <div class="text-warning">{{ __('Use comma (,) to add multiple Phone Numbers') }}</div>
                  @if ($errors->has('contact_numbers'))
                    <p class="mb-0 text-danger">{{ $errors->first('contact_numbers') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label>{{ __('Email') }} <span class="text-danger">**</span></label>
                  <input class="form-control ltr" data-role="tagsinput" name="contact_mails"
                    value="{{ $abe->contact_mails }}" placeholder="{{ __('Enter Email Addresses') }}">
                  <div class="text-warning">{{ __('Use comma (,) to add multiple Email Addresses') }}</div>
                </div>

              </div>
            </div>
          </div>
          <div class="card-footer pt-3">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button id="displayNotif" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
