@extends('user-front.layout')
@section('breadcrumb_title', $pageHeading->billing_details_page ?? __('Billing Details'))
@section('page-title', $pageHeading->billing_details_page ?? __('Billing Details'))

@section('content')
  <!-- Dashboard Start -->
  <section class="user-dashboard pt-100 pb-70">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('user-front.customer.side-navbar')
        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="account-info radius-md">
                  <div class="title">
                    <h3>{{ $keywords['Billing Details'] ?? __('Billing Details') }} </h3>
                  </div>
                  <div class="edit-info-area">
                    <form action="{{ route('customer.billing-update', getParam()) }}" method="POST"
                      enctype="multipart/form-data">
                      @csrf
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder="{{ $keywords['First_Name'] ?? __('First Name') }} " name="billing_fname"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->billing_fname) }}"
                              value="{{ Request::old('fname') }}">
                            @error('first_name')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }} " name="billing_lname"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->billing_lname) }}"
                              value="{{ Request::old('fname') }}">
                            @error('last_name')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="email" class="form-control"
                              placeholder=" {{ $keywords['Email'] ?? __('Email') }} " name="billing_email"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->billing_email) }}">
                            @error('last_name')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder=" {{ $keywords['Phone'] ?? __('Phone') }} " name="billing_number"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->billing_number) }}">
                            @error('contact_number')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder=" {{ $keywords['City'] ?? __('City') }} " name="billing_city"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->billing_city) }}">
                            @error('contact_number')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder=" {{ $keywords['State'] ?? __('State') }} " name="billing_state"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->billing_state) }}">
                            @error('billing_state')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder=" {{ $keywords['Country'] ?? __('Country') }} " name="billing_country"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->billing_country) }}">
                            @error('contact_number')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group mb-30">
                            <textarea name="billing_address" class="form-control" placeholder=" {{ $keywords['Address'] ?? __('Address') }} ">{{ convertUtf8(Auth::guard('customer')->user()->billing_address) }}</textarea>
                          </div>
                        </div>
                        <div class="col-lg-12 mb-15">
                          <div class="form-button">
                            <button type="submit"
                              class="btn btn-md radius-sm">{{ $keywords['Update'] ?? __('Update') }}</button>
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
      </div>
    </div>
  </section>
  <!-- Dashboard End -->
@endsection
