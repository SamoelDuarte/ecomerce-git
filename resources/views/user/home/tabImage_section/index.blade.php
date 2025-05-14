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
    <h4 class="page-title">{{ __('Tab Images') }}</h4>
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
        <a href="#">{{ __('Tab Images') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Tab Images') }}</div>
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
                {{ __('Add Tab Image') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              @if (count($tabImages) == 0)
                <h3 class="text-center">{{ __('NO Tab Image FOUND') }}</h3>
              @else
                <div class="row">
                  @foreach ($tabImages as $tabImage)
                    <div class="col-md-3">
                      <div class="card">
                        <div class="card-body">
                          <img src="{{ asset('assets/front/img/user/tabImages/' . $tabImage->tabImage_img) }}"
                            alt="tabImage image" class="w-100">
                          <span>{{ $tabImage->position }}</span>
                        </div>

                        <div class="card-footer text-center">
                          <a class="editbtn btn btn-secondary btn-sm mr-2 " href="#" data-toggle="modal"
                            data-target="#editModal" data-id="{{ $tabImage->id }}"
                            data-tabImage_img="{{ asset('assets/front/img/user/tabImages/' . $tabImage->tabImage_img) }}"
                            data-tabImage_url="{{ $tabImage->tabImage_url }}" data-title="{{ $tabImage->title }}"
                            data-subtitle="{{ $tabImage->subtitle }}" data-button_text="{{ $tabImage->button_text }}"
                            data-position="{{ $tabImage->position }}"
                            data-serial_number="{{ $tabImage->serial_number }}"
                            data-language="{{ $tabImage->serial_number }}">
                            <span class="btn-label">
                              <i class="fas fa-edit"></i>
                            </span>
                            {{ __('Edit') }}
                          </a>

                          <form class="deleteform d-inline-block" action="{{ route('user.tabImage.delete') }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="tabImage_id" value="{{ $tabImage->id }}">
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
  @include('user.home.tabImage_section.create')
  {{-- edit modal --}}
  @include('user.home.tabImage_section.edit')
@endsection
