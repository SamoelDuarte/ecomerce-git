@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Testimonials') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="#">
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
        <a href="#">{{ __('Testimonials') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">{{ __('Update Information') }}
              </div>
            </div>
            <div class="col-lg-4">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          </div>
        </div>
        <div class="card-body">
          <form id="testimonialSectionForm" action="{{ route('user.about_us.testimonials.section_info.update') }}"
            method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ @$data->id }}" id="">
            <input type="hidden" name="language_id" value="{{ @$lang_id }}" id="">
            <div class="form-group">
              <label for="">{{ __('Title') }} </label>
              <input type="text" class="form-control" name="testimonial_section_title"
                placeholder="{{ __('Enter Title') }}" value="{{ @$data->testimonial_section_title }}">
              @error('testimonial_section_title')
                <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
              @enderror
            </div>

            <div class="form-group">
              <label for="">{{ __('Subtitle') }}</label>
              <textarea class="form-control" name="testimonial_section_subtitle" rows="4" cols="80"
                placeholder="{{ __('Enter Subtitle') }}">{{ @$data->testimonial_section_subtitle }}</textarea>
              @error('testimonial_section_subtitle')
                <p class="mt-2 mb-0 text-danger em">{{ $message }}</p>
              @enderror
            </div>
          </form>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success" form="testimonialSectionForm">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Testimonials') }}</div>
            </div>
            <div class="col-lg-4">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>
                    {{ __('Select a Language') }}</option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary float-right btn-sm mb-2" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i>
                {{ __('Add Testimonial') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('user.about_us.testimonial.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
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
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Designation') }}</th>
                        <th scope="col">{{ __('Rating') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($testimonials as $key => $testimonial)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $testimonial->id }}">
                          </td>
                          <td>
                            <img src="{{ asset('assets/front/img/user/about/testimonial/' . $testimonial->image) }}"
                              class="">
                          </td>
                          <td>
                            {{ strlen($testimonial->name) > 30 ? mb_substr($testimonial->name, 0, 30, 'UTF-8') . '...' : $testimonial->name }}
                          </td>
                          <td>{{ $testimonial->designation }}</td>
                          <td>
                            {{ $testimonial->rating }}
                          </td>
                          <td>
                            <a href="{{ route('user.about_us.testimonial.edit', $testimonial->id) }}"
                              class="btn btn-warning btn-sm mb-1">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>
                            <form class="deleteform d-inline-block"
                              action="{{ route('user.about_us.testimonial.delete') }}" method="post">
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
  @includeIf('user.about.testimonials.create')
@endsection
