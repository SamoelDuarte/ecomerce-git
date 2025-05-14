@extends('user-front.layout')
@section('breadcrumb_title', $pageHeading->edit_profile_page ?? __('My Profile'))
@section('page-title', $pageHeading->edit_profile_page ?? __('My Profile'))

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
                    <h3>{{ $keywords['My Profile'] ?? __('My Profile') }} </h3>
                  </div>
                  <div class="edit-info-area">
                    <form action="{{ route('customer.update_profile', getParam()) }}" method="POST"
                      enctype="multipart/form-data">
                      @csrf
                      <div class="upload-img">
                        <div class="img-box">
                          <img id="imagePreview" class="lazyload"
                            src="{{ is_null($authUser->image) ? asset('assets/user-front/images/avatar-1.jpg') : asset('assets/user-front/images/users/' . $authUser->image) }}"
                            alt="Image">
                        </div>
                        <div class="file-upload-area">
                          <div class="upload-file">
                            <input type="file" name="image" class="upload" id="imageUpload">
                            <span class="btn btn-md radius-sm w-100">{{ $keywords['Upload'] ?? __('Upload') }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder="{{ $keywords['First_Name'] ?? __('First Name') }}" name="first_name"
                              value="{{ $authUser->first_name }}" required>
                            @error('first_name')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder="{{ $keywords['Last_Name'] ?? __('Last Name') }}" name="last_name"
                              value="{{ $authUser->last_name }}" required>
                            @error('last_name')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="email" class="form-control"
                              placeholder="{{ $keywords['Email'] ?? __('Email') }}" name="email"
                              value="{{ $authUser->email }}" required>
                            @error('last_name')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group mb-30">
                            <input type="text" class="form-control"
                              placeholder="{{ $keywords['Phone'] ?? __('Phone') }}" name="contact_number"
                              value="{{ $authUser->contact_number }}" required>
                            @error('contact_number')
                              <p class="mb-3 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group mb-30">
                            <textarea class="form-control" placeholder="{{ $keywords['Address'] ?? __('Address') }}" name="address" required>{{ $authUser->address }}</textarea>
                          </div>
                        </div>
                        <div class="col-lg-12 mb-15">
                          <div class="form-button">
                            <button type="submit"
                              class="btn btn-md radius-sm">{{ $keywords['Submit'] ?? __('Submit') }}</button>
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
