<div class="menu-action-item-area">
  <ul class="menu-action-item-wrapper">
    <li class="menu-action-item">
      @if ($userCurrentLang->id)
        <a href="javascript:void(0)">
          <span class="icon"><i class="fal fa-globe"></span></i>{{ convertUtf8($userCurrentLang->name) }}
          <span class="plus-icon"><i class="fal fa-plus"></i></span>
        </a>
      @endif
      <ul class="setting-dropdown">
        @foreach ($userLangs as $userLang)
          <li>
            <a href="{{ route('front.user.changeUserLanguage', ['code' => $userLang->code, getParam()]) }}"
              class="menu-link" data-value="{{ $userLang->code }}">{{ convertUtf8($userLang->name) }}</a>
          </li>
        @endforeach
      </ul>
    </li>
    @php
      $userCurrentCurr = \App\Models\User\UserCurrency::where('id', session()->get('user_curr'))->first();
    @endphp
    <li class="menu-action-item">
      @if ($userCurrentCurr->id)
      <a href="javascript:void(0)">
          <span class="icon">
            <i>{{ $userCurrentCurr->symbol }}</i>
          </span>
          {{ convertUtf8($userCurrentCurr->text) }} <span class="plus-icon"><i class="fal fa-plus"></i></span></a>

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
    <li class="menu-action-item">
      <a href="javascript:void(0)"><span class="icon"><i class="fal fa-user"></i></span>
        {{ $keywords['My Account'] ?? __('My Account') }} <span class="plus-icon"><i class="fal fa-plus"></i></span></a>
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
          <li>
            <a class="menu-link"
              href="{{ route('customer.dashboard', getParam()) }}">{{ $keywords['Dashboard'] ?? __('Dashboard') }}</a>
          </li>
          <li>
            <a class="menu-link"
              href="{{ route('customer.logout', getParam()) }}">{{ $keywords['Logout'] ?? __('Logout') }}</a>
          </li>
        @endguest
      </ul>
    </li>
  </ul>
</div>
