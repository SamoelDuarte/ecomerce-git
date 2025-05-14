@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Package Features') }}</h4>
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
        <a href="#">{{ __('Packages Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Package Features') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Package Features') }}</div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 m-auto">
              <form id="permissionsForm" class="" action="{{ route('admin.package.features_update') }}"
                method="post">
                {{ csrf_field() }}
                <div class="alert alert-warning text-dark">
                  {{ __('Only these selected features will be visible in frontend Pricing Section') }}
                </div>
                <div class="form-group">
                  <label class="form-label">{{ __('Package Features') }}</label>
                  <div class="selectgroup selectgroup-pills">
                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Custom Domain" class="selectgroup-input"
                        @if (is_array($features) && in_array('Custom Domain', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Custom Domain') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Subdomain" class="selectgroup-input"
                        @if (is_array($features) && in_array('Subdomain', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Subdomain') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="QR Builder" class="selectgroup-input"
                        @if (is_array($features) && in_array('QR Builder', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('QR Builder') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Blog" class="selectgroup-input"
                        @if (is_array($features) && in_array('Blog', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Blog') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Custom Page" class="selectgroup-input"
                        @if (is_array($features) && in_array('Custom Page', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Custom Page') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Posts Limit" class="selectgroup-input"
                        @if (is_array($features) && in_array('Posts Limit', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Posts Limit') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Google Login" class="selectgroup-input"
                        @if (is_array($features) && in_array('Google Login', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Google Login') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Google Analytics" class="selectgroup-input"
                        @if (is_array($features) && in_array('Google Analytics', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Google Analytics') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Facebook Pixel" class="selectgroup-input"
                        @if (is_array($features) && in_array('Facebook Pixel', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Facebook Pixel') }}</span>
                    </label>


                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Google Recaptcha" class="selectgroup-input"
                        @if (is_array($features) && in_array('Google Recaptcha', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Google Recaptcha') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="WhatsApp Chat Button" class="selectgroup-input"
                        @if (is_array($features) && in_array('WhatsApp Chat Button', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('WhatsApp Chat Button') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Tawk to" class="selectgroup-input"
                        @if (is_array($features) && in_array('Tawk to', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Tawk to') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="features[]" value="Disqus" class="selectgroup-input"
                        @if (is_array($features) && in_array('Disqus', $features)) checked @endif>
                      <span class="selectgroup-button">{{ __('Disqus') }}</span>
                    </label>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" id="permissionBtn" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
