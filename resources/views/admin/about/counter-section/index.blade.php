@extends('admin.layout')

@if (!empty($abe->language) && $abe->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Counter Section') }}</h4>
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
        <a href="#">{{ __('About Us') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Counter Section') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-md-9">
                  <div class="card-title">{{ __('Update Counter Section Information') }}</div>
                </div>
                <div class="col-lg-3">
                  @include('admin.partials.languages')
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form id="counterImgForm" action="{{ route('admin.home_page.update_counter_section_info') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="lang_code" value="{{ request()->input('language') }}">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0 pr-0">
                        <label for="image"><strong>{{ __('Image') }}</strong></label>
                      </div>
                      <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                        <img
                          src="{{ @$counterSection->image ? asset('assets/front/img/counter-section/' . $counterSection->image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="image">
                      </div>
                      @error('image')
                        <p class="mb-0 text-danger">{{ $errors->first('image') }}</p>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="">{{ __('Section Title') }} <span class="text-danger">**</span></label>
                      <input type="text" name="title" class="form-control" placeholder="{{ __('Enter Title') }}"
                        value="{{ @$counterSection->title }}">
                      @error('title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="">{{ __('Section Text') }} <span class="text-danger">**</span></label>
                      <textarea name="text" class="form-control" placeholder="{{ __('Enter Sub Title') }}">{{ @$counterSection->text }}</textarea>

                      @error('text')
                        <p class="mt-2
                        mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" form="counterImgForm" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-5">
              <div class="card-title">{{ __('Counter Informations') }}</div>
            </div>
            <div class="col-lg-3">
              @include('admin.partials.languages')
            </div>
            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.home_page.bulk_delete_counter') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              @if (count($counters) == 0)
                <h3 class="text-center mt-2">{{ __('NO INFORMATION FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($counters as $counter)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $counter->id }}">
                          </td>
                          <td><i class="{{ $counter->icon }}"></i></td>
                          <td>{{ $counter->amount }}</td>
                          <td>
                            {{ strlen($counter->title) > 20 ? mb_substr($counter->title, 0, 20, 'UTF-8') . '...' : $counter->title }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1  mt-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $counter->id }}" data-icon="{{ $counter->icon }}"
                              data-amount="{{ $counter->amount }}" data-color="{{ $counter->color }}"
                              data-title="{{ $counter->title }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.home_page.delete_counter', ['id' => $counter->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm  mt-1 deleteBtn">
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

        <div class="card-footer"></div>
      </div>
    </div>
  </div>
  {{-- create modal --}}
  @includeIf('admin.about.counter-section.create')
  {{-- edit modal --}}
  @includeIf('admin.about.counter-section.edit')
@endsection
