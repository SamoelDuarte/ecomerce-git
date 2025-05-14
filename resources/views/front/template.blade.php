@extends('front.layout')

@section('pagename')
  - {{ $pageHeading ?? __('Templates') }}
@endsection
@php
  $additional_section_status = json_decode($bs->additional_section_status, true);
@endphp
@section('meta-description', !empty($seo) ? $seo->home_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->home_meta_keywords : '')
@section('breadcrumb-title')
  {{ $pageHeading ?? __('Templates') }}
@endsection
@section('breadcrumb-link')
  {{ $pageHeading ?? __('Templates') }}
@endsection

@section('content')
  <section class="template-area">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="section-title text-center" data-aos="fade-up">
            <span class="subtitle">{{ $bs->preview_templates_title }}</span>
            <h2 class="title"> {{ $bs->preview_templates_subtitle }}</h2>
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
@endsection
