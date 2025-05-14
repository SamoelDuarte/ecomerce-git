@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
@endphp

@extends('user.layout')
@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/cropper.css') }}">
@endsection
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Post') }}</h4>
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
        <a href="#">{{ __('Blog') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('user.blog.index', ['language' => $de_lang->code]) }}">{{ __('Posts') }}</a>
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
        <a href="#">{{ __('Edit Post') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Post') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('user.blog.index', ['language' => $de_lang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-9 m-auto">
              <div class="alert alert-danger pb-1 d-none" id="postErrors">
                <ul></ul>
              </div>
              <form id="itemForm" action="{{ route('user.blog.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                <div class="row">
                  <div class="col-lg-12">

                    <div class="form-group px-0">
                      <div class="mb-2">
                        <label for="image"><strong>{{ __('Image') }} <span
                              class="text-danger">**</span></strong></label>
                      </div>
                      <div class="showImage mb-3  pl-0 pr-0">
                        <img
                          src="{{ $blog->image ? asset('assets/front/img/user/blogs/' . $blog->image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="cropped-thumbnail-image">
                      </div>
                      <br>
                      <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#thumbnail-image-modal">{{ __('Choose Image') }}</button>
                    </div>
                    <p class="text-warning p-0 mb-1">
                      {{ __('Recommended Image size : 900X600') }}
                    </p>
                  </div>


                  <div class="col-md-4">
                    <div class="form-group px-0">
                      <label>{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                      <input class="form-control" type="number" name="serial_number"
                        placeholder="{{ __('Enter Serial Number') }}" value="{{ $blog->serial_number }}">
                      <p class="text-danger mt-2 em" id="errserial_number"></p>
                      <p class="text-warning mt-2 mb-0">
                        <small>{{ __('The higher the serial number is, the later the blog will be shown.') }}</small>
                      </p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group px-0">
                      <label>{{ __('Status') }} <span class="text-danger">**</span></label>
                      <select name="status" class="form-control">
                        <option @selected($blog->status == 1) value="1">{{ __('Active') }}
                        </option>
                        <option @selected($blog->status == 0) value="0">{{ __('Deactive') }}
                        </option>
                      </select>
                      <p class="text-danger mt-2 em" id="errstatus"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      @php
                        $categories = DB::table('user_blog_categories')
                            ->where('language_id', $de_lang->id)
                            ->where('user_id', Auth::guard('web')->user()->id)
                            ->where('status', 1)
                            ->orderByDesc('id')
                            ->get();
                        $blog_content = DB::table('user_blog_contents')
                            ->where([['blog_id', $blog->id], ['language_id', $de_lang->id]])
                            ->first();
                      @endphp
                      <label for="">{{ __('Category') }} <span class="text-danger">**</span></label>
                      <select name="category_id" class="form-control">
                        <option selected disabled> {{ __('Select Category') }}
                        </option>

                        @foreach ($categories as $category)
                          <option {{ @$blog_content->category_id == $category->id ? 'selected' : '' }}
                            value="{{ $category->unique_id }}">
                            {{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <div class="mt-3" id="accordion">
                  @foreach ($userLangs as $language)
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
                        <div class="card-body {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                          @php
                            $blog_content = DB::table('user_blog_contents')
                                ->where([['blog_id', $blog->id], ['language_id', $language->id]])
                                ->first();
                          @endphp
                          <div class="row">
                            <div class="col-lg-8">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title') }} <span class="text-danger">**</span></label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="{{ __('Enter Title') }}" value="{{ @$blog_content->title }}">
                                <p class="text-danger mt-2 em" id="err{{ $language->code }}_title"></p>
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="col">
                                <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                  <label>{{ __('Author') }} <span class="text-danger">**</span></label>
                                  <input type="text" class="form-control" name="{{ $language->code }}_author"
                                    placeholder="{{ __('Enter Author Name') }}" value="{{ @$blog_content->author }}">
                                  <p class="text-danger mt-2 em" id="err{{ $language->code }}_author"></p>
                                </div>
                              </div>
                            </div>

                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Content') }} <span class="text-danger">**</span></label>
                                <textarea class="form-control summernote" name="{{ $language->code }}_content" id="{{ $language->code }}_content"
                                  placeholder="{{ __('Enter Blog Content') }}" data-height="300">{{ @$blog_content->content }}</textarea>

                                <p class="text-danger mt-2 em" id="err{{ $language->code }}_content"></p>
                              </div>
                            </div>

                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput"
                                  value="{{ @$blog_content->meta_keywords }}">
                              </div>
                            </div>

                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="{{ __('Enter Meta Description') }}">{{ @$blog_content->meta_description }}</textarea>
                              </div>
                            </div>

                            <div class="col-lg-12">
                              @php $currLang = $language; @endphp
                              @foreach ($userLangs as $lang)
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
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="itemForm" class="btn btn-success">
                {{ __('Save') }}
              </button>
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
  <script src="{{ asset('assets/admin/js/plugin/cropper.js') }}"></script>
  <script src="{{ asset('assets/user/js/blog-cropper-init.js') }}"></script>
@endsection
