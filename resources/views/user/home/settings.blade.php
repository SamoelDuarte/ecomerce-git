@extends('user.layout')
@section('content')
  @php
    $type = request()->input('type');
  @endphp
  <div class="page-header">
    <h4 class="page-title">{{ __('Item Highlights') }}</h4>
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
        <a href="#">{{ __('Home Page Manager') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Item Highlights') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Update Item Highlights') }}</div>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="settingsForm" action="{{ route('user.sections.item_highlight_update') }}" method="post">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Categories Count') }}<span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="categories_count"
                    value="{{ $shopsettings ? $shopsettings->categories_count : '' }}"
                    placeholder="{{ __('Enter categories count') }}">
                  <p id="errcategories_count" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Flash Sale Items Count') }}<span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="flash_item_count"
                    value="{{ $shopsettings ? $shopsettings->flash_item_count : '' }}"
                    placeholder="{{ __('Enter flash sale items count') }}">
                  <p id="errflash_item_count" class="mb-0 text-danger em"></p>
                </div>

                @if ($userBs->theme == 'vegetables')
                  <div class="form-group">
                    <label for="">{{ __('Top Selling Items') }}<span class="text-danger">**</span></label>
                    <input type="text" class="form-control" name="top_selling_count"
                      value="{{ $shopsettings ? $shopsettings->top_selling_count : '' }}"
                      placeholder="{{ __('Enter top selling count') }}">
                    <p id="errtop_selling_count" class="mb-0 text-danger em"></p>
                  </div>
                @endif

                @if ($userBs->theme == 'vegetables' || $userBs->theme == 'furniture' || $userBs->theme == 'manti')
                  <div class="form-group">
                    <label for="">{{ __('Top Rated Items') }}<span class="text-danger">**</span></label>
                    <input type="text" class="form-control" name="top_rated_count"
                      value="{{ $shopsettings ? $shopsettings->top_rated_count : '' }}"
                      placeholder="{{ __('Enter top rated count') }}">
                    <p id="errtop_rated_count" class="mb-0 text-danger em"></p>
                  </div>
                @endif

                @if ($userBs->theme == 'electronics')
                  <div class="form-group">
                    <label for="">{{ __('Subcategories Count') }}<span class="text-danger">**</span></label>
                    <input type="text" class="form-control" name="subcategories_count"
                      value="{{ $shopsettings ? $shopsettings->subcategories_count : '' }}"
                      placeholder="{{ __('Enter subcategories count') }}">
                    <p id="errsubcategories_count" class="mb-0 text-danger em"></p>
                  </div>
                @endif

                @if ($userBs->theme == 'electronics' || $userBs->theme == 'kids')
                  <div class="form-group">
                    <label for="">{{ __('Latest Items Count') }}<span class="text-danger">**</span></label>
                    <input type="text" class="form-control" name="latest_item_count"
                      value="{{ $shopsettings ? $shopsettings->latest_item_count : '' }}"
                      placeholder="{{ __('Enter latest products count') }}">
                    <p id="errlatest_item_count" class="mb-0 text-danger em"></p>
                  </div>
                @endif

              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" form="settingsForm" class="btn btn-success">{{ __('Submit') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
