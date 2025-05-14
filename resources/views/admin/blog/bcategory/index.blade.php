@extends('admin.layout')

@php
  $selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Category') }}</h4>
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
        <a href="#">{{ __('Blogs') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Category') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-5">
              <div class="card-title d-inline-block">{{ __('Category') }}</div>
            </div>
            <div class="col-lg-2">
              @include('admin.partials.languages')
            </div>
            <div class="col-lg-5 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add Blog Category') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('admin.bcategory.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($bcategorys) == 0)
                <h3 class="text-center">{{ __('NO BLOG CATEGORY FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bcategorys as $key => $bcategory)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $bcategory->id }}">
                          </td>
                          <td>{{ $bcategory->name }}</td>
                          <td>
                            @if ($bcategory->status == 1)
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active') }}</span>
                              </h2>
                            @else
                              <h2 class="d-inline-block"><span class="badge badge-danger">{{ __('Deactive') }}</span>
                              </h2>
                            @endif
                          </td>
                          <td>{{ $bcategory->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm editBtn mb-1"
                              href="{{ route('admin.bcategory.edit', ['id' => $bcategory->id, 'language' => request()->input('language')]) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>

                            </a>
                            <form class="deleteform d-inline-block" action="{{ route('admin.bcategory.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="bcategory_id" value="{{ $bcategory->id }}">
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


  <!-- Create Blog Category Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Blog Category') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxForm" class="modal-form create" action="{{ route('admin.bcategory.store') }}" method="POST">
            @csrf
            @foreach ($langs as $lang)
              <div class="form-group">
                <label for="">{{ __('Name') }} ({{ $lang->name }})
                    @if($lang->is_default == 1)<span class="text-danger">**</span>@endif
                </label>
                <input type="text"
                  class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                  name="{{ $lang->code }}_name" value="" placeholder="{{ __('Enter name') }}">
                <p id="err{{ $lang->code }}_name" class="mb-0 text-danger em"></p>
              </div>
            @endforeach
            <div class="form-group">
              <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
              <select class="form-control" name="status">
                <option value="" selected disabled>{{ __('Select a status') }}</option>
                <option value="1">{{ __('Active') }}</option>
                <option value="0">{{ __('Deactive') }}</option>
              </select>
              <p id="errstatus" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
              <input type="number" class="form-control" name="serial_number" value=""
                placeholder="{{ __('Enter Serial Number') }}">
              <p id="errserial_number" class="mb-0 text-danger em"></p>
              <p class="text-warning">
                <small>{{ __('The higher the serial number is, the later the blog category will be shown.') }}</small>
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
@endsection
