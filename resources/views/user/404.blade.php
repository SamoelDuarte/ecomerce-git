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
    <h4 class="page-title">{{ __('404 Page') }}</h4>
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
        <a href="#">{{ __('404 Page') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update 404 Page') }}</div>
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
            <div class="col-lg-6 m-auto">
              <form id="ajaxForm"
                action="{{ route('user.not_found_page.update', ['language' => request()->input('language')]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <div class="mb-2">
                    <label for="image">
                      <strong>{{ __('Image') }}*</strong>
                    </label>
                  </div>
                  <div class="showImage mb-3">
                    <img
                      src="{{ @$image ? asset('assets/user-front/images/' . $image) : asset('assets/admin/img/noimage.jpg') }}"
                      alt="..." class="img-thumbnail">
                  </div>
                  <br>
                  <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                    {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="page_not_found_image">
                  </div>
                  <p id="errpage_not_found_image" class="mb-0 text-danger em"></p>
                  <p class="text-warning p-0 mb-1">
                    {{ __('Recommended Image size : 600X400') }}
                  </p>
                </div>

                <div class="form-group">
                  <label>{{ __('Title') }}*</label>
                  <input class="form-control" name="user_not_found_title"
                    value="{{ $data->user_not_found_title ?? null }}" />
                  <p id="erruser_not_found_title" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label>{{ __('Subtitle') }}*</label>
                  <input class="form-control" name="user_not_found_subtitle"
                    value="{{ $data->user_not_found_subtitle ?? null }}" />
                  <p id="erruser_not_found_subtitle" class="mb-0 text-danger em"></p>
                </div>
              </form>
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
      </div>
    </div>
  </div>
@endsection
