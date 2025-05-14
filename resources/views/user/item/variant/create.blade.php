@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where([
      ['code', request()->input('language')],
      ['user_id', Auth::guard('web')->user()->id],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)->get();
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Variant') }}</h4>
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
        <a href="#">{{ __('Shop Management') }}</a>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Variants') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Variant') }}</a>
      </li>
    </ul>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="row">
          <div class="col-lg-7">
            {{ __('Add Variant') }}
          </div>

          <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0 {{ $dashboard_language->rtl == 1 ? 'text-left' : 'text-right' }}">
            <a class="btn btn-info text-white btn-sm"
              href="{{ route('user.variant.index') . '?language=' . $selLang->code }}">
              <i class="fas fa-backward"></i> {{ __('Back') }}</a>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">

      <form id="itemVariationForm" action="{{ route('user.variant.store') }}" method="post"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-10 mx-auto">
            <div class="alert alert-danger pb-1 d-none" id="postErrors">
              <ul></ul>
            </div>
            <div id="variant-container">
              <!-- Variants will be appended here -->
              <div class="row variant-box" data-index="1">
                <div class="col-lg-12 p-0 variant-main">
                  <div class="row">
                    @php
                      $categories = App\Models\User\UserItemCategory::where([
                          ['user_id', Auth::guard('web')->user()->id],
                          ['language_id', $selLang->id],
                          ['status', 1],
                      ])->get();
                    @endphp
                    <div class="col-md-3 category_dropdown">
                      <div class="form-group">
                        <label for="">{{ __('Category') }}<span class="text-danger">**</span></label>
                        <select class="form-control variation_category" data-language_id="{{ $selLang->id }}"
                          data-language_code="{{ $selLang->code }}" name="category_id">
                          <option value="">{{ __('Select Category') }}</option>
                          @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                          @endforeach
                        </select>
                        <p class="mb-0 text-danger em errcategory_id">
                      </div>
                    </div>
                    <div class="col-md-3 subcategory_dropdown">
                      <div class="form-group">
                        <label for="">{{ __('Subcategory') }}</label>
                        <select class="form-control variation_subcategory" name="sub_category_id">
                          <option value="">{{ __('Select Subcategory') }}
                          </option>
                        </select>
                        <p class="mb-0 text-danger em errsub_category_id">
                      </div>
                    </div>
                    @foreach ($userLanguages as $language)
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="">{{ __('Variant Name') }}
                            ({{ $language->code }})
                            <span class="text-danger">**</span></label>
                          <input name="variant_names[{{ $language->code }}][]" type="text"
                            class="form-control {{ $language->rtl == 1 ? 'rtl' : '' }}"
                            placeholder="{{ __('e.g., size, color etc.') }}">
                          <p class="mb-0 text-danger em errvariant_names.{{ $language->code }}">
                          </p>
                        </div>
                      </div>
                    @endforeach

                  </div>
                </div>

                <div class="col-lg-12 pl-0 mt-2">
                  <button type="button" class="btn btn-secondary btn-sm add-option"><i class="fas fa-plus"></i>
                    {{ __('Add Option') }}</button>
                </div>
                <div class="col-lg-8 options-container">
                  <!-- Options will be appended here -->
                </div>
              </div>
              <!-- Variants will be appended here end-->
            </div>
          </div>
        </div>
        <div class="form-group text-center">
          <button type="submit" class="btn btn-success">{{ __('Save Variation') }}</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('vuescripts')
  <script>
    'use strict';

    function renderOption(option, vIndex) {
      var optionHtml = `
            <div class="row option-box" data-vindex="${vIndex}" data-oindex="${option.uniqid}">
                <div class="col-lg-12">
                    <div class="row">
                        @foreach ($userLanguages as $language)
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ __('Option Name') }} ({{ $language->code }}) <span class="text-danger">**</span></label>
                                    <input name="option_names[{{ $language->code }}][${option.uniqid}][]" type="text" class="form-control {{ $language->rtl == 1 ? 'rtl' : '' }}" placeholder="{{ __('e.g., m, xl, black, red etc.') }}">
                                      <p class="mb-0 text-danger em erroption_names.{{ $language->code }}.${option.uniqid}"></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-danger btn-sm text-white ml-2 remove-option">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
      $(`[data-index="${vIndex}"] .options-container`).append(optionHtml);
    }

    var get_subcategory_url = "{{ route('user.variant.get-subcategory') }}";
  </script>
  <script src="{{ asset('assets/user/js/create-variant.js') }}"></script>
@endsection
