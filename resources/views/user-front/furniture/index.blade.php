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

@php
  $user_currency = user_currency(Session::get('user_curr'));
  $additional_section_status = json_decode($userBs->additional_section_status, true);
@endphp
@section('content')
  @if ($ubs->slider_section == 1)
    <!-- Home Slider Start -->
    <section class="home-slider home-slider-2 bg-cover-no-repeat"
      style="background-image: url({{ !is_null(@$hero_slider->hero_section_background_image) ? asset('assets/front/img/hero_slider/' . @$hero_slider->hero_section_background_image) : asset('assets/user-front/images/furniture/banner/banner-1.jpg') }})">
      <div class="container">
        <div class="animated-slider" id="home-slider-2"
          data-slick='{
            "dots": false,
            "speed": 500,
            "autoplaySpeed": 6000,
            "dots": true
          }'>
          @if (count($hero_sliders) > 0)
            @foreach ($hero_sliders as $hero_slider)
              <div class="slider-item">
                <div class="slider-content">
                  <h1 class="title" data-animation="animate__fadeInDown" data-delay=".1s">{!! $hero_slider->title !!}</h1>
                  <p class="text-lg" data-animation="animate__fadeInDown" data-delay=".1s">{{ $hero_slider->subtitle }}
                  </p>
                  <div class="d-flex flex-sm-row flex-column gap-4 align-items-sm-center"
                    data-animation="animate__fadeInUp" data-delay=".1s">
                    @if ($hero_slider->btn_url && $hero_slider->btn_name)
                      <a href="{{ $hero_slider->btn_url }}"
                        class="btn btn-lg btn-primary me-4">{{ $hero_slider->btn_name }}</a>
                    @endif

                    <div class="video-btn-wrapper d-flex gap-2 align-items-center">
                      @if ($hero_slider->video_url)
                        <a href="{{ $hero_slider->video_url }}" class="btn video-btn youtube-popup me-4"><i
                            class="fas fa-play"></i>
                        </a>
                      @endif
                      <a href="javascript:void(0)">{{ $hero_slider->video_button_text }}</a>
                    </div>
                  </div>
                  <div class="pb-20"></div>
                </div>
              </div>
            @endforeach
          @else
            <div class="slider-item">
              <div class="slider-content">
                <h1 class="title" data-animation="animate__fadeInDown" data-delay=".1s">
                  {{ __('Best Luxurious Furniture  Store') }}</h1>
                <p class="text-lg" data-animation="animate__fadeInDown" data-delay=".1s">
                  {{ __('We create happiness and make sure your what you want from us, Make your home more beautiful') }}
                </p>
                <div class="d-flex align-items-center" data-animation="animate__fadeInUp" data-delay=".1s">
                  <a href="#" class="btn btn-lg btn-primary me-4">{{ __('Start Shopping') }}</a>
                  <a href="https://www.youtube.com/watch?v=QSwvg9Rv2EI" class="btn video-btn youtube-popup me-4"><i
                      class="fas fa-play"></i>
                  </a>
                  <a href="#">{{ __('How to Place Order!') }}</a>
                </div>
                <div class="pb-20"></div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </section>
    <!-- Home Slider End -->
  @endif

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

  @if ($ubs->top_middle_banner_section == 1)
    <!-- Banner Collection Start -->
    <div class="banner-collection pb-70">
      <div class="container">
        <div class="row g-0">
          @if (count($banners) > 0)
            @php
              $middle_banner_count = 1;
            @endphp
            @foreach ($banners as $banner)
              @if ($banner->position == 'top_middle')
                @if ($middle_banner_count <= 2)
                  <div class="col-sm-6">
                    <a href="{{ $banner->banner_url }}">
                      <div class="banner-sm mb-30 ratio p-60 ratio-21-9">
                        <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                        <div class="banner-content mw-80">
                          <div class="content-inner">
                            <span class="sub-title color-medium">{{ $banner->title }}</span>
                            <h3 class="title lc-2">{{ $banner->subtitle }}</h3>
                            <span class="line left_right_slide_anim"></span>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                  @php
                    $middle_banner_count = $middle_banner_count + 1;
                  @endphp
                @endif
              @endif
            @endforeach
          @endif
        </div>
      </div>
    </div>
    <!-- Banner Collection End -->
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

  @if ($ubs->category_section == 1)
    <!-- Category Start -->
    <section class="category category-2 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-inline mb-20">
              <h2 class="title mb-20">{{ $userSec->category_section_title ?? ($keywords['Category'] ?? __('Category')) }}
                <span class="line left_right_slide_anim"></span>
              </h2>
              <p class="text">{{ $userSec->category_section_subtitle ?? '' }} </p>
            </div>
          </div>
          <div class="col-12">
            @if (count($item_categories) == 0)
              <h5 class="text-center mb-20">
                {{ $keywords['NO CATEGORIES FOUND'] ?? __('NO CATEGORIES FOUND') }}
              </h5>
            @else
              <div class="category-slider" id="cat-slider-furniture" data-slick='{"dots": true, "slidesToShow": 6}'>

                @foreach ($item_categories as $cat)
                  <div class="category-item mb-30 color-1">
                    <div class="category-icon" style="--category-bg:#{{ $cat->color }}">
                      <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/items/categories/' . $cat->image) }}" alt="">
                    </div>
                    <div class="category-content border">
                      <h3 class="category-title lc-1"><a
                          href="{{ route('front.user.shop', [getParam(), 'category=' . $cat->slug]) }}">{{ $cat->name }}</a>
                      </h3>
                      <span class="line"></span>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>
    <!-- Category End -->
  @endif

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

  @if ($ubs->flash_section == 1)
    @include('user-front.furniture.flash_content')
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

  @if ($ubs->video_banner_section == 1)
    <!-- Video Banner Start -->
    <section class="video-banner pb-100">
      <div class="container">
        <div class="section-title title-center mb-40">
          <h2 class="title mb-20">{{ $userSec->video_section_title ?? __('Video Section') }}</h2>
          <p class="text mx-auto">{{ $userSec->video_section_subtitle ?? '' }}</p>
          <span class="line left_right_slide_anim mx-auto"></span>
        </div>
      </div>
      <div class="banner-inner">
        <div class="bg-overlay"></div>
        <div class="mt-negative"></div>
        <div class="container">
          <!-- Background Image -->
          <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
            data-src="{{ @$userSec->video_background_image ? asset('assets/front/img/hero_slider/' . $userSec->video_background_image) : asset('assets/front/img/hero_slider/video-banner.png') }}"
            alt="Bg-img">
          <a href="{{ $userSec->video_url ?? '' }}" class="btn video-btn youtube-popup p-absolute">
            <i class="fas fa-play"></i>
          </a>
        </div>
      </div>
    </section>
    <!-- Video Banner End -->
  @endif

  @if (count($after_video) > 0)
    @foreach ($after_video as $cusVideo)
      @if (isset($additional_section_status[$cusVideo->id]))
        @if ($additional_section_status[$cusVideo->id] == 1)
          @php
            $cusVideoContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusVideo->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusVideoContent,
              'possition' => $cusVideo->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->tab_section == 1)
    @include('user-front.furniture.tab_content')
  @endif

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

  <!-- Banner Collection Start -->
  @if ($ubs->middle_banner_section == 1)
    <div class="banner-collection pb-70">
      <div class="container">
        <div class="row">
          @if ($banners)
            @php
              $banner_count = 1;
            @endphp
            @for ($i = 2; $i < count($banners); $i++)
              @if ($banners[$i]->position == 'middle')
                @if ($banner_count <= 2)
                  <div class="col-md-6 col-lg-6">
                    <a href="{{ $banners[$i]->banner_url }}">
                      <div class="banner-sm content-top mb-30 ratio ratio-21-9">
                        <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                          alt="Banner">
                        <div class="banner-content mx-auto">
                          <div class="content-inner p-20">
                            <h3 class="title text-white">{{ $banners[$i]->title }}</h3>
                            @if ($banners[$i]->button_text)
                              <span class="btn-text icon-end text-white">{{ $banners[$i]->button_text }}<i
                                  class="fal fa-long-arrow-right"></i></span>
                            @endif
                          </div>
                        </div>
                        <div class="inner-border"></div>
                        <div class="overlay"></div>
                      </div>
                    </a>
                  </div>
                  @php
                    $banner_count++;
                  @endphp
                @else
                  @break
                @endif
              @endif
            @endfor
          @endif
        </div>
      </div>
  @endif

  @if (count($after_banners) > 0)
    @foreach ($after_banners as $cusBanners)
      @if (isset($additional_section_status[$cusBanners->id]))
        @if ($additional_section_status[$cusBanners->id] == 1)
          @php
            $cusBannersContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusBanners->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusBannersContent,
              'possition' => $cusBanners->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif
  </div>
  <!-- Banner Collection End -->
  @if ($ubs->cta_section_status == 1)
    <!-- Newsletter Start -->
    <section class="newsletter p-0">
      <div class="container">
        <div class="image-inner ptb-100">
          <!-- Background Image -->
          <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
            data-src="{{ @$userSec->action_section_background_image ? asset('assets/front/img/cta/' . @$userSec->action_section_background_image) : asset('assets/front/img/subscriber/default_bg.jpg') }}"
            alt="Bg-img">
          <!-- Background Image -->
          <div class="row">
            <div class="col-md-6">
              <div class="content mw-100 mb-30">
                <h3 class="title mb-20">
                  {{ @$userSec->call_to_action_section_title ?? __('Subscribe our newsletter for home delivery.') }}
                </h3>
                @if (!is_null(@$userSec->call_to_action_section_button_url))
                  <a href="{{ $userSec->call_to_action_section_button_url }}"
                    class="btn btn-md btn-primary rounded-pill">{{ $userSec->call_to_action_section_button_text }}</a>
                @endif
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Newsletter End -->
  @endif

  @if (count($after_call_to_action) > 0)
    @foreach ($after_call_to_action as $cusSubscribe)
      @if (isset($additional_section_status[$cusSubscribe->id]))
        @if ($additional_section_status[$cusSubscribe->id] == 1)
          @php
            $cusSubscribeContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusSubscribe->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusSubscribeContent,
              'possition' => $cusSubscribe->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif


  @include('user-front.furniture.rated_content')


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

  {{-- Variation Modal Starts --}}
  @include('user-front.partials.variation-modal')
  {{-- Variation Modal Ends --}}

  <!-- Quick View Modal Start -->
  <div class="modal custom-modal quick-view-modal fade" id="quickViewModal" tabindex="-1"
    aria-labelledby="quickViewModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content radius-lg">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
