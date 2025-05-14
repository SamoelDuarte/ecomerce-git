@extends('user.layout')

@includeIf('user.partials.rtl-style')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Testimonial') }}</h4>
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
        <a href="#">{{ __('About Us') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a
          href="{{ route('user.about_us.testimonial.index', ['language' => $d_lang->code]) }}">{{ __('Testimonials') }}</a>
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
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title">{{ __('Edit Testimonial') }}</div>
            </div>
            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="{{ route('user.about_us.testimonial.index', ['language' => $d_lang->code]) }}"
                class="btn btn-info btn-sm float-lg-right float-left"><i class="fas fa-backward"></i>
                {{ __('Back') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mx-auto">
              <form id="testimonialUpdateForm"
                action="{{ route('user.about_us.testimonialUpdate', ['id' => $data->id]) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <div class="col-12 mb-2 pl-0">
                    <label for="image"><strong>{{ __('Image') }}</strong></label>
                  </div>
                  <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                    <img
                      src="{{ !is_null(@$data->image) ? asset('assets/front/img/user/about/testimonial/' . @$data->image) : asset('assets/admin/img/noimage.jpg') }}"
                      alt="..." class="img-thumbnail">
                  </div>

                  <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                    {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="image">
                  </div>

                  <p class="mb-0 text-warning">
                    {{ __('Recommended Image size : 50X70') }}</p>
                  @if ($errors->has('image'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('slider_img') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control {{ $d_lang->rtl == 1 ? 'rtl' : '' }}" name="name"
                    placeholder="{{ __('Enter name') }}" id="inname" value="{{ $data->name }}">
                  @error('name')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="">{{ __('Designation') }} <span class="text-danger">**</span></label>
                  <input type="text" name="designation" class="form-control {{ $d_lang->rtl == 1 ? 'rtl' : '' }}"
                    placeholder="{{ __('Enter designation') }}" id="indesignation" value="{{ $data->designation }}">
                  @error('designation')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="">{{ __('Rating') }} <span class="text-danger">**</span></label>
                  <input type="number" name="rating" class="form-control" placeholder="{{ __('Enter rating') }}"
                    id="inrating" value="{{ $data->rating }}">
                  @error('rating')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
                  <input id="incolor" class="form-control jscolor ltr" name="color" value="{{ $data->color }}">
                  @error('color')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="">{{ __('Comment') }} <span class="text-danger">**</span></label>
                  <textarea class="form-control" name="comment" rows="4" cols="80" placeholder="{{ __('Enter comment') }}">{{ $data->comment }}</textarea>
                  @error('comment')
                    <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
                  @enderror
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success" form="testimonialUpdateForm">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
