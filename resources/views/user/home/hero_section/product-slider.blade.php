@extends('user.layout')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Hero Section Products') }}</h4>
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
        <a href="#">{{ __('Hero Section Products') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Hero Section Products') }}
          </div>
        </div>
        <div class="card-body product_slider_hero pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">

              <form action="{{ route('user.home_page.heroSec.product_slider.update') }}" method="post">
                @csrf
                <div class="from-wrapper">
                  <select name="products[]" class="form-control select2 " multiple="multiple">
                    <option value="" disabled>{{ __('Select Products') }}</option>
                    @if ($items)
                      @foreach ($items as $item)
                        <option @selected(in_array($item->item_id, $added_products ?? [])) value="{{ $item->item_id }}">{{ $item->title }} </option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <!-- select2-search select2-search--inline this feild display none css -->

                <div class="card-footer">
                  <div class="form">
                    <div class="form-group from-show-notify row">
                      <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success">{{ __('Submit') }}</button>
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
