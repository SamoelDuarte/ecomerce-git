@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Push Notification Settings') }}</h4>
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
        <a href="#">{{ __('Push Notification') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">

        <div class="card-header">
          <div class="card-title">{{ __('Push Notification Settings') }}</div>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="pushSettingsForm" action="{{ route('admin.pushnotification.updateSettings') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <div class="col-12 mb-2 pl-0 pr-0">
                    <label for="image"><strong>{{ __('Icon Image') }} <span
                          class="text-danger">**</span></strong></label>
                  </div>
                  <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                    <img src="{{ asset('assets/front/img/' . 'pushnotification_icon.png') . '?' . time() }}"
                      alt="..." class="img-thumbnail">
                  </div>
                  <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                    {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="file">
                  </div>

                  @if ($errors->has('file'))
                    <p class="mb-0 text-danger em">{{ $errors->first('file') }}</p>
                  @endif
                </div>


                @if (env('VAPID_PUBLIC_KEY') == null && env('VAPID_PRIVATE_KEY') == null)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="">{{ __('VAPID Public Key') }} <span class="text-danger">**</span></label>
                        <input type="text" class="form-control" name="vapid_public_key"
                          placeholder="{{ __('Enter VAPID Public Key') }}">
                        @error('vapid_public_key')
                          <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                        @enderror
                      </div>

                      <div class="form-group">
                        <label for="">{{ __('VAPID Private Key') }} <span class="text-danger">**</span></label>
                        <input type="text" class="form-control" name="vapid_private_key"
                          placeholder="{{ __('Enter VAPID Private Key') }}">
                        @error('vapid_private_key')
                          <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                        @enderror
                      </div>

                      <p class="ml-3 mb-0 text-light">
                        <a href="//www.attheminute.com/vapid-key-generator/" target="_blank" class="text-decoration-none">
                          {{ __('Click Here') }}
                        </a> {{ __('to generate the VAPID Public Key & the VAPID Private Key') . '.' }}
                      </p>
                    </div>
                  </div>
                @endif
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="form-group from-show-notify row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success" form="pushSettingsForm">{{ __('Update') }}</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
