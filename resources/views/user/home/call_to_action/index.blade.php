@extends('user.layout')

@includeIf('user.partials.rtl-style')
@php
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Call to action section') }}</h4>
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
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Call to action section') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">
                {{ __('Update Call to action section') }}</div>
            </div>
            <div class="col-lg-4">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          </div>
        </div>
        <form action="{{ route('user.cta_section.update') }}" method="post" enctype="multipart/form-data">
          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-6 ">
                @csrf
                <input type="hidden" name="id" value="{{ @$data->id }}">
                <input type="hidden" name="language_id" value="{{ @$language_id }}">
                <div class="row">

                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0">
                        <label for="image"><strong>
                            {{ __('Background Image') }}</strong></label>
                      </div>
                      <div class="col-md-12 showImage mb-3  pl-0 pr-0">
                        <img
                          src="{{ !is_null(@$data->background_image) ? asset('assets/front/img/cta/' . @$data->background_image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="background_image">
                      </div>
                      <p class="text-warning p-0 mb-1">
                        {{ __('Recommended Image size : 1920X300') }}
                      </p>
                      <p id="errbackground_image" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  @if ($userBs->theme !== 'kids')
                    <div class="col-lg-12">
                      <div class="form-group">
                        <div class="col-12 mb-2  pl-0">
                          <label for="image"><strong>
                              {{ __('Side Image') }}</strong></label>
                        </div>
                        <div class="col-md-12 showImage2 mb-3 pl-0 pr-0">
                          <img
                            src="{{ !is_null(@$data->side_image) ? asset('assets/front/img/cta/' . @$data->side_image) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="..." class="img-thumbnail">
                        </div>

                        <div role="button" class="btn btn-primary btn-sm upload-btn" id="image2">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="side_image">
                        </div>
                        <p class="text-warning p-0 mb-1">
                          {{ __('Recommended Image size : 400X260') }}
                        </p>
                        <p id="errside_image" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                  @endif
                </div>
                <div class="form-group">
                  <label for="">{{ __('Title') }}</label>
                  <input class="form-control" name="title" placeholder="{{ __('Enter title') }}"
                    value="{{ @$data->title }}">
                  <p id="errtitle" class="mb-0 text-danger em"></p>
                </div>

                @if ($userBs->theme == 'electronics')
                  <div class="form-group">
                    <label for="">{{ __('Text') }}</label>
                    <input class="form-control" name="text" placeholder="{{ __('Enter text') }}"
                      value="{{ @$data->text }}">
                    <p id="errtext" class="mb-0 text-danger em"></p>
                  </div>
                @endif

                <div class="form-group">
                  <label for="">{{ __('Button Text') }}</label>
                  <input class="form-control" name="button_text" placeholder="{{ __('Enter Button Text') }}"
                    value="{{ @$data->button_text }}">
                  <p id="errbutton_text" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Button URL') }}</label>
                  <input type="url" class="form-control" name="button_url"
                    placeholder="{{ __('Enter Button URL') }}" value="{{ @$data->button_url }}">
                  <p id="errbutton_url" class="mb-0 text-danger em"></p>
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
        </form>
      </div>
    </div>
  </div>
  </div>
@endsection
