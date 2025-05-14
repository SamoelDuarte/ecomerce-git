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
    <h4 class="page-title">{{ __('Banners') }}</h4>
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
        <a href="#">{{ __('Banners') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Banners') }}</div>
            </div>

            <div class="col-lg-3">
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

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm {{ $dashboard_language->rtl == 1 ? 'float-lg-left float-right' : 'float-lg-right float-left' }}"><i
                  class="fas fa-plus"></i>
                {{ __('Add Banner') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              @if (count($banners) == 0)
                <h3 class="text-center">{{ __('NO BANNER FOUND') . '!' }}</h3>
              @else
                <div class="row">
                  @foreach ($banners as $banner)
                    <div class="col-md-3">
                      <div class="card">
                        <div class="card-body">
                          <img src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}"
                            alt="banner image" class="w-100">
                          <span class="mt-3 h4 mb-0 d-inline-block">{{ __('Possition') . ':' }}
                            {{ __(ucwords(str_replace('_', ' ', $banner->position))) }}</span>
                        </div>

                        <div class="card-footer ">
                          <a class="bannerEditBtn btn btn-secondary btn-sm mr-2 " href="#" data-toggle="modal"
                            data-target="#editModal" data-id="{{ $banner->id }}"
                            data-bannerimg="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}"
                            data-banner_url="{{ $banner->banner_url }}" data-title="{{ $banner->title }}"
                            data-subtitle="{{ $banner->subtitle }}"
                            @if ($userBs->theme == 'manti') data-text="{{ $banner->text }}" @endif
                            data-button_text="{{ $banner->button_text }}" data-position="{{ $banner->position }}"
                            data-serial_number="{{ $banner->serial_number }}"
                            data-language="{{ $banner->serial_number }}">
                            <span class="btn-label">
                              <i class="fas fa-edit"></i>
                            </span>
                            {{ __('Edit') }}
                          </a>

                          <form class="deleteform d-inline-block"
                            action="{{ route('user.home_page.banner_section.delete_banner') }}" method="post">
                            @csrf
                            <input type="hidden" name="banner_id" value="{{ $banner->id }}">
                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                              <span class="btn-label">
                                <i class="fas fa-trash"></i>
                              </span>
                              {{ __('Delete') }}
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('user.home.banner_section.create')

  {{-- edit modal --}}
  @include('user.home.banner_section.edit')
@endsection
@section('scripts')
  <script>
    var theme = "{{ $userBs->theme }}";
  </script>
  <script src="{{ asset('assets/user/js/banner.js') }}"></script>
@endsection
