@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Plugins') }}</h4>
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
        <a href="#">{{ __('Site Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Plugins') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="row">

        {{-- Disqus --}}
        @if (in_array('Disqus', $features))
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title">{{ __('Disqus') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form action="{{ route('user.disqus.update') }}" id="disqusForm" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('Disqus Status') }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="is_disqus" value="1" class="selectgroup-input"
                              {{ $userBs->is_disqus == 1 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="is_disqus" value="0" class="selectgroup-input"
                              {{ $userBs->is_disqus == 0 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                          </label>
                        </div>
                        @if ($errors->has('is_disqus'))
                          <p class="mb-0 text-danger">{{ $errors->first('is_disqus') }}</p>
                        @endif
                      </div>

                      <div class="form-group">
                        <label>{{ __('Disqus Shortname') }}</label>
                        <input class="form-control" name="disqus_shortname" value="{{ $userBs->disqus_shortname }}">
                        @if ($errors->has('disqus_shortname'))
                          <p class="mb-0 text-danger">{{ $errors->first('disqus_shortname') }}</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer text-center">
                <button class="btn btn-success" type="submit" form="disqusForm">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        @endif

        @if (in_array('Google Analytics', $features))
          {{-- Google Analytics --}}
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title">{{ __('Google Analytics') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form action="{{ route('user.google.analytics.update') }}" method="POST" id="gaForm">
                  @csrf
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('Google Analytics Status') }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="is_analytics" value="1" class="selectgroup-input"
                              {{ isset($userBs) && $userBs->is_analytics == 1 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                          </label>

                          <label class="selectgroup-item">
                            <input type="radio" name="is_analytics" value="0" class="selectgroup-input"
                              {{ !isset($userBs) || $userBs->is_analytics != 1 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                          </label>
                        </div>

                        @if ($errors->has('is_analytics'))
                          <p class="mt-1 mb-0 text-danger">{{ $errors->first('is_analytics') }}</p>
                        @endif
                      </div>

                      <div class="form-group">
                        <label>{{ __('Measurement ID') }} </label>
                        <input type="text" class="form-control" name="measurement_id"
                          value="{{ isset($userBs) && $userBs->measurement_id ? $userBs->measurement_id : null }}">
                        @if ($errors->has('measurement_id'))
                          <p class="mt-1 mb-0 text-danger">{{ $errors->first('measurement_id') }}</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer text-center">
                <button class="btn btn-success" type="submit" form="gaForm">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        @endif

        @if (in_array('Tawk to', $features))
          {{-- tawk.to --}}
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title">{{ __('Tawk.to') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form action="{{ route('user.tawk.update') }}" id="tawkForm" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('Tawk.to Status') }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="is_tawkto" value="1" class="selectgroup-input"
                              {{ $userBs->is_tawkto == 1 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="is_tawkto" value="0" class="selectgroup-input"
                              {{ $userBs->is_tawkto == 0 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                          </label>
                        </div>
                        <p class="mb-0 text-warning">
                          {{ __('If you enable Tawk.to, then WhatsApp must be disabled.') }}
                        </p>
                        @if ($errors->has('is_tawkto'))
                          <p class="mb-0 text-danger">{{ $errors->first('is_tawkto') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>{{ __('Tawk.to Property Id') }}*</label>
                        <input class="form-control" name="tak_to_property_id"
                          value="{{ $userBs->tak_to_property_id }}">
                        @if ($errors->has('tak_to_property_id'))
                          <p class="mb-0 text-danger">{{ $errors->first('tak_to_property_id') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>{{ __('Tawk.to Widget Id') }}*</label>
                        <input class="form-control" name="tak_to_widget_id" value="{{ $userBs->tak_to_widget_id }}">
                        @if ($errors->has('tak_to_widget_id'))
                          <p class="mb-0 text-danger">{{ $errors->first('tak_to_widget_id') }}</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer text-center">
                <button class="btn btn-success" form="tawkForm" type="submit">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        @endif

        @if (in_array('Google Login', $features))
          {{-- Google Login --}}
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title">{{ __('Google Login') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form action="{{ route('user.googlelogin.update') }}" method="POST" id="googleLoginForm">
                  @csrf
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('Status') }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="is_google_login" value="1" class="selectgroup-input"
                              {{ $userBs->is_google_login == 1 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="is_google_login" value="0" class="selectgroup-input"
                              {{ $userBs->is_google_login == 0 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                          </label>
                        </div>
                        @if ($errors->has('is_google_login'))
                          <p class="mb-0 text-danger">{{ $errors->first('is_google_login') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>{{ __('Google Client ID') }}</label>
                        <input class="form-control" name="google_client_id" value="{{ $userBs->google_client_id }}">
                        @if ($errors->has('google_client_id'))
                          <p class="text-danger">{{ $errors->first('google_client_id') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>{{ __('Google Client Secret') }}</label>
                        <input class="form-control" name="google_client_secret"
                          value="{{ $userBs->google_client_secret }}">
                        @if ($errors->has('google_client_secret'))
                          <p class="text-danger">{{ $errors->first('google_client_secret') }}</p>
                        @endif
                        <p class="text-warning mb-0">
                          {{ __('Google Client ID & Client Secret are required for Google Login.') }}
                        </p>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer text-center">
                <button type="submit" class="btn btn-success" form="googleLoginForm">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        @endif

        @if (in_array('Google Recaptcha', $features))
          {{-- Google rechaptcha --}}
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title">{{ __('Google Recaptcha') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form action="{{ route('user.recaptcha.update') }}" id="grForm" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('Google Recaptcha Status') }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="is_recaptcha" value="1" class="selectgroup-input"
                              {{ $userBs->is_recaptcha == 1 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="is_recaptcha" value="0" class="selectgroup-input"
                              {{ $userBs->is_recaptcha == 0 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                          </label>
                        </div>
                        @if ($errors->has('is_recaptcha'))
                          <p class="mb-0 text-danger">{{ $errors->first('is_recaptcha') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>{{ __('Google Recaptcha Site key') }}</label>
                        <input class="form-control" name="google_recaptcha_site_key"
                          value="{{ $userBs->google_recaptcha_site_key }}">
                        @if ($errors->has('google_recaptcha_site_key'))
                          <p class="mb-0 text-danger">{{ $errors->first('google_recaptcha_site_key') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>{{ __('Google Recaptcha Secret key') }}</label>
                        <input class="form-control" name="google_recaptcha_secret_key"
                          value="{{ $userBs->google_recaptcha_secret_key }}">
                        @if ($errors->has('google_recaptcha_secret_key'))
                          <p class="mb-0 text-danger">{{ $errors->first('google_recaptcha_secret_key') }}</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer text-center">
                <button class="btn btn-success" type="submit" form="grForm">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        @endif

        @if (in_array('Facebook Pixel', $features))
          {{-- Facebook Pixel --}}
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card-title">{{ __('Facebook Pixel') }}</div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form action="{{ route('user.pixel.update') }}" id="pixelForm" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('Facebook Pixel Status') }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="is_facebook_pixel" value="1" class="selectgroup-input"
                              {{ $userBs->is_facebook_pixel == 1 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                          </label>

                          <label class="selectgroup-item">
                            <input type="radio" name="is_facebook_pixel" value="0" class="selectgroup-input"
                              {{ $userBs->is_facebook_pixel != 1 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                          </label>
                        </div>
                        <p id="erris_facebook_pixel" class="mb-0 text-danger em"></p>
                        <p class="text text-warning">
                          <strong>Hint:</strong> <a class="text-primary" href="https://prnt.sc/5u1ZP6YjAw5O"
                            target="_blank">{{ __('Click Here') }}</a>
                          {{ __('to see where to get the Facebook Pixel ID') }}
                        </p>
                        @if ($errors->has('is_facebook_pixel'))
                          <p class="text-danger">{{ $errors->first('is_facebook_pixel') }}</p>
                        @endif
                      </div>

                      <div class="form-group">
                        <label>{{ __('Facebook Pixel ID') }}</label>
                        <input type="text" class="form-control" name="pixel_id" value="{{ $userBs->pixel_id }}">
                        <p id="errpixel_id" class="mb-0 text-danger em"></p>
                        @if ($errors->has('pixel_id'))
                          <p class="text-danger">{{ $errors->first('pixel_id') }}</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer text-center">
                <button type="submit" class="btn btn-success" form="pixelForm">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        @endif

        @if (in_array('WhatsApp Chat Button', $features))
          {{-- Whatsapp Chat Button --}}
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <div class="card-title">{{ __('WhatsApp Chat Button') }}</div>
              </div>
              <div class="card-body">
                <form action="{{ route('user.whatsapp.update') }}" method="POST" id="waForm">
                  @csrf
                  <div class="form-group">
                    <label>Status</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="is_whatsapp" value="1" class="selectgroup-input"
                          {{ $userBs->is_whatsapp == 1 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="is_whatsapp" value="0" class="selectgroup-input"
                          {{ $userBs->is_whatsapp == 0 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                    <p class="text-warning mb-0">
                      {{ __('If you enable WhatsApp, then Tawk.to must be disabled.') }}
                    </p>
                  </div>
                  <div class="form-group">
                    <label>{{ __('WhatsApp Number') }}</label>
                    <input class="form-control" type="text" name="whatsapp_number"
                      value="{{ $userBs->whatsapp_number }}">
                    <p class="text-warning mb-0">
                      {{ __('Enter Phone number with Country Code') }}
                    </p>
                  </div>
                  <div class="form-group">
                    <label>{{ __('WhatsApp Header Title') }}</label>
                    <input class="form-control" type="text" name="whatsapp_header_title"
                      value="{{ $userBs->whatsapp_header_title }}">
                    @if ($errors->has('whatsapp_header_title'))
                      <p class="mb-0 text-danger">{{ $errors->first('whatsapp_header_title') }}</p>
                    @endif
                  </div>
                  <div class="form-group">
                    <label>{{ __('WhatsApp Popup Message') }}</label>
                    <textarea class="form-control" name="whatsapp_popup_message" rows="2">{{ $userBs->whatsapp_popup_message }}</textarea>
                    @if ($errors->has('whatsapp_popup_message'))
                      <p class="mb-0 text-danger">{{ $errors->first('whatsapp_popup_message') }}</p>
                    @endif
                  </div>
                  <div class="form-group">
                    <label>{{ __('Popup') }}</label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="whatsapp_popup" value="1" class="selectgroup-input"
                          {{ $userBs->whatsapp_popup == 1 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="whatsapp_popup" value="0" class="selectgroup-input"
                          {{ $userBs->whatsapp_popup == 0 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                    @if ($errors->has('whatsapp_popup'))
                      <p class="mb-0 text-danger">{{ $errors->first('whatsapp_popup') }}</p>
                    @endif
                  </div>
                </form>
              </div>
              <div class="card-footer text-center">
                <button type="submit" class="btn btn-success" form="waForm">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        @endif

      </div>
    </div>
  </div>
@endsection
