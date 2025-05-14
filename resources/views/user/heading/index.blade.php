@extends('user.layout')

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{  __('Headings') }}</h4>
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
        <a href="#">{{  __('Breadcrumbs') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{  __('Headings') }}</a>
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
              @if (!empty($u_langs))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($u_langs as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          </div>
        </div>
        <div class="card-body pt-5 pb-4">
          <div class="row">
            <div class="col-lg-10 m-auto">
              <form id="ajaxForm"
                action="{{ route('user.breadcrumb.heading_update', ['language_id' => $language->id]) }}" method="post">
                @csrf
                <div class="row">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Shop Page Title') }}</label>
                      <input name="shop_page" class="form-control" value="{{ $heading->shop_page ?? '' }}">
                      <p id="errshop_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Blog Page Title') }}</label>
                      <input name="blog_page" class="form-control" value="{{ $heading->blog_page ?? '' }}">
                      <p id="errblog_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Contact Page Title') }}</label>
                      <input name="contact_page" class="form-control" value="{{ $heading->contact_page ?? '' }}">
                      <p id="errcontact_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('About Page Title') }}</label>
                      <input name="about_page" class="form-control" value="{{ $heading->about_page ?? '' }}">
                      <p id="errabout_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Compare Page Title') }}</label>
                      <input name="compare_page" class="form-control" value="{{ $heading->compare_page ?? '' }}">
                      <p id="errcompare_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Wishlist Page Title') }}</label>
                      <input name="wishlist_page" class="form-control" value="{{ $heading->wishlist_page ?? '' }}">
                      <p id="errwishlist_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Cart Page Title') }}</label>
                      <input name="cart_page" class="form-control" value="{{ $heading->cart_page ?? '' }}">
                      <p id="errcart_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Login Page Title') }}</label>
                      <input name="login_page" class="form-control" value="{{ $heading->login_page ?? '' }}">
                      <p id="errlogin_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Signup Page Title') }}</label>
                      <input name="signup_page" class="form-control" value="{{ $heading->signup_page ?? '' }}">
                      <p id="errsignup_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label
                        for="">{{  __('Forget Password Page Title') }}</label>
                      <input name="forget_password_page" class="form-control"
                        value="{{ $heading->forget_password_page ?? '' }}">
                      <p id="errforget_password_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label
                        for="">{{  __('Dashboard Page Title') }}</label>
                      <input name="dashboard_page" class="form-control" value="{{ $heading->dashboard_page ?? '' }}">
                      <p id="errdashboard_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Orders Page Title') }}</label>
                      <input name="orders_page" class="form-control" value="{{ $heading->orders_page ?? '' }}">
                      <p id="errorders_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label
                        for="">{{  __('Edit Profile Page Title') }}</label>
                      <input name="edit_profile_page" class="form-control"
                        value="{{ $heading->edit_profile_page ?? '' }}">
                      <p id="erredit_profile_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label
                        for="">{{  __('Billing Details Page Title') }}</label>
                      <input name="billing_details_page" class="form-control"
                        value="{{ $heading->billing_details_page ?? '' }}">
                      <p id="errbilling_details_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label
                        for="">{{  __('Shipping Details Page Title') }}</label>
                      <input name="shipping_details_page" class="form-control"
                        value="{{ $heading->shipping_details_page ?? '' }}">
                      <p id="errshipping_details_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label
                        for="">{{  __('Change Password Page Title') }}</label>
                      <input name="change_password_page" class="form-control"
                        value="{{ $heading->change_password_page ?? '' }}">
                      <p id="errchange_password_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('FAQ Page Title') }}</label>
                      <input name="faq_page" class="form-control" value="{{ $heading->faq_page ?? '' }}">
                      <p id="errfaq_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('Checkout Page Title') }}</label>
                      <input name="checkout_page" class="form-control" value="{{ $heading->checkout_page ?? '' }}">
                      <p id="errcheckout_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                   <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{  __('404 Page Title') }}</label>
                      <input name="not_found_page" class="form-control" value="{{ $heading->not_found_page ?? '' }}">
                      <p id="errnot_found_page" class="em text-danger mb-0"></p>
                    </div>
                  </div>

                  @if (count($pages) > 0)
                    @foreach ($pages as $page)
                      <div class="col-lg-4">
                        <div class="form-group">
                          <label>{{ $page->title }} {{ __('Page Title') }}</label>
                          <input type="text" class="form-control" name="custom_page_heading[{{ $page->id }}]"
                            value="{{ isset($decodedHeadings[$page->id]) ? $decodedHeadings[$page->id] : '' }}">
                          @if ($errors->has('custom_page_heading.' . $page->id))
                            <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  @endif
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
              </form> <!-- Close the form here -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
