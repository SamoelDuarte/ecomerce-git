@extends('user-front.layout')

@section('breadcrumb_title', $pageHeading->shipping_details_page ?? __('Shipping Details'))
@section('page-title', $pageHeading->shipping_details_page ?? __('Shipping Details'))
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
                  <div class="title pb-0">
                    <h3>{{ $keywords['Shipping Details'] ?? __('Shipping Details') }} </h3>
                  </div>
                  <div class="edit-info-area">
                    <form action="{{ route('customer.shipping-update', getParam()) }}" method="POST"
                      enctype="multipart/form-data">
                      @csrf
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}" name="shipping_fname"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->shipping_fname) }}"
                              value="{{ Request::old('fname') }}">
                            @error('shipping_fname')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}" name="shipping_lname"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->shipping_lname) }}"
                              value="{{ Request::old('fname') }}">
                            @error('shipping_lname')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="email" class="form-control"
                              placeholder=" {{ $keywords['Email'] ?? __('Email') }}" name="shipping_email"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->shipping_email) }}">
                            @error('shipping_email')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder=" {{ $keywords['Phone'] ?? __('Phone') }} " name="shipping_number"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->shipping_number) }}">
                            @error('shipping_number')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder="{{ $keywords['City'] ?? __('City') }} " name="shipping_city"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->shipping_city) }}">
                            @error('shipping_city')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder=" {{ $keywords['State'] ?? __('State') }} " name="shipping_state"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->shipping_state) }}">
                            @error('shipping_state')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder="{{ $keywords['Country'] ?? __('Country') }}" name="shipping_country"
                              value="{{ convertUtf8(Auth::guard('customer')->user()->shipping_country) }}">
                            @error('shipping_country')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group mb-30">
                            <textarea name="shipping_address" class="form-control" placeholder="{{ $keywords['Address'] ?? __('Address') }}">{{ convertUtf8(Auth::guard('customer')->user()->shipping_address) }}</textarea>
                          </div>
                        </div>
                        <div class="col-lg-12 mb-15">
                          <div class="form-button">
                            <button type="submit" class="btn btn-md radius-sm">{{ $keywords['Update'] ?? __('Update') }}
                            </button>
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
