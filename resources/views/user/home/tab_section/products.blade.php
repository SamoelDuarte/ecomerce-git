@extends('user.layout')
@section('content')
  @php
    $type = request()->input('type');
  @endphp
  <div class="page-header">
    <h4 class="page-title">{{ __('Products') }}</h4>
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
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $userBs->theme === 'manti' ? __('Sections') : __('Tabs') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Products') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Products') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('user.tab.index') . '?language=' . request()->input('language') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 ">

              <form action="{{ route('user.tab.products.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="language_id" value="{{ $language_id }}">
                <input type="hidden" name="user_id" value="{{ $user_id }}">
                <input type="hidden" name="tab_id" value="{{ $tab_id }}">
                <div class="row">
                  @php
                    if (is_null($tab_products)) {
                        $tab_products = [];
                    }
                  @endphp
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Add Products in this section') }}</label>
                      <select name="products[]" class="form-control select2 " multiple="multiple">
                        @foreach ($items as $item)
                          <option @selected(in_array($item->item_id, $tab_products)) value="{{ $item->item_id }}">{{ $item->title }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-success">{{ __('Submit') }}</button>
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
