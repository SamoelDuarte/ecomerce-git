@extends('admin.layout')

@php
  use App\Models\Language;
  $selLang = Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang->language) && $selLang->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit package') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Package Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.package.index') . '?language=' . $selLang->code }}">{{ __('Packages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ $package->title }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Package') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Package') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('admin.package.index') . '?language=' . $selLang->code }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="ajaxForm" class="" action="{{ route('admin.package.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="package_id" value="{{ $package->id }}">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Icon') }} <span class="text-danger">**</span></label>
                      <div class="btn-group d-block">
                        <button type="button" class="btn btn-primary iconpicker-component"><i
                            class="{{ $package->icon }}"></i></button>
                        <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car"
                          data-toggle="dropdown">
                        </button>
                        <div class="dropdown-menu"></div>
                      </div>
                      <input id="inputIcon" type="hidden" name="icon" value="{{ $package->icon }}">
                      @if ($errors->has('icon'))
                        <p class="mb-0 text-danger">{{ $errors->first('icon') }}</p>
                      @endif
                      <div class="mt-2">
                        <small>{{ __('NB: click on the dropdown sign to select an icon.') }}</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="title">{{ __('Package title') }} <span class="text-danger">**</span></label>
                      <input id="title" type="text" class="form-control" name="title"
                        value="{{ $package->title }}" placeholder="{{ __('Enter name') }}">
                      <p id="errtitle" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="price">{{ __('Price') }} ({{ $bex->base_currency_text }}) <span
                          class="text-danger">**</span></label>
                      <input id="price" type="number" class="form-control" name="price"
                        placeholder="{{ __('Enter Package price') }}" value="{{ $package->price }}">
                      <p class="text-warning mb-0"><small>{{ __('If price is 0 , than it will appear as free') }}</small>
                      </p>
                      <p id="errprice" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="plan_term">{{ __('Package term') }} <span class="text-danger">**</span></label>
                      <select id="plan_term" name="term" class="form-control">
                        <option value="" selected disabled>{{ __('choose_plan_term') }}</option>
                        <option value="monthly" {{ $package->term == 'monthly' ? 'selected' : '' }}>{{ __('monthly') }}
                        </option>
                        <option value="yearly" {{ $package->term == 'yearly' ? 'selected' : '' }}>{{ __('yearly') }}
                        </option>
                        <option value="lifetime" {{ $package->term == 'lifetime' ? 'selected' : '' }}>
                          {{ __('lifetime') }}
                        </option>
                      </select>
                      <p id="errterm" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  @php
                    $permissions = $package->features;
                    if (!empty($package->features)) {
                        $permissions = json_decode($permissions, true);
                    }
                  @endphp
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="form-label">{{ __('Package Features') }}</label>
                      <div class="selectgroup selectgroup-pills">
                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="Custom Domain" class="selectgroup-input"
                            @if (is_array($permissions) && in_array('Custom Domain', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Custom Domain') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="Subdomain" class="selectgroup-input"
                            @if (is_array($permissions) && in_array('Subdomain', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Subdomain') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="QR Builder" class="selectgroup-input"
                            @if (is_array($permissions) && in_array('QR Builder', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('QR Builder') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" id="post_limit_feature" value="Blog"
                            class="selectgroup-input" @if (is_array($permissions) && in_array('Blog', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Blog') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" id="" value="Custom Page"
                            class="selectgroup-input" @if (is_array($permissions) && in_array('Custom Page', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Custom Page') }}</span>
                        </label>


                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="Google Login" class="selectgroup-input"
                            @if (is_array($permissions) && in_array('Google Login', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Google Login') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="Google Analytics" class="selectgroup-input"
                            @if (is_array($permissions) && in_array('Google Analytics', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Google Analytics') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="Facebook Pixel" class="selectgroup-input"
                            @if (is_array($permissions) && in_array('Facebook Pixel', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Facebook Pixel') }}</span>
                        </label>


                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="Google Recaptcha" class="selectgroup-input"
                            @if (is_array($permissions) && in_array('Google Recaptcha', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Google Recaptcha') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="WhatsApp Chat Button"
                            class="selectgroup-input" @if (is_array($permissions) && in_array('WhatsApp Chat Button', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('WhatsApp Chat Button') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="Tawk to" class="selectgroup-input"
                            @if (is_array($permissions) && in_array('Tawk to', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Tawk to') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="checkbox" name="features[]" value="Disqus" class="selectgroup-input"
                            @if (is_array($permissions) && in_array('Disqus', $permissions)) checked @endif>
                          <span class="selectgroup-button">{{ __('Disqus') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">{{ __('Featured') }} <span class="text-danger">**</span></label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="featured" value="1" class="selectgroup-input"
                            {{ $package->featured == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Yes') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="featured" value="0" class="selectgroup-input"
                            {{ $package->featured == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('No') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">{{ __('Popular') }} <span class="text-danger">**</span></label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="recommended" value="1"
                            class="selectgroup-input"{{ $package->recommended == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Yes') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="recommended" value="0" class="selectgroup-input"
                            {{ $package->recommended == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('No') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  @php
                      $d_none = 'none';
                      $d_block = 'block';
                  @endphp
                  <div class="col-md-6" id="post_limit"
                    @if (is_array($permissions) && in_array('Blog', $permissions)) style="display: {{ $d_block }}" @else style="display: {{ $d_none }}" @endif>
                    <div class="form-group">
                      <label for="post_limit">{{ __('Blog Post Limit') }} <span class="text-danger">**</span>
                        {{ $package->post_limit == 999999 ? __('(Unlimited)') : '' }}</label>
                      <input id="post_limit" type="number" class="form-control" name="post_limit"
                        placeholder="{{ __('Enter Blog Post Limit') }}" value="{{ $package->post_limit }}">
                      <p id="errpost_limit" class="mb-0 text-danger em"></p>
                      <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                    </div>
                  </div>
                  <div class="col-md-6" id="product_limit" style="display: {{ $d_block }}">
                    <div class="form-group">
                      <label for="product_limit">{{ __('Product Limit') }} <span class="text-danger">**</span>
                        {{ $package->post_limit == 999999 ? __('(Unlimited)') : '' }}</label>
                      <input id="product_limit" type="number" class="form-control" name="product_limit"
                        placeholder="{{ __('Enter Product Limit') }}" value="{{ $package->product_limit }}">
                      <p id="errproduct_limit" class="mb-0 text-danger em"></p>
                      <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                    </div>
                  </div>
                  <div class="col-md-6" id="categories_limit" style="display: {{ $d_block }}">
                    <div class="form-group">
                      <label for="categories_limit">{{ __('Categories Limit') }} <span class="text-danger">**</span>
                        {{ $package->post_limit == 999999 ? __('(Unlimited)') : '' }}</label>
                      <input id="categories_limit" type="number" class="form-control" name="categories_limit"
                        placeholder="{{ __('Enter Categories Limit') }}" value="{{ $package->categories_limit }}">
                      <p id="errcategories_limit" class="mb-0 text-danger em"></p>
                      <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                    </div>
                  </div>
                  <div class="col-md-6" id="subcategories_limit" style="display: {{ $d_block }}">
                    <div class="form-group">
                      <label for="subcategories_limit">{{ __('Subcategories Limit') }} <span
                          class="text-danger">**</span>
                        {{ $package->post_limit == 999999 ? __('(Unlimited)') : '' }}</label>
                      <input id="subcategories_limit" type="number" class="form-control" name="subcategories_limit"
                        placeholder="{{ __('Enter Subcategories Limit') }}"
                        value="{{ $package->subcategories_limit }}">
                      <p id="errsubcategories_limit" class="mb-0 text-danger em"></p>
                      <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                    </div>
                  </div>
                  <div class="col-md-6" id="order_limit" style="display: {{ $d_block }}">
                    <div class="form-group">
                      <label for="order_limit">{{ __('Order Limit') }} <span class="text-danger">**</span>
                        {{ $package->post_limit == 999999 ? __('(Unlimited)') : '' }}</label>
                      <input id="order_limit" type="number" class="form-control" name="order_limit"
                        placeholder="{{ __('Enter Order Limit') }}" value="{{ $package->order_limit }}">
                      <p id="errorder_limit" class="mb-0 text-danger em"></p>
                      <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                    </div>
                  </div>

                  <div
                    class="col-md-6 custom-page-box {{ is_array($permissions) && in_array('Custom Page', $permissions) ? '' : 'd-none' }}">
                    <div class="form-group">
                      <label for="">{{ __('Number of Custom Page') }} <span class="text-danger">**</span>
                      </label>
                      <input type="number" class="form-control" name="number_of_custom_page"
                        value="{{ $package->number_of_custom_page }}">
                      <p id="errnumber_of_custom_page" class="mb-0 text-danger em"></p>
                      <p class="text-warning">{{ __('Enter 999999 , then it will appear as unlimited') }}</p>
                    </div>
                  </div>

                  <div class="col-md-6" id="language_limit" style="display: {{ $d_block }}">
                    <div class="form-group">
                      <label for="language_limit">{{ __('Additional Language Limit') }} <span
                          class="text-danger">**</span>
                        {{ $package->post_limit == 999999 ? __('(Unlimited)') : '' }}</label>
                      <input id="language_limit" type="number" class="form-control" name="language_limit"
                        placeholder="{{ __('Enter Additional Language Limit') }}"
                        value="{{ $package->language_limit }}">
                      <p id="errlanguage_limit" class="mb-0 text-danger em"></p>
                      <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">{{ __('Status') }} <span class="text-danger">**</span></label>
                      <select id="status" class="form-control ltr" name="status">
                        <option value="" selected disabled>{{ __('Select a status') }}</option>
                        <option value="1" {{ $package->status == '1' ? 'selected' : '' }}>{{ __('Active') }}
                        </option>
                        <option value="0" {{ $package->status == '0' ? 'selected' : '' }}>{{ __('Deactive') }}
                        </option>
                      </select>
                      <p id="errstatus" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label">{{ __('Trial') }} <span class="text-danger">**</span></label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="is_trial" value="1" class="selectgroup-input"
                            {{ $package->is_trial == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Yes') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="is_trial" value="0" class="selectgroup-input"
                            {{ $package->is_trial == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('No') }}</span>
                        </label>
                      </div>
                      <p id="errtrial_days" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  @if ($package->is_trial == 1)
                    <div class="col-md-6" id="trial_day" style="display: {{ $d_block }}">
                      <div class="form-group">
                        <label for="trial_days_2">{{ __('Trial days') }} <span class="text-danger">**</span></label>
                        <input id="trial_days_2" type="number" class="form-control" name="trial_days"
                          placeholder="{{ __('Enter trial days') }}" value="{{ $package->trial_days }}">
                        <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                      </div>
                    </div>
                  @else
                    <div class="col-md-6" id="trial_day" style="display: {{ $d_none }}">
                      <div class="form-group">
                        <label for="trial_days_1">{{ __('Trial days') }} <span class="text-danger">**</span></label>
                        <input id="trial_days_1" type="number" class="form-control" name="trial_days"
                          placeholder="{{ __('Enter trial days') }}" value="{{ $package->trial_days }}">
                        <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                      </div>
                    </div>
                  @endif
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="meta_keywords">{{ __('Meta Keywords') }}</label>
                      <input id="meta_keywords" type="text" class="form-control" name="meta_keywords"
                        value="{{ $package->meta_keywords }}" data-role="tagsinput">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="meta_description">{{ __('Meta Description') }}</label>
                      <textarea id="meta_description" type="text" class="form-control" name="meta_description" rows="5">{{ $package->meta_description }}</textarea>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
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
      </div>

    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('assets/admin/js/packages.js') }}"></script>
@endsection
