<script>
  "use strict";
  var mainurl = "{{ route('front.user.detail.view', getParam()) }}";
  var textPosition = "{{ $userBs->base_currency_text_position }}";
  var currSymbol = "{{ currency_sign() }}";
  var currValue = "{{ currency_value() }}";

  var position = "{{ $userBs->base_currency_symbol_position }}";
  var variation_url = "{{ route('front.user.get_variation', getParam()) }}";
  var show_more = "{{ $keywords['Show More'] ?? __('Show More') }}";
  var show_less = "{{ $keywords['Show Less'] ?? __('Show Less') }}";
  var show_variations = "{{ $keywords['View Variations'] ?? __('View Variations') }}";
  var less_variations = "{{ $keywords['Less Variations'] ?? __('Less Variations') }}";
  var stock_unavailable = "{{ $keywords['stock unavailable'] ?? __('stock unavailable') }}";
  var select_a_variant = "{{ $keywords['Select A variation first'] ?? __('Select A variation first') }}";
  var success = "{{ $keywords['Success'] ?? __('Success') }}";
  var nextText = "{{ $keywords['Next'] ?? __('Next') }}";
  var previousText = "{{ $keywords['Previous'] ?? __('Previous') }}";
  var showText = "{{ $keywords['Show'] ?? __('Show') }}";
  var entriesText = "{{ $keywords['entries'] ?? __('entries') }}";
  var Search = "{{ $keywords['Search'] ?? __('Search') }}";
  var Showing = "{{ $keywords['Showing'] ?? __('Showing') }}";
  var to = "{{ $keywords['to'] ?? __('to') }}";
  var ofText = "{{ $keywords['of'] ?? __('of') }}";
  var currentTime = "{{ \Carbon\Carbon::now($userBs->timezon)->toDateTimeString() }}";
</script>


<!-- Jquery JS -->
<script src="{{ asset('assets/user-front/js/plugins.js') }}"></script>

<!-- Main script JS -->
<script src="{{ asset('assets/user-front/js/shop.js') }}"></script>
<script src="{{ asset('assets/user-front/js/script.js') }}"></script>
<script src="{{ asset('assets/user-front/js/cart.js') }}"></script>

@if (session()->has('success'))
  <script>
    "use strict";
    toastr['success']("{{ session('success') }}");
  </script>
@endif

@if (session()->has('error'))
  <script>
    "use strict";
    toastr['error']("{{ session('error') }}");
  </script>
@endif

@if (session()->has('warning'))
  <script>
    "use strict";
    toastr['warning']("{{ session('warning') }}");
  </script>
@endif
