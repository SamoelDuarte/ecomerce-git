<!DOCTYPE html>
<html lang="{{ $userCurrentLang->code }}" dir="{{ $userCurrentLang->rtl == 1 ? 'rtl' : '' }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

  <title>@yield('page-title') | {{ $user->username }} </title>
  <link rel="icon" href="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}">

  <meta name="description" content="@yield('meta-description')">
  <meta name="keywords" content="@yield('meta-keywords')">
  <link rel="canonical" href="{{ canonicalUrl() }}">
  @yield('og-meta')
  @includeif('user-front.styles')
  @php
    $selLang = App\Models\Language::where('code', request()->input('language'))->first();
  @endphp

  <style>
    :root {
      --color-primary: #{{ $userBs->base_color }};
      --color-primary-rgb: {{ hexToRgba($userBs->base_color) }}
    }
  </style>

  @yield('styles')

  @if ($userBs->is_analytics == 1 && in_array('Google Analytics', $packagePermissions))
    <script async src="//www.googletagmanager.com/gtag/js?id={{ $userBs->measurement_id }}"></script>
    <script>
      "use strict";
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());

      gtag('config', '{{ $userBs->measurement_id }}');
    </script>
  @endif

</head>

<body @if (request()->cookie('user-theme') == 'dark') data-background-color="dark" @endif>
  {{-- Loader --}}
  <div class="request-loader">
    <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/admin/img/loader.gif') }}" alt="">
  </div>
  {{-- Loader --}}

  <!-- Preloader Start -->
  @if ($userBs->preloader_status == 1)
    <div class="preloader">
      <div class="preloader-wrapper">
        <img class="lazyload"
        src="{{ asset('assets/front/images/placeholder.png') }}"
          data-src="{{ !is_null($userBs->preloader) ? asset('assets/front/img/user/' . $userBs->preloader) : asset('assets/user-front/images/preloader.gif') }}"
          alt="preloder-image">
      </div>
    </div>
  @endif
  <!-- Preloader End -->

  <div class="wrapper">
    {{-- top navbar area start --}}
    @if ($userBs->theme == 'electronics')
      @includeif('user-front.electronics.partials.header')
    @elseif($userBs->theme == 'vegetables')
      @includeif('user-front.grocery.partials.header')
    @elseif($userBs->theme == 'fashion')
      @includeif('user-front.fashion.partials.header')
    @elseif($userBs->theme == 'furniture')
      @includeif('user-front.furniture.partials.header')
    @elseif($userBs->theme == 'kids')
      @includeif('user-front.kids.partials.header')
    @elseif($userBs->theme == 'manti')
      @includeif('user-front.manti.partials.header')
    @endif


    @if (!request()->routeIs('front.user.detail.view'))
      @includeIf('user-front.partials.breadcrumb')
    @endif

    <div class="main-panel">
      <div class="content">
        <div class="page-inner">
          @yield('content')
        </div>
      </div>

      @if ($userBs->footer_section == 1)
        @if ($userBs->theme == 'electronics')
          @includeif('user-front.electronics.partials.footer')
        @elseif($userBs->theme == 'vegetables')
          @includeif('user-front.grocery.partials.footer')
        @elseif($userBs->theme == 'fashion')
          @includeif('user-front.fashion.partials.footer')
        @elseif($userBs->theme == 'furniture')
          @includeif('user-front.furniture.partials.footer')
        @elseif($userBs->theme == 'kids')
          @includeif('user-front.kids.partials.footer')
        @elseif($userBs->theme == 'manti')
          @includeif('user-front.manti.partials.footer')
        @endif
      @endif
    </div>
  </div>

  <div class="go-top active"><i class="fal fa-angle-double-up"></i></div>
  @if (@$userBe->cookie_alert_status == 1)
    <div class="cookie">
      @include('cookie-consent::index')
    </div>
  @endif

  @includeIf('user-front.partials.mobile-footer-menu')

  <!-- WhatsApp Chat Button -->
  <div id="WAButton"></div>

  @includeif('user-front.scripts')
  @yield('scripts')
  @includeIf('user-front.partials.plugins')
</body>

</html>
