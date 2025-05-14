<!-- Footer Area -->
<footer class="footer-area aaaaaa">
  <div class="footer-top pt-120 pb-90">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-12">
          <div class="footer-widget" data-aos="fade-up" data-aos-delay="100">
            <div class="navbar-brand">
              <a href="{{ route('front.index') }}">
                <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/front/img/' . $bs->footer_logo) }}" alt="Logo">
              </a>
            </div>
            <p>{{ $bs->footer_text }}</p>
            <div class="social-link">


            </div>
          </div>
        </div>
        <div class="col-lg-1 col-md-1"></div>
        <div class="col-lg-2 col-md-3 col-sm-6">
          <div class="footer-widget" data-aos="fade-up" data-aos-delay="200">
            @php
              $ulinks = App\Models\Ulink::where('language_id', $currentLang->id)
                  ->orderby('id', 'desc')
                  ->get();
            @endphp
            <h3>{{ $bs->useful_links_title }}</h3>
            <ul class="footer-links">
              @foreach ($ulinks as $ulink)
                <li><a href="{{ $ulink->url }}">{{ $ulink->name }}</a></li>
              @endforeach

            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <div class="footer-widget" data-aos="fade-up" data-aos-delay="400">
            <h3> {{ $bs->contact_info_title }}</h3>

            <ul class="info-list">
              <li>
                <i class="fal fa-map-marker-alt"></i>
                <span>{{ $be->contact_addresses }}</span>
              </li>
              <li>
                <i class="fal fa-phone"></i>
                <a href="tel:00123456789">{{ $be->contact_numbers }}</a>
              </li>
              <li>
                <i class="fal fa-envelope"></i>
                <a href="mailto:info@example.com">{{ $be->contact_mails }}</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6">
          <div class="footer-widget" data-aos="fade-up" data-aos-delay="500">
            <h3>{{ $bs->newsletter_title }}</h3>
            <p>{{ $bs->newsletter_subtitle }}</p>
            <form id="footerSubscriber" action="{{ route('front.subscribe') }}" method="POST">
              @csrf
              <div class="input-group">
                <input class="form-control" placeholder="{{ __('Enter Your Email') }}" name="email"
                  autocomplete="off">
                <button class="btn btn-sm primary-btn" type="submit">{{ __('Subscribe') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if ($bs->copyright_section == 1)
    <div class="copy-right-area">
      <div class="container">
        <div class="copy-right-content">
          @if ($bs->copyright_section == 1)
            <span>
              {!! $bs->copyright_text !!}
            </span>
          @endif
        </div>
      </div>
    </div>
  @endif
</footer>
<!-- Footer Area -->
