@extends('user.layout')

@php
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where(
      'user_id',
      \Illuminate\Support\Facades\Auth::guard('web')->user()->id,
  )->get();
@endphp

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Categories') }}</h4>
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
        <a href="#">{{ __('Categories') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Categories') }}</div>
            </div>
            <div class="col-lg-3">
              @if (!is_null($userDefaultLang))
                @if (!empty($userLanguages))
                  <select name="userLanguage" class="form-control"
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
              @endif
            </div>
            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i>
                {{ __('Add Category') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('user.itemcategory.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($itemcategories) == 0)
                <h3 class="text-center">{{ __('NO CATEGORY FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Image') }}</th>
                        @if ($userBs->theme == 'electronics' || $userBs->theme == 'kids' || $userBs->theme == 'manti')
                          <th scope="col">{{ __('Featured') }}</th>
                        @endif
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($itemcategories as $key => $category)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $category->id }}">
                          </td>

                          <td>{{ convertUtf8($category->name) }}</td>
                          <td>
                            <img
                              src="{{ $category->image ? asset('assets/front/img/user/items/categories/' . $category->image) : asset('assets/admin/img/noimage.jpg') }}"
                              alt="..." class="img-thumbnail table-image">
                          </td>
                          @if ($userBs->theme == 'manti' || $userBs->theme == 'electronics' || $userBs->theme == 'kids')
                            <td>
                              <form action="{{ route('user.itemcategory.feature') }}" method="POST">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <select
                                  class="w-max-content form-control form-control-sm {{ $category->is_feature == 1 ? 'bg-success' : 'bg-danger' }}"
                                  name="is_feature" onchange="this.closest('form').submit()">
                                  <option value="1" {{ $category->is_feature == 1 ? 'selected' : '' }}>
                                    {{ __('Yes') }}</option>
                                  <option value="0" {{ $category->is_feature == 0 ? 'selected' : '' }}>
                                    {{ __('No') }}</option>
                                </select>
                              </form>
                            </td>
                          @endif
                          <td>
                            @if ($category->status == 1)
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active') }}</span>
                              </h2>
                            @else
                              <h2 class="d-inline-block"><span class="badge badge-danger">{{ __('Deactive') }}</span>
                              </h2>
                            @endif
                          </td>
                          <td>
                            {{ $category->serial_number }}
                          </td>
                          <td>

                            <a class="btn btn-secondary btn-sm editbtn mb-1"
                              href="{{ route('user.itemcategory.edit', $category->id) . '?language=' . request()->input('language') }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>


                            <form class="deleteform d-inline-block" action="{{ route('user.itemcategory.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="category_id" value="{{ $category->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn  mb-1">
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
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $itemcategories->appends(['language' => request()->input('language')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Create Service Category Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Category') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxForm" class="modal-form" enctype="multipart/form-data"
            action="{{ route('user.itemcategory.store') }}" method="POST">
            @csrf
            <div class="form-group">
              <div class="col-12 mb-2 pl-0">
                <label for="image"><strong>{{ __('Category Image') }} <span
                      class="text-danger">**</span></strong></label>
              </div>
              <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="img-thumbnail">
              </div>
              <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                {{ __('Choose Image') }}
                <input type="file" class="img-input" name="image">
              </div>
              <p class="text-warning p-0 mb-1">
                {{ __('Recommended Image size : 100X100') }}
              </p>
              <p id="errimage" class="mb-0 text-danger em"></p>
            </div>

            @foreach ($userLanguages as $lang)
              <div class="form-group">
                <label for="">{{ __('Name') }} ({{ $lang->name }}) <span class="text-danger">
                    @if ($lang->is_default == 1)
                      <span class="text-danger">**</span>
                  </span>
            @endif
            </label>
            <input type="text"
              class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
              name="{{ $lang->code }}_name" value="" placeholder="{{ __('Enter name') }}">
            <p id="err{{ $lang->code }}_name" class="mb-0 text-danger em"></p>
        </div>
        @endforeach
        @if ($userBs->theme == 'vegetables' || $userBs->theme == 'furniture')
          <div class="form-group">
            <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control jscolor" name="color" value="">
            <p id="errcolor" class="mb-0 text-danger em"></p>
          </div>
        @endif

        <div class="form-group">
          <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
          <select class="form-control" name="status">
            <option value="" selected disabled>{{ __('Enter name') }}
            </option>
            <option value="1">{{ __('Active') }}</option>
            <option value="0">{{ __('Deactive') }}</option>
          </select>
          <p id="errstatus" class="mb-0 text-danger em"></p>
        </div>

        <div class="form-group">
          <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
          <input type="number" class="form-control" name="serial_number"
            placeholder="{{ __('Enter Serial Number') }}">
          <p id="errserial_number" class="mb-0 text-danger em"></p>
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
