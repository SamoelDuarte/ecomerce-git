@extends('user-front.layout')

@section('breadcrumb_title', $pageHeading->change_password_page ?? __('Change Password'))
@section('page-title', $pageHeading->change_password_page ?? __('Change Password'))

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
                    <h3>{{ $keywords['Change password'] ?? __('Change Password') }} </h3>
                  </div>
                  <div class="edit-info-area">
                    <form action="{{ route('customer.update_password', getParam()) }}" method="POST">
                      @csrf
                      <div class="form-group mb-30">
                        <input type="password" id="currentPass" class="form-control"
                          placeholder="{{ $keywords['Current Password'] ?? __('Current Password') }}"
                          name="current_password" required>
                        <span toggle="#currentPass" class="show-password-field">
                          <i class="show-icon"></i>
                        </span>
                        @error('current_password')
                          <p class="mb-3 text-danger">{{ $keywords[$message] ?? $message }}</p>
                        @enderror
                      </div>
                      <div class="form-group mb-30">
                        <input type="password" id="newPass" class="form-control"
                          placeholder="{{ $keywords['New Password'] ?? __('New Password') }}" name="new_password"
                          required>
                        <span toggle="#newPass" class="show-password-field">
                          <i class="show-icon"></i>
                        </span>
                        @error('new_password')
                          <p class="mb-3 text-danger">{{ $message }}</p>
                        @enderror
                      </div>
                      <div class="form-group mb-30">
                        <input type="password" id="confirmPass" class="form-control"
                          placeholder="{{ $keywords['Confirm New Password'] ?? __('Confirm New Password') }}"
                          name="new_password_confirmation" required>
                        <span toggle="#confirmPass" class="show-password-field">
                          <i class="show-icon"></i>
                        </span>
                        @error('new_password_confirmation')
                          <p class="mb-3 text-danger">{{ $message }}</p>
                        @enderror
                      </div>
                      <div class="mb-30">
                        <div class="form-button">
                          <button class="btn btn-md radius-sm">{{ $keywords['Submit'] ?? __('Save Change') }}</button>
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
