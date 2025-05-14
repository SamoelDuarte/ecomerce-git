<!DOCTYPE html>
<html lang="ar">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <title>{{ $bs->website_title }} - {{  __('User Dashboard') }}</title>
  <link rel="icon" href="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}">
  @includeif('user.partials.styles')
  @php
    $selLang = App\Models\Language::where('code', request()->input('language'))->first();
  @endphp

  @yield('styles')

</head>

<body @if (request()->cookie('user-theme') == 'dark') data-background-color="dark" @endif
  data-form-language="{{ @$selLang->rtl == 1 ? 'rtl' : '' }}"
  data-dashboard-language="{{ $dashboard_language->rtl == 1 ? 'rtl' : '' }}">
  <div class="wrapper">

    {{-- top navbar area start --}}
    @includeif('user.partials.top-navbar')
    {{-- top navbar area end --}}


    {{-- side navbar area start --}}
    @includeif('user.partials.side-navbar')
    {{-- side navbar area end --}}


    <div class="main-panel">
      <div class="content">
        <div class="page-inner">
          @yield('content')
        </div>
      </div>
      @includeif('user.partials.footer')
    </div>

  </div>

  @includeif('user.partials.scripts')

  {{-- Loader --}}
  <div class="request-loader">
    <img src="{{ asset('assets/admin/img/loader.gif') }}" alt="">
  </div>
  {{-- Loader --}}
</body>

</html>
