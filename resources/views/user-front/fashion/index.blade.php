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
    <section class="home-slider home-slider-3">

      @if (!is_null(@$hero_slider->hero_section_background_image))
        <!-- Background Image -->
        <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/img/hero_slider/' . $hero_slider->hero_section_background_image) ?? '' }}"
          alt="Banner">
      @else
        <img class="lazyload bg-img blur-up" src="{{ asset('assets/user-front/images/fashion/banner/banner-1.jpg') }}"
          alt="Banner">
      @endif

      <div class="container">
        @if (count($hero_product_sliders) == 0)
          <h5 class="title text-center mb-20">
            {{ $keywords['NO SLIDERS FOUND'] ?? __('NO SLIDERS FOUND') }}
          </h5>
        @else
          <div class="animated-slider" id="home-slider-3"
            data-slick='{
            "dots": false,
            "speed": 500,
            "autoplaySpeed": 6000,
            "dots": true,
            "asNavFor": ".home-slider-3-thumb"
          }'>
            @foreach ($hero_product_sliders as $hero_product_slider)
              <div class="slider-item">
                <div class="slider-content">
                  <h1 class="title lc-2" data-animation="animate__fadeInDown">{{ $hero_product_slider->title }}</h1>
                  <p class="text-lg lc-4 mb-30" data-animation="animate__fadeInDown" data-delay=".1s">
                    {!! truncateString($hero_product_slider->summary, 110) !!}</p>

                  @php
                    $flash_info = flashAmountStatus($hero_product_slider->item_id, $hero_product_slider->current_price);
                    $flash_status = $flash_info['status'];
                  @endphp
                  @if ($flash_status == true)
                    @php
                      $hero_new_price = symbolPrice(
                          $user_currency->symbol_position,
                          $user_currency->symbol,
                          currency_converter($flash_info['amount']),
                      );
                      $hero_old_price = symbolPrice(
                          $user_currency->symbol_position,
                          $user_currency->symbol,
                          currency_converter($hero_product_slider->current_price),
                      );
                    @endphp
                  @else
                    @php
                      $hero_new_price = symbolPrice(
                          $user_currency->symbol_position,
                          $user_currency->symbol,
                          currency_converter($hero_product_slider->current_price),
                      );
                      $hero_old_price = symbolPrice(
                          $user_currency->symbol_position,
                          $user_currency->symbol,
                          currency_converter($hero_product_slider->previous_price),
                      );
                    @endphp
                  @endif
                  <div class="product-price d-flex align-items-center mb-30" data-animation="animate__fadeInDown"
                    data-delay=".1s">
                    <span class="h2 me-3">{{ $hero_new_price }}</span>
                    <span class="h3 color-medium text-decoration-line-through">{{ $hero_old_price }}</span>
                  </div>
                  <div class="d-flex align-items-center mb-20" data-animation="animate__fadeInUp" data-delay=".1s">
                    <a href="{{ route('front.user.productDetails', [getParam(), 'slug' => $hero_product_slider->slug]) }}"
                      class="btn btn-lg btn-primary rounded-pill me-4">{{ $keywords['View Details'] ?? __('View Details') }}</a>
                  </div>
                </div>
              </div>
            @endforeach
        @endif
      </div>
      <div class="product-thumb mb-40">
        <div class="home-slider-3-thumb" data-slick='{"asNavFor": "#home-slider-3"}'>
          @foreach ($hero_product_sliders as $hero_product_slider)
            <div class="product-default product-default-3 product-center">
              <figure class="product-img">
                <div class="ratio ratio-1-1">
                  <img class="lazyload blur-up default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $hero_product_slider->thumbnail) }}"
                    alt="Product">
                  <img class="lazyload blur-up hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $hero_product_slider->thumbnail) }}"
                    alt="Product">
                </div>
              </figure>
              <div class="product-details">
                <h3 class="product-title lc-1">{{ $hero_product_slider->title }}</h3>
                @php
                  $flash_info = flashAmountStatus($hero_product_slider->item_id, $hero_product_slider->current_price);
                  $flash_status = $flash_info['status'];

                  if ($flash_status == true) {
                      $hero_new_price = symbolPrice(
                          $user_currency->symbol_position,
                          $user_currency->symbol,
                          currency_converter($flash_info['amount']),
                      );
                  } else {
                      $hero_new_price = symbolPrice(
                          $user_currency->symbol_position,
                          $user_currency->symbol,
                          currency_converter($hero_product_slider->current_price),
                      );
                  }
                @endphp
                <div class="line"></div>
                <div class="product-price mb-0">
                  <span class="new-price color-dark">{{ $hero_new_price }}</span>
                </div>
              </div>
            </div> <!-- product-default -->
          @endforeach

        </div>
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

  @if ($ubs->middle_banner_section == 1)
    <!-- Banner Collection Start -->
    <div class="banner-collection pb-70">
      <div class="container-fluid p-0">
        <div class="row g-0">
          @if (count($banners) > 0)
            @foreach ($banners as $banner)
              @if ($loop->iteration <= 3)
                <div class="col-sm-4">
                  <a href="{{ $banner->banner_url }}">
                    <div class="banner-sm ratio ratio-16-9">
                      <img class=" bg-img " src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                      <div class="banner-content">
                        <div class="content-inner">
                          <span class="sub-title color-medium">{{ $banner->subtitle }}</span>
                          <p class="title h2">{{ $banner->title }}</p>
                          <span class="text-decoration-underline">{{ $banner->button_text }}</span>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
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
    <section class="category category-3 pb-100">
      <div class="container">
        <div class="row">

          <div class="col-12">
            <div class="section-title title-center mb-40">
              <span
                class="sub-title color-primary mb-10">{{ $userSec->category_section_title ?? ($keywords['CATEGORIES'] ?? __('CATEGORIES')) }}</span>
              <h2 class="title mb-20">{{ $userSec->category_section_subtitle ?? __('Category Subtitle') }}</h2>
              <span class="line line-2 left_right_slide_anim mx-auto"></span>
            </div>
          </div>


          <div class="col-12">
            @if (count($item_categories) == 0)
              <h5 class="title text-center mb-20">
                {{ $userSec->category_section_title ?? ($keywords['NO CATEGORIES FOUND'] ?? __('NO CATEGORIES FOUND')) }}
              </h5>
            @else
              <div class="category-slider" id="cat-slider-fashion" data-slick='{"dots": true, "slidesToShow": 6}'>

                @foreach ($item_categories as $cat)
                  <div class="category-item border mb-30 text-center">
                    <a href="{{ route('front.user.shop', [getParam(), 'category=' . $cat->slug]) }}">
                      <div class="category-img ratio ratio-1-1">
                        <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/categories/' . $cat->image) }}" alt="Category">
                      </div>
                      <h3 class="lc-1">{{ $cat->name }}</h3>
                      <div class="line mb-10"></div>
                      <span class="color-medium">{{ count($cat->items) }}+ {{ $keywords['Items'] ?? __('Items') }}
                      </span>
                    </a>
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
    @include('user-front.fashion.flash_content')
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

  @if ($ubs->tab_section == 1)
    @include('user-front.fashion.tab_content')
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
    <section class="video-banner video-banner-2 pt-100 pb-70">
      <!-- Background Image -->
      <div class="bg-overlay">
        <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ $userSec ? asset('assets/front/img/hero_slider/' . $userSec->video_background_image) : asset('assets/user-front/images/fashion/banner/video-banner.jpg') }}"
          alt="Bg-img">
      </div>
      <div class="container">
        <div class="row">
          <div class="col-lg-5">
            <div class="content mb-30">
              <h2 class="title text-white mb-10">
                {{ $userSec->video_section_title ?? __('Your Favorite Fashionable Collection Brand') }}</h2>
              <span class="line line-2 mb-40"></span>
              <a href="{{ $userSec->video_section_button_url ?? route('front.user.shop', getParam()) }}"
                class="btn btn-lg btn-primary rounded-pill">
                {{ $userSec->video_section_button_name ?? ($keywords['All Collection'] ?? __('All Collection')) }}</a>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="text-end h-100 position-relative video-btn-area">
              <a href="{{ $userSec->video_url ?? '' }}" class="btn video-btn youtube-popup p-absolute">
                <i class="fas fa-play"></i>
              </a>
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

  <!-- Featured Start -->
  @if ($ubs->featuers_section == 1)
    <section class="featured featured-2 pt-100 pb-70">
      <div class="container">
        <div class="row align-items-center gx-xl-5">
          @foreach ($how_work_steps as $how_work_step)
            <div class="col-xxl-1-5 col-xl-4 col-lg-4 col-sm-6 mb-30">
              <div class="featured-item">
                <div class="icon color-primary">
                  <i class="{{ $how_work_step->icon }}"></i>
                </div>
                <div class="content">
                  <h5>{{ $how_work_step->title }}</h5>
                  <p class="text-sm">{{ $how_work_step->text }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif
  <!-- Featured End -->

  @if (count($after_featuers) > 0)
    @foreach ($after_featuers as $cusFeatuers)
      @if (isset($additional_section_status[$cusFeatuers->id]))
        @if ($additional_section_status[$cusFeatuers->id] == 1)
          @php
            $cusFeatuersContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusFeatuers->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusFeatuersContent,
              'possition' => $cusFeatuers->possition,
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
        <button type="button" class="close_modal_btn" data-bs-dismiss="modal" aria-label="Close"><i
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
