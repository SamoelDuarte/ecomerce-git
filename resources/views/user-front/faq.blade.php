@extends('user-front.layout')

@section('breadcrumb_title', $pageHeading->faq_page ?? __('FAQs'))
@section('page-title', $pageHeading->faq_page ?? __('FAQs'))
@section('meta-description', !empty($seo) ? $seo->faqs_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->faqs_meta_keywords : '')

@section('content')

  <!--====== Start faqs-section ======-->
  <div id="faq" class="faq-area pt-120 pb-90">
    <div class="container">
      <div class="accordion ptb-100" id="faqAccordion">
        <div class="row justify-content-center">
          <div class="col-lg-8 has-time-line" data-aos="fade-right">
            @if (count($faqs) > 0)
              <div class="row">
                @foreach ($faqs as $key => $faq)
                  @if ($key == 0)
                    <div class="col-12">
                      <div class="accordion-item">
                        <h6 class="accordion-header" id="heading{{ $key }}">
                          <button class="accordion-button {{ $key === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $key }}" aria-expanded="{{ $key === 0 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $key }}">
                            {{ $faq->question }}
                          </button>
                        </h6>
                        <div id="collapse{{ $key }}" class="accordion-collapse collapse {{ $key === 0 ? 'show' : '' }}"
                          aria-labelledby="heading{{ $key }}" data-bs-parent="#faqAccordion">
                          <div class="accordion-body">
                            <p>{{ $faq->answer }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    <div class="col-12">
                      <div class="accordion-item">
                        <h6 class="accordion-header" id="heading{{ $key }}">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $key }}" aria-expanded="false"
                            aria-controls="collapse{{ $key }}">
                            {{ $faq->question }}
                          </button>
                        </h6>
                        <div id="collapse{{ $key }}" class="accordion-collapse collapse"
                          aria-labelledby="heading{{ $key }}" data-bs-parent="#faqAccordion">
                          <div class="accordion-body">
                            <p>{{ $faq->answer }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach

              </div>
            @else
              <div class="ptb-100 text-center">
                <h3 class="ptb-100">
                  {{ $keywords['No Faq Found'] ?? __('No Faq Found') }}
                </h3>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End faqs-section ======-->




  <div class="mobile-menu-overlay"></div>
  <!-- Responsive Mobile Menu -->
  <div class="mobile-menu">
    <div class="mobile-menu-wrapper">
      <div class="mobile-menu-top">

        <div class="logo">
          <!-- logo -->
          <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
            <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/front/img/user/' . $userBs->logo) }}" alt="logo">
          </a>
        </div>
        <span class="mobile-menu-close"><i class="fal fa-times"></i></span>

      </div>
    </div>
  </div>
  <!-- Responsive Mobile Menu -->

@endsection
