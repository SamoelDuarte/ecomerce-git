@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where('code', request()->input('language'))
      ->where('user_id', Auth::guard('web')->user()->id)
      ->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@if (!empty($footer->language) && $footer->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Footer Informations') }}</h4>
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
        <a href="#">{{ __('Footer') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Footer Informations') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Footer Informations') }}
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
            <div class="col-lg-8 mx-auto">

              <form id="ajaxForm" action="{{ route('user.footer.update', $lang_id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="mb-2">
                        <label for="image"><strong>{{ __('Logo') }}</strong></label>
                      </div>
                      <div class="showImage mb-3">
                        <img
                          src="{{ !is_null(@$footer->footer_logo) ? asset('assets/front/img/footer/' . $footer->footer_logo) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <br>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="file">
                      </div>
                      <p id="errfile" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="mb-2">
                        <label for="image"><strong>{{ __('Footer Background Image') }}</strong></label>
                      </div>
                      <div class="showImage2 mb-3">
                        <img
                          src="{{ !is_null(@$footer->background_image) ? asset('assets/front/img/footer/' . $footer->background_image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image2">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="footer_background_image">
                      </div>
                      <p id="errfooter_background_image" class="mb-0 text-danger em"></p>
                      <p class="text-warning p-0 mb-1">
                        {{ __('Recommended Image size : 1920X600') }}
                      </p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Footer Text') }}</label>
                      <textarea name="footer_text" class="form-control" rows="3">{{ $footer->footer_text ?? '' }}</textarea>
                      <p id="errfooter_text" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Useful Links Title') }}</label>
                      <input type="text" class="form-control" name="useful_links_title"
                        value="{{ $footer->useful_links_title ?? '' }}">
                      <p id="erruseful_links_title" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Subscriber Title') }}</label>
                      <input type="text" class="form-control" name="subscriber_title"
                        value="{{ $footer->subscriber_title ?? '' }}">
                      <p id="errsubscriber_title" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Subscriber Text') }}</label>
                      <textarea name="subscriber_text" class="form-control" rows="3">{{ $footer->subscriber_text ?? '' }}</textarea>
                      <p id="errsubscriber_text" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Copyright Text') }}</label>
                  <textarea id="copyright_text" name="copyright_text" class="summernote form-control" data-height="100">{{ replaceBaseUrl($footer->copyright_text ?? '') }}</textarea>
                  <p id="errcopyright_text" class="em text-danger mb-0"></p>
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
