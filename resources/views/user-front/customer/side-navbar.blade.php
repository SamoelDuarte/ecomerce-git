@php
  $shopSettings = App\Models\User\UserShopSetting::where('user_id', $user->id)->first();
@endphp

<div class="col-lg-3">
  <div class="sidebar-widget-area radius-md">
    <div class="widget">
      <ul class="links">
        <li><a class="@if (request()->routeIs('customer.dashboard')) active @endif"
            href="{{ route('customer.dashboard', getParam()) }}"><i class="fal fa-tachometer-alt"></i>
            {{ $keywords['Dashboard'] ?? __('Dashboard') }}</a></li>
        @if ($shop_settings->catalog_mode != 1)
          <li><a class=" @if (request()->routeIs('customer.orders') || request()->routeIs('customer.orders-details')) active @endif"
              href="{{ route('customer.orders', getParam()) }}">
              <i class="fal fa-shopping-cart"></i>
              {{ $keywords['My Orders'] ?? __('My Orders') }}</a></li>
        @endif
        <li><a class=" @if (request()->routeIs('customer.wishlist')) active @endif"
            href="{{ route('customer.wishlist', getParam()) }}"><i class="fal fa-heart"></i>
            {{ $keywords['My Wishlist'] ?? __('My Wishlist') }}</a></li>

        <li><a class=" @if (request()->routeIs('customer.edit_profile')) active @endif"
            href="{{ route('customer.edit_profile', getParam()) }}"><i class="fal fa-user"></i>
            {{ $keywords['My Profile'] ?? __('My Profile') }}</a></li>
        @if (@$shopSettings->catalog_mode == 0)
          <li>
            <a class=" @if (request()->routeIs('customer.billing-details')) active @endif"
              href="{{ route('customer.billing-details', getParam()) }}">
              <i class="fal fa-wallet"></i>
              {{ $keywords['Billing Details'] ?? __('Billing Details') }}</a>
          </li>
          <li>
            <a class=" @if (request()->routeIs('customer.shpping-details')) active @endif"
              href="{{ route('customer.shpping-details', getParam()) }}">
              <i class="fal fa-shipping-fast"></i>
              {{ $keywords['Shipping Details'] ?? __('Shipping Details') }}</a>
          </li>
        @endif
        <li><a class=" @if (request()->routeIs('customer.change_password')) active @endif"
            href="{{ route('customer.change_password', getParam()) }}"><i class="fal fa-unlock-alt"></i>
            {{ $keywords['Change Password'] ?? __('Change Password') }} </a></li>
        <li><a href="{{ route('customer.logout', getParam()) }}"><i class="fal fa-sign-out"></i>
            {{ $keywords['Sign out'] ?? __('Sign out') }}</a></li>
      </ul>
    </div>
  </div>
</div>
