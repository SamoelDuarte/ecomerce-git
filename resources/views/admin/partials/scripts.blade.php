<script>
  "use strict";
  var mainurl = "{{ url('/') }}";
  var storeUrl = "";
  var removeUrl = "";
  var rmvdbUrl = "";
  var select_a_category = "{{ __('Select a category') }}";
  var are_you_sure = "{{ __('Are you sure ?') }}";
  var wont_revert_text = @json(__('You won\'t be able to revert this!'));
  var yes_delete_it = "{{ __('Yes, delete it') }}";
  var cancel = "{{ __('Cancel') }}";
  var demo_mode = "{{ env('DEMO_MODE') }}";
  var shopSetting = "{{ $bs->time_format }}";
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
<script src="{{ asset('assets/admin/js/plugin/jquery-ui/jquery-ui.min.js') }}" ></script>
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
<!-- Datatable -->
<script src="{{ asset('assets/admin/js/plugin/datatables.min.js') }}"></script>
<!-- Dropzone JS -->
<script src="{{ asset('assets/admin/js/plugin/dropzone/jquery.dropzone.min.js') }}"></script>

{{-- tinymce js --}}
<script type="text/javascript" src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

<!-- JS color JS -->
<script src="{{ asset('assets/admin/js/plugin/jscolor/jscolor.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ asset('assets/admin/js/plugin/select2.min.js') }}"></script>

<!-- Atlantis JS -->
<script src="{{ asset('assets/admin/js/atlantis.min.js') }}"></script>

<!-- Fontawesome Icon Picker JS -->
<script src="{{ asset('assets/admin/js/plugin/fontawesome-iconpicker/fontawesome-iconpicker.min.js') }}"></script>

{{-- fonts and icons script --}}
<script src="{{ asset('assets/admin/js/plugin/webfont/webfont.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/admin/js/custom.js') }}"></script>

@yield('variables')
<!-- misc JS -->
<script src="{{ asset('assets/admin/js/misc.js') }}"></script>

@yield('scripts')

@yield('vuescripts')

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
    content.title = "{{ __('Warning') . '!' }}";
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
