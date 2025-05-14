@extends('admin.layout')

@if (!empty(@$data->language) && @$data->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Images & Texts') }}</h4>
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
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-lg-10">
          <div class="card-title d-inline-block">{{ __('Update Images & Texts') }}</div>
        </div>
        <div class="col-lg-2">
          @include('admin.partials.languages')
        </div>
      </div>
    </div>
  </div>
  <form id="ajaxForm" action="{{ route('admin.herosection.update', $lang_id) }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <div class="row">

      <div class="col-lg-6">
        <div class="row">
          <!--hero sections -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title text-warning">{{ __('Hero Section') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12 mx-auto">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <div class="mb-2">
                            <label for="image"><strong>{{ __('Image') }}</strong></label>
                          </div>
                          <div class="showImage mb-3">
                            <img
                              src="{{ !empty(@$data->image) ? asset('assets/front/img/' . @$data->image) : asset('assets/admin/img/noimage.jpg') }}"
                              alt="..." class="img-thumbnail w-100">
                          </div>
                          <br>
                          <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                            {{ __('Choose Image') }}
                            <input type="file" class="img-input" name="image">
                          </div>
                          <p id="errimage" class="mb-0 text-danger em"></p>
                          <p class="p-0 text-warning">
                            {{ __('Recommended Image size : 755X570') }}
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Title') }}</label>
                          <input name="hero_section_title" class="form-control" value="{{ @$data->hero_section_title }}">
                          <p id="errhero_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Text') }}</label>
                          <input name="hero_section_text" class="form-control" value="{{ @$data->hero_section_text }}">
                          <p id="errhero_section_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Description') }}</label>
                          <input name="hero_section_desc" class="form-control" value="{{ @$data->hero_section_desc }}">
                          <p id="errhero_section_desc" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Button Text') }} </label>
                          <input type="text" class="form-control" name="hero_section_button_text"
                            value="{{ @$data->hero_section_button_text }}">
                          <p id="errhero_section_button_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Button URL') }} </label>
                          <input type="text" class="form-control ltr" name="hero_section_button_url"
                            value="{{ @$data->hero_section_button_url }}">
                          <p id="errhero_section_button_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Video URL') }} </label>
                          <input type="text" class="form-control ltr" name="hero_section_video_url"
                            value="{{ @$data->hero_section_video_url }}">
                          <p id="errhero_section_video_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Features sections -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title text-warning">{{ __('Features Section') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12 mx-auto">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Features Section Title') }}</label>
                          <input name="features_section_title" class="form-control"
                            value="{{ @$data->features_section_title }}">
                          <p id="errfeatures_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Features Section Subtitle') }}</label>
                          <input name="features_section_subtitle" class="form-control"
                            value="{{ @$data->features_section_subtitle }}">
                          <p id="errfeatures_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Features Section Text') }}</label>
                          <input name="features_section_text" class="form-control"
                            value="{{ @$data->features_section_text }}">
                          <p id="errfeatures_section_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Features Section Button Text') }} </label>
                          <input type="text" class="form-control" name="features_section_btn_text"
                            value="{{ @$data->features_section_btn_text }}">
                          <p id="errfeatures_section_btn_text" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Features Section Button URL') }} </label>
                          <input type="text" class="form-control ltr" name="features_section_btn_url"
                            value="{{ @$data->features_section_btn_url }}">
                          <p id="errfeatures_section_btn_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Features Section Video URL') }} </label>
                          <input type="text" class="form-control ltr" name="features_section_video_url"
                            value="{{ @$data->features_section_video_url }}">
                          <p id="errfeatures_section_video_url" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Blog Section -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title text-warning">{{ __('Blog Section') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label for="">{{ __('Blog Title') }}</label>
                          <input name="blog_section_title" class="form-control"
                            value="{{ @$data->blog_section_title }}">
                          <p id="errblog_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="row">
          <!--partner section -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title text-warning">{{ __('Partner Section') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Partner Section Title') }}</label>
                          <input name="partner_section_title" class="form-control"
                            value="{{ @$data->partner_section_title }}">
                          <p id="errpartner_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Partner Section Title') }}</label>
                          <input name="partner_section_subtitle" class="form-control"
                            value="{{ @$data->partner_section_subtitle }}">
                          <p id="errpartner_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--work process section -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title text-warning">{{ __('Work Process Section') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label for="">{{ __('Work Process Section Title') }}</label>
                          <input name="work_process_section_title" class="form-control"
                            value="{{ @$data->work_process_section_title }}">
                          <p id="errwork_process_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--template section -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title text-warning">{{ __('Preview Templates Section') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Template Section Title') }}</label>
                          <input name="template_section_title" class="form-control"
                            value="{{ @$data->template_section_title }}">
                          <p id="errtemplate_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Template Section Subtitle') }}</label>
                          <input name="template_section_subtitle" class="form-control"
                            value="{{ @$data->template_section_subtitle }}">
                          <p id="errtemplate_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--priceing section -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title text-warning">{{ __('Pricing Section') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Pricing Section Title') }}</label>
                          <input name="pricing_section_title" class="form-control"
                            value="{{ @$data->pricing_section_title }}">
                          <p id="errpricing_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Pricing Section Subtitle') }}</label>
                          <input name="pricing_section_subtitle" class="form-control"
                            value="{{ @$data->pricing_section_subtitle }}">
                          <p id="errpricing_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Featured Shop Section -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title text-warning">{{ __('Featured Shop Section') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Featured Shop Title') }}</label>
                          <input name="featured_shop_section_title" class="form-control"
                            value="{{ @$data->featured_shop_section_title }}">
                          <p id="errfeatured_shop_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label for="">{{ __('Featured Shop Subtitle') }}</label>
                          <input name="featured_shop_section_subtitle" class="form-control"
                            value="{{ @$data->featured_shop_section_subtitle }}">
                          <p id="errfeatured_shop_section_subtitle" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--testimonial Section -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title text-warning">{{ __('Testimonial Section') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="form-group">
                          <label for="">{{ __('Testimonial Title') }}</label>
                          <input name="testimonial_section_title" class="form-control"
                            value="{{ @$data->testimonial_section_title }}">
                          <p id="errtestimonial_section_title" class="em text-danger mb-0"></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </form>
  <div class="card">
    <div class="card-footer text-center">
      <button class="btn btn-success" id="submitBtn">{{ __('Save & Changes') }}</button>
    </div>
  </div>
@endsection
