@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where([
      ['code', request()->input('language')],
      ['user_id', Auth::guard('web')->user()->id],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)->get();
@endphp

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Variant') }}</h4>
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
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
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
        <a href="{{ route('user.variant.index') . '?language=' . $selLang->code }}">{{ __('Variations') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Variant') }}</a>
      </li>
    </ul>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="row">
          <div class="col-lg-7">
            {{ __('Edit Variant') }}
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
      <form id="itemVariationForm" action="{{ route('user.variant.update', $variant->id) }}" method="post">
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
                  @php
                    $user_id = Auth::guard('web')->user()->id;
                    $categories = App\Models\User\UserItemCategory::where([
                        ['user_id', $user_id],
                        ['language_id', $selLang->id],
                        ['status', 1],
                    ])->get();
                    $variant_content = App\Models\VariantContent::where([
                        ['user_id', $user_id],
                        ['language_id', $selLang->id],
                        ['variant_id', $variant->id],
                    ])->first();
                    $subcategories = !is_null(@$variant_content->category_id)
                        ? App\Models\User\UserItemSubCategory::where([
                            ['user_id', $user_id],
                            ['category_id', @$variant_content->category_id],
                            ['status', 1],
                        ])->get()
                        : [];
                  @endphp
                  <div class="row ">
                    <div class="col-md-3 category_dropdown">
                      <div class="form-group">
                        <label for="">{{ __('Category') }} <span class="text-danger">**</span></label>
                        <select class="form-control variation_category" data-language_id="{{ $selLang->id }}"
                          data-language_code="{{ $selLang->code }}" name="category_id">
                          <option value="">{{ __('Select Category') }}</option>
                          @foreach ($categories as $category)
                            <option @selected($category->id == $variant_content->category_id) value="{{ $category->id }}">{{ $category->name }}
                            </option>
                          @endforeach
                        </select>
                        <small class="form-text text-warning" data-tooltip="tooltip" data-bs-placement="top"
                          title="{{ __('After changing the category, you must re-add item variations; otherwise, variations from the previous category may be displayed incorrectly.') }}">
                          {{ __('Changing the category may affect your product variations.') }}
                        </small>
                        <p class="mb-0 text-danger em errcategory_id"></p>
                      </div>
                    </div>
                    <div class="col-md-3 subcategory_dropdown">
                      <div class="form-group">
                        <label for="">{{ __('Subcategory') }} <span class="text-danger">**</span></label>
                        <select class="form-control variation_subcategory" name="sub_category_id">
                          <option value="">{{ __('Select Subcategory') }}
                          </option>
                          @foreach ($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" @selected($subcategory->id == $variant_content->sub_category_id)>
                              {{ $subcategory->name }}</option>
                          @endforeach
                        </select>
                        <p class="mb-0 text-danger em errsub_category_id"></p>
                      </div>
                    </div>
                    @foreach ($userLanguages as $language)
                      @php
                        $variant_content = App\Models\VariantContent::where([
                            ['user_id', $user_id],
                            ['language_id', $language->id],
                            ['variant_id', $variant->id],
                        ])->first();
                      @endphp
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="">{{ __('Variant Name') }}
                            ({{ $language->code }})
                            <span class="text-danger">**</span></label>
                          <input name="variant_names[{{ $language->code }}][]" value="{{ @$variant_content->name }}"
                            type="text" class="form-control {{ $language->rtl == 1 ? 'rtl' : '' }}"
                            placeholder="{{ __('e.g., size, color etc.') }}">
                          <p class="mb-0 text-danger em errvariant_names.{{ $language->code }}"></p>
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
                  @php
                    $variant_option_contents = App\Models\VariantOptionContent::where([
                        ['variant_id', $variant->id],
                        ['language_id', $selLang->id],
                    ])->get();
                  @endphp
                  @foreach ($variant_option_contents as $variant_option_content)
                    <div class="row option-box" data-vindex="{{ $loop->iteration }}"
                      data-oindex="{{ $variant_option_content->index_key }}">
                      <div class="col-lg-11">
                        <div class="row">
                          @foreach ($userLanguages as $language)
                            @if ($language->id == $selLang->id)
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="">{{ __('Option Name') }}
                                    ({{ $language->code }})
                                    <span class="text-danger">**</span>
                                  </label>
                                  <input
                                    name="option_names[{{ $language->code }}][{{ @$variant_option_content->index_key }}][]"
                                    type="text" class="form-control {{ $language->rtl == 1 ? 'rtl' : '' }}"
                                    value="{{ @$variant_option_content->option_name ?? '' }}"
                                    placeholder="{{ __('e.g., m, xl, black, red etc.') }}">
                                  <p
                                    class="mb-0 text-danger em erroption_names.{{ $language->code }}.{{ @$variant_option_content->index_key }}">
                                  </p>
                                </div>
                              </div>
                            @else
                              @php
                                $variant_option_content1 = App\Models\VariantOptionContent::where([
                                    ['variant_id', $variant->id],
                                    ['language_id', $language->id],
                                    ['index_key', $variant_option_content->index_key],
                                ])->first();
                              @endphp
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <label for="">{{ __('Option Name') }}
                                    ({{ $language->code }}) <span class="text-danger">**</span></label>
                                  <input
                                    name="option_names[{{ $language->code }}][{{ @$variant_option_content1->index_key }}][]"
                                    type="text" class="form-control {{ $language->rtl == 1 ? 'rtl' : '' }}"
                                    value="{{ @$variant_option_content1->option_name ?? '' }}"
                                    placeholder="{{ __('e.g., m, xl, black, red etc.') }}">
                                  <p
                                    class="mb-0 text-danger em erroption_names.{{ $language->code }}.{{ @$variant_option_content1->index_key }}">
                                  </p>
                                </div>
                              </div>
                            @endif
                          @endforeach
                        </div>
                      </div>
                      <button type="button" class="btn btn-danger btn-sm text-white ml-2 delete-option"
                        data-oindex="{{ $variant_option_content->index_key }}">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  @endforeach
                </div>
              </div>
              <!-- Variants will be appended here end-->
            </div>
          </div>
        </div>
        <div class="form-group text-center">
          <button type="submit" class="btn btn-success">{{ __('Update Variation') }}</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('vuescripts')
  <script>
    'use strict';

    function renderOption(option, vIndex) {
      let optionHtml = `
            <div class="row option-box" data-vindex="${vIndex}" data-oindex="${option.uniqid}">
                <div class="col-lg-11">
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
                </div>
                <button type="button" class="btn btn-danger btn-sm text-white ml-2 remove-option">
                        <i class="fas fa-times"></i>
                    </button>
            </div>
        `;
      $(`[data-index="${vIndex}"] .options-container`).append(optionHtml);
    }
    var get_subcategory_url = "{{ route('user.variant.get-subcategory') }}";
    var delete_option_url = "{{ route('user.variant.delete-option') }}";
  </script>
  <script src="{{ asset('assets/user/js/edit-variant.js') }}"></script>
@endsection
