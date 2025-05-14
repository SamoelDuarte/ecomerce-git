@extends('user.layout')

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Images & Texts') }}</h4>
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
        <a href="#">{{ __('Images & Texts') }}</a>
      </li>
    </ul>
  </div>
  <form id="ajaxForm" action="{{ route('user.home.section.update', $language->id) }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <div class="row">

      <div class="col-lg-6">
        <div class="row">
          <!--category section -->
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-8">
                    <div class="card-title text-warning">{{ __('Category Section') }}
                    </div>
                  </div>
                  <div class="col-lg-4">
                    @include('user.partials.languages')
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Category Section Title') }}</label>
                      <input name="category_section_title" class="form-control"
                        value="{{ $ubs->category_section_title ?? '' }}">
                      <p id="errcategory_section_title" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                  @if ($setting->theme == 'fashion' || $setting->theme == 'furniture' || $setting->theme == 'electronics')
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Category Section Subtitle') }}</label>
                        <input name="category_section_subtitle" class="form-control"
                          value="{{ $ubs->category_section_subtitle ?? '' }}">
                        <p id="errcategory_section_subtitle" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <!--Latest Product -->
          @if ($setting->theme == 'kids' || $setting->theme == 'electronics')
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="card-title text-warning">
                        {{ __('Latest Product Section') }}
                      </div>
                    </div>
                    <div class="col-lg-4">
                      @if (!empty($u_langs))
                        <select name="language" class="form-control"
                          onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                          <option value="" selected disabled>
                            {{ __('Select a Language') }}
                          </option>
                          @foreach ($u_langs as $lang)
                            <option value="{{ $lang->code }}"
                              {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                            </option>
                          @endforeach
                        </select>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Latest Product Section Title') }}</label>

                        <input name="latest_product_section_title" class="form-control"
                          value="{{ $ubs->latest_product_section_title ?? '' }}">
                        <p id="errlatest_product_section_title" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
          <!--toprated & selling section -->
          @if ($setting->theme == 'furniture' || $setting->theme == 'vegetables' || $setting->theme == 'manti')
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="card-title text-warning">
                        {{ __('Top Selling & Rated') }}
                      </div>
                    </div>
                    <div class="col-lg-4">
                      @if (!empty($u_langs))
                        <select name="language" class="form-control"
                          onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                          <option value="" selected disabled>
                            {{ __('Select a Language') }}
                          </option>
                          @foreach ($u_langs as $lang)
                            <option value="{{ $lang->code }}"
                              {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                            </option>
                          @endforeach
                        </select>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    @php
                      $allow_theme = ['furniture', 'vegetables', 'manti'];
                    @endphp
                    @if (in_array($setting->theme, $allow_theme))
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Top Rated Product Section Title') }}</label>

                          <input name="top_rated_product_section_title" class="form-control"
                            value="{{ $ubs->top_rated_product_section_title ?? '' }}">
                          <p id="errtop_rated_product_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      @if ($setting->theme != 'manti')
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('Top Selling Product Section Title') }}</label>

                            <input name="top_selling_product_section_title" class="form-control"
                              value="{{ $ubs->top_selling_product_section_title ?? '' }}">
                            <p id="errtop_selling_product_section_title" class="em text-danger mb-0"></p>
                          </div>
                        </div>
                      @endif
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endif
          <!--call to actions section -->
          @if ($setting->theme != 'fashion')
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="card-title text-warning">
                        {{ __('Call To Action Section') }}
                      </div>
                    </div>
                    <div class="col-lg-4">
                      @if (!empty($u_langs))
                        <select name="language" class="form-control"
                          onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                          <option value="" selected disabled>
                            {{ __('Select a Language') }}
                          </option>
                          @foreach ($u_langs as $lang)
                            <option value="{{ $lang->code }}"
                              {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                            </option>
                          @endforeach
                        </select>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div
                      class="{{ $setting->theme == 'fashion' || $setting->theme == 'vegetables' || $setting->theme == 'electronics' || $setting->theme == 'manti' ? 'col-lg-6' : 'col-lg-12' }}">
                      <div class="form-group">
                        <div class="col-12 mb-2 pl-0">
                          <label for="image"><strong>
                              {{ __('Background Image') }}</strong></label>
                        </div>
                        <div class="col-md-12 showImage2 showImage-position mb-3  pl-0 pr-0">
                          <img
                            src="{{ !is_null(@$ubs->action_section_background_image) ? asset('assets/front/img/cta/' . @$ubs->action_section_background_image) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="..." class="img-thumbnail">
                          @if (!is_null(@$ubs->action_section_background_image))
                            <button class="remove-img btn btn-sm btn-remove text-white btn-danger" type="button"
                              data-url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                              data-name="action_section_background_image">
                              <i class="fas fa-times"></i>
                            </button>
                          @endif
                        </div>
                        <div role="button" class="btn btn-primary btn-sm upload-btn" id="image2">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="action_section_background_image">
                        </div>
                        <p class="text-warning p-0 mb-1">
                          {{ __('Recommended Image size : 1920X300') }}
                        </p>
                        <p id="erraction_section_background_image" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                    @if (
                        $setting->theme == 'fashion' ||
                            $setting->theme == 'vegetables' ||
                            $setting->theme == 'electronics' ||
                            $setting->theme == 'manti')
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="col-12 mb-2  pl-0">
                            <label for="image"><strong>
                                {{ __('Side Image') }}</strong></label>
                          </div>
                          <div class="col-md-12 showImage4 showImage-position 4 mb-3 pl-0 pr-0">
                            <img
                              src="{{ !is_null(@$ubs->action_section_side_image) ? asset('assets/front/img/cta/' . @$ubs->action_section_side_image) : asset('assets/admin/img/noimage.jpg') }}"
                              alt="..." class="img-thumbnail">
                            @if (!is_null(@$ubs->action_section_side_image))
                              <button class="remove-img btn btn-sm btn-remove text-white btn-danger" type="button"
                                data-url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                                data-name="action_section_side_image">
                                <i class="fas fa-times"></i>
                              </button>
                            @endif
                          </div>

                          <div role="button" class="btn btn-primary btn-sm upload-btn" id="image4">
                            {{ __('Choose Image') }}
                            <input type="file" class="img-input" name="action_section_side_image">
                          </div>
                          <p class="text-warning p-0 mb-1">
                            {{ __('Recommended Image size : 400X260') }}
                          </p>
                          <p id="erraction_section_side_image" class="mb-0 text-danger em"></p>
                        </div>
                      </div>
                    @endif
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Call to Action Title') }}</label>

                        <input class="form-control" name="call_to_action_section_title"
                          value="{{ @$ubs->call_to_action_section_title }}">
                        <p id="errcall_to_action_section_title" class="mb-0 text-danger em"></p>
                      </div>
                    </div>

                    @if ($setting->theme == 'electronics' || $setting->theme == 'vegetables')
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Call to Action Text') }}</label>

                          <input class="form-control" name="call_to_action_section_text"
                            value="{{ @$ubs->call_to_action_section_text }}">
                          <p id="errcall_to_action_section_text" class="mb-0 text-danger em"></p>
                        </div>
                      </div>
                    @endif

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Button Text') }}</label>

                        <input class="form-control" name="call_to_action_section_button_text"
                          value="{{ @$ubs->call_to_action_section_button_text }}">
                        <p id="errcall_to_action_section_button_text" class="mb-0 text-danger em"></p>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Button URL') }}</label>
                        <input type="url" class="form-control" name="call_to_action_section_button_url"
                          value="{{ @$ubs->call_to_action_section_button_url }}">
                        <p id="errcall_to_action_section_button_url" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
          <!--video content section -->
          @if ($setting->theme == 'fashion')
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="card-title text-warning">{{ __('Video Section') }}</div>
                    </div>
                    <div class="col-lg-4">
                      @if (!empty($u_langs))
                        <select name="language" class="form-control"
                          onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                          <option value="" selected disabled>
                            {{ __('Select a Language') }}
                          </option>
                          @foreach ($u_langs as $lang)
                            <option value="{{ $lang->code }}"
                              {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                            </option>
                          @endforeach
                        </select>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <div class="col-12 mb-2 pl-0">
                          <label for="image"><strong>{{ __('Background Image') }}</strong></label>
                        </div>
                        <div class="col-md-12 showImage5 showImage-position mb-3 pl-0 pr-0">
                          <img
                            src="{{ isset($ubs->video_background_image) ? asset('assets/front/img/hero_slider/' . $ubs->video_background_image) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="..." class="img-thumbnail">
                          @if ((@$ubs->video_background_image))
                            <button class="remove-img btn btn-sm btn-remove text-white btn-danger" type="button"
                              data-url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                              data-name="video_background_image">
                              <i class="fas fa-times"></i>
                            </button>
                          @endif
                        </div>

                        <div role="button" class="btn btn-primary btn-sm upload-btn" id="image5">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="video_background_image">
                        </div>
                        <p id="errvideo_background_image" class="em text-danger mb-0"></p>
                        <p class="text-warning p-0 mb-1">
                          @if ($setting->theme === 'furniture')
                            {{ __('Recommended Image size : 1920X860') }}
                          @else
                            {{ __('Recommended Image size : 1920X600') }}
                          @endif
                        </p>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Video Section Title') }}</label>
                        <input type="text" class="form-control" name="video_section_title"
                          value="{{ $ubs->video_section_title ?? null }}">
                        <p id="errvideo_section_title" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                    @if ($setting->theme != 'fashion')
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Video Section Subtitle') }}</label>
                          <input type="text" class="form-control" name="video_section_subtitle"
                            value="{{ $ubs->video_section_subtitle ?? null }}">
                          <p id="errvideo_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    @endif
                    @if ($setting->theme != 'furniture')
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Button Name') }}</label>
                          <input type="text" class="form-control" name="video_section_button_name"
                            value="{{ $ubs->video_section_button_name ?? null }}">
                          <p id="errvideo_section_button_name" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label>{{ __('Button URL') }}</label>
                          <input type="url" class="form-control" name="video_section_button_url"
                            value="{{ $ubs->video_section_button_url ?? null }}">
                          <p id="errvideo_section_button_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    @endif

                    @if ($setting->theme == 'electronics' || $setting->theme == 'vegetables')
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Video Section Text') }}</label>
                          <input type="text" class="form-control" name="video_section_text"
                            value="{{ $ubs->video_section_text ?? null }}">
                          <p id="errvideo_section_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    @endif

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Video URL') }}</label>
                        <input type="text" class="form-control" name="video_url"
                          value="{{ $ubs->video_url ?? null }}">
                        <p id="errvideo_url" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>

      <div class="col-lg-6">
        <div class="row">
          <!--tabs section -->
          @if ($setting->theme != 'manti')
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="card-title text-warning">{{ __('Tabs Section') }}</div>
                    </div>
                    <div class="col-lg-4">
                      @if (!empty($u_langs))
                        <select name="language" class="form-control"
                          onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                          <option value="" selected disabled>
                            {{ __('Select a Language') }}
                          </option>
                          @foreach ($u_langs as $lang)
                            <option value="{{ $lang->code }}"
                              {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                            </option>
                          @endforeach
                        </select>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Tabs Section Title') }}</label>
                        <input name="tab_section_title" class="form-control"
                          value="{{ $ubs->tab_section_title ?? '' }}">
                        <p id="errtab_section_title" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                    @if ($setting->theme == 'fashion')
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Tabs Section Subtitle') }}</label>
                          <input name="tab_section_subtitle" class="form-control"
                            value="{{ $ubs->tab_section_subtitle ?? '' }}">
                          <p id="errtab_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endif
          <!--flash section -->
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-8">
                    <div class="card-title text-warning">{{ __('Flash Section') }}</div>
                  </div>
                  <div class="col-lg-4">
                    @if (!empty($u_langs))
                      <select name="language" class="form-control"
                        onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                        <option value="" selected disabled>
                          {{ __('Select a Language') }}
                        </option>
                        @foreach ($u_langs as $lang)
                          <option value="{{ $lang->code }}"
                            {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                          </option>
                        @endforeach
                      </select>
                    @endif
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Flash Section Title') }}</label>
                      <input name="flash_section_title" class="form-control"
                        value="{{ $ubs->flash_section_title ?? '' }}">
                      <p id="errflash_section_title" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                  @if ($setting->theme == 'fashion' || $setting->theme == 'furniture')
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Flash Section Subtitle') }}</label>
                        <input name="flash_section_subtitle" class="form-control"
                          value="{{ $ubs->flash_section_subtitle ?? '' }}">
                        <p id="errflash_section_subtitle" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <!--video content section -->
          @if ($setting->theme == 'furniture' || $setting->theme == 'kids')
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="card-title text-warning">{{ __('Video Section') }}</div>
                    </div>
                    <div class="col-lg-4">
                      @if (!empty($u_langs))
                        <select name="language" class="form-control"
                          onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                          <option value="" selected disabled>
                            {{ __('Select a Language') }}
                          </option>
                          @foreach ($u_langs as $lang)
                            <option value="{{ $lang->code }}"
                              {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                            </option>
                          @endforeach
                        </select>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <div class="col-12 mb-2 pl-0">
                          <label for="image"><strong>{{ __('Background Image') }}</strong></label>
                        </div>
                        <div class="col-md-12 showImage5 showImage-position mb-3 pl-0 pr-0">
                          <img
                            src="{{ isset($ubs->video_background_image) ? asset('assets/front/img/hero_slider/' . $ubs->video_background_image) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="..." class="img-thumbnail">
                          @if (@$ubs->video_background_image)
                            <button class="remove-img btn btn-sm btn-remove text-white btn-danger" type="button"
                              data-url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                              data-name="video_background_image">
                              <i class="fas fa-times"></i>
                            </button>
                          @endif
                        </div>

                        <div role="button" class="btn btn-primary btn-sm upload-btn" id="image5">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="video_background_image">
                        </div>
                        <p id="errvideo_background_image" class="em text-danger mb-0"></p>
                        <p class="text-warning p-0 mb-1">
                          @if ($setting->theme === 'furniture')
                            {{ __('Recommended Image size : 1920X860') }}
                          @else
                            {{ __('Recommended Image size : 1920X600') }}
                          @endif
                        </p>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Video Section Title') }}</label>
                        <input type="text" class="form-control" name="video_section_title"
                          value="{{ $ubs->video_section_title ?? null }}">
                        <p id="errvideo_section_title" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                    @if ($setting->theme != 'fashion')
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Video Section Subtitle') }}</label>
                          <input type="text" class="form-control" name="video_section_subtitle"
                            value="{{ $ubs->video_section_subtitle ?? null }}">
                          <p id="errvideo_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    @endif
                    @if ($setting->theme != 'furniture')
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Button Name') }}</label>
                          <input type="text" class="form-control" name="video_section_button_name"
                            value="{{ $ubs->video_section_button_name ?? null }}">
                          <p id="errvideo_section_button_name" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label>{{ __('Button URL') }}</label>
                          <input type="url" class="form-control" name="video_section_button_url"
                            value="{{ $ubs->video_section_button_url ?? null }}">
                          <p id="errvideo_section_button_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    @endif

                    @if ($setting->theme == 'electronics' || $setting->theme == 'vegetables')
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Video Section Text') }}</label>
                          <input type="text" class="form-control" name="video_section_text"
                            value="{{ $ubs->video_section_text ?? null }}">
                          <p id="errvideo_section_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    @endif

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Video URL') }}</label>
                        <input type="text" class="form-control" name="video_url"
                          value="{{ $ubs->video_url ?? null }}">
                        <p id="errvideo_url" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
          <!--Featured Product -->
          @if ($setting->theme == 'manti')
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="card-title text-warning">
                        {{ __('Featured Section') }}
                      </div>
                    </div>
                    <div class="col-lg-4">
                      @if (!empty($u_langs))
                        <select name="language" class="form-control"
                          onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                          <option value="" selected disabled>
                            {{ __('Select a Language') }}
                          </option>
                          @foreach ($u_langs as $lang)
                            <option value="{{ $lang->code }}"
                              {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                            </option>
                          @endforeach
                        </select>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Featured Section Title') }}</label>
                        <input name="featured_section_title" class="form-control"
                          value="{{ $ubs->featured_section_title ?? '' }}">
                        <p id="errfeatured_section_title" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
          <!--features content section -->
          @if ($setting->theme == 'vegetables')
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="col-lg-8">
                      <div class="card-title text-warning">
                        {{ __('Features Section') }}
                      </div>
                    </div>
                    <div class="col-lg-4">
                      @if (!empty($u_langs))
                        <select name="language" class="form-control"
                          onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                          <option value="" selected disabled>
                            {{ __('Select a Language') }}
                          </option>
                          @foreach ($u_langs as $lang)
                            <option value="{{ $lang->code }}"
                              {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                            </option>
                          @endforeach
                        </select>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <div class="col-12 mb-2 pl-0">
                          <label for="image"><strong>{{ __('Image') }}</strong></label>
                        </div>
                        <div class="col-md-12 showImage showImage-position mb-3 pl-0 pr-0">
                          <img
                            src="{{ !is_null(@$ubs->featured_img) ? asset('assets/front/img/user/feature/' . $ubs->featured_img) : asset('assets/admin/img/noimage.jpg') }}"
                            alt="..." class="img-thumbnail">
                          @if (!is_null(@$ubs->featured_img))
                            <button class="remove-img btn btn-sm btn-remove text-white btn-danger" type="button"
                              data-url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                              data-name="featured_img">
                              <i class="fas fa-times"></i>
                            </button>
                          @endif
                        </div>

                        <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="featured_img">
                        </div>
                        <p class="p-0 text-warning">
                          {{ __('Recommended Image size : 680X670') }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>

    </div>
  </form>
  <div class="card">
    <div class="card-footer text-center">
      <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Update') }}</button>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="{{ asset('assets/user/js/image-text.js') }}"></script>
@endsection
