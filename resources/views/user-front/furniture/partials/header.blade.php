<!-- Header Start -->
<header class="header header-fixed">
  <!-- Mobile Navbar -->
  <div class="mobile-navbar d-block d-xl-none">
    <div class="container">
      <div class="mobile-navbar-inner">
        <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
          <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
            data-src="{{ !empty($userBs->logo) ? asset('assets/front/img/user/' . $userBs->logo) : asset('assets/front/img/logo.png') }}"
            alt="logo">
        </a>
        <button class="mobile-menu-toggler" type="button">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>

  <div class="header-top bg-white">
    <div class="container">
      <div class="row">
        <div class="col-xl-4 col-lg-4">
          <div class="header-left">
            <ul>

              <li><i class="{{ $header->header_logo ?? '' }}"></i>{{ $header->header_text ?? '' }}</li>
              @php
                $emails = !empty($userContact->contact_mails) ? explode(',', $userContact->contact_mails) : [];
              @endphp
              @if (count($emails) > 0)
                <li>
                  @foreach ($emails as $email)
                    <i class="fal fa-envelope"></i>
                    <a href="mailTo:{{ $email }}">{{ $email }}</a>{{ !$loop->last ? ', ' : '' }}
                  @endforeach
                </li>
              @endif
            </ul>
          </div>
        </div>
        <div class="col-xl-5 col-lg-4">
          <div class="header-center text-center">
            <p class="m-0">{{ $header->header_middle_text ?? '' }}</p>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 text-end">
          <div class="header-right">
            <ul class="menu ">
              <li class="menu-item">

                @if ($userCurrentLang->id)
                  <a href="javascript:void(0)"><i class="fal fa-globe"></i>{{ convertUtf8($userCurrentLang->name) }}</a>
                @endif
                <ul class="setting-dropdown">

                  @foreach ($userLangs as $userLang)
                    <li>
                      <a href="{{ route('front.user.changeUserLanguage', ['code' => $userLang->code, getParam()]) }}"
                        class="menu-link" data-value="{{ $userLang->code }}">
                        {{ convertUtf8($userLang->name) }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </li>

              @php
                $userCurrentCurr = \App\Models\User\UserCurrency::where('id', session()->get('user_curr'))->first();
              @endphp

              <li class="menu-item">
                @if ($userCurrentCurr->id)
                  <a href="javascript:void(0)">{{ $userCurrentCurr->symbol }}
                    &nbsp;{{ convertUtf8($userCurrentCurr->text) }}</a>
                @endif

                <ul class="setting-dropdown">
                  @foreach ($userCurrency as $userCurr)
                    <li>
                      <a href="{{ route('front.user.changeUserCurrency', ['id' => $userCurr->id, getParam()]) }}"
                        class="menu-link">{{ $userCurr->text }}</a>
                    </li>
                  @endforeach
                </ul>

              </li>

              <li class="menu-item">
                <a href="#"><i class="fal fa-user"></i>{{ $keywords['My Account'] ?? __('My Account') }}</a>
                <ul class="setting-dropdown">
                  @guest('customer')
                    <li>
                      <a class="menu-link"
                        href="{{ route('customer.login', getParam()) }}">{{ $keywords['Login'] ?? __('Login') }}</a>
                    </li>
                    <li>
                      <a class="menu-link"
                        href="{{ route('customer.signup', getParam()) }}">{{ $keywords['Signup'] ?? __('Signup') }}</a>
                    </li>
                  @endguest
                  @auth('customer')
                    @php $authUserInfo = Auth::guard('customer')->user(); @endphp
                    <li>
                      <a href="{{ route('customer.dashboard', getParam()) }}"
                        class="menu-link">{{ $keywords['Dashboard'] ?? __('Dashboard') }}</a>
                    </li>
                    <li>
                      <a href="{{ route('customer.logout', getParam()) }}"
                        class="menu-link">{{ $keywords['Logout'] ?? __('Logout') }}</a>
                    </li>
                  @endauth
                </ul>
              </li>

            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="header-middle sticky-header-2">

    <div class="container">
      <div class="header-left">
        <div class="brand-logo">
          <a href="{{ route('front.user.detail.view', getParam()) }}" title="" target="_self">
            <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
              data-src="{{ !empty($userBs->logo) ? asset('assets/front/img/user/' . $userBs->logo) : asset('assets/front/img/logo.png') }}"
              alt="">
          </a>
        </div>
      </div>
      <div class="header-center">
        <nav class="menu mobile-nav">
          <ul class="menu-right me-auto">
            @php
              $links = json_decode($userMenus, true);
            @endphp
            @foreach ($links as $link)
              @php
                $href = getUserHref($link, $userCurrentLang->id);
              @endphp
              @if (!array_key_exists('children', $link))
                <li class="nav-item">
                  <a class="nav-link {{ url()->current() == $href ? 'active' : '' }}" target="{{ $link['target'] }}"
                    href="{{ $href }}">{{ $link['text'] }}</a>
                </li>
              @else
                <li class="nav-item has-submenu">
                  <a href="{{ $href }}" target="{{ $link['target'] }}" class="nav-link">{{ $link['text'] }}<i
                      class="fal fa-plus"></i></a>
                  <ul class="submenu">
                    @foreach ($link['children'] as $level2)
                      @php
                        $l2Href = getUserHref($level2, $userCurrentLang->id);
                      @endphp
                      <li class="nav-item">
                        <a class="nav-link" href="{{ $l2Href }}"
                          target="{{ $level2['target'] }}">{{ $level2['text'] }}</a>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @endif
            @endforeach

          </ul>
        </nav>
      </div>
      <div class="header-right">
        <ul class="menu">
          <li class="menu-item menu-wishlist border-end">
            <a href="{{ route('customer.wishlist', getParam()) }}" class="menu-link"><i class="fal fa-heart"><span
                  class="badge wishlist-count">{{ $wishListCount }}</span></i>
              {{ $keywords['Wishlist'] ?? __('Wishlist') }} </a>
          </li>
          <li class="menu-item border-end">
            <a href="{{ route('front.user.compare', getParam()) }}" class="menu-link"><i class="fal fa-random"><span
                  class="badge" id="compare-count">{{ $compareCount }}</span></i>
              {{ $keywords['Compare'] ?? __('Compare') }} </a>
          </li>
          @if ($shop_settings->catalog_mode != 1)
            <li class="menu-item">
              <a href="javascript:void(0)" class="menu-link "><i class="fal fa-shopping-cart"><span
                    class="badge cart-dropdown-count">{{ $cartCount }}</span></i>
                {{ $keywords['Cart'] ?? __('Cart') }} </a>
              <div class="cart-dropdown" id="cart-dropdown-header">

              </div>
            </li>
          @endif
        </ul>
      </div>
    </div>
  </div>
</header>
<!-- Header End -->
