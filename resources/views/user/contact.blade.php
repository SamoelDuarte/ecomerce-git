@extends('user.layout')
@php
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Contact Page') }}</h4>
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
        <a href="#">{{ __('Contact Page') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Contact Page') }}</div>
            </div>

            <div class="col-lg-2">
              @if (!is_null($userDefaultLang))
                @if (!empty($userLanguages))
                  <select name="userLanguage" class="form-control"
                    onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                    <option value="" selected disabled>
                      {{ __('Select a Language') }}</option>
                    @foreach ($userLanguages as $lang)
                      <option value="{{ $lang->code }}"
                        {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}</option>
                    @endforeach
                  </select>
                @endif
              @endif
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="contactSecForm"
                action="{{ route('user.contact.update', ['language' => request()->input('language')]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label>{{ __('Address') }}</label>
                  <textarea class="form-control" name="contact_addresses" rows="3">{{ $data->contact_addresses ?? null }}</textarea>
                  <p class="mb-0 text-warning">
                    {{ __('Use newline to seperate multiple addresses.') }}
                  </p>
                  @if ($errors->has('contact_addresses'))
                    <p class="mb-0 text-danger">{{ $errors->first('contact_addresses') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Phone') }}</label>
                  <input class="form-control" name="contact_numbers" data-role="tagsinput"
                    value="{{ $data->contact_numbers ?? null }}" />
                  <p class="mb-0 text-warning">
                    {{ __('Use comma (,) to seperate multiple contact numbers.') }}
                  </p>
                  @if ($errors->has('contact_numbers'))
                    <p class="mb-0 text-danger">{{ $errors->first('contact_numbers') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Email') }}</label>
                  <input class="form-control" name="contact_mails" data-role="tagsinput"
                    value="{{ $data->contact_mails ?? null }}">
                  <p class="mb-0 text-warning">
                    {{ __('Use comma (,) to seperate multiple contact mails.') }}
                  </p>
                  @if ($errors->has('contact_mails'))
                    <p class="mb-0 text-danger">{{ $errors->first('contact_mails') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Latitude') }}</label>
                  <input type="text" class="form-control" name="latitude"
                    value="{{ @$data->latitude != null ? $data->latitude : '' }}"
                    placeholder="{{ __('Enter Latitude') }}">
                  @if ($errors->has('latitude'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('latitude') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Longitude') }}</label>
                  <input type="text" class="form-control" name="longitude"
                    value="{{ @$data->longitude != null ? $data->longitude : '' }}"
                    placeholder="{{ __('Enter Longitude') }}">
                  @if ($errors->has('longitude'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('longitude') }}</p>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="contactSecForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
