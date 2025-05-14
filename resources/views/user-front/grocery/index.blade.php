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
    <section class="home-slider home-slider-1 pt-40 pb-70 overflow-hidden header-next">
      <div class="container">
        <div class="row">

          <div class="{{ $ubs->top_right_banner_section == 1 ? 'col-xl-8' : 'col-lg-12' }}  mb-30">
            <div class="animated-slider" id="home-slider-11111"
              data-slick='{
            "dots": false,
            "speed": 500,
            "autoplaySpeed": 6000,
            "dots": true
          }'>
              @if (count($sliders) > 0)
                @foreach ($sliders->where('is_static', 0) as $slider)
                  <div class="slider-item radius-xl ratio">
                    <img class=" bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/hero_slider/' . $slider->img) }}" alt="Banner">

                    <div class="d-flex align-items-center">
                      <div class="slider-content">
                        <span class="sub-title color-primary" data-animation="animate__fadeInUp" data-delay=".3s">
                          {{ $slider->title }}
                        </span>
                        <h1 class="title lc-2" data-animation="animate__fadeInDown" data-delay=".1s">
                          {{ $slider->subtitle }}
                        </h1>
                        <p class="text-lg lc-3 text-dark" data-animation="animate__fadeInDown" data-delay=".2s">
                          {{ $slider->text }}
                        </p>
                        @if ($slider->btn_url && $slider->btn_name)
                          <a href="{{ $slider->btn_url }}" class="btn btn-lg btn-primary radius-sm"
                            data-animation="animate__fadeInUp" data-delay=".3s">
                            {{ $slider->btn_name }}
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                <div class="slider-item radius-xl ratio">
                  <img class=" bg-img blur-up" src="{{ asset('assets/user-front/images/grocery/banner-1.jpg') }}"
                    alt="Banner">
                  <div class="d-flex align-items-center">
                    <div class="slider-content">
                      <span class="sub-title color-primary" data-animation="animate__fadeInUp" data-delay=".3s">
                        {{ __('Slider Title') }}
                      </span>
                      <h1 class="title lc-2" data-animation="animate__fadeInDown" data-delay=".1s">
                        {{ __('Slider Subtitle') }}
                      </h1>
                      <p class="text-lg" data-animation="animate__fadeInDown" data-delay=".2s">
                        {{ __('Lorem ipsum dolor sit amet consectetur') }}
                      </p>
                      <a href="#" class="btn btn-lg btn-primary radius-sm" data-animation="animate__fadeInUp"
                        data-delay=".3s">
                        {{ $keywords['Explore More'] ?? __('Explore More') }}
                      </a>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>

          @if ($ubs->top_right_banner_section == 1)
            <div class="col-xl-4 col-lg-12">
              <div class="row">
                @if ($banners)
                  @for ($i = 0; $i < count($banners); $i++)
                    @if ($banners[$i]->position == 'right')
                      <div class="col-xl-12 col-lg-6">
                        <div class="banner-sm radius-xl mb-30 ratio ratio-5-3">
                          <img class=" bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                            alt="Banner">
                          <div class="banner-content">
                            <div class="content-inner">
                              <span class="sub-title lc-1">{{ $banners[$i]->title }}</span>
                              <h3 class="title-sm lc-2">{{ $banners[$i]->subtitle }}</h3>
                              @if ($banners[$i]->banner_url && $banners[$i]->button_text)
                                <a href="{{ $banners[$i]->banner_url }}"
                                  class="btn btn-md btn-primary radius-sm">{{ $banners[$i]->button_text }}</a>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endfor
                @endif
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
    <section class="category category-1 pb-100">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-inline mb-10">
              <h2 class="title mb-20">
                {{ $userSec->category_section_title ?? ($keywords['Categories'] ?? __('Categories')) }} </h2>
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
                    <a href="{{ route('front.user.shop', [getParam(), 'category' => $category->slug]) }}">
                      <div class="category-img" style="--item-bg-color: #{{ $category->color }}">
                        <img class="absolute-img lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/user/items/categories/' . $category->image) }}"
                          alt="">
                      </div>
                      <h3 class="lc-1">{{ $category->name }}</h3>
                      @php
                        $category_items_count = ProductCountByCategory($uLang, $category->id);
                      @endphp
                      <span>{{ $category_items_count }}
                        {{ $category_items_count > 1 ? $keywords['Items'] ?? __('Items') : $keywords['Item'] ?? __('Item') }}</span>
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

  @if ($ubs->middle_banner_section == 1)
    <!-- Banner Collection Start -->
    <div class="banner-collection pb-70">
      <div class="container">
        <div class="row">

          @if ($banners)
            @for ($i = 0; $i < count($banners); $i++)
              @if ($banners[$i]->position == 'middle')
                <div class="col-lg-4 col-md-6">
                  <div class="banner-sm radius-lg mb-30 ratio">
                    <img class="bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banners[$i]->banner_img) }}"
                      alt="Banner">
                    <div class="banner-content">
                      <div class="content-inner">
                        <h3 class="title lc-2">{{ $banners[$i]->title }}</h3>
                        @if ($banners[$i]->banner_url && $banners[$i]->button_text)
                          <a href="{{ $banners[$i]->banner_url }}"
                            class="btn btn-md btn-primary radius-sm">{{ $banners[$i]->button_text }}</a>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            @endfor
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

  @if ($ubs->flash_section == 1)
    @include('user-front.grocery.flash_content')
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

  @if ($ubs->featuers_section == 1)
    <!-- Featured Start -->
    <section class="featured pb-70">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-3 col-md-6">
            @php
              $count = ceil(count($how_work_steps) / 2);
            @endphp

            @for ($i = 0; $i < $count; $i++)
              <div class="featured-item mb-30">
                <div class="icon color-1">
                  <i class="{{ str_replace(['fas ', 'far '], 'fal ', $how_work_steps[$i]['icon']) }}"></i>
                </div>
                <div class="content">
                  <h4 class="lc-1">{{ $how_work_steps[$i]['title'] }}</h4>
                  <p class="lc-2">{{ $how_work_steps[$i]['text'] }}</p>
                </div>
              </div>
            @endfor
          </div>

          <div class="col-lg-6 col-md-6">
            <div class="image mb-30">
              @if (@$userSec->featured_img != null)
                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/front/img/user/feature/' . $userSec->featured_img) }}" alt="Icon">
              @endif
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            @for ($i = $count; $i < count($how_work_steps); $i++)
              <div class="featured-item mb-30">
                <div class="icon color-1">
                  <i class="{{ str_replace(['fas ', 'far '], 'fal ', $how_work_steps[$i]['icon']) }}"></i>
                </div>
                <div class="content">
                  <h4 class="lc-1">{{ $how_work_steps[$i]['title'] }}</h4>
                  <p class="lc-2">{{ $how_work_steps[$i]['text'] }}</p>
                </div>
              </div>
            @endfor
          </div>

        </div><!-- .row -->
      </div><!-- .container -->
    </section>
    <!-- Featured End -->

  @endif

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

  @if ($ubs->tab_section == 1)
    @include('user-front.grocery.tab_content')
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

  @if ($ubs->cta_section_status == 1)
    <!-- Newsletter Start -->
    <section class="newsletter">
      <!-- Background Image -->
      <div class="bg-overlay">
        <img class=" bg-img"
          data-src="{{ @$userSec->action_section_background_image ? asset('assets/front/img/cta/' . @$userSec->action_section_background_image) : asset('assets/user-front/images/grocery/video_bg.jpg') }}"
          alt="Bg-img">
      </div>
      <div class="container">
        <div class="row align-items-center justify-content-between">
          <div class="col-lg-5">
            <div class="content pt-20 mb-30">
              <h2 class="title mb-20">
                {{ $userSec->call_to_action_section_title ?? ($keywords['We Make Your Daily Life More Easy'] ?? __('We Make Your Daily Life More Easy')) }}
              </h2>
              <div class="text text-lg mb-40">
                {{ $userSec->call_to_action_section_text ?? ($keywords['Subscribe to Our Newsletter'] ?? __('Subscribe to Our Newsletter')) }}
              </div>
              @if (!is_null(@$userSec->call_to_action_section_button_url))
                <a href="{{ $userSec->call_to_action_section_button_url }}"
                  class="btn btn-md btn-primary rounded-pill">{{ $userSec->call_to_action_section_button_text }}</a>
              @endif
            </div>
          </div>
          <div class="col-lg-6">
            <div class="image mb-30">
              @if (@$userSec->action_section_side_image)
                <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ $userSec ? asset('assets/front/img/cta/' . $userSec->action_section_side_image) : '' }}"
                  alt="Image">
              @else
                <img class="lazyload blur-up" src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/user-front/images/grocery/video_side.png') }}" alt="Image">
              @endif

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


  <!-- Product List Start -->
  <section class="pt-100 pb-70">
    <div class="container">
      <div class="row justify-content-center gx-xl-5">
        @if ($ubs->bottom_left_banner_section == 1)
          <div class="col-xl-4 col-lg-8">
            @if ($banners)
              @php
                $find = false;
              @endphp
              @foreach ($banners as $banner)
                @if ($banner->position == 'bottom_left' && $find == false)
                  @php
                    $find = true;
                  @endphp
                  <div class="banner-sm banner-vertical content-top radius-xl mb-30 ratio">
                    <img class=" bg-img" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/user/banners/' . $banner->banner_img) }}" alt="Banner">
                    <div class="banner-content">
                      <div class="content-inner">
                        <h2 class="title-md">{{ $banner->title }}</h2>
                        @if ($banner->banner_url && $banner->button_text)
                          <a href="{{ $banner->banner_url }}"
                            class="btn btn-md btn-primary radius-sm">{{ $banner->button_text }}</a>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            @endif

          </div>
        @endif

        @if ($ubs->top_rated_section == 1)
          @include('user-front.grocery.rated_content')
        @endif

        @if ($ubs->top_selling_section == 1)
          @include('user-front.grocery.selling_content')
        @endif
      </div>
    </div>
  </section>
  <!-- Product List End -->

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


  <div class="mobile-menu-overlay"></div>
  <!-- Responsive Mobile Menu -->
  <div class="mobile-menu">
    <div class="mobile-menu-wrapper">
      <div class="mobile-menu-top">
        <span class="mobile-menu-close"><i class="fal fa-times"></i></span>
      </div>
    </div>
  </div>

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
            <div class="row gx-0" id="quickViewModalContent">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Quick View Modal End -->
@endsection
