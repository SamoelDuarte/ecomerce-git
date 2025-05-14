@extends('front.layout')

@section('pagename')
  - {{ __('Home') }}
@endsection
@php
  $additional_section_status = json_decode($bs->additional_section_status, true);
@endphp
@section('meta-description', !empty($seo) ? $seo->home_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->home_meta_keywords : '')

@section('content')

  @if ($bs->feature_section == 1)
    <!-- Home Start-->
    <section id="home" class="home-banner pb-120">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="content" data-aos="fade-left">
              @if (@$homeSec->hero_section_title)
                <span class="subtitle">{{ @$homeSec->hero_section_title }}
                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/images/icon-trophy.png') }}" alt="Icon">
                </span>
              @endif
              <h1 class="title">{{ @$homeSec->hero_section_text }}</h1>
              <p class="text">{{ @$homeSec->hero_section_desc }}</p>
              <div class="content-botom d-flex align-items-center">
                @if (@$homeSec->hero_section_button_url)
                  <a href="{{ @$homeSec->hero_section_button_url }}"
                    class="btn primary-btn mt-0">{{ @$homeSec->hero_section_button_text }}</a>
                @endif
                @if (@$homeSec->hero_section_video_url)
                  <a href="{{ @$homeSec->hero_section_video_url }}" class="btn video-btn youtube-popup"><i
                      class="fas fa-play"></i></a>
                @endif
              </div>
            </div>
          </div>
          <div class="col-10 col-sm-8 col-lg-6">
            <div class="banner-img image-right" data-aos="fade-right">
              <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                data-src="{{ asset('assets/front/img/' . @$homeSec->image) }}" alt="Banner Image">
            </div>
          </div>
        </div>
      </div>
      <!-- Bg Shape -->
      <div class="shape">
        <img class="lazyload shape-1" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-1.png') }}" alt="Shape">
        <img class="lazyload shape-2" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-2.png') }}" alt="Shape">
        <img class="lazyload shape-3" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
        <img class="lazyload shape-4" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
        <img class="lazyload shape-5" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-5.png') }}" alt="Shape">
        <img class="lazyload shape-6" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-6.png') }}" alt="Shape">
        <img class="lazyload shape-7" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
        <img class="lazyload shape-8" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-8.png') }}" alt="Shape">
        <img class="lazyload shape-9" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-9.png') }}" alt="Shape">
        <img class="lazyload shape-10" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
        <img class="lazyload shape-11" src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ asset('assets/front/images/shape/shape-5.png') }}" alt="Shape">
      </div>
    </section>
    <!-- Home End -->
  @endif

  @if (count($after_hero) > 0)
    @foreach ($after_hero as $cusHero)
      @if (isset($additional_section_status[$cusHero->id]))
        @if ($additional_section_status[$cusHero->id] == 1)
          @php
            $cusHeroContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusHero->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusHeroContent,
              'possition' => $cusHero->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->partners_section == 1)
    <!-- Sponsor Start  -->
    <section class="sponsor pt-120">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title text-center" data-aos="fade-up">
              <span class="subtitle">{{ @$homeSec->partner_section_title }} </span>
              <h2 class="title">{{ @$homeSec->partner_section_subtitle }} </span></h2>
            </div>
          </div>
          <div class="col-12">
            <div class="swiper sponsor-slider">
              <div class="swiper-wrapper">

                @foreach ($partners as $partner)
                  <div class="swiper-slide">
                    <div class="item-single d-flex" data-aos="fade-up" data-aos-delay="100">
                      <a href="{{ $partner->url }}" target="_blank">
                        <div class="sponsor-img">
                          <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/partners/' . $partner->image) }}" alt="Sponsor">
                        </div>
                      </a>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="swiper-pagination" data-aos="fade-up"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Sponsor End -->
  @endif

  @if (count($after_partner) > 0)
    @foreach ($after_partner as $cusPartner)
      @if (isset($additional_section_status[$cusPartner->id]))
        @if ($additional_section_status[$cusPartner->id] == 1)
          @php
            $cusPartnerContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusPartner->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusPartnerContent,
              'possition' => $cusPartner->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->process_section == 1)
    <!-- Store Start -->
    <section class="store-area pt-120">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title d-flex align-items-center mb-0 justify-content-between mw-100" data-aos="fade-up"
              data-aos-delay="100">
              <h2 class="title">{{ @$homeSec->work_process_section_title }}</h2>
            </div>
          </div>
          <div class="col-12">
            <div class="row justify-content-center">
              @foreach ($processes as $process)
                <div class="col-sm-6 col-lg-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
                  <div class="card mb-30">
                    <div class="card-icon" style="background-color: #{{ $process->color }}">
                      <i class=" {{ $process->icon }}"></i>
                    </div>
                    <div class="card-content">
                      <a href="javascript:void(0)">
                        <h3 class="card-title"> {{ $process->title }}</h3>
                      </a>
                      <p class="card-text"> {{ $process->text }}</p>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <!-- Bg Overlay -->
      <img class="bg-overlay lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
        data-src="{{ asset('assets/front/images/shadow-bg-1.png') }}" alt="Bg">
      <!-- Bg Shape -->
      <div class="shape">
        <img class="lazyload shape-1" src="{{ asset('assets/front/images/shape/shape-10.png') }}" alt="Shape">
        <img class="lazyload shape-2" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
        <img class="lazyload shape-3" src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
        <img class="lazyload shape-4" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
      </div>
    </section>
    <!-- Store End -->
  @endif

  @if (count($after_work_process) > 0)
    @foreach ($after_work_process as $cusWorkProcess)
      @if (isset($additional_section_status[$cusWorkProcess->id]))
        @if ($additional_section_status[$cusWorkProcess->id] == 1)
          @php
            $cusWorkProcessContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusWorkProcess->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusWorkProcessContent,
              'possition' => $cusWorkProcess->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->templates_section == 1)
    <!-- Template Start -->
    <section class="template-area pt-120">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title text-center" data-aos="fade-up">
              <span class="subtitle">{{ @$homeSec->template_section_title }}</span>
              <h2 class="title"> {{ @$homeSec->template_section_subtitle }}</h2>
            </div>
          </div>
          <div class="col-12">
            <div class="row justify-content-center">
              @foreach ($templates as $template)
                @php
                  $themeName = App\Models\User\BasicSetting::where('user_id', $template->id)->pluck('theme')->first();
                @endphp
                <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-delay="50">
                  <div class="card text-center mb-50">
                    <div class="card-image">
                      <a href="{{ detailsUrl($template) }}" target="_blank" class="lazy-container">
                        <img class="lazyload lazy-image" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/template-previews/' . $template->template_img) }}"
                          alt="Demo Image" />
                      </a>
                    </div>
                    <h4 class="mt-3">
                      <a href="{{ detailsUrl($template) }}" target="_blank">
                        @if ($themeName == 'vegetables')
                          {{ __('Grocery') }}
                        @elseif($themeName == 'manti')
                          {{ __('Multipurpose') }}
                        @else
                          {{ __(ucfirst($themeName)) }}
                        @endif
                        {{ __('Theme') }}
                      </a>
                    </h4>
                  </div>
                </div>
              @endforeach

            </div>
          </div>
        </div>
      </div>
      <!-- Bg Overlay -->
      <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-1.png') }}" alt="Bg">
      <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-2.png') }}" alt="Bg">
      <!-- Vector Line -->
      <img class="lazyload vector-line" src="{{ asset('assets/front/images/vector-line.png') }}" alt="Vector Line"
        data-aos="fade-in" data-aos-delay="1000">
      <!-- Bg Shape -->
      <div class="shape">
        <img class="lazyload shape-1" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
        <img class="lazyload shape-2" src="{{ asset('assets/front/images/shape/shape-10.png') }}" alt="Shape">
        <img class="lazyload shape-3" src="{{ asset('assets/front/images/shape/shape-9.png') }}" alt="Shape">
        <img class="lazyload shape-4" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
        <img class="lazyload shape-5" src="{{ asset('assets/front/images/shape/shape-10.png') }}" alt="Shape">
        <img class="lazyload shape-6" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
        <img class="lazyload shape-7" src="{{ asset('assets/front/images/shape/shape-10.png') }}" alt="Shape">
        <img class="lazyload shape-8" src="{{ asset('assets/front/images/shape/shape-9.png') }}" alt="Shape">
        <img class="lazyload shape-9" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
        <img class="lazyload shape-10" src="{{ asset('assets/front/images/shape/shape-10.png') }}" alt="Shape">
      </div>
    </section>
    <!-- Template End -->
  @endif

  @if (count($after_template) > 0)
    @foreach ($after_template as $cusTemplate)
      @if (isset($additional_section_status[$cusTemplate->id]))
        @if ($additional_section_status[$cusTemplate->id] == 1)
          @php
            $cusTemplateContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusTemplate->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusTemplateContent,
              'possition' => $cusTemplate->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->intro_section == 1)
    <!-- Choose Start -->
    <section class="choose-area pt-120 pb-90">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="choose-content mb-30" data-aos="fade-right">
              <span class="subtitle">{{ @$homeSec->features_section_title }}</span>
              <h2 class="title">{{ @$homeSec->features_section_subtitle }}</h2>
              <p class="text">{{ @$homeSec->features_section_text }} </p>
              <div class="d-flex align-items-center">
                <a href="{{ @$homeSec->features_section_btn_url }}"
                  class="btn primary-btn">{{ @$homeSec->features_section_btn_text }}</a>
                <a href="{{ @$homeSec->features_section_video_url }}" class="btn video-btn youtube-popup"><i
                    class="fas fa-play"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="row justify-content-center">
              @foreach ($features as $feature)
                <div class="col-lg-6 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                  <div class="card mt-30 mb-sm-30">
                    <div class="card-icon primary">
                      <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/front/img/feature/' . $feature->icon) }}" alt="">
                    </div>
                    <div class="card-content">
                      <a href="javascript:void(0)">
                        <h3 class="card-title">{{ $feature->title }}</h3>
                      </a>
                      <p class="card-text">{{ $feature->text }}</p>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <!-- Bg Shape -->
      <div class="shape">
        <img class="lazyload shape-1" src="{{ asset('assets/front/images/shape/shape-6.png') }}" alt="Shape">
        <img class="lazyload shape-2" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
        <img class="lazyload shape-3" src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
        <img class="lazyload shape-4" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
        <img class="lazyload shape-5" src="{{ asset('assets/front/images/shape/shape-5.png') }}" alt="Shape">
        <img class="lazyload shape-6" src="{{ asset('assets/front/images/shape/shape-11.png') }}" alt="Shape">
      </div>
    </section>
    <!-- Choose End -->
  @endif

  @if (count($after_features) > 0)
    @foreach ($after_features as $cusFeatures)
      @if (isset($additional_section_status[$cusFeatures->id]))
        @if ($additional_section_status[$cusFeatures->id] == 1)
          @php
            $cusFeaturesContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusFeatures->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusFeaturesContent,
              'possition' => $cusFeatures->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->pricing_section == 1)
    <!-- Pricing Start -->
    <section class="pricing-area pb-90">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title text-center" data-aos="fade-up">
              <span class="subtitle">{{ @$homeSec->pricing_section_title }}</span>
              <h2 class="title">{{ @$homeSec->pricing_section_subtitle }}</h2>

            </div>
          </div>
          <div class="col-12">
            @if (count($terms) > 1)
              <div class="nav-tabs-navigation text-center" data-aos="fade-up">
                <ul class="nav nav-tabs">
                  @foreach ($terms as $term)
                    <li class="nav-item">
                      <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                        data-bs-target="#{{ __("$term") }}" type="button">{{ __("$term") }}</button>
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
            <div class="tab-content">
              @foreach ($terms as $term)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} " id="{{ __("$term") }}">
                  <div class="row">
                    @php
                      $packages = \App\Models\Package::where('status', '1')
                          ->where('featured', '1')
                          ->where('term', strtolower($term))
                          ->get();
                    @endphp
                    @foreach ($packages as $package)
                      @php
                        $pFeatures = json_decode($package->features);
                      @endphp
                      <div class="col-md-6 col-lg-4">
                        <div class="card mb-30 {{ $package->recommended == '1' ? 'active' : '' }}" data-aos="fade-up"
                          data-aos-delay="100">
                          <div class="d-flex align-items-center mb-4">
                            <div class="icon primary"><i class="{{ $package->icon }}"></i></div>
                            <div class="label">
                              <h3>{{ __($package->title) }}</h3>

                              @if ($package->recommended == '1')
                                <span>{{ __('Popular') }}</span>
                              @endif
                            </div>
                          </div>
                          <div class="d-flex align-items-center">
                            <span
                              class="price">{{ $package->price != 0 && $be->base_currency_symbol_position == 'left' ? $be->base_currency_symbol : '' }}{{ $package->price == 0 ? __('Free') : $package->price }}{{ $package->price != 0 && $be->base_currency_symbol_position == 'right' ? $be->base_currency_symbol : '' }}</span>
                            <span class="period">/ {{ __("$package->term") }}</span>


                          </div>
                          <h5>{{ __("What's Included") }}</h5>
                          <div class="list-item-area mb-20">
                            <ul class="item-list list-unstyled p-0 toggle-list" data-toggle-list="amenitiesToggle"
                              data-toggle-show="7">
                              <li class=""><i class="fal fa-check"></i>{{ __('Categories Limit') . ' :' }}
                                {{ $package->categories_limit != '999999' ? $package->categories_limit : __('Unlimited') }}
                              </li>
                              <li class=""><i class="fal fa-check"></i>{{ __('Subcategories Limit') . ' :' }}
                                {{ $package->subcategories_limit != '999999' ? $package->subcategories_limit : __('Unlimited') }}
                              </li>
                              <li class=""><i class="fal fa-check"></i>{{ __('Products Limit') . ' :' }}
                                {{ $package->product_limit != '999999' ? $package->product_limit : __('Unlimited') }}
                              </li>

                              <li class=""><i class="fal fa-check"></i>{{ __('Orders Limit') . ' :' }}
                                {{ $package->order_limit != '999999' ? $package->order_limit : __('Unlimited') }}</li>

                              <li class=""><i class="fal fa-check"></i>{{ __('Additional Languages') . ' :' }}
                                {{ $package->language_limit != '999999' ? $package->language_limit : __('Unlimited') }}
                              </li>

                              @if (is_array($pFeatures) && in_array('Blog', $pFeatures))
                                <li class=""><i class="fal fa-check"></i>{{ __('Posts Limit') . ':' }}
                                  {{ $package->post_limit != '999999' ? $package->post_limit : __('Unlimited') }}</li>
                              @else
                                <li class="disabled"><i class="fal fa-times"></i>{{ __('Blog') }}
                                </li>
                              @endif

                              @if (is_array($pFeatures) && in_array('Custom Page', $pFeatures))
                                <li class=""><i class="fal fa-check"></i>{{ __('Custom Pages Limit') . ' :' }}
                                  {{ $package->number_of_custom_page != '999999' ? $package->number_of_custom_page : __('Unlimited') }}
                                </li>
                              @else
                                <li class="disabled"><i class="fal fa-times"></i>{{ __('Custom Page') }}
                                </li>
                              @endif

                              @php
                                $hideFeatures = ['Posts Limit', 'Blog', 'Custom Page'];
                              @endphp
                              @foreach ($allPfeatures as $feature)
                                @if (!in_array($feature, $hideFeatures))
                                  <li
                                    class="{{ is_array($pFeatures) && in_array($feature, $pFeatures) ? '' : 'disabled' }}">
                                    <i
                                      class="{{ is_array($pFeatures) && in_array($feature, $pFeatures) ? 'fal fa-check' : 'fal fa-times' }}"></i>{{ __("$feature") }}
                                  </li>
                                @endif
                              @endforeach
                            </ul>
                            <span class="show-more font-sm" data-toggle-btn="toggleListBtn">{{ __('Show More') }}
                              +</span>
                          </div>
                          <div class="d-flex align-items-center">
                            @if ($package->is_trial === '1' && $package->price != 0)
                              <a href="{{ route('front.register.view', ['status' => 'trial', 'id' => $package->id]) }}"
                                class="btn secondary-btn">{{ __('Trial') }}</a>
                            @endif

                            @if ($package->price == 0)
                              <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $package->id]) }}"
                                class="btn secondary-btn">{{ __('Signup') }}</a>
                            @else
                              <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $package->id]) }}"
                                class="btn primary-btn">{{ __('Purchase') }}</a>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endforeach

                  </div>
                </div>
              @endforeach

            </div>
          </div>
        </div>
      </div>
      <!-- Bg Overlay -->
      <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-2.png') }}" alt="Bg">
      <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-1.png') }}" alt="Bg">
      <!-- Bg Shape -->
      <div class="shape">
        <img class="lazyload shape-1" src="{{ asset('assets/front/images/shape/shape-6.png') }}" alt="Shape">
        <img class="lazyload shape-2" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
        <img class="lazyload shape-3" src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
        <img class="lazyload shape-4" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
        <img class="lazyload shape-5" src="{{ asset('assets/front/images/shape/shape-5.png') }}" alt="Shape">
        <img class="lazyload shape-6" src="{{ asset('assets/front/images/shape/shape-11.png') }}" alt="Shape">
      </div>
    </section>
    <!-- Pricing End -->
  @endif

  @if (count($after_pricing) > 0)
    @foreach ($after_pricing as $cusPricing)
      @if (isset($additional_section_status[$cusPricing->id]))
        @if ($additional_section_status[$cusPricing->id] == 1)
          @php
            $cusPricingContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusPricing->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusPricingContent,
              'possition' => $cusPricing->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->featured_users_section == 1)
    <!-- User Profile Start -->
    <section class="user-profile-area pb-120">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title text-center" data-aos="fade-up">
              @if (!empty(@$homeSec->featured_shop_section_title))
                <span class="subtitle">{{ @$homeSec->featured_shop_section_title }}</span>
              @endif
              @if (!empty(@$homeSec->featured_shop_section_subtitle))
                <h2 class="title">{{ @$homeSec->featured_shop_section_subtitle }}</h2>
              @endif

            </div>
          </div>
          <div class="col-12">
            <div class="swiper user-slider">
              <div class="swiper-wrapper">
                @foreach ($featured_users as $featured_user)
                  <div class="swiper-slide">
                    <div class="card" data-aos="fade-up" data-aos-delay="100">
                      <div class="icon">
                        <img class="lazyload rounded-circle" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ isset($featured_user->photo) ? asset('assets/front/img/user/' . $featured_user->photo) : asset('assets/user/img/profile.png') }}"
                          alt="user">
                      </div>
                      <div class="card-content primary">
                        <h3 class="card-title mb-3">
                          {{ $featured_user->first_name . ' ' . $featured_user->last_name }}
                        </h3>
                        <div class="social-link">
                          <div class="social-link">
                            @foreach ($featured_user->social_media as $social)
                              <a href="{{ $social->url }}" target="_blank"><i class="{{ $social->icon }}"></i></a>
                            @endforeach
                          </div>

                        </div>

                        <div class="cta-btns">
                          @php
                            if (!empty($featured_user)) {
                                $currentPackage = App\Http\Helpers\UserPermissionHelper::userPackage(
                                    $featured_user->id,
                                );
                                $preferences = App\Models\User\UserPermission::where([
                                    ['user_id', $featured_user->id],
                                    ['package_id', $currentPackage->package_id],
                                ])->first();
                                $permissions = isset($preferences) ? json_decode($preferences->permissions, true) : [];
                            }
                          @endphp
                          <a href="{{ detailsUrl($featured_user) }}" class="btn btn-sm primary-btn"
                            target="_blank">{{ __('View Shop') }}</a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="swiper-pagination" data-aos="fade-up"></div>
            </div>
          </div>
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
    <!-- User Profile End -->
  @endif

  @if (count($after_featured_shop) > 0)
    @foreach ($after_featured_shop as $cusFeatureShop)
      @if (isset($additional_section_status[$cusFeatureShop->id]))
        @if ($additional_section_status[$cusFeatureShop->id] == 1)
          @php
            $cusFeatureShopContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusFeatureShop->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusFeatureShopContent,
              'possition' => $cusFeatureShop->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->testimonial_section == 1)
    <!-- Testimonial Start -->
    <section class="testimonial-area">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title ms-0" data-aos="fade-right">
              @if (!empty(@$homeSec->testimonial_section_title))
                <h2 class="title">{{ @$homeSec->testimonial_section_title }}</h2>
              @endif
            </div>
          </div>
          <div class="col-12">
            <div class="row align-items-center gx-xl-5">
              <div class="col-lg-6">
                <div class="image image-left" data-aos="fade-right">
                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/front/img/testimonials/' . $be->testimonial_img) }}"
                    alt="Banner Image">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="swiper testimonial-slider" data-aos="fade-left">
                  <div class="swiper-wrapper">

                    @for ($i = 0; $i <= count($testimonials); $i = $i + 2)
                      @if ($i < count($testimonials) - 1)
                        <div class="swiper-slide">

                          <div class="slider-item">
                            <div class="quote">
                              <span class="icon"><i class="fas fa-quote-right"></i></span>
                              <p class="text">
                                {{ $testimonials[$i]->comment }}
                              </p>
                            </div>
                            <div class="client">
                              <div class="image">
                                <div class="lazy-container aspect-ratio-1-1">
                                  <img class="lazyload lazy-image"
                                    src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ $testimonials[$i]->image ? asset('assets/front/img/testimonials/' . $testimonials[$i]->image) : asset('assets/front/img/thumb-1.jpg') }}"
                                    alt="Person Image">
                                </div>
                              </div>
                              <div class="content">
                                <h6 class="name">{{ $testimonials[$i]->name }}</h6>
                                <span class="designation">{{ $testimonials[$i]->designation }}</span>
                              </div>
                            </div>
                          </div>

                          <div class="slider-item">
                            <div class="quote">
                              <span class="icon"><i class="fas fa-quote-right"></i></span>
                              <p class="text">
                                {{ $testimonials[$i + 1]->comment }}
                              </p>
                            </div>
                            <div class="client">
                              <div class="image">
                                <div class="lazy-container aspect-ratio-1-1">
                                  <img class="lazyload lazy-image"
                                    src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ $testimonials[$i + 1]->image ? asset('assets/front/img/testimonials/' . $testimonials[$i + 1]->image) : asset('assets/front/img/thumb-1.jpg') }}"
                                    alt="Person Image">
                                </div>
                              </div>
                              <div class="content">
                                <h6 class="name">{{ $testimonials[$i + 1]->name }}</h6>
                                <span class="designation">{{ $testimonials[$i + 1]->designation }}</span>
                              </div>
                            </div>
                          </div>

                        </div>
                      @endif
                    @endfor

                  </div>
                  <div class="swiper-pagination"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Bg Overlay -->
      <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-1.png') }}" alt="Bg">
      <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-2.png') }}" alt="Bg">
      <!-- Bg Shape -->
      <div class="shape">
        <img class="lazyload shape-1" src="{{ asset('assets/front/images/shape/shape-8.png') }}" alt="Shape">
        <img class="lazyload shape-2" src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
        <img class="lazyload shape-3" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
        <img class="lazyload shape-4" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
        <img class="lazyload shape-5" src="{{ asset('assets/front/images/shape/shape-6.png') }}" alt="Shape">
        <img class="lazyload shape-6" src="{{ asset('assets/front/images/shape/shape-10.png') }}" alt="Shape">
      </div>
    </section>
    <!-- Testimonial End -->
  @endif

  @if (count($after_testimonial) > 0)
    @foreach ($after_testimonial as $cusTestimonial)
      @if (isset($additional_section_status[$cusTestimonial->id]))
        @if ($additional_section_status[$cusTestimonial->id] == 1)
          @php
            $cusTestimonialContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusTestimonial->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusTestimonialContent,
              'possition' => $cusTestimonial->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->blog_section == 1)
    <!-- Blog Start -->
    <section class="blog-area ptb-90">
      <div class="container">
        <div class="section-title text-center" data-aos="fade-up">
          @if (!empty(@$homeSec->blog_section_title))
            <h2 class="title">{{ @$homeSec->blog_section_title }}</h2>
          @endif
        </div>
        <div class="row justify-content-center">

          @foreach ($blogs as $blog)
            <div class="col-md-6 col-lg-4">
              <article class="card mb-30" data-aos="fade-up" data-aos-delay="100">
                <div class="card-image">
                  <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}"
                    class="lazy-container aspect-ratio-16-9">
                    <img class="lazyload lazy-image" src="{{ asset('assets/front/images/placeholder.png') }}"
                      data-src="{{ asset('assets/front/img/blogs/' . $blog->main_image) }}" alt="Banner Image">
                  </a>
                  <ul class="info-list">
                    <li><i class="fal fa-user"></i>{{ __('Admin') }}</li>
                    <li><i class="fal fa-calendar"></i>{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}
                    </li>
                    <li><a href="{{ route('front.blogs', ['category' => $blog->bcategory_id]) }}"><i
                          class="fal fa-tag"></i>{{ $blog->bcategory->name }}</a></li>
                  </ul>
                </div>
                <div class="content">
                  <h3 class="card-title">
                    <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}">
                      {{ $blog->title }}
                    </a>
                  </h3>
                  <p class="card-text">
                    {!! substr(strip_tags($blog->content), 0, 150) !!}

                  </p>
                  <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}"
                    class="card-btn">{{ __('Read More') }}</a>
                </div>
              </article>
            </div>
          @endforeach
        </div>
      </div>
      <!-- Bg Overlay -->
      <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-2.png') }}" alt="Bg">
      <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-1.png') }}" alt="Bg">
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
    <!-- Blog End -->
  @endif

  @if (count($after_blog) > 0)
    @foreach ($after_blog as $cusBlog)
      @if (isset($additional_section_status[$cusBlog->id]))
        @if ($additional_section_status[$cusBlog->id] == 1)
          @php
            $cusBlogContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusBlog->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusBlogContent,
              'possition' => $cusBlog->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

@endsection
