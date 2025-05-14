@extends('user.layout')
@section('content')
  @php
    $type = request()->input('type');
  @endphp
  <div class="page-header">
    <h4 class="page-title">{{ __('Shop Settings') }}</h4>
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
        <a href="#">{{ __('Shop Settings') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Shop Settings') }}</div>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="settingsForm" class="" action="{{ route('user.item.settings_update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
            
                <div class="form-group">
                  <label>{{ __('Catalog Mode') }} <span class="text-danger">**</span></label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="catalog_mode" value="1" class="selectgroup-input"
                        @if ($shopsettings) {{ $shopsettings->catalog_mode == 1 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="catalog_mode" value="0" class="selectgroup-input"
                        @if ($shopsettings) {{ $shopsettings->catalog_mode == 0 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  <p class="text-warning mb-0">
                    {{ __('If you enable catalog mode, then pricing, cart, checkout option of items will be removed. But item & item details page will remain.') }}
                  </p>
                </div>
                <div class="form-group">
                  <label>{{ __('Rating System') }} <span class="text-danger">**</span></label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="item_rating_system" value="1" class="selectgroup-input"
                        @if ($shopsettings) {{ $shopsettings->item_rating_system == 1 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="item_rating_system" value="0" class="selectgroup-input"
                        @if ($shopsettings) {{ $shopsettings->item_rating_system == 0 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{ __('Disqus Comment System') }} <span class="text-danger">**</span></label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="disqus_comment_system" value="1" class="selectgroup-input"
                        @if ($shopsettings) {{ $shopsettings->disqus_comment_system == 1 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="disqus_comment_system" value="0" class="selectgroup-input"
                        @if ($shopsettings) {{ $shopsettings->disqus_comment_system == 0 ? 'checked' : '' }} @endif>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Time Format') }} <span class="text-danger">**</span></label>
                  <select name="time_format" class="form-control select2">
                    <option value="12" @selected($shopsettings->time_format == 12)>{{ __('12 Hour') }}
                    </option>
                    <option value="24" @selected($shopsettings->time_format == 24)>{{ __('24 Hour') }}
                    </option>
                  </select>
                  <p id="errtop_rated_count" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Tax') }}(%) <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="tax"
                    value="{{ $shopsettings ? $shopsettings->tax : '' }}" placeholder="{{ __('Enter tax') }}">
                  <p id="errtax" class="mb-0 text-danger em"></p>
                </div>
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
