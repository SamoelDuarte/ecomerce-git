@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Themes') }}</h4>
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
        <a href="#">{{ __('Themes') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('Themes') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="ajaxForm" action="{{ route('user.theme.update') }}" method="post">
                @csrf

                <div class="form-group">
                  <label class="form-label">{{ __('Theme') }} <span class="text-danger">**</span></label>
                  <div class="row">

                    <div class="col-6 col-sm-4 mb-2">
                      <label class="imagecheck mb-2">
                        <input name="theme" type="radio" value="electronics" class="imagecheck-input"
                          {{ !empty($data->theme) && $data->theme == 'electronics' ? 'checked' : '' }}>
                        <figure class="imagecheck-figure">
                          <img src="{{ asset('assets/front/img/user/themes/electronics.png') }}" alt="title"
                            class="imagecheck-image">
                        </figure>
                      </label>
                      <h5 class="text-center">{{ __('Electronics') }} </h5>
                    </div>
                    <div class="col-6 col-sm-4 mb-2">
                      <label class="imagecheck mb-2">
                        <input name="theme" type="radio" value="fashion" class="imagecheck-input"
                          {{ !empty($data->theme) && $data->theme == 'fashion' ? 'checked' : '' }}>
                        <figure class="imagecheck-figure">
                          <img src="{{ asset('assets/front/img/user/themes/fashion.png') }}" alt="title"
                            class="imagecheck-image">
                        </figure>
                      </label>
                      <h5 class="text-center">{{ __('Fashion') }} </h5>
                    </div>

                    <div class="col-6 col-sm-4 mb-2">
                      <label class="imagecheck mb-2">
                        <input name="theme" type="radio" value="furniture" class="imagecheck-input"
                          {{ !empty($data->theme) && $data->theme == 'furniture' ? 'checked' : '' }}>
                        <figure class="imagecheck-figure">
                          <img src="{{ asset('assets/front/img/user/themes/furniture.png') }}" alt="title"
                            class="imagecheck-image">
                        </figure>
                      </label>
                      <h5 class="text-center">{{ __('Furniture') }} </h5>
                    </div>
                    <div class="col-6 col-sm-4 mb-2">
                      <label class="imagecheck mb-2">
                        <input name="theme" type="radio" value="kids" class="imagecheck-input"
                          {{ !empty($data->theme) && $data->theme == 'kids' ? 'checked' : '' }}>
                        <figure class="imagecheck-figure">
                          <img src="{{ asset('assets/front/img/user/themes/kids.png') }}" alt="title"
                            class="imagecheck-image">
                        </figure>
                      </label>
                      <h5 class="text-center">{{ __('Kids') }} </h5>
                    </div>

                    <div class="col-6 col-sm-4 mb-2">
                      <label class="imagecheck mb-2">
                        <input name="theme" type="radio" value="vegetables" class="imagecheck-input"
                          {{ !empty($data->theme) && $data->theme == 'vegetables' ? 'checked' : '' }}>
                        <figure class="imagecheck-figure">
                          <img src="{{ asset('assets/front/img/user/themes/vegetables.png') }}" alt="title"
                            class="imagecheck-image">
                        </figure>
                      </label>
                      <h5 class="text-center">{{ __('Grocery') }} </h5>
                    </div>

                    <div class="col-6 col-sm-4 mb-2">
                      <label class="imagecheck mb-2">
                        <input name="theme" type="radio" value="manti" class="imagecheck-input"
                          {{ !empty($data->theme) && $data->theme == 'manti' ? 'checked' : '' }}>
                        <figure class="imagecheck-figure">
                          <img src="{{ asset('assets/front/img/user/themes/manti.png') }}" alt="title"
                            class="imagecheck-image">
                        </figure>
                      </label>
                      <h5 class="text-center">{{ __('Multipurpose') }} </h5>
                    </div>

                  </div>
                </div>

              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="submitBtn" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
