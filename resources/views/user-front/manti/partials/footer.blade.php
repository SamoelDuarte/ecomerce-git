<!-- Footer Start -->
<footer class="footer-area">
  <!-- Background Image -->
  <img class="lazyload bg-img"
    data-src="{{ !is_null(@$footer->background_image) ? asset('assets/front/img/footer/' . @$footer->background_image) : asset('assets/user-front/images/manti/footer.jpg') }}"
    alt="Bg-img">
  <div class="footer-top pt-100">
    <div class="container">
      <div class="row gx-xl-5">
        <div class="col-xl-3 col-lg-6 col-md-6">
          <div class="footer-widget">
            <div class="navbar-brand">
              <a href="{{ route('front.user.detail.view', getParam()) }}">
                <img class=" bg-img"
                src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ !is_null(@$footer->footer_logo) ? asset('assets/front/img/footer/' . @$footer->footer_logo) : asset('assets/front/img/logo.png') }}"
                  alt="Logo">
              </a>
            </div>
            <p class="text footer_description">{{ @$footer->footer_text ?? '' }}</p>
            @if (strlen(@$footer->footer_text) > 250)
              <span class="show-more-footer">{{ $keywords['Show More'] ?? __('Show More') }} +</span>
            @endif

          </div>
        </div>
        <div class="col-xl-2 col-md-6">
          <div class="footer-widget">
     <h3>{{ $footer->useful_links_title ?? __('Useful Links') }}</h3>
            @if (count($ulinks) == 0)
              <h6 class="title mb-20">
                {{ $userSec->category_section_title ?? ($keywords['NO LINKS FOUND'] ?? __('NO LINKS FOUND')) }}
              </h6>
            @else
            <ul class="footer-links">
              @foreach ($ulinks as $link)
                @if ($loop->iteration > 5)
                @break
              @endif
              <li>
                <a href="{{ $link->url }}">{{ $link->name }}</a>
              </li>
            @endforeach
          </ul>
          @endif
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="footer-widget">
          <h3>{{ $keywords['Contact Us'] ?? __('Contact Us') }}</h3>
          @php
            $phone_numbers = !empty(@$userContact->contact_numbers) ? explode(',', @$userContact->contact_numbers) : [];
            $emails = !empty(@$userContact->contact_mails) ? explode(',', @$userContact->contact_mails) : [];
            $addresses = !empty(@$userContact->contact_addresses)
                ? explode(PHP_EOL, @$userContact->contact_addresses)
                : [];
          @endphp
          <ul class="footer-links">
            @if (count($phone_numbers) > 0)
              <li>
                <div class="footer-widget-number">
                  @foreach ($phone_numbers as $phone_number)
                    <a href="tel: {{ $phone_number }}">{{ $phone_number }}</a>{{ !$loop->last ? ', ' : '' }}
                  @endforeach
                </div>
              </li>
            @endif
            @if (count($emails) > 0)
              <li>
                <div class="footer-widget-email">
                  @foreach ($emails as $email)
                    <a href="mailto: {{ $email }}">{{ $email }}</a>{{ !$loop->last ? ', ' : '' }}
                  @endforeach
                </div>
              </li>
            @endif
            @if (count($addresses) > 0)
              <div class="footer-widget-address">
                @foreach ($addresses as $address)
                  <li>
                    {{ $address }}
                  </li>
                @endforeach
              </div>
            @endif
          </ul>
        </div>
      </div>

      <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="footer-widget">
          <h3>{{ @$footer->subscriber_title ?? ($keywords['Subscribe'] ?? __('Subscribe')) }}</h3>
          <p>{{ @$footer->subscriber_text }}</p>
          <form class="newsletter-form form-inline mb-20 w-100"
            action="{{ route('front.user.subscribe', getParam()) }}">
            @csrf
            <div class="input-group p-0 rounded-pill">
              <input class="form-control"
                placeholder="{{ $keywords['Enter_Email_Address'] ?? __('Enter Your Email') }}" type="text"
                name="email" autocomplete="off">
              <button class="btn btn-lg btn-primary rounded-pill icon-end shadow" type="submit">
                {{ $keywords['Subscribe'] ?? __('Subscribe') }} <i class="fal fa-paper-plane"></i>
              </button>
            </div>
          </form>
          <!-- social-link -->
          <div class="social-link">
            @foreach ($social_medias as $social)
              <a href="{{ $social->url }}" target="_blank">
                <i class="{{ $social->icon }}"></i>
              </a>
            @endforeach
          </div>

        </div>
      </div>
    </div>
  </div>
  @if ($ubs->copyright_section == 1)
    <div class="copy-right-area border-top">
      <div class="container">
        <div class="copy-right-content">
          <span>
            {!! replaceBaseUrl(@$footer->copyright_text ?? null) !!}
          </span>
        </div>
      </div>
    </div>
  @endif
</footer>
<!-- Footer End -->


<div class="mobile-menu-overlay"></div>
<!-- Responsive Mobile Menu -->
<div class="mobile-menu">
<div class="mobile-menu-wrapper">
  <div class="mobile-menu-top">

    <div class="logo">
      <!-- logo -->
      <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
        <img class="lazyload"
        src="{{ asset('assets/front/images/placeholder.png') }}"
        data-src="{{ asset('assets/front/img/user/' . $userBs->logo) }}"
         alt="logo">
      </a>
    </div>
    <span class="mobile-menu-close"><i class="fal fa-times"></i></span>

  </div>
</div>


<!-- menu-item-action-wrapper -->
@includeIf('user-front.partials.mobile-menu')
<!-- menu-item-action-wrapper -->

</div>
<!-- Responsive Mobile Menu -->
