@extends('front.layout')

@section('pagename')
  - {{ $pageHeading ?? __('FAQs') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->faqs_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->faqs_meta_keywords : '')

@section('breadcrumb-title')
{{ $pageHeading ?? __('FAQs') }}
@endsection
@section('breadcrumb-link')
{{ $pageHeading ?? __('FAQs') }}
@endsection

@section('content')

  <!--====== Start faqs-section ======-->



  <div id="faq" class="faq-area pt-90 pb-90">
    <div class="container">
      <div class="accordion" id="faqAccordion">
        @if (count($faqs) == 0)
          <div class="bg-light text-center py-5 d-block w-100">
            <h3>{{ __('NO FAQ FOUND') }}</h3>
          </div>
        @else
          <div class="row justify-content-center">
            <div class="col-lg-8 has-time-line" data-aos="fade-right">
              <div class="row">
                @foreach ($faqs as $key => $faq)
                  @if ($key == 0)
                    <div class="col-12">
                      <div class="accordion-item">
                        <h6 class="accordion-header" id="heading{{ $key }}">
                          <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $key }}" aria-expanded="true"
                            aria-controls="collapse{{ $key }}">
                            {{ $faq->question }}
                          </button>
                        </h6>
                        <div id="collapse{{ $key }}" class="accordion-collapse collapse show"
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
                          <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $key }}" aria-expanded="false"
                            aria-controls="collapse{{ $key }}">
                            {{ $faq->question }}
                          </button>
                        </h6>
                        <div id="collapse{{ $key }}" class="accordion-collapse collapse "
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
            </div>

          </div>
        @endif
      </div>
    </div>
  </div>
  <!--====== End faqs-section ======-->
@endsection
