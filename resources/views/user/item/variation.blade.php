@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where([
      ['code', request()->input('language')],
      ['user_id', Auth::guard('web')->user()->id],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)->get();

  $language_variation_data = [];

  foreach ($userLanguages as $language) {
      $item_content = App\Models\User\UserItemContent::where([
          ['item_id', $item_id],
          ['language_id', $language->id],
          ['user_id', Auth::guard('web')->user()->id],
      ])
          ->select('category_id', 'subcategory_id')
          ->first();

      $category_id = $item_content->category_id ?? null;
      $subcategory_id = $item_content->subcategory_id ?? null;

      $variation_contents = App\Models\VariantContent::where('category_id', $category_id)
          ->orWhere('sub_category_id', $subcategory_id)
          ->get();

      $language_variation_data[$language->id] = $variation_contents;
  }
@endphp

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Manage Variations') }}</h4>
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
        <a href="{{ route('user.item.index') . '?language=' . $selLang->code }}">{{ __('Items') }}</a>
      </li>
           <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ truncateString($title,35) ?? '-' }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Manage Variations') }}</a>
      </li>
    </ul>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <div class="row">
          <div class="col-lg-7">
            {{ __('Manage Variations') }}
          </div>
          <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0 {{ $dashboard_language->rtl == 1 ? 'text-left' : 'text-right' }}">
            <a class="btn btn-secondary text-white btn-sm"
              href="{{ route('user.item.index') . '?language=' . $selLang->code }}">{{ __('Back') }}</a>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <form id="itemVariationForm" action="{{ route('user.item.variation.store') }}" method="post">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item_id }}">
        <div class="row">
          <div class="col-md-11 mx-auto">
            <div class="alert alert-danger pb-1 d-none" id="postErrors">
              <ul></ul>
            </div>
            <div id="variant-container">
              <!-- Variants will be appended here -->
              @if (count($variations) > 0)
                @foreach ($variations as $variation)
                  <div class="row border variant-box">
                    <input type="hidden" class="unique_id" name="unique_id[]" value="{{ $variation->unique_id }}">
                    <div class="col-md-12 variation-row">
                      <div class="row">
                        @php
                          $product_variation_content = App\Models\User\ProductVariationContent::where([
                              ['product_variation_id', $variation->id],
                              ['language_id', $selLang->id],
                          ])->first();
                        @endphp
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="">{{ __('Variation Name') }}
                              <span class="text-danger">**</span>
                            </label>
                            <select name="{{ $variation->unique_id }}_variation_name[]"
                              class="form-control product_variation" data-language_id="{{ $selLang->id }}"
                              data-language_code="{{ $selLang->code }}">
                              <option value="">{{ __('Select Variation') }}
                              </option>
                              @foreach ($language_variation_data[$selLang->id] as $variation_content)
                                <option
                                  {{ @$product_variation_content->variation_name == @$variation_content->id ? 'selected' : '' }}
                                  value="{{ $variation_content->id }}"
                                  data-variant_id="{{ $variation_content->variant_id }}">
                                  {{ $variation_content->name }}</option>
                              @endforeach
                            </select>
                            <p class="mb-0 text-danger em err{{ $variation->unique_id }}_variation_name">
                          </div>
                        </div>

                      </div>
                    </div>
                    @php
                      $product_variation_options = App\Models\User\ProductVariantOption::where(
                          'product_variation_id',
                          $variation->id,
                      )->get();
                    @endphp
                    @foreach ($product_variation_options as $key => $product_variation_option)
                      <div class="col-md-12">
                        <div class="row varitant-option">

                          <div class="col-md-3">
                            <div class="form-group">
                              @php
                                $selected_option = App\Models\User\ProductVariantOptionContent::where([
                                    ['product_variant_option_id', $product_variation_option->id],
                                    ['language_id', $selLang->id],
                                ])->first();
                                if ($selected_option) {
                                    $variant_option_content = App\Models\VariantOptionContent::where(
                                        'id',
                                        intval($selected_option->option_name),
                                    )->first();
                                    if ($variant_option_content) {
                                        $variant_option_contents = App\Models\VariantOptionContent::where([
                                            ['variant_id', $variant_option_content->variant_id],
                                            ['language_id', $selLang->id],
                                        ])->get();
                                    } else {
                                        $variant_option_contents = [];
                                    }
                                } else {
                                    $variant_option_contents = [];
                                }

                              @endphp
                              <label for="">{{ __('Option') }}
                                <span class="text-danger">**</span>
                              </label>
                              <select name="{{ $variation->unique_id }}_option_name[]"
                                class="form-control {{ $selLang->code }}_option_name">
                                <option value="">{{ __('Select Option') }}</option>
                                @foreach ($variant_option_contents as $option)
                                  <option @selected($selected_option->option_name == $option->id) value="{{ $option->id }}">
                                    {{ $option->option_name }}
                                  </option>
                                @endforeach
                              </select>
                              <p class="mb-0 text-danger em err{{ $variation->unique_id }}_option_name">
                            </div>
                          </div>

                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="">{{ __('Price') }}
                                ({{ $userBs->base_currency_symbol }})
                                <span class="text-danger">**</span></label>
                              <input type="number" name="{{ $variation->unique_id }}_price[]" step="any"
                                value="{{ $product_variation_option->price }}" class="form-control">
                              <p class="mb-0 text-danger em err{{ $variation->unique_id }}_price">
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="form-group">
                              <label for="">{{ __('Stock') }} <span class="text-danger">**</span></label>
                              <input type="number" name="{{ $variation->unique_id }}_stock[]"
                                value="{{ $product_variation_option->stock }}" class="form-control">
                              <p class="mb-0 text-danger em err{{ $variation->unique_id }}_stock">
                            </div>
                          </div>
                          <input type="hidden" name="{{ $variation->unique_id }}_optionid[]" class="option_id"
                            value="{{ $product_variation_option->id }}">

                          <a href="{{ route('user.item.variation.option.delete') }}"
                            class="btn btn-danger btn-sm ml-25px remove_option mb-2"
                            data-id="{{ $product_variation_option->id }}"><i class="fas fa-times"></i></a>
                        </div>
                        @if ($loop->last)
                          <a href="" class="btn btn-success btn-sm add_option">+
                            {{ __('Add Option') }}</a>
                        @endif
                      </div>
                    @endforeach

                    <a
                      href="{{ route('user.item.variation.delete', $variation->unique_id) }}"class="btn btn-danger btn-sm  variant-close-btn "><i
                        class="fas fa-times"></i></a>
                  </div>
                @endforeach
              @else
                <div class="row border variant-box">
                  @php
                    $unique_id = \Str::random(9);
                  @endphp
                  <input type="hidden" class="unique_id" name="unique_id[]" value="{{ $unique_id }}">
                  <div class="col-md-12 variation-row">
                    <div class="row">


                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="">{{ __('Variation Name') }}
                            <span class="text-danger">**</span>
                          </label>
                          <select name="{{ $unique_id }}_variation_name[]" class="form-control product_variation"
                            data-language_id="{{ $selLang->id }}" data-language_code="{{ $selLang->code }}">
                            <option value="">{{ __('Select Variation') }}
                            </option>
                            @foreach ($language_variation_data[$selLang->id] as $variation_content)
                              <option
                                {{ @$product_variation_content->variation_id == @$variation_content->id ? 'selected' : '' }}
                                value="{{ $variation_content->id }}"
                                data-variant_id="{{ $variation_content->variant_id }}">
                                {{ $variation_content->name }}</option>
                            @endforeach
                          </select>
                          <p class="mb-0 text-danger em err{{ $unique_id }}_variation_name">
                        </div>
                      </div>


                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="row varitant-option">

                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="">{{ __('Option') }}
                            <span class="text-danger">**</span>
                          </label>
                          <select name="{{ $unique_id }}_option_name[]"
                            class="form-control {{ $selLang->code }}_option_name">
                            <option value="">{{ __('Select Option') }}</option>
                          </select>
                          <p id="" class="mb-0 text-danger em err{{ $unique_id }}_option_name">
                        </div>
                      </div>

                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="">{{ __('Price') }} <span class="text-danger">**</span></label>
                          <input type="number" name="{{ $unique_id }}_price[]" class="form-control"
                            step="any" value="0">
                          <p class="mb-0 text-danger em err{{ $unique_id }}_price">
                          </p>

                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="">{{ __('Stock') }} <span class="text-danger">**</span></label>
                          <input type="number" name="{{ $unique_id }}_stock[]" class="form-control"
                            value="0">
                          <p class="mb-0 text-danger em err{{ $unique_id }}_stock">
                          </p>
                        </div>
                      </div>
                      <input type="hidden" name="{{ $unique_id }}_optionid[]" class="option_id" value="new">
                    </div>
                    <a href="" class="btn btn-success btn-sm add_option">+
                      {{ __('Add Option') }}</a>
                  </div>
                </div>
              @endif

              {{-- add variant button --}}
              <div class="row">
                <div class="col-lg-12 p-0 mb-2">
                  <button type="button" class="btn btn-primary add-variant"><i class="fas fa-plus"></i>
                    {{ __('Add Variant') }}</button>
                </div>
              </div>
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
    var variation_option_delete_url = "{{ route('user.item.variation.option.delete') }}";
    var product_get_all_variation_url = "{{ route('user.item.variations.get_variation') }}";
  </script>
  <script src="{{ asset('assets/user/js/product-variation.js') }}"></script>
@endsection
