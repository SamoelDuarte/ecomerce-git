@extends('front.layout')

@section('pagename')
  - {{ $pageHeading ?? __('Shops') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->listing_page_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->listing_page_meta_keyword : '')

@section('breadcrumb-title')
  {{ $pageHeading ?? __('Shops') }}
@endsection
@section('breadcrumb-link')
  {{ $pageHeading ?? __('Shops') }}
@endsection

@section('content')

  <!--====== Start saas-featured-users section ======-->

  <section class="user-profile-area pt-120 pb-120">
    <div class="container">
      <form action="{{ route('front.user.view') }}" action="get" id="userSearchForm">
        <div class="row justify-content-between mb-10">
          <div class="col-md-6 col-lg-4 col-xl-3 mb-20">
            <select name="category" class="form-control"
              onchange="document.getElementById('userSearchForm').submit()">
              <option selected value="">{{ __('Select Category') }}</option>
              @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected($category->slug == request()->input('category'))>{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3 mb-20">
            <div class="search-group-field">
              <input type="text" name="shop_name" value="{{ request()->input('shop_name') }}" class="form-control radius-sm"
                onsubmit="document.getElementById('userSearchForm').submit()"
                placeholder="{{ __('Enter shop name...') }}">
                <span class="search-icon">
                  <i class="far fa-search"></i>
                </span>
            </div>

          </div>

        </div>
      </form>


      <div class="row">
        @if (count($users) == 0)
          <div class="bg-light text-center py-5 d-block w-100">
            <h3>{{ __('NO SHOP FOUND') }}</h3>
          </div>
        @else
          @foreach ($users as $user)
            <div class="col-lg-4 col-sm-6">
              <div class="swiper-slide user-card mb-30">
                <div class="card" data-aos="fade-up" data-aos-delay="100">
                  <div class="icon">
                    @if (isset($user->photo))
                      <img class="rounded-circle lazyload"
                        src="{{ isset($user->photo) ? asset('assets/front/img/user/' . $user->photo) : asset('assets/user/img/profile.png') }}"
                        alt="user" class="">
                    @else
                      <img src="{{ asset('assets/user/img/profile.png') }}" alt="..."
                        class="avatar-img rounded-circle lazyload">
                    @endif
                  </div>
                  <div class="card-content blue">
                    <h3 class="card-title">{{ $user->shop_name }}</h3>
                    <div class="social-link">
                      @foreach ($user->social_media as $social)
                        <a href="{{ $social->url }}" target="_blank"><i class="{{ $social->icon }}"></i></a>
                      @endforeach
                    </div>
                    <div class="cta-btns">
                      @php
                        if (!empty($user)) {
                            $currentPackage = App\Http\Helpers\UserPermissionHelper::userPackage($user->id);
                            $preferences = App\Models\User\UserPermission::where([
                                ['user_id', $user->id],
                                ['package_id', $currentPackage->package_id],
                            ])->first();
                            $permissions = isset($preferences) ? json_decode($preferences->permissions, true) : [];
                        }
                      @endphp
                      <a href="{{ detailsUrl($user) }}" class="btn btn-sm secondary-btn"
                        target="_blank">{{ __('View Shop') }}</a>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        @endif
      </div>
      <div class="pagination mb-30 justify-content-center">
        {{ $users->appends(['search' => request()->input('search'), 'designation' => request()->input('designation'), 'location' => request()->input('location')])->links() }}
      </div>
    </div>
    <!-- Bg Overlay -->
    <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-2.png') }}" alt="Bg">
    <!-- Bg Shape -->
    <div class="shape">
      <img class="lazyload shape-1" src="{{ asset('assets/front/images/shape/shape-10.png') }}" alt="Shape">
      <img class="lazyload shape-2" src="{{ asset('assets/front/images/shape/shape-6.png') }}" alt="Shape">
      <img class="lazyload shape-3" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
      <img class="lazyload shape-4" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
      <img class="lazyload shape-5" src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
      <img class="lazyload shape-6" src="{{ asset('assets/front/images/shape/shape-8.png') }}" alt="Shape">
    </div>
  </section>
  <!--====== End saas-featured-users section ======-->
@endsection
