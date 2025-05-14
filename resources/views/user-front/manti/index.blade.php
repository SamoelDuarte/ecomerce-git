@extends('user-front.layout')

@section('meta-description', !empty($seo) ? $seo->home_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->home_meta_keywords : '')
@section('page-title', $keywords['Home'] ?? __('Home'))
@section('og-meta')
  <!--- For Social Media Share Thumbnail --->
  <meta property="og:title" content="{{ $user->username }}">
  <meta property="og:image" content="{{ !empty($userBs->logo) ? asset('assets/front/img/user/' . $userBs->logo) : '' }}">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
  <!--- For Social Media Share Thumbnail --->
@endsection

@section('content')
  @php
    $user_currency = user_currency(Session::get('user_curr'));
    $additional_section_status = json_decode($userBs->additional_section_status, true);
  @endphp

  @if ($ubs->slider_section == 1)
    <section class="home-slider home-slider-6 pt-40 header-next">
      <div class="container">
        <div class="animated-slider" id="home-slider-6"
          data-slick='{
            "dots": false,
            "speed": 500,
            "autoplaySpeed": 6000,
            "dots": true
          }'>
          @if (count($hero_sliders) > 0)
            @foreach ($hero_sliders as $hero_slider)
              <div class="slider-item ratio radius-md">
                <img class="lazyload bg-img blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/front/img/hero_slider/' . $hero_slider->img) }}" alt="Banner">
                <div class="d-flex align-items-center">
                  <div class="slider-content">
                    <span class="sub-title lc-1" data-animation="animate__fadeInUp" data-delay=".3s">
                      {{ $hero_slider->title }}
                    </span>
                    <h1 class="title lc-2" data-animation="animate__fadeInDown" data-delay=".1s">
                      {{ $hero_slider->subtitle }}
                    </h1>
                    <p class="text-lg lc-3" data-animation="animate__fadeInDown" data-delay=".2s">
                      {{ $hero_slider->text }}
                    </p>
                    <a href="{{ $hero_slider->btn_url }}" class="btn btn-lg btn-primary radius-sm icon-end"
                      data-animation="animate__fadeInUp" data-delay=".3s">
                      {{ $hero_slider->btn_name }}
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="slider-item ratio radius-md">
              <img class="lazyload bg-img blur-up" src="{{ asset('assets/user-front/images/manti/hero.jpg') }}"
                alt="Banner">
              <div class="d-flex align-items-center">
                <div class="slider-content">
                  <span class="sub-title lc-1" data-animation="animate__fadeInUp" data-delay=".3s">
                    {{ __('Slider Title') }}
                  </span>
                  <h1 class="title lc-2" data-animation="animate__fadeInDown" data-delay=".1s">
                    {{ __('Slider Subtitle') }}
                  </h1>
                  <p class="text-lg lc-3" data-animation="animate__fadeInDown" data-delay=".2s">
                    {{ __('Lorem ipsum dolor sit amet consectetur') }}
                  </p>
                  <a href="#" class="btn btn-lg btn-primary radius-sm icon-end" data-animation="animate__fadeInUp"
                    data-delay=".3s">
                    {{ $keywords['Explore More'] ?? __('Explore More') }}
                  </a>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </section>
  @endif

  <!-- Section banner End -->
  @if (count($after_hero) > 0)
    @foreach ($after_hero as $cusHero)
      @if (isset($additional_section_status[$cusHero->id]))
        @if ($additional_section_status[$cusHero->id] == 1)
          @php
            $cusHeroContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusHero->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusHeroContent,
              'possition' => $cusHero->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  <!-- Section featured End -->
  @if ($ubs->featuers_section == 1)
    <section class="featured featured-2 pt-30 pb-70">
      <div class="container">
        <div class="row align-items-center gx-xl-5">
          @foreach ($how_work_steps as $how_work_step)
            <div class="col-lg-3 col-md-6 mb-30">
              <div class="featured-item">
                <div class="icon color-primary">
                  <i class="{{ str_replace(['fas ', 'far '], 'fal ', $how_work_step->icon) }}"></i>
                </div>
                <div class="content">
                  <h5>{{ $how_work_step->title }}</h5>
                  <p class="text-sm pe-lg-4">{{ $how_work_step->text }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif
  <!-- Section featured End -->

  @if (count($after_featuers) > 0)
    @foreach ($after_featuers as $cusHowWork)
      @if (isset($additional_section_status[$cusHowWork->id]))
        @if ($additional_section_status[$cusHowWork->id] == 1)
          @php
            $cusHowWorkContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusHowWork->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusHowWorkContent,
              'possition' => $cusHowWork->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  <!-- Category Start -->
  @if ($userBs->category_section == 1)
    <section class="category category-6 pb-100">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-inline mb-20">
              <h2 class="title mb-20">{{ $userSec->category_section_title ?? __('Browse By Categories') }}</h2>
              <div class="slider-arrow mb-20" id="cat-slider-grocery-arrows"></div>
            </div>
          </div>
          <div class="col-12">
            @if (count($item_categories) == 0)
              <h5 class="title text-center mb-20">
                {{ $keywords['NO CATEGORIES FOUND'] ?? __('NO CATEGORIES FOUND') }}
              </h5>
            @else
              <div class="category-slider" id="cat-slider-grocery" data-slick='{"arrows": true, "slidesToShow": 7}'>
                @foreach ($item_categories as $category)
                  <div class="category-item">
                    <a href="{{ route('front.user.shop', [getParam(), 'category=' . $category->slug]) }}">
                      <div class="category-img">
                        <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/categories/' . $category->image) }}"
                          alt="Vegetable">
                      </div>
                      <h3 class="category-item-title">{{ $category->name }}</h3>
                    </a>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Category End -->
  @if (count($after_categories) > 0)
    @foreach ($after_categories as $cusCategory)
      @if (isset($additional_section_status[$cusCategory->id]))
        @if ($additional_section_status[$cusCategory->id] == 1)
          @php
            $cusCategoryContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusCategory->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusCategoryContent,
              'possition' => $cusCategory->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  <!-- Best Sale Product Start -->
  @if ($ubs->flash_section == 1)
    @include('user-front.manti.flash_content')
  @endif

  @if (count($after_flash) > 0)
    @foreach ($after_flash as $cusFlash)
      @if (isset($additional_section_status[$cusFlash->id]))
        @if ($additional_section_status[$cusFlash->id] == 1)
          @php
            $cusFlashContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusFlash->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusFlashContent,
              'possition' => $cusFlash->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->featured_section == 1)
    @include('user-front.manti.featured_content')
  @endif

  @if (count($after_featured) > 0)
    @foreach ($after_featured as $cusFeatured)
      @if (isset($additional_section_status[$cusFeatured->id]))
        @if ($additional_section_status[$cusFeatured->id] == 1)
          @php
            $cusFeaturedContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusFeatured->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusFeaturedContent,
              'possition' => $cusFeatured->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  <!-- Best Sale Product End -->
  @if ($ubs->middle_banner_section == 1)
    <!-- Section banner Start -->
    <section class="section-banner pb-70">
      <div class="container">
        <div class="row">
          @if ($banners)
            @php
              $serial = 1;
            @endphp
            @for ($i = 0; $i < count($banners); $i++)
              @if ($banners[$i]->position == 'middle' && $serial <= 2)
                <div class="col-lg-6">
                  <div class="banner-sm banner-sm-style-3 radius-md mb-30 ratio ratio-5-3">
                    <img class="lazyload bg-img blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}" alt="Banner">
                    <div class="banner-content">
                      <div class="content-inner">
                        <span class="sub-title color-primary">{{ $banners[$i]->title }}</span>
                        <h2 class="title">{{ $banners[$i]->subtitle }}</h2>
                        <a href="{{ $banners[$i]->banner_url }}"
                          class="btn btn-md btn-primary radius-sm">{{ $banners[$i]->button_text }}</a>
                      </div>
                    </div>
                  </div>
                </div>
                @php
                  $serial = $serial + 1;
                @endphp
              @endif
            @endfor
          @endif

        </div>
      </div>
    </section>
    <!-- Section banner End -->
  @endif

  @if (count($after_middle_banner) > 0)
    @foreach ($after_middle_banner as $cusMiddleBanner)
      @if (isset($additional_section_status[$cusMiddleBanner->id]))
        @if ($additional_section_status[$cusMiddleBanner->id] == 1)
          @php
            $cusMiddleBannerContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusMiddleBanner->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusMiddleBannerContent,
              'possition' => $cusMiddleBanner->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  {{-- tab section --}}
  @for ($i = 0; $i < count($tabs); $i++)
    @include('user-front.manti.tab-content')
  @endfor

  @if (count($after_tab) > 0)
    @foreach ($after_tab as $cusTab)
      @if (isset($additional_section_status[$cusTab->id]))
        @if ($additional_section_status[$cusTab->id] == 1)
          @php
            $cusTabContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusTab->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusTabContent,
              'possition' => $cusTab->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->top_rated_section == 1)
    @include('user-front.manti.rated-content')
  @endif

  @if (count($after_top_rate_top_selling) > 0)
    @foreach ($after_top_rate_top_selling as $cusTopSR)
      @if (isset($additional_section_status[$cusTopSR->id]))
        @if ($additional_section_status[$cusTopSR->id] == 1)
          @php
            $cusTopSRContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusTopSR->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusTopSRContent,
              'possition' => $cusTopSR->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif


  @if ($ubs->cta_section_status == 1)
    <!-- Newsletter Start -->
    <section class="newsletter py-0">
      <div class="container">
        <div class="image-inner radius-md pt-4 pb-1 px-4">
          <!-- Background Image -->
          <img class="blur-up bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
            data-src="{{ @$userSec->action_section_background_image ? asset('assets/front/img/cta/' . @$userSec->action_section_background_image) : asset('assets/user-front/images/manti/newsl-bg-5.jpg') }}"
            alt="Bg-img">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="content mw-100">
                <h3 class="title mb-20">
                  {{ $userSec->call_to_action_section_title ?? ($keywords[' Subscribe to Our Newsletter and Enjoy Weakly 50% Discount Offer '] ?? __(' Subscribe to Our Newsletter and Enjoy Weakly 50% Discount Offer ')) }}
                  <br>
                  <br>
                  @if (!is_null(@$userSec->call_to_action_section_button_url))
                    <a href="{{ $userSec->call_to_action_section_button_url }}"
                      class="btn btn-md btn-primary radius-sm">{{ $userSec->call_to_action_section_button_text }}</a>
                  @endif
                </h3>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="image mb-3">
                <img class="blur-up lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ @$userSec->action_section_side_image ? asset('assets/front/img/cta/' . @$userSec->action_section_side_image) : asset('assets/user-front/images/manti/newsl-bg-5.jpg') }}"
                  alt="Image">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Newsletter End -->

  @if (count($after_call_to_action) > 0)
    @foreach ($after_call_to_action as $cusCall)
      @if (isset($additional_section_status[$cusCall->id]))
        @if ($additional_section_status[$cusCall->id] == 1)
          @php
            $cusCallContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusCall->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusCallContent,
              'possition' => $cusCall->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  {{-- Variation Modal Starts --}}
  @include('user-front.partials.variation-modal')
  <!-- Quick View Modal Start -->
  <div class="modal custom-modal quick-view-modal fade" id="quickViewModal" tabindex="-1"
    aria-labelledby="quickViewModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">

      <div class="modal-content radius-sm">
        <button type="button" class="close_modal_btn" data-bs-dismiss="modal" aria-label="Close"> <i
            class="fal fa-times"></i></button>
        <div class="modal-body">
          <div class="product-single-default">
            <div class="row" id="quickViewModalContent">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Quick View Modal End -->
@endsection
