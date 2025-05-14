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
    <!-- Home Slider Start -->
    <section class="home-slider home-slider-5 header-next"
      style="background-image: url({{ !is_null(@$hero_slider->hero_section_background_image) ? asset('assets/front/img/hero_slider/' . @$hero_slider->hero_section_background_image) : asset('assets/user-front/images/kid/banner/banner-1.jpg') }})">

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
                  <h1 class="title lc-2" data-animation="animate__fadeInDown" data-delay=".1s">{{ $hero_slider->title }}
                  </h1>
                  <p class="text-lg lc-3" data-animation="animate__fadeInDown" data-delay=".1s">
                    {{ $hero_slider->subtitle }}
                  </p>
                  @if ($hero_slider->btn_url && $hero_slider->btn_name)
                    <a href="{{ $hero_slider->btn_url }}" class="btn btn-lg btn-primary rounded-pill icon-end"
                      data-animation="animate__fadeInUp" data-delay=".3s">{{ $hero_slider->btn_name }}
                      <span class="icon"><i class="fal fa-arrow-right"></i></span>
                    </a>
                  @endif
                  <div class="pb-20"></div>
                </div>
              </div>
            @endforeach
          @else
            <div class="slider-item">
              <div class="slider-content">
                <h1 class="title lc-2" data-animation="animate__fadeInDown" data-delay=".1s">
                  {{ __('Best Kids Fashion Shop Collection') }}</h1>
                <p class="text-lg lc-3" data-animation="animate__fadeInDown" data-delay=".1s">
                  {{ __('We create happiness and make sure your what you want from us, Make your home more beautiful.') }}
                </p>
                <a href="{{ route('front.user.shop', getParam()) }}" class="btn btn-lg btn-primary rounded-pill icon-end"
                  data-animation="animate__fadeInUp" data-delay=".3s">{{ $keywords['Shop Now'] ?? __('Shop Now') }}
                  <span class="icon"><i class="fal fa-arrow-right"></i></span>
                </a>
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

  @if ($ubs->category_section == 1)
    <!-- Category Start -->
    <section class="category category-5 pt-100">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-center mb-40">
              <h2 class="title mb-20">{{ $userSec->category_section_title ?? ($keywords['Category'] ?? __('Category')) }}
              </h2>
            </div>
          </div>
          <div class="col-12">
            @if (count($item_categories) == 0)
              <h5 class="title text-center mb-20">
                {{ $keywords['NO CATEGORIES FOUND'] ?? __('NO CATEGORIES FOUND') }}
              </h5>
            @else
              <div class="position-relative">
                <div class="category-slider" id="cat-slider-kid" data-slick='{"arrows": true, "slidesToShow": 5}'>
                  @foreach ($item_categories as $category)
                    <div class="category-item color-1">
                      <div class="category-icon mb-20">
                        <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/categories/' . $category->image) }}"
                          alt="Image">
                      </div>
                      <div class="category-content">
                        <h3 class="category-title"><a
                            href="{{ route('front.user.shop', [getParam(), 'category' => $category->slug]) }}">{{ $category->name }}</a>
                        </h3>
                      </div>
                    </div>
                  @endforeach

                </div>
                <div class="slider-arrow absolute-btn" id="cat-slider-kid-arrows"></div>
              </div>
              <div class="text-center mt-40">
                <a href="{{ route('front.user.shop', getParam()) }}"
                  class="btn btn-lg btn-primary rounded-pill icon-end shadow">{{ $keywords['Shop More'] ?? __('Shop More') }}
                  <span class="icon"><i class="fal fa-arrow-right"></i></span>
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
      <!-- Bg Shape -->
      <div class="shape">
        <img class="shape-1" src="{{ asset('assets/front/img/shape/shape-1.png') }}" alt="Shape">
        <img class="shape-2" src="{{ asset('assets/front/img/shape/shape-2.png') }}" alt="Shape">
        <img class="shape-3" src="{{ asset('assets/front/img/shape/shape-3.png') }}" alt="Shape">
        <img class="shape-4" src="{{ asset('assets/front/img/shape/shape-4.png') }}" alt="Shape">
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
      @include('user-front.kids.flash_content')
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

  @if ($ubs->categoryProduct_section == 1)
    @include('user-front.kids.cat_content')
  @endif

  @if (count($after_category_product) > 0)
    @foreach ($after_category_product as $cusCategoryProduct)
      @if (isset($additional_section_status[$cusCategoryProduct->id]))
        @if ($additional_section_status[$cusCategoryProduct->id] == 1)
          @php
            $cusCategoryProductContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusCategoryProduct->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusCategoryProductContent,
              'possition' => $cusCategoryProduct->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  <!-- Banner Collection Start -->
  <div class="banner-collection pt-70">
    <div class="container">
      <div class="row">

        @if ($ubs->left_banner_section == 1)
          <div class="col-lg-4">
            <div class="row">

              @if ($banners)
                @php
                  $leftBanners = $banners->where('position', 'left')->take(2);
                @endphp
                @foreach ($leftBanners as $banner)
                  <div class="col-lg-12 col-sm-6">
                    <div class="banner-sm radius-lg mb-30 ratio ratio-16-9">
                      <img class=" bg-img " src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                      <div class="banner-content mw-80">
                        <div class="content-inner">
                          <span class="sub-title">{{ $banner->title }}</span>
                          <h3 class="title">{{ $banner->subtitle }}
                          </h3>
                          @if ($banner->button_text)
                            <a href="{{ $banner->banner_url }}"
                              class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banner->button_text }}
                              <span class="icon"><i class="fal fa-arrow-right"></i></span>
                            </a>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
          </div>
        @endif

        @if ($ubs->middle_banner_section == 1)
          <div class="col-lg-4 col-md-12">
            @if ($banners)
              @for ($i = 0; $i < count($banners); $i++)
                @if ($banners[$i]->position == 'middle')
                  <div class="banner-sm content-top radius-lg mb-30 ratio ratio-1-2">
                    <img class=" bg-img " src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                      alt="Banner">
                    <div class="banner-content mx-auto justify-content-center px-5 mw-100">
                      <div class="content-inner">
                        <span class="sub-title">{{ $banners[$i]->title }}</span>
                        <h3 class="title">{{ $banners[$i]->subtitle }}</h3>
                        @if ($banners[$i]->button_text)
                          <a href="{{ $banners[$i]->banner_url }}"
                            class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banners[$i]->button_text }}
                            <span class="icon"><i class="fal fa-arrow-right"></i></span>
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif
              @endfor
            @endif
          </div>
        @endif

        @if ($ubs->middle_right_banner_section == 1)
          <div class="col-lg-4">
            <div class="row">
              @if ($banners)
                @php
                  $totalBanners = $banners->where('position', 'middle_right')->values();
                  $rightBanners = $totalBanners->take(2);
                @endphp
                @foreach ($rightBanners as $banner)
                  <div class="col-lg-12 col-sm-6">
                    <div class="banner-sm radius-lg mb-30 ratio ratio-16-9">
                      <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                      <div class="banner-content mw-80">
                        <div class="content-inner">
                          <span class="sub-title">{{ $banner->title }}</span>
                          <h3 class="title">{{ $banner->subtitle }}</h3>
                          @if ($banner->button_text)
                            <a href="{{ $banner->banner_url }}"
                              class="btn btn-sm btn-outline rounded-pill icon-end">{{ $banner->button_text }}
                              <span class="icon"><i class="fal fa-arrow-right"></i></span>
                            </a>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif

            </div>
          </div>
        @endif

      </div>
    </div>
  </div>
  <!-- Banner Collection End -->

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

  @if ($ubs->tab_section == 1)
    @include('user-front.kids.tab_content')
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

  @if ($ubs->video_banner_section == 1)
    <!-- Video Banner Start -->
    <section class="video-banner ptb-100">
      <!-- Background Image -->
      <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
        data-src="{{ @$userSec->video_background_image ? asset('assets/front/img/hero_slider/' . $userSec->video_background_image) : asset('assets/front/img/hero_slider/kids_default.png') }}"
        alt="Bg-img">
      <div class="border-dashed"></div>
      <div class="container">
        <div class="row">
          <div class="col-lg-5">
            <div class="content mb-30">
              <span class="sub-title">{{ $userSec->video_section_title ?? __('Video Section') }}</span>
              <h2 class="title mb-20">{{ $userSec->video_section_subtitle ?? '' }}
              </h2>

              <div class="d-flex align-items-center">
                @if (!is_null(@$userSec->video_section_button_url))
                  <a href="{{ @$userSec->video_section_button_url }}"
                    class="btn btn-lg btn-primary rounded-pill icon-end shadow">{{ $userSec->video_section_button_name ?? '' }}
                    <span class="icon"><i class="fal fa-arrow-right"></i></span>
                  </a>
                @endif
                <a href="{{ @$userSec->video_url ?? '' }}" class="btn video-btn video-btn-2 youtube-popup ms-4"><i
                    class="fas fa-play"></i>
                </a>
              </div>
            </div>
          </div>
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

  @if ($ubs->latest_product_section == 1)
    @include('user-front.kids.latest_content')
  @endif

  @if (count($after_latest_product) > 0)
    @foreach ($after_latest_product as $cusLatestProduct)
      @if (isset($additional_section_status[$cusLatestProduct->id]))
        @if ($additional_section_status[$cusLatestProduct->id] == 1)
          @php
            $cusLatestProductContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusLatestProduct->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusLatestProductContent,
              'possition' => $cusLatestProduct->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($ubs->cta_section_status == 1)
    <!-- Newsletter Start -->
    <section class="newsletter pt-70 pb-100">
      <div class="container">
        <div class="image-inner ptb-40 rounded-pill">
          <!-- Background Image -->
          <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
            data-src="{{ @$userSec->action_section_background_image ? asset('assets/front/img/cta/' . @$userSec->action_section_background_image) : asset('assets/front/img/subscriber/kids_call_default.jpg') }}"
            alt="Bg-img">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-lg-6">
              <div class="content mw-100">
                <h3 class="title-md mb-30">
                  {{ @$userSec->call_to_action_section_title ?? ($keywords['Ready to Dress Your Little One in Style?'] ?? __('Ready to Dress Your Little One in Style?')) }}
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


  {{-- Variation Modal Starts --}}
  @include('user-front.partials.variation-modal')
  {{-- Variation Modal Ends --}}

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
