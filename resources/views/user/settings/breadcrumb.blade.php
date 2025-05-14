@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Image') }}</h4>
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
        <a href="#">{{ __('Breadcrumbs') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Image') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Image') }}</div>
            </div>
            <div class="col-lg-2">
              @if (!empty($u_langs))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($u_langs as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          </div>

        </div>
        <div class="card-body pt-5 pb-4">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form enctype="multipart/form-data" action="{{ route('user.breadcrumb.update',['language'=>request()->input('language')]) }}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0">
                        <label for="image"><strong>{{ __('Image') }} <span
                              class="text-danger">**</span></strong></label>
                      </div>
                      <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                        <img
                          src="{{ isset($breadcrumb) ? asset('assets/front/img/user/' . $breadcrumb) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="breadcrumb">
                      </div>
                      @if ($errors->has('breadcrumb'))
                        <p id="errbreadcrumb" class="mb-0 text-danger em">{{ $errors->first('breadcrumb') }}</p>
                      @endif
                      <p class="text-warning p-0 mb-1">
                        {{ __('Recommended Image size : 1600X300') }}
                      </p>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <div class="form">
                    <div class="form-group from-show-notify row">
                      <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
