@extends('user.layout')
@php
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Contact Page') }}</h4>
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
        <a href="#">{{ __('Contact Page') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Contact') }}</div>
            </div>

            <div class="col-lg-2">
              @if (!is_null($userDefaultLang))
                @if (!empty($userLanguages))
                  <select name="userLanguage" class="form-control"
                    onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                    <option value="" selected disabled>
                      {{ __('Select a Language') }}</option>
                    @foreach ($userLanguages as $lang)
                      <option value="{{ $lang->code }}"
                        {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}</option>
                    @endforeach
                  </select>
                @endif
              @endif
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 ">
              <form id="contactSecForm"
                action="{{ route('user.home_page.heroStatic.update_slider_info', ['language' => request()->input('language')]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-12 mb-2">
                        <label for="image"><strong>{{ __('Image') }}</strong></label>
                      </div>
                      <div class="col-md-12 showImage mb-3 pl-0  pr-0">
                        <img
                          src="{{ isset($data->img) ? asset('assets/front/img/hero_slider/' . $data->img) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>

                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="slider_img">
                      </div>

                      @if ($errors->has('slider_img'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('slider_img') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                @if ($userBs->theme == 'furniture' || $userBs->theme == 'kids')
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Title') }}</label>
                        <input type="text" class="form-control" name="title" placeholder="{{ __('Enter Title') }}"
                          value="{{ @$data->title }}">
                        @if ($errors->has('title'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('title') }}</p>
                        @endif
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Subtitle') }}</label>
                        <input type="text" class="form-control" name="subtitle"
                          placeholder="{{ __('Enter Subtitle') }}" value="{{ @$data->subtitle }}">
                        @if ($errors->has('subtitle'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('subtitle') }}</p>
                        @endif
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Button Name') }}</label>
                        <input type="text" class="form-control" name="btn_name"
                          placeholder="{{ __('Enter Button Name') }}" value="{{ @$data->btn_name }}">
                        @if ($errors->has('btn_name'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('btn_name') }}</p>
                        @endif
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Button URL') }}</label>
                        <input type="url" class="form-control" name="btn_url"
                          placeholder="{{ __('Enter Button URL') }}" value="{{ @$data->btn_url }}">
                        @if ($errors->has('btn_url'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('btn_url') }}</p>
                        @endif
                      </div>
                    </div>

                  </div>

                  <div class="row">

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Text') }} <span class="text-danger">**</span></label>
                        <input type="text" class="form-control" name="text" placeholder="{{ __('Enter Text') }}"
                          value="{{ @$data->text }}">
                        @if ($errors->has('text'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('text') }}</p>
                        @endif
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Video URL') }}</label>
                        <input type="text" class="form-control" name="video_url"
                          placeholder="{{ __('Enter Video URL') }}" value="{{ @$data->video_url }}">
                        @if ($errors->has('video_url'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('video_url') }}</p>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif

              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="contactSecForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
