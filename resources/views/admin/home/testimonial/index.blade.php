@extends('admin.layout')

@if (!empty($abs->language) && $abs->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Testimonials') }}</h4>
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
        <a href="#">{{ __('Testimonials') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-5">
              <div class="card-title d-inline-block">{{ __('Testimonials') }}</div>
            </div>
            <div class="col-lg-2">
              @include('admin.partials.languages')
            </div>
            <div class="col-lg-5">
              <a href="#" class="btn btn-primary float-right btn-sm mt-2 mt-lg-0" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add Testimonial') }}</a>
              <a href="#"
                class="btn btn-secondary float-right btn-sm {{ $default->rtl == 1 ? 'ml-3' : 'mr-3' }} mt-2 mt-lg-0"
                data-toggle="modal" data-target="#sideImageModal"><i class="fas fa-plus"></i>
                {{ __('Add Side Image') }}</a>

            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($testimonials) == 0)
                <h3 class="text-center">{{ __('NO TESTIMONIAL FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Designation') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($testimonials as $key => $testimonial)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td><img src="{{ asset('assets/front/img/testimonials/' . $testimonial->image) }}"
                              alt="" width="40"></td>
                          <td>{{ $testimonial->name }}</td>
                          <td>{{ $testimonial->designation }}</td>
                          <td>{{ $testimonial->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm mb-1"
                              href="{{ route('admin.testimonial.edit', $testimonial->id) . '?language=' . request()->input('language') }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>
                            <form class="deleteform d-inline-block" action="{{ route('admin.testimonial.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="testimonial_id" value="{{ $testimonial->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn mb-1">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Create Testimonial Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Testimonial') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form id="ajaxForm" class="modal-form" action="{{ route('admin.testimonial.store') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Language') }} <span class="text-danger">**</span></label>
              <select name="language_id" class="form-control">
                <option value="" selected disabled>{{ __('Select a Language') }}</option>
                @foreach ($langs as $lang)
                  <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                @endforeach
              </select>
              <p id="errlanguage_id" class="mb-0 text-danger em"></p>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <div class="col-12 pl-0 pr-0">
                    <label for="image"><strong>{{ __('Image') }}</strong></label>
                  </div>
                  <div class="col-md-12 showImage2 mb-3 pl-0 pr-0">
                    <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="img-thumbnail">
                  </div>

                  <div role="button" class="btn btn-primary btn-sm upload-btn" id="image2">
                    {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="image">
                  </div>

                  <p id="errimage" class="mb-0 text-danger em"></p>
                  <p class="p-0 text-warning">
                    {{ __('Recommended Image size : 62X62') }}
                  </p>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="name" value=""
                placeholder="{{ __('Enter name') }}">
              <p id="errname" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Designation') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="designation" value=""
                placeholder="{{ __('Enter designation') }}">
              <p id="errdesignation" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Comment') }} <span class="text-danger">**</span></label>
              <textarea class="form-control" name="comment" rows="3" cols="80"
                placeholder="{{ __('Enter comment') }}"></textarea>
              <p id="errcomment" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
              <input type="number" class="form-control ltr" name="serial_number" value=""
                placeholder="{{ __('Enter Serial Number') }}">
              <p id="errserial_number" class="mb-0 text-danger em"></p>
              <p class="text-warning">
                <small>{{ __('The higher the serial number is, the later the testimonial will be shown.') }}</small>
              </p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="sideImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Side Image') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="modal-form" action="{{ route('admin.testimonial.sideImageStore') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <div class="col-12 mb-2 pl-0 pr-0">
                    <label for="image"><strong>{{ __('Image') }}</strong></label>
                  </div>

                  <div class="col-md-12 showImage mb-3  pl-0 pr-0">
                    <img src="{{ asset('assets/front/img/testimonials/' . $be->testimonial_img) }}" alt="..."
                      class="img-thumbnail">
                  </div>

                  <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                    {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="image">
                  </div>
                  <p id="errimage" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="submitBtn" type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
        </form>
      </div>
    </div>
  </div>
@endsection
