@extends('user-front.layout')

@section('breadcrumb_title', $pageHeading->about_page ?? __('About Us'))
@section('page-title', $pageHeading->about_page ?? __('About Us'))
@section('meta-description', !empty($seo) ? $seo->about_page_meta_keywords : '')
@section('meta-keywords', !empty($seo) ? $seo->about_page_meta_description : '')
@php
  $additional_section_status = json_decode($userBs->about_additional_section_status, true);
@endphp

@section('content')
  @if ($userBs->about_info_section == 1)
    <section class="section pt-100 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <div class="about-content mb-20">
              <h2 class="mb-20">{{ @$aboutInfo->title }}</h2>
              <p class="mb-30 about-content-desc">{{ @$aboutInfo->subtitle }}</p>
              <div class="about-list-item-area">
                @foreach ($aboutFeatures as $aboutFeature)
                  <div class="about-list-item">
                    <div class="about-list-item-img" style="--item-bg-color: #{{ $aboutFeature->color }};">
                      <span class="icon"><i class="{{ $aboutFeature->icon }}"></i></span>
                    </div>
                    <div class="content">
                      <h3>{{ $aboutFeature->title }}</h3>
                      <p class="mb-0 about-list-item-txt fw-medium">{{ $aboutFeature->subtitle }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
              <!-- btn -->
              @if (!is_null(@$aboutInfo->button_url))
                <a href="{{ @$aboutInfo->button_url }}"
                  class="btn btn-lg btn-primary radius-sm">{{ $aboutInfo->button_text }}</a>
              @endif
            </div>
          </div>
          <div class="col-lg-6">
            <div class="about-image ratio ratio-5-4 blur-up lazyload">
              <img class="ls-is-cached lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                data-src="{{ !is_null(@$aboutInfo->image) ? asset('assets/front/img/user/about/' . @$aboutInfo->image) : asset('images/about-image.png') }}"
                alt="about-image">
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if (count($after_about_info) > 0)
    @foreach ($after_about_info as $cusAboutInfo)
      @if (isset($additional_section_status[$cusAboutInfo->id]))
        @if ($additional_section_status[$cusAboutInfo->id] == 1)
          @php
            $cusAboutInfoContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusAboutInfo->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusAboutInfoContent,
              'possition' => $cusAboutInfo->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif


  @if ($userBs->about_features_section == 1)
    <!-- featured section start  -->
    <section class="featured featured-2 pt-30 pb-70">
      <div class="container">
        <div class="row align-items-center">
          @foreach ($how_work_steps as $how_work_step)
            @php
              if ($userBs->theme == 'electronics') {
                  $class_name = 'col-xxl-1-5 col-xl-4 col-lg-4 col-sm-6 mb-30';
              } elseif ($userBs->theme == 'fashion') {
                  $class_name = 'col-xxl-1-5 col-xl-4 col-lg-4 col-sm-6 mb-30';
              } else {
                  $class_name = 'col-xl-3 col-lg-4 col-sm-6 mb-30';
              }
            @endphp
            <div class="{{ $class_name }}">
              <div class="featured-item">
                <div class="icon color-primary">
                  <i class="{{ str_replace(['fas ', 'far '], 'fal ', $how_work_step->icon) }}"></i>
                </div>
                <div class="content">
                  <h4>{{ $how_work_step->title }}</h4>
                  <p class="text-sm pe-lg-4">{{ $how_work_step->text }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif
  <!-- featured section End  -->

  @if (count($after_features) > 0)
    @foreach ($after_features as $cusFeatures)
      @if (isset($additional_section_status[$cusFeatures->id]))
        @if ($additional_section_status[$cusFeatures->id] == 1)
          @php
            $cusFeaturesContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusFeatures->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusFeaturesContent,
              'possition' => $cusFeatures->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  <!-- ========= START Achievements ========= -->
  @if ($userBs->about_counter_section == 1)
    <section class="section-achievement pt-120 pb-70">
      <div class="container">
        <div class="row gx-xl-5">
          <div class="col-lg-6">
            <div class="about-image mb-30 ratio ratio-5-4 blur-up lazyload">
              <img class="ls-is-cached lazyload radius-sm" src="{{ asset('assets/front/images/placeholder.png') }}"
                data-src="{{ !is_null(@$counterSection->image) ? asset('assets/front/img/user/about/' . @$counterSection->image) : asset('images/actvment.png') }}"
                alt="about-image">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="acrivment-heading px-20 px-lg-0">
              <h2 class="mb-14">{{ @$counterSection->title }}</h2>
              <P class="mb-30">{{ @$counterSection->text }}</P>
            </div>
            <div class="achievement-grid">

              <!-- achievement-item -->
              @foreach ($counterInformations as $counterInformation)
                <div class="achievement-item-style-1">
                  <div class="achievement-body">
                    <div class="d-flex align-items-center justify-content-center ltr">
                      <h2 class="odometer mb-0 fw-bold" data-count="{{ $counterInformation->amount }}"><i
                          class="{{ $counterInformation->icon }}"></i></h2>
                      <h6 class="mb-0 fs-2 fw-bold"><i class="{{ @$counterInformation->icon }}"></i></h6>
                    </div>
                    <p class="mb-0">{{ @$counterInformation->title }}</p>
                  </div>
                </div>
              @endforeach

            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- ========= END Achievements ========= -->

  @if (count($after_counter) > 0)
    @foreach ($after_counter as $cusCounterSec)
      @if (isset($additional_section_status[$cusCounterSec->id]))
        @if ($additional_section_status[$cusCounterSec->id] == 1)
          @php
            $cusCounterSecContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusCounterSec->id],
            ])->first();
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusCounterSecContent,
              'possition' => $cusCounterSec->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($userBs->about_testimonial_section == 1)
    <!-- ========= START TESTIMONIAL ========= -->
    <section class="section-testimonial pt-120 pb-100">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-7">
            <div class="section-heading text-center mb-40 ">
              <h2 class="mb-10">{{ @$testimonial_info->testimonial_section_title }}</h2>
              <P class="mb-10">{{ @$testimonial_info->testimonial_section_subtitle }}</P>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-lg-12">
            @if (count($testimonials) > 0)
              <!-- Swiper -->
              <div class="testimonial-slider">
                @foreach ($testimonials as $testimonial)
                  <!-- testimonial-slide-item -->
                  <div class="testimonial-slide-item" style="background: #{{ @$testimonial->color }};">
                    <div
                      class="testimonial-slide-header d-flex flex-wrap justify-content-between align-items-center gap-sm-30 gap-14">
                      <div class="testimonial-author-info">
                        <div class="image">
                          <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                            data-src="{{ asset('assets/front/img/user/about/testimonial/' . @$testimonial->image) }}"
                            alt="testimonial">
                        </div>
                        <div>
                          <h6 class="small font-family-inter mb-1"><a href="#">{{ @$testimonial->name }}</a>
                          </h6>
                          <p class="mb-0 extra-small fw-medium  designation">{{ @$testimonial->designation }}</p>
                        </div>
                      </div>
                      <div class="reating">
                        <div class="rating-icons">
                          @for ($i = 1; $i <= @$testimonial->rating; $i++)
                            <i class="fas fa-star"></i>
                          @endfor
                        </div>
                      </div>
                    </div>
                    <div class="testimonial-content">
                      <img class="img-fluid quote-icon lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('images/quote.svg') }}" alt="quote">
                      <p class="small fw-medium mb-0">{{ @$testimonial->comment }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>
    <!-- ========= END TESTIMONIAL ========= -->
  @endif

  @php
    $has_testimonal = false;
  @endphp
  @if (count($after_testimonial) > 0)
    @foreach ($after_testimonial as $cusTestimonialSec)
      @if (isset($additional_section_status[$cusTestimonialSec->id]))
        @if ($additional_section_status[$cusTestimonialSec->id] == 1)
          @php
            $cusTestimonialSecContent = App\Models\User\AdditionalSectionContent::where([
                ['language_id', $uLang],
                ['addition_section_id', $cusTestimonialSec->id],
            ])->first();
            $has_testimonal = true;
          @endphp
          @includeIf('user-front.additional-section', [
              'data' => $cusTestimonialSecContent,
              'possition' => $cusTestimonialSec->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif
@endsection
