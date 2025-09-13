@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Mail Information') }}</h4>
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
        <a href="#">{{ __('Email Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Mail Information') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <form action="{{ route('user.mail.subscriber') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="card-title">{{ __('Mail Information') }}</div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-8 m-auto">
                <div class="form-group">
                  <label for="email">{{ __('Reply To') }} <span class="text-danger">**</span></label>
                  <input id="email" type="email" class="form-control" name="email"
                    value="{{ $user->email }}" placeholder="{{ __('Enter Email Address') }}">
                  @if ($errors->has('email'))
                    <p class="text-danger mb-0">{{ $errors->first('email') }}</p>
                  @endif
                </div>
                
                <div class="form-group">
                  <label for="from-name">{{ __('From Name') }} <span class="text-danger">**</span></label>
                  <input id="from-name" type="text" class="form-control" name="from_name"
                    value="{{ $user->from_name ?? $user->company_name }}"
                    placeholder="{{ __('Enter From name') }}">
                  @if ($errors->has('from_name'))
                    <p class="text-danger mb-0">{{ $errors->first('from_name') }}</p>
                  @endif
                </div>

                <div class="form-group">
                    <label>{{__("SMTP Status")}}</label>
                    <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                            <input type="checkbox" name="smtp_status" value="1" class="selectgroup-input" {{$user->smtp_status == 1 ? 'checked' : ''}}>
                            <span class="selectgroup-button">{{__("Active")}}</span>
                        </label>
                    </div>
                </div>

                <div class="smtp-fields" id="smtp-fields" style="{{$user->smtp_status != 1 ? 'display: none;' : ''}}">
                    <div class="form-group">
                        <label>{{__("SMTP Host")}}</label>
                        <input type="text" class="form-control" name="smtp_host" value="{{$user->smtp_host}}">
                        @if ($errors->has('smtp_host'))
                            <p class="mt-2 mb-0 text-danger">{{$errors->first('smtp_host')}}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{__("SMTP Port")}}</label>
                        <input type="text" class="form-control" name="smtp_port" value="{{$user->smtp_port}}">
                        @if ($errors->has('smtp_port'))
                            <p class="mt-2 mb-0 text-danger">{{$errors->first('smtp_port')}}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{__("Encryption")}}</label>
                        <select class="form-control" name="encryption">
                            <option value="tls" {{$user->encryption == 'tls' ? 'selected' : ''}}>TLS</option>
                            <option value="ssl" {{$user->encryption == 'ssl' ? 'selected' : ''}}>SSL</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{__("SMTP Username")}}</label>
                        <input type="text" class="form-control" name="smtp_username" value="{{$user->smtp_username}}">
                        @if ($errors->has('smtp_username'))
                            <p class="mt-2 mb-0 text-danger">{{$errors->first('smtp_username')}}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>{{__("SMTP Password")}}</label>
                        <input type="password" class="form-control" name="smtp_password" value="{{$user->smtp_password}}">
                        @if ($errors->has('smtp_password'))
                            <p class="mt-2 mb-0 text-danger">{{$errors->first('smtp_password')}}</p>
                        @endif
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer text-center">
            <button type="submit" class="btn btn-success">
              {{ __('save') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('input[name="smtp_status"]').on('change', function() {
            if($(this).is(':checked')) {
                $('#smtp-fields').show();
            } else {
                $('#smtp-fields').hide();
            }
        });
    });
</script>
@endsection
