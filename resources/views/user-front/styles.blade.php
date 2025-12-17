<!--====== Favicon Icon ======-->
<link rel="shortcut icon" href="{{ !empty($userBs->favicon) ? asset('assets/front/img/user/' . $userBs->favicon) : '' }}"
  type="img/png" />


<link rel="stylesheet" href="{{ asset('assets/user-front/css/plugins.css') }}">

<!-- Anti-aliasing CSS para melhor qualidade visual -->
<link rel="stylesheet" href="{{ asset('assets/css/anti-aliasing.css') }}">
<!-- Melhorias visuais específicas para e-commerce -->
<link rel="stylesheet" href="{{ asset('assets/css/ecommerce-enhancements.css') }}">
<!-- Correção específica de serrilhas -->
<link rel="stylesheet" href="{{ asset('assets/css/serrated-fix.css') }}">

<link rel="stylesheet" href="{{ asset('assets/user-front/fonts/fontawesome/css/all.min.css') }}">
<!-- Main Style CSS -->
<link rel="stylesheet" href="{{ asset('assets/user-front/css/common/style.css?v=1.0.4') }}">
<link rel="stylesheet" href="{{ asset('assets/user-front/css/common/header-1.css') }}">
<link rel="stylesheet" href="{{ asset('assets/user-front/css/tinymce-content.css') }}">

@if ($userBs->theme == 'vegetables')
<link rel="stylesheet" href="{{ asset('assets/user-front/css/grocery/home-1.css') }}">
@elseif ($userBs->theme == 'furniture')
<link rel="stylesheet" href="{{ asset('assets/user-front/css/furniture/home-2.css') }}">
@elseif ($userBs->theme == 'fashion')
<link rel="stylesheet" href="{{ asset('assets/user-front/css/fashion/home-3.css') }}">
@elseif ($userBs->theme == 'electronics')
<link rel="stylesheet" href="{{ asset('assets/user-front/css/electronics/home-4.css') }}">
@elseif ($userBs->theme == 'kids')
<link rel="stylesheet" href="{{ asset('assets/user-front/css/kids/home-5.css') }}">
@elseif ($userBs->theme == 'manti')
<link rel="stylesheet" href="{{ asset('assets/user-front/css/manti/home-6.css?v=1.0.1') }}">
@endif
<!--====== Style css ======-->

<!--====== RTL css ======-->
@if ($userCurrentLang->rtl == 1)
<link rel="stylesheet" href="{{ asset('assets/front/css/rtl.css') }}">
@endif

@yield('styles')