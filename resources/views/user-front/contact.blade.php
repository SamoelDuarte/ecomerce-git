@extends('user-front.layout')

@section('meta-description', !empty($seo) ? $seo->contact_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->contact_meta_keywords : '')
@section('breadcrumb_title', $pageHeading->contact_page ?? __('Contact'))
@section('page-title', $pageHeading->contact_page ?? __('Contact'))

@section('content')


  <!--====== Start contacts-section ======-->
  <div class="contact-area ptb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12">
          <div class="row justify-content-center mb-20">
            @if (!empty($contact->contact_numbers))
              @php
                if ($contact) {
                    $phones = explode(',', $contact->contact_numbers);
                }
              @endphp
              @if (!empty($phones))
                <div class="col-xl-4 col-lg-6">
                  <div class="card mb-30 blue" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon">
                      <i class="fal fa-phone-plus"></i>
                    </div>
                    <div class="card-text">
                      @foreach ($phones as $phone)
                        <p class="mb-0"><a href="tel:{{ $phone }}">{{ $phone }}</a></p>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endif
            @endif

            @if (!empty($contact->contact_mails))
              @php
                if (!empty($contact)) {
                    $mails = explode(',', $contact->contact_mails);
                }
              @endphp
              @if (!empty($mails))
                <div class="col-xl-4 col-lg-6">
                  <div class="card mb-30 green" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon">
                      <i class="fal fa-envelope"></i>
                    </div>
                    <div class="card-text">
                      @foreach ($mails as $mail)
                        <p class="mb-0"><a href="mailTo:{{ $mail }}">{{ $mail }}</a></p>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endif
            @endif


            @if (!empty($contact->contact_addresses))
              @php
                if ($contact) {
                    $addresses = explode(PHP_EOL, $contact->contact_addresses);
                }
              @endphp
              @if (!empty($addresses))
                <div class="col-xl-4 col-lg-6">
                  <div class="card mb-30 orange" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon">
                      <i class="fal fa-map-marker-alt"></i>
                    </div>
                    <div class="card-text">
                      @foreach ($addresses as $address)
                        <p class="mb-0"><a href="">{{ $address }}</a></p>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endif
            @endif
          </div>
          <div class="row">
            <div class="col-lg-6 mb-30" data-aos="fade-up" data-aos-delay="100">
              @if (Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
              @endif
              <form id="contactForm" action="{{ route('front.user.contact.send_message', getParam()) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <input type="text"
                        placeholder="{{ $keywords['Enter your name'] ?? __('Enter your name') . ' *' }}"
                        class="form-control" name="name" value="{{ old('name') }}" >
                      @error('name')
                        <p class="text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <input type="email" name="email" class="form-control" id="email"
                        placeholder="{{ $keywords['Enter your email'] ?? __('Enter your email') . ' *' }}" />
                      @if ($errors->has('email'))
                        <p class="help-block with-errors text-danger">{{ $errors->first('email') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group mb-30">
                      <input type="text" name="subject" class="form-control"  data-error="Enter Subject"
                        placeholder="{{ $keywords['Enter Subject'] ?? __('Enter Subject') . ' *' }}" />
                      @if ($errors->has('subject'))
                        <p class="help-block with-errors text-danger">{{ $errors->first('subject') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group mb-30">
                      <textarea name="message" id="message" class="form-control" cols="30" rows="8"
                        data-error="Please enter your message"
                        placeholder="{{ $keywords['Enter Your Message'] ?? __('Enter Your Message') . '...' }}"></textarea>
                      @if ($errors->has('message'))
                        <div class="help-block with-errors">{{ $errors->first('message') }}</div>
                      @endif
                    </div>
                  </div>
                  @if ($userBs->is_recaptcha == 1 && in_array('Google Recaptcha', $packagePermissions))
                    <div class="col-md-12">
                      <div class="form-group mb-30">
                        <div class="d-block mb-4">
                          {!! NoCaptcha::renderJs() !!}
                          {!! NoCaptcha::display() !!}
                          @if ($errors->has('g-recaptcha-response'))
                            @php
                              $errmsg = $errors->first('g-recaptcha-response');
                            @endphp
                            <p class="text-danger mb-0 mt-2">{{ __("$errmsg") }}</p>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endif

                  <div class="col-md-12">
                    <button type="submit" class="btn btn-lg btn-primary radius-md"
                      title="Send message">{{ $keywords['Send Message'] ?? __('Send Message') }}</button>
                    <div id="msgSubmit"></div>
                  </div>
                </div>
              </form>
            </div>
            @if (!empty($contact->latitude) && !empty($contact->longitude))
              <div class="col-lg-6 mb-30" data-aos="fade-up" data-aos-delay="200">
                <iframe width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                  src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ intval($contact->latitude) }},%20{{ intval($contact->longitude) }}+({{ $userBs->website_title }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
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
  </div>
  <!--====== End contacts-section ======-->


  <div class="mobile-menu-overlay"></div>
  <!-- Responsive Mobile Menu -->
  <div class="mobile-menu">
    <div class="mobile-menu-wrapper">
      <div class="mobile-menu-top">

        <div class="logo">
          <!-- logo -->
          <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
            <img src="{{ asset('assets/front/img/user/' . $userBs->logo) }}" alt="logo">
          </a>
        </div>
        <span class="mobile-menu-close"><i class="fal fa-times"></i></span>

      </div>
    </div>
  </div>
  <!-- Responsive Mobile Menu -->

@endsection
