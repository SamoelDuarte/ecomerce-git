<!DOCTYPE html>
<html lang="en" @if ($rtl == 1) dir="rtl" @endif>

<head>
  <!--====== Required meta tags ======-->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="@yield('meta-description')">
  <meta name="keywords" content="@yield('meta-keywords')">


  @yield('og-meta')

  <!-- Title -->
  <title>{{ $bs->website_title }} @yield('pagename')</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('assets/front/img/' . $bs->favicon) }}" type="image/x-icon">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/bootstrap.min.css') }}">
  <!-- Fontawesome Icon CSS -->
  <link rel="stylesheet" href="{{ asset('assets/front/fonts/fontawesome/css/all.min.css') }}">
  <!-- Kreativ Icon -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/font-gigo.css') }}">
  <!-- Magnific Popup CSS -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/magnific-popup.min.css') }}">
  <!-- Swiper Slider -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/swiper-bundle.min.css') }}">
  <!-- AOS Animation CSS -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/aos.min.css') }}">
  <!-- toastr css -->
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/common/toastr.min.css') }}">
  <!-- Meanmenu CSS -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/meanmenu.min.css') }}">
  <!-- Nice Select -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/nice-select.css') }}">
  <!-- CountDown -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/odometer.min.css') }}">
  {{-- floating-whatsapp --}}
  <link rel="stylesheet" href="{{ asset('assets/front/css/floating-whatsapp.css') }}">
  <!-- Main Style CSS -->
  <link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/user-front/css/tinymce-content.css') }}">

  <meta name="csrf-token" content="{{ csrf_token() }}" />

  @yield('styles')

  @if ($bs->is_whatsapp == 0 && $bs->is_tawkto == 0)
    <style>
      .back-to-top {
        left: auto;
        right: 30px;
      }
    </style>
  @endif


  @php
    $primaryColor = $bs->base_color;
    function checkColorCode($color)
    {
        return preg_match('/^#[a-f0-9]{6}/i', $color);
    }

    // if, primary color value does not contain '#', then add '#' before color value
    if (isset($primaryColor) && checkColorCode($primaryColor) == 0) {
        $primaryColor = '#' . $primaryColor;
    }

    // change decimal point into hex value for opacity
    if (!function_exists('rgb')) {
        function rgb($color = null)
        {
            if (!$color) {
                echo '';
            }

            $hex = htmlspecialchars($color);
            [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');
            echo "$r, $g, $b";
        }
    }

  @endphp
  <style>
    :root {
      --color-primary: #{{ $bs->base_color }};
      --color-primary2: #{{ $bs->base_color_2 }};
      --color-primary-rgb: {{ rgb(htmlspecialchars($primaryColor)) }};
    }
  </style>

    <script>
    if (top.location != location) {
        top.location.replace(location);
    }
    </script>
    <!-- ===/External Code=== -->  
</head>

<body>

  @if ($bs->preloader_status == 1)
    <!--====== Start Preloader ======-->

    <!--====== Start Preloader ======-->
    <!--<div class="preloader" id="preLoader">
      <div class="lds-ellipsis loader">
        <img class="lazy" data-src="{{ asset('assets/front/img/' . $bs->preloader) }}" alt="">
      </div>
    </div>--> <!--====== End Preloader ======-->
    <!--====== End Preloader ======-->
  @endif

  @includeIf('front.partials.header')

  @if (!request()->routeIs('front.index'))
    <!-- Page Title Start-->
    <div class="page-title-area" style="background-image:url('{{ asset('assets/front/img/' . $bs->breadcrumb) }} ')">
      <div class="container">
        <div class="row">
          <div class="col-lg-10">
            <div class="content">
              <h2>@yield('breadcrumb-title')</h1>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb justify-content-start">
                    <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb-link') </li>
                  </ol>
                </nav>
            </div>
          </div>

        </div>

      </div>
    </div>
    <!-- Page Title End-->
  @endif

  @yield('content')

  {{-- footer section --}}
  @includeIf('front.partials.footer')
  @if ($be->cookie_alert_status == 1)
    <div class="cookie">
      @include('cookie-consent::index')
    </div>
  @endif

  {{-- Popups start --}}
  @includeIf('front.partials.popups')
  {{-- Popups end --}}

  {{-- WhatsApp Chat Button --}}
  <div id="WAButton"></div>

  <!-- Go to Top -->
  <div class="go-top"><i class="fal fa-angle-up"></i></div>
  <!-- Go to Top -->

  <!-- Jquery JS -->
  <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
  <!-- Popper JS -->
  <script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
  <!-- Bootstrap JS -->
  <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
  <!-- Nice Select JS -->
  <script src="{{ asset('assets/front/js/jquery.nice-select.min.js') }}"></script>
  <!-- Magnific Popup JS -->
  <script src="{{ asset('assets/front/js/jquery.magnific-popup.min.js') }}"></script>
  {{-- lazyload js --}}
  <script src="{{ asset('assets/front/js/vanilla-lazyload.min.js') }}"></script>
  {{-- syotimer --}}
  <script src="{{ asset('assets/front/js/jquery-syotimer.min.js') }}"></script>
  <!-- Swiper Slider JS -->
  <script src="{{ asset('assets/front/js/swiper-bundle.min.js') }}"></script>
  <!-- Lazysizes -->
  <script src="{{ asset('assets/front/js/lazysizes.min.js') }}"></script>
  <script src="{{ asset('assets/user-front/js/toastr.min.js') }}"></script>
  <!-- Meanmenu JS -->
  <script src="{{ asset('assets/front/js/jquery.meanmenu.min.js') }}"></script>
  {{-- floatingWhatsApp --}}
  <script src="{{ asset('assets/front/js/floating-whatsapp.js') }}"></script>
  <!-- AOS JS -->
  <script src="{{ asset('assets/front/js/aos.min.js') }}"></script>

  <!-- CountDown -->
  <script src="{{ asset('assets/front/js/appear.min.js') }}"></script>
  <script src="{{ asset('assets/front/js/odometer.min.js') }}"></script>

  <script>
    "use strict";
    var rtl = {{ $rtl }};
    var mainurl = "{{ url('/') }}";
    var vapid_public_key = "{{ env('VAPID_PUBLIC_KEY') }}";
    var show_more = "{{ __('Show More') }}";
    var show_less = "{{ __('Show Less') }}";
  </script>

  <!-- Main script JS -->
  <script src="{{ asset('assets/front/js/script.js') }}"></script>

  {{-- push notification js --}}
  <script src="{{ asset('assets/front/js/push-notification.js') }}"></script>

  @yield('scripts')

  @yield('vuescripts')


  @if (session()->has('success'))
    <script>
      "use strict";
      toastr['success']("{{ __(session('success')) }}");
    </script>
  @endif

  @if (session()->has('error'))
    <script>
      "use strict";
      toastr['error']("{{ __(session('error')) }}");
    </script>
  @endif

  @if (session()->has('warning'))
    <script>
      "use strict";
      toastr['warning']("{{ __(session('warning')) }}");
    </script>
  @endif
  <script>
    "use strict";

    function handleSelect(elm) {
      window.location.href = "{{ route('changeLanguage', '') }}" + "/" + elm.value;
    }
  </script>

  {{-- whatsapp init code --}}
  @if ($bs->is_whatsapp == 1)
    <script type="text/javascript">
      "use strict";
      var whatsapp_popup = {{ $bs->whatsapp_popup }};
      var whatsapp_number = "{{ $bs->whatsapp_number }}"
      var whatsapp_header_title = "{{ $bs->whatsapp_header_title }}";
      var popup_message = `{!! $bs->whatsapp_popup_message !!}`;
      var whatsappImg = "{{ asset('assets/front/images/whatsapp.svg') }}";
      $(function() {
        $('#WAButton').floatingWhatsApp({
          phone: whatsapp_number, //WhatsApp Business phone number
          headerTitle: whatsapp_header_title, //Popup Title
          popupMessage: popup_message, //Popup Message
          showPopup: whatsapp_popup == 1 ? true : false, //Enables popup display
          buttonImage: '<img src="' + whatsappImg + '" />', //Button Image
          position: "right" //Position: left | right

        });
      });
    </script>
  @endif

  @if ($bs->is_tawkto == 1)
    <script type="text/javascript">
      var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();

      (function() {
        var s1 = document.createElement("script"),
          s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/{{ $bs->tak_to_property_id }}/{{ $bs->tak_to_widget_id }}';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
      })();
    </script>
  @endif

</body>

</html>
