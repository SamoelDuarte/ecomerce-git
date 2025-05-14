@extends('admin.layout')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Headings') }}</h4>
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
        <a href="#">{{ __('Headings') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Headings') }}</div>
            </div>
            <div class="col-lg-2">
              @include('admin.partials.languages')
            </div>
          </div>
        </div>
        <div class="card-body pt-5 pb-4">
          <div class="row">
            <div class="col-lg-10 m-auto">
              <form id="ajaxForm"
                action="{{ route('admin.breadcrumb.heading_update', ['language_id' => $language->id]) }}" method="post">
                @csrf
                <div class="row">
                  @php
                    $fields = [
                        'template_title' => 'Template Page Title',
                        'pricing_title' => 'Pricing Page Title',
                        'shop_title' => 'Shop Page Title',
                        'faq_title' => 'FAQ Page Title',
                        'contact_title' => 'Contact Page Title',
                        'blog_title' => 'Blog Page Title',
                        'login_title' => 'Login Page Title',
                        'reset_password_title' => 'Reset Password Page Title',
                        'signup_title' => 'Signup Page Title',
                        'checkout_title' => 'Checkout Page Title',
                        'not_found_title' => '404 Page Title'
                    ];
                  @endphp

                  @foreach ($fields as $field => $label)
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for="">{{ __($label) }}</label>
                        <input name="{{ $field }}" class="form-control" value="{{ $heading->$field ?? '' }}">
                        <p id="err{{ $field }}" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                  @endforeach
                  @foreach ($pages as $page)
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label for=""> {{ $page->title }} {{ __('Page Title') }} </label>
                        <input name="custom_page_heading[{{ $page->id }}]" class="form-control" value="{{ isset($decodedHeadings[$page->id]) ? $decodedHeadings[$page->id] : '' }}">
                        <p id="err{{ $field }}" class="em text-danger mb-0"></p>
                      </div>
                    </div>
                  @endforeach
                </div>



                <div class="card-footer">
                  <div class="form">
                    <div class="form-group from-show-notify row">
                      <div class="col-12 text-center">
                        <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Update') }}</button>
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
