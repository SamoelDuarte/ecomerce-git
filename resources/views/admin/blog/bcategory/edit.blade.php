@extends('admin.layout')

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
        <a
          href="{{ route('admin.bcategory.index') . '?language=' . request()->input('language') }}">{{ __('Categories') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Category') }}</a>
      </li>
    </ul>
  </div>


  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Category') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('admin.bcategory.index') . '?language=' . request()->input('language') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="ajaxForm" action="{{ route('admin.bcategory.update') }}" method="POST">
                @csrf
                @foreach ($languages as $language)
                  @php
                    $category = App\Models\Bcategory::where([
                        ['language_id', $language->id],
                        ['unique_id', $data->unique_id],
                    ])->first();
                  @endphp

                  <input type="hidden" name="{{ $language->code }}_id" value="{{ @$category->id }}">
                  <div class="form-group">
                    <label for="">{{ __('Name') }}
                      ({{ $language->name }})
                      @if ($language->is_default == 1)
                        <span class="text-danger">**</span>
                      @endif
                    </label>
                    <input type="text"
                      class="form-control {{ $language->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                      name="{{ $language->code }}_name" value="{{ @$category->name }}"
                      placeholder="{{ __('Enter name') }}">
                    <p id="err{{ $language->code }}_name" class="mb-0 text-danger em"></p>
                    @if ($language->is_default != 1 && !empty($category->name))
                      <p class="text-warning">
                        <small>{{ __('You cannot remove the category name for') . ' ' . $language->name . '. ' . __('Delete data manually.') }}</small>
                      </p>
                    @endif
                  </div>
                @endforeach
                <input type="hidden" name="category_id" value="{{ $data->id }}">
                <div class="form-group">
                  <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
                  <select class="form-control" name="status">
                    <option value="" selected disabled>{{ __('Select a status') }}
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
                  <input type="number" class="form-control" name="serial_number"
                    placeholder="{{ __('Enter Serial Number') }}" value="{{ $data->serial_number }}">
                  <p id="errserial_number" class="mb-0 text-danger em"></p>
                  <p class="text-warning">
                    <small>{{ __('The higher the serial number is, the later the blog category will be shown.') }}</small>
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
