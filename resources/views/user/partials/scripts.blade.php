<script>
  "use strict";
  var mainurl = "{{ url('/') }}";
  var storeUrl = "";
  var removeUrl = "";
  var rmvdbUrl = "";
  var are_you_sure = "{{ __('Are you sure ?') }}";
  var wont_revert_text = "{!! __('You won\'t be able to revert this!') !!}";

  var yes_delete_it = "{{ __('Yes, delete it') }}";
  var cancel = "{{ __('Cancel') }}";
  var yes = "{{ __('Yes') }}";
  var default_currency_msg =
    "{{ __('Important: Changing your default currency will affect the pricing of your products. You may need to adjust or reset your product prices to reflect the new currency settings') }}";
  var demo_mode = "{{ env('DEMO_MODE') }}";
  var shopSetting = "{{ $shopSetting->time_format }}";
  var processing_text = "{{ __('Processing') }}...";
  var WarningText = "{{ __('Warning') }}";
  var downgradText = "{{ __('Your feature limit is over or down graded!') }}";
  var reco1920_300 = "{{ __('Recommended Image size : 1920X300') }}";
  var reco400_260 = "{{ __('Recommended Image size : 400X260') }}";
  var reco680_670 = "{{ __('Recommended Image size : 680X670') }}";
  var reco870_590 = "{{ __('Recommended Image size : 870X590') }}";
  var reco700_375 = "{{ __('Recommended Image size : 700X375') }}";
  var reco700_850 = "{{ __('Recommended Image size : 700X850') }}";
  var reco860_1150 = "{{ __('Recommended Image size : 860X1150') }}";
  var reco860_1320 = "{{ __('Recommended Image size : 860X1320') }}";
  var reco860_400 = "{{ __('Recommended Image size : 860X400') }}";
  var reco445_195 = "{{ __('Recommended Image size : 445X195') }}";
  var reco490_730 = "{{ __('Recommended Image size : 490X730') }}";
  var reco700_280 = "{{ __('Recommended Image size : 700X280') }}";
  var reco750_330 = "{{ __('Recommended Image size : 750X330') }}";
  var reco450_240 = "{{ __('Recommended Image size : 450X240') }}";
  var reco485_730 = "{{ __('Recommended Image size : 485X730') }}";
  var reco500_265 = "{{ __('Recommended Image size : 500X265') }}";
  var reco688_320 = "{{ __('Recommended Image size : 688X320') }}";
  var reco625_570 = "{{ __('Recommended Image size : 625X570') }}";
  var success = "{{ __('Success') }}";
  var nextText = "{{ __('Next') }}";
  var previousText = "{{ __('Previous') }}";
  var showText = "{{ __('Show') }}";
  var entriesText = "{{ __('entries') }}";
  var Search = "{{ __('Search') }}";
  var Showing = "{{ __('Showing') }}";
  var to = "{{ __('to') }}";
  var ofText = "{{ __('of') }}";
</script>
<!--   Core JS Files   -->
<script src="{{ asset('assets/admin/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugin/vue/vue.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugin/vue/axios.js') }}"></script>
<script src="{{ asset('assets/admin/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/core/bootstrap.min.js') }}"></script>

<!-- jQuery UI -->
<script src="{{ asset('assets/admin/js/plugin/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>

<!-- jQuery flatpickr -->
<script src="{{ asset('assets/admin/js/plugin/flatpickr.js') }}"></script>
<!-- jQuery Scrollbar -->
<script src="{{ asset('assets/admin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

<!-- Bootstrap Notify -->
<script src="{{ asset('assets/admin/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

<!-- Sweet Alert -->
<script src="{{ asset('assets/admin/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

<!-- Bootstrap Tag Input -->
<script src="{{ asset('assets/admin/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

<!-- Dropzone JS -->
<script src="{{ asset('assets/admin/js/plugin/dropzone/jquery.dropzone.min.js') }}"></script>

{{-- tinymce js --}}
<script type="text/javascript" src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

<!-- JS color JS -->
<script src="{{ asset('assets/admin/js/plugin/jscolor/jscolor.js') }}"></script>

<!-- choose color JS -->
<script src="{{ asset('assets/admin/js/plugin/choose-color/choose-color.js') }}"></script>

<!-- Datatable -->
<script src="{{ asset('assets/admin/js/plugin/datatables.min.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ asset('assets/admin/js/plugin/select2.min.js') }}"></script>

<!-- Atlantis JS -->
<script src="{{ asset('assets/admin/js/atlantis.min.js') }}"></script>

<!-- Fontawesome Icon Picker JS -->
<script src="{{ asset('assets/admin/js/plugin/fontawesome-iconpicker/fontawesome-iconpicker.min.js') }}"></script>

<!-- Fonts and icons -->
<script src="{{ asset('assets/admin/js/plugin/webfont/webfont.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/user/js/custom.js') }}"></script>
@yield('variables')
<!-- misc JS -->
<script>
  var category_url = "{{ route('user.blog.getcats') }}";
  var user_status = "{{ route('user-status') }}";
</script>
<script src="{{ asset('assets/admin/js/misc.js') }}"></script>

@yield('scripts')

@yield('vuescripts')
@if (session()->has('modal-show'))
  <script>
    $(document).ready(function() {
      $('#limitModal').modal('show');
    });
  </script>
  @php
    session()->forget('modal-show');
  @endphp
@endif

@if (session()->has('success'))
  <script>
    "use strict";
    var content = {};

    content.message = '{{ session('success') }}';
    content.title = "{{ __('Success') }}";
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'success',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000,
    });
  </script>
@endif


@if (session()->has('warning'))
  <script>
    "use strict";
    var content = {};

    content.message = '{{ session('warning') }}';
    content.title = "{{ __('Warning') }}";
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'warning',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000,
    });
  </script>
@endif
