<!-- CSS Files -->
{{-- fontawesome css --}}
<link rel="stylesheet" href="{{ asset('assets/front/css/all.min.css') }}">
{{-- fontawesome icon picker css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/fontawesome-iconpicker.min.css') }}">
{{-- bootstrap css --}}
<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/choose-color.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/atlantis.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/tinymce-content.css') }}">

@if (request()->cookie('user-theme') == 'dark')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/dark.css') }}">
@endif

@php
  $trans_lang = App\Models\Language::where('code', request()->cookie('userDashboardLang'))->first();
@endphp
@if ($trans_lang->rtl == 1)
  <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl-style.css') }}">
@endif


@yield('styles')
