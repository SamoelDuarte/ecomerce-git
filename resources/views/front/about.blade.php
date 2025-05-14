@extends('front.layout')

@section('meta-keywords', !empty($seo) ? $seo->about_meta_keywords : '')
@section('meta-description', !empty($seo) ? $seo->about_meta_description : '')
@php
  $additional_section_status = json_decode($bs->about_additional_section_status, true);
@endphp
@section('pagename')
  - {{ __('About') }}
@endsection
@section('breadcrumb-title')
  {{ __('About') }}
@endsection
@section('breadcrumb-link')
  {{ __('About') }}
@endsection

@section('content')


  @if ($bs->about_features_section_status == 1)
    <!-- Choose Start -->
    <section class="choose-area pt-120 pb-120">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5">
            <div class="choose-content mb-30" data-aos="fade-right">
              <span class="subtitle">{{ @$homeSec->features_section_title }}</span>
              <h2 class="title">{{ $homeSec->features_section_subtitle }}</h2>
              <p class="text">{{ $homeSec->features_section_text }} </p>
              <div class="d-flex align-items-center">
                <a href="{{ $homeSec->features_section_btn_url }}" class="btn primary-btn">{{ $homeSec->features_section_btn_text }}</a>
                <a href="{{ $homeSec->features_section_video_url }}" class="btn video-btn youtube-popup"><i class="fas fa-play"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-7">
            <div class="row justify-content-center">
              @foreach ($features as $feature)
                <div class="col-lg-6 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                  <div class="card mt-30 mb-sm-30">
                    <div class="card-icon primary">
                      <img class="lazyload" src="{{ asset('assets/front/img/feature/' . $feature->icon) }}" alt="">
                    </div>
                    <div class="card-content">

                      <h3 class="card-title">{{ $feature->title }}</h3>

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


  @if ($bs->about_work_process_section_status == 1)
    <!-- Store Start -->
    <section class="store-area">
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
                      <h3 class="card-title"> {{ $process->title }}</h3>
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
      <img class="bg-overlay" src="{{ asset('assets/front/images/shadow-bg-1.png') }}" alt="Bg">
      <!-- Bg Shape -->
      <div class="shape">
        <img class="shape-1" src="{{ asset('assets/front/images/shape/shape-10.png') }}" alt="Shape">
        <img class="shape-2" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
        <img class="shape-3" src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
        <img class="shape-4" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
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

  @if ($bs->about_counter_section_status == 1)
    <!-- ========= START Achievements ========= -->
    <section class="section-achievement pt-120">
      <div class="container">
        <div class="row gx-xl-5 align-items-center">
          <div class="col-lg-6">
            <div class="acrivment-image mb-20">
              <img class="lazyload" src="{{ asset('assets/front/img/counter-section/' . @$counterSection->image) }}" alt="achievement">
            </div>
          </div>
          <div class="col-lg-6">
            <div class="acrivment-heading px-20 px-lg-0">
              <h2 class="mb-14">{{ @$counterSection->title }}</h2>
              <P class="mb-30">{{ @$counterSection->text }}</P>
            </div>
            <div class="achievement-grid">
              @foreach ($counters as $counter)
                <!-- achievement-item -->
                <div class="achievement-item-style-1" style="background: #{{ $counter->color }}">
                  <div class="achievement-body">
                    <div class="d-flex align-items-center justify-content-center direction-ltr">
                      <h2 class="odometer mb-0 fw-bold" data-count="{{ $counter->amount }}">0</h2>
                      <h6 class="fs-2 fw-bold"><i class="{{ $counter->icon }}"></i></h6>
                    </div>
                    <p class="mb-0">{{ $counter->title }}</p>
                  </div>
                </div>
              @endforeach

            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ========= END Achievements ========= -->
  @endif

  @if (count($after_counter) > 0)
    @foreach ($after_counter as $cusCounter)
      @if (isset($additional_section_status[$cusCounter->id]))
        @if ($additional_section_status[$cusCounter->id] == 1)
          @php
            $cusCounterContent = App\Models\AdditionalSectionContent::where([
                ['language_id', $lang_id],
                ['addition_section_id', $cusCounter->id],
            ])->first();
          @endphp
          @includeIf('front.additional-section', [
              'data' => $cusCounterContent,
              'possition' => $cusCounter->possition,
          ]);
        @endif
      @endif
    @endforeach
  @endif

  @if ($bs->about_testimonial_section_status == 1)
    <!-- Testimonial Start -->
    <section class="testimonial-area pt-120">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title ms-0" data-aos="fade-right">
                <h2 class="title">{{ @$homeSec->testimonial_section_title }}</h2>
            </div>
          </div>
          <div class="col-12">
            <div class="row align-items-center gx-xl-5">
              <div class="col-lg-6">
                <div class="image image-left" data-aos="fade-right">
                  <img class="lazyload" src="{{ asset('assets/front/img/testimonials/' . $be->testimonial_img) }}" alt="Banner Image">
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
      <div class="lazyload shape">
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

  @if ($bs->about_blog_section_status == 1)
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
                    <img class="lazyload lazy-image"
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
      <div class="lazyload shape">
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
