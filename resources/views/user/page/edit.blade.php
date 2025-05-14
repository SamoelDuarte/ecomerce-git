@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
@endphp

@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Page') }}</h4>
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
        <a href="#">{{ __('Additional Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('user.page.index', ['language' => $de_lang->code]) }}">{{ __('All Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Page') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Page') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('user.page.index', ['language' => $de_lang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 m-auto">
              <div class="alert alert-danger pb-1 d-none" id="postErrors">
                <ul></ul>
              </div>
              <form id="itemForm" action="{{ route('user.page.update', $page->id) }}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group px-0">
                      <label>{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                      <input class="form-control" type="number" name="serial_number"
                        placeholder="{{ __('Enter Serial Number') }}" value="{{ $page->serial_number }}">
                      <p class="text-warning mt-2 mb-0">
                        <small>{{ __('The higher the serial number is, the later the blog will be shown.') }}</small>
                      </p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group px-0">
                      <label>{{ __('Status') }} <span class="text-danger">**</span></label>
                      <select name="status" class="form-control">
                        <option @selected($page->status == 1) value="1">{{ __('Active') }}
                        </option>
                        <option @selected($page->status == 0) value="0">{{ __('Deactive') }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="accordion accordion-secondary mt-3" id="accordion">
                  @foreach ($userLangs as $language)
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . ' ' . __('Language') }}
                            {{ $language->is_default == 1 ? __('(Default)') : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        @php
                          $page_content = DB::table('user_page_contents')
                              ->where([['page_id', $page->id], ['language_id', $language->id]])
                              ->first();
                        @endphp
                        <div class="card-body">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title') }} <span class="text-danger">**</span></label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="{{ __('Enter Title') }}" value="{{ @$page_content->title }}">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Body') }} <span class="text-danger">**</span></label>
                                <textarea class="form-control summernote" name="{{ $language->code }}_body" id="{{ $language->code }}_body"
                                  placeholder="{{ __('Enter Page Body') }}" data-height="300">{{ @$page_content->body }}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              @php $currLang = $language; @endphp
                              @foreach ($userLangs as $lang)
                                @continue($lang->id == $currLang->id)
                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                      onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $lang->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }}
                                      <strong class="text-capitalize text-secondary">{{ $lang->name }}</strong>
                                      {{ __('language') }}</span>
                                  </label>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="itemForm" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
