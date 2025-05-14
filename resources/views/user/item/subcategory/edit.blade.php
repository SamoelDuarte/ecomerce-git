@extends('user.layout')

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Subcategory') }}</h4>
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
        <a
          href="{{ route('user.itemsubcategory.index') . '?language=' . request()->input('language') }}">{{ __('Subcategories') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Subcategory') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Subcategory') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('user.itemsubcategory.index') . '?language=' . request()->input('language') }}">
            <span class="btn-label">
              <i class="fas fa-backward font-12"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="ajaxForm" action="{{ route('user.itemsubcategory.update') }}" method="POST">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Category') }} <span class="text-danger">**</span></label>
                  <select id="language" name="category_id" class="form-control">
                    <option value="" selected disabled>{{ __('Select Category') }}
                    </option>
                    @foreach ($categories as $category)
                      <option {{ $data->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">
                        {{ $category->name }}</option>
                    @endforeach
                  </select>
                  <p id="errcategory_id" class="mb-0 text-danger em"></p>
                </div>
                @foreach ($languages as $lang)
                  @php
                    $subcategory = App\Models\User\UserItemSubCategory::where([
                        ['language_id', $lang->id],
                        ['unique_id', $data->unique_id],
                    ])->first();
                  @endphp
                  <input type="hidden" name="{{ $lang->code }}_id" value="{{ @$subcategory->id }}">
                  <div class="form-group">
                    <label for="">{{ __('Name') }} ({{ $lang->name }}) <span
                        class="text-danger">**</span></label>
                    <input type="text"
                      class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                      name="{{ $lang->code }}_name" value="{{ @$subcategory->name }}"
                      placeholder="{{ __('Enter name') }}">
                    <p id="err{{ $lang->code }}_name" class="mb-0 text-danger em"></p>
                    @if ($lang->is_default != 1 && !empty($subcategory->name))
                      <p class="text-warning">
                        <small>{{ __('You cannot remove the subcategory name for') . ' ' . $lang->name . '. ' . __('Delete data manually.') }}</small>
                      </p>
                    @endif
                  </div>
                @endforeach
                <input type="hidden" name="subcategory_id" value="{{ $data->id }}">

                <div class="form-group">
                  <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
                  <select class="form-control" name="status">
                    <option value="" selected disabled>{{ __('Enter name') }}
                    </option>
                    <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>
                      {{ __('Active') }}</option>
                    <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>
                      {{ __('Deactive') }}</option>
                  </select>
                  <p id="errstatus" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                  <input type="number" class="form-control" name="serial_number" value="{{ $data->serial_number }}"
                    placeholder="{{ __('Enter Serial Number') }}">
                  <p id="errserial_number" class="mb-0 text-danger em"></p>
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
