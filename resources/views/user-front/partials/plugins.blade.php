<script>
  @if (Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}"
    switch (type) {
      case 'info':
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "timeOut": 10000,
          "extendedTimeOut": 10000,
          "positionClass": "toast-top-right",
        }
        toastr.info("{{ Session::get('message') }}");
        break;
      case 'success':
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "timeOut ": 10000,
          "extendedTimeOut": 10000,
          "positionClass": "toast-top-right",
        }
        toastr.success("{{ Session::get('message') }}");
        break;
      case 'warning':
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "timeOut ": 10000,
          "extendedTimeOut": 10000,
          "positionClass": "toast-top-right",
        }
        toastr.warning("{{ Session::get('message') }}");
        break;
      case 'error':
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "timeOut ": 10000,
          "extendedTimeOut": 10000,
          "positionClass": "toast-top-right",
        }
        toastr.error("{{ Session::get('message') }}");
        break;
    }
  @endif
</script>

@if ($userBs->is_facebook_pixel == 1 && in_array('Facebook Pixel', $packagePermissions))
  <!-- Meta Pixel Code -->
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $userBs->pixel_id }}');
    fbq('track', 'PageView');
  </script>
  @php
    $d_none = 'none';
  @endphp
  <noscript><img height="1" width="1" style="display:{{ $d_none }}"
      src="https://www.facebook.com/tr?id={{ $userBs->pixel_id }}&ev=PageView&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->
@endif


{{-- whatsapp init code --}}
@if ($userBs->is_whatsapp == 1 && in_array('WhatsApp Chat Button', $packagePermissions))
  <script type="text/javascript">
    "use strict";
    var whatsapp_popup = {{ $userBs->whatsapp_popup }};
    var whatsappImg = "{{ asset('assets/front/images/whatsapp.svg') }}";
    $(function() {
      var whatsappButtonImage = `<img data-src="${whatsappImg}" class="lazyload" />`;
      $('#WAButton').floatingWhatsApp({
        phone: "{{ $userBs->whatsapp_number }}", //WhatsApp Business phone number
        headerTitle: "{{ $userBs->whatsapp_header_title }}", //Popup Title
        popupMessage: `{!! !empty($userBs->whatsapp_popup_message) ? nl2br($userBs->whatsapp_popup_message) : '' !!}`, //Popup Message
        showPopup: whatsapp_popup == 1 ? true : false, //Enables popup display
        buttonImage: whatsappButtonImage, //Button Image
        position: "left" //Position: left | right

      });
    });
  </script>
@endif

@if ($userBs->is_tawkto == 1)
  <script type="text/javascript">
    var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();

    (function() {
      var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/{{ $userBs->tak_to_property_id }}/{{ $userBs->tak_to_widget_id }}';
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  </script>
@endif
