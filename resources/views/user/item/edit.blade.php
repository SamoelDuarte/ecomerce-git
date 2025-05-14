@extends('user.layout')
@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/cropper.css') }}">
@endsection
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Item') }}</h4>
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
        <a href="{{ route('user.item.index') . '?language=' . request()->input('language') }}">{{ __('Items') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ truncateString($title, 35) ?? '-' }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Item') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Item') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('user.item.index') . '?language=' . request()->input('language') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-9 m-auto">
              <div class="alert alert-danger pb-1 d-none" id="postErrors">
                <ul></ul>
              </div>
              {{-- Slider images upload start --}}
              <div class="px-2">
                <label for="" class="mb-2"><strong>{{ __('Slider Images') }}
                    <span class="text-danger">**</span></strong></label>
                <div class="row">
                  <div class="col-12">
                    <table class="table table-striped" id="imgtable">
                      @if (!is_null($item->sliders))
                        @foreach ($item->sliders as $key => $img)
                          <tr class="trdb" id="trdb{{ $key }}">
                            <td>
                              <div class="thumbnail">
                                <img class="width-150"
                                  src="{{ asset('assets/front/img/user/items/slider-images/' . $img->image) }}"
                                  alt="">
                              </div>
                            </td>
                            <td>
                              <button type="button" class="btn btn-danger pull-right rmvbtndb"
                                onclick="rmvdbimg({{ $key }},{{ $img->id }})">
                                <i class="fa fa-times"></i>
                              </button>
                            </td>
                          </tr>
                        @endforeach
                      @endif
                    </table>
                  </div>
                </div>
                <form action="{{ route('user.item.slider') }}" id="my-dropzone" enctype="multipart/form-data"
                  class="dropzone create">
                  <div class="dz-message">
                    {{ __('Drag and drop files here to upload') }}
                  </div>
                  @csrf
                  <div class="fallback">
                  </div>
                </form>
                <p class="text-warning">
                  <strong>{{ __('Recommended Image Size : 800 x 800') }}</strong>
                </p>
                @if ($errors->has('image'))
                  <p class="mt-2 mb-0 text-danger">{{ $errors->first('image') }}</p>
                @endif
              </div>
              {{-- Slider images upload end --}}

              <form id="itemForm" class="" action="{{ route('user.item.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="type" value="{{ $item->type }}">
                <div id="sliders"></div>
                {{-- thumbnail image start --}}
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0">
                        <label for="">{{ __('Thumbnail Image') }} <span class="text-danger">**</span></label>
                      </div>

                      <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                        <img
                          src="{{ isset($item->thumbnail) ? asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="cropped-thumbnail-image">
                      </div>

                      <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#thumbnail-image-modal">{{ __('Choose Image') }}</button>
                    </div>
                  </div>
                  {{-- thumbnail image end --}}
                  @if ($item->type == 'physical')
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="">{{ __('Stock') }} <span class="text-danger">**</span></label>
                        <input type="number" class="form-control" name="stock" placeholder="{{ __('Enter Stock') }}"
                          min="0" value="{{ $item->stock ?? 0 }}">
                        <p class="mb-0 text-warning">
                          {{ __('If the item has variations, then set the stocks in the variations page') }}
                        </p>
                      </div>
                    </div>
                  @endif
                  @if ($item->type == 'digital')
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="">{{ __('Type') }} <span class="text-danger">**</span></label>
                        <select name="file_type" class="form-control" id="fileType" onchange="toggleFileUpload();">
                          <option value="upload" {{ !empty($item->download_file) ? 'selected' : '' }}>
                            {{ __('File Upload') }}
                          </option>
                          <option value="link" {{ !empty($item->download_link) ? 'selected' : '' }}>
                            {{ __('File Download Link') }}</option>
                        </select>
                      </div>
                    </div>
                  @endif
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
                      <select class="form-control" name="status">
                        <option value="" selected disabled>
                          {{ __('Select Status') }}</option>
                        <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>
                          {{ __('Show') }}
                        </option>
                        <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>
                          {{ __('Hide') }}
                        </option>
                      </select>
                    </div>
                  </div>

                  @if ($item->type == 'digital')
                    <div class="col-lg-4">
                      <div id="downloadFile" class="form-group {{ !empty($item->download_link) ? 'd-none' : '' }}">
                        <label for="">{{ __('Downloadable File') }} <span class="text-danger">**</span></label>
                        <br>

                        <input name="download_file" type="file" class="form-control">
                        <p class="mb-0 text-warning">
                          {{ __('Only zip file is allowed.') }}</p>
                      </div>

                      <div id="downloadLink" class="form-group {{ !empty($item->download_link) ? '' : 'd-none' }}">
                        <label for="">{{ __('Downloadable Link') }} <span class="text-danger">**</span></label>
                        <input name="download_link" type="text" class="form-control"
                          value="{{ $item->download_link }}">
                      </div>
                    </div>
                  @endif
                  @if ($item->type == 'physical')
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for=""> {{ __('Product Sku') }} <span class="text-danger">**</span></label>
                        <input type="text" class="form-control" name="sku"
                          placeholder="{{ __('Enter Product sku') }}" value="{{ $item->sku }}">
                      </div>
                    </div>
                  @endif
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for=""> {{ __('Current Price') }}
                        ({{ $currency->symbol }}) <span class="text-danger">**</span></label>
                      <input type="number" class="form-control" name="current_price" min="0.01"
                        value="{{ $item->current_price }}" step="any"
                        placeholder="{{ __('Enter Current Price') }}">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{ __('Previous Price') }} (
                        {{ $currency->symbol }}
                        )</label>
                      <input type="number" class="form-control" name="previous_price" min="0.01"
                        value="{{ $item->previous_price }}" step="any"
                        placeholder="{{ __('Enter Previous Price') }}">
                    </div>
                  </div>



                  @php
                    $postData = $lang->itemInfo()->where('item_id', $item->id)->first();

                    $categories = App\Models\User\UserItemCategory::where('language_id', $lang->id)
                        ->where('user_id', Auth::guard('web')->user()->id)
                        ->where('status', 1)
                        ->orderBy('name', 'asc')
                        ->get();
                  @endphp
                  <input hidden id="subcatGetterForItem" value="{{ route('user.item.subcatGetter') }}">
                  <div class="col-lg-4">
                    <div class="form-group {{ $lang->rtl == 1 ? 'rtl text-right' : '' }}">
                      <label>{{ __('Category') }} <span class="text-danger">**</span></label>
                      <select data-code="{{ $lang->code }}" name="category" class="form-control getSubCategory">
                        <option value="">{{ __('Select Category') }}
                        </option>
                        @foreach ($categories as $category)
                          <option @selected(@$postData->category_id == $category->id) value="{{ $category->id }}">
                            {{ $category->name }}</option>
                        @endforeach
                      </select>
                      <small class="form-text text-warning" data-tooltip="tooltip" data-bs-placement="top"
                        title="{{ __('After changing the category, you must re-add item variations; otherwise, variations from the previous category may be displayed incorrectly.') }}">
                        {{ __('Changing the category may affect your product variations.') }}
                      </small>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group {{ $lang->rtl == 1 ? 'rtl text-right' : '' }}">
                      <label>{{ __('Subcategory') }}</label>
                      <select data-code="{{ $lang->code }}" name="subcategory" id="{{ $lang->code }}_subcategory"
                        class="form-control">
                        <option value="">
                          {{ __('Select Subcategory') }}</option>
                        @php
                          if ($postData) {
                              $sub_categories = App\Models\User\UserItemSubCategory::where('language_id', $lang->id)
                                  ->where('user_id', Auth::guard('web')->user()->id)
                                  ->where('category_id', $postData->category_id)
                                  ->get();
                          }
                        @endphp

                        @foreach ($sub_categories as $sub)
                          <option @selected(@$postData->subcategory_id == $sub->id) value="{{ $sub->id }}">
                            {{ $sub->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>





                </div>
                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    @php
                      $postData = $language->itemInfo()->where('item_id', $item->id)->first();
                    @endphp
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . ' ' . __('Language') }}
                            {{ $language->is_default == 1 ? __('(Default)') : '' }}
                          </button>
                        </h5>
                      </div>
                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body {{ $language->rtl == 1 ? 'rtl text-right' : '' }}"
                          id="app{{ $language->code }}">
                          <div class="row">
                            <div class="col-lg-8">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title') }} <span class="text-danger">**</span></label>
                                <input type="text"
                                  class="form-control {{ $language->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                                  name="{{ $language->code }}_title"
                                  value="{{ is_null($postData) ? '' : $postData->title }}"
                                  placeholder="{{ __('Enter Title') }}">
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Product Label') }}</label>
                                <select name="{{ $language->code }}_label_id" class="form-control">
                                  <option value="" selected>
                                    {{ __('Select product label') }}
                                  </option>

                                  @php
                                    $product_labels = App\Models\User\Label::where([
                                        ['user_id', Auth::guard('web')->user()->id],
                                        ['language_id', $language->id],
                                    ])->get();
                                  @endphp

                                  @foreach ($product_labels as $product_label)
                                    <option {{ $product_label->id == @$postData->label_id ? 'selected' : '' }}
                                      value="{{ $product_label->id }}">
                                      {{ $product_label->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>
                                  {{ __('Summary') }} <span class="text-danger">**</span>
                                </label>
                                <textarea class="form-control {{ $language->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                                  name="{{ $language->code }}_summary" placeholder="{{ __('Enter Summary') }}" rows="8">{{ is_null($postData) ? '' : $postData->summary }}</textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') }} <span class="text-danger">**</span></label>
                                <textarea id="{{ $language->code }}_PostContent" class="form-control summernote"
                                  name="{{ $language->code }}_description" placeholder="{{ __('Enter Description') }}" data-height="300">{{ is_null($postData) ? '' : $postData->description }}</textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="{{ __('Enter Meta Keywords') }}"
                                  value="{{ is_null($postData) ? '' : $postData->meta_keywords }}"
                                  data-role="tagsinput">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="{{ __('Enter Meta Description') }}">{{ is_null($postData) ? '' : $postData->meta_description }}</textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              @php $currLang = $language; @endphp
                              @foreach ($languages as $lang)
                                @continue($lang->id == $currLang->id)
                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                      onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $lang->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }}
                                      <strong class="text-capitalize text-secondary">{{ $lang->name }}</strong>
                                      {{ __('language') }}</span>
                                  </label>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" form="itemForm" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- thumbnail --}}
  <p class="d-none" id="blob_image"></p>
  <div class="modal fade" id="thumbnail-image-modal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header d-flex justify-content-between align-items-center">
          <h2>{{ __('Thumbnail') }} <span class="text-danger">**</span></h2>
          <button role="button" class="close btn btn-secondary mr-2 destroy-cropper d-none text-white"
            data-dismiss="modal" aria-label="Close">
            {{ __('Crop') }}
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            @php
              $d_none = 'none';
            @endphp
            <div class="thumb-preview" style="background: {{ $d_none }}">
              <img src="{{ asset('assets/admin/img/noimage.jpg') }}"
                data-no_image="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                class="uploaded-thumbnail-img" id="image">
            </div>
            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="thumbnail-input" name="thumbnail-image" accept="image/*">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- thumbnail end --}}
@endsection

@section('scripts')
  <script>
    "use strict";
    const currUrl = "{{ url()->current() }}";
    const fullUrl = "{!! url()->full() !!}";
    const uploadSliderImage = "{{ route('user.item.slider') }}";
    const rmvSliderImage = "{{ route('user.item.slider-remove') }}";
    const rmvDbSliderImage = "{{ route('user.item.db-slider-remove') }}";
  </script>
  <script src="{{ asset('assets/user/js/dropzone-slider.js') }}"></script>

  <script src="{{ asset('assets/admin/js/plugin/cropper.js') }}"></script>
  <script src="{{ asset('assets/user/js/cropper-init.js') }}"></script>
@endsection
