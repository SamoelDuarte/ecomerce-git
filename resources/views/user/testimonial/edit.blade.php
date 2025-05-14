@extends('user.layout')

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Testimonial') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('user.testimonials.index') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Testimonial Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Testimonial') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Testimonial') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('user.testimonials.index') . '?language=' . $testimonial->language->code }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 ">
              <form id="ajaxForm" class="" action="{{ route('user.testimonial.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $testimonial->id }}">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-12 mb-2">
                        <label for="image"><strong>{{ __('Image') }} <span
                              class="text-danger">**</span></strong></label>
                      </div>
                      <div class="col-md-12 showImage mb-3  pl-0 pr-0">
                        <img
                          src="{{ $testimonial->image ? asset('assets/front/img/user/testimonials/' . $testimonial->image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <input type="file" name="image" id="image" class="form-control">
                      <p id="errimage" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="name" value="{{ $testimonial->name }}"
                    placeholder="{{ __('Enter name') }}">
                  <p id="errname" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="">{{ __('Occupation') }}</label>
                  <input type="text" class="form-control" name="occupation" value="{{ $testimonial->occupation }}"
                    placeholder="{{ __('Enter occupation') }}">
                  <p id="erroccupation" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="">{{ __('Feedback') }} <span class="text-danger">**</span></label>
                  <textarea class="form-control" name="content" rows="5" placeholder="{{ __('Enter content') }}">{{ replaceBaseUrl($testimonial->content) }}</textarea>
                  <p id="errcontent" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                  <input type="number" class="form-control" name="serial_number"
                    value="{{ $testimonial->serial_number }}" placeholder="{{ __('Enter Serial Number') }}">
                  <p id="errserial_number" class="mb-0 text-danger em"></p>
                  <p class="text-warning">
                    <small>{{ __('The higher the serial number is, the later the blog will be shown.') }}</small>
                  </p>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
