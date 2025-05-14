<!-- Responsive Bottom Toolbar -->
<div class="mobile-bottom-toolbar d-block d-lg-none">
  <div class="container">
    <nav class="toolbar" id="nav">
      <ul class="toolbar-nav">
        <li class="tolbar-item">
          <a class="active" href="{{ route('front.user.detail.view', getParam()) }}" target="_self">
            <i class="fal fa-home"></i>
            <span>{{ $keywords['Home'] ?? __('Home') }}</span>
          </a>
        </li>
        <li class="tolbar-item">
          <a href="{{ route('front.user.cart', getParam()) }}" target="_self">
            <i class="fal fa-shopping-bag"></i>
            {{ $keywords['Cart'] ?? __('Cart') }}
            <span class="badge cart-dropdown-count">0</span>
          </a>
        </li>
        <li class="tolbar-item">
          <a href="{{ route('customer.wishlist', getParam()) }}" target="_self">
            <i class="fal fa-heart"></i>
            <span>{{ $keywords['Wishlist'] ?? __('Wishlist') }}</span>
            <span class="badge wishlist-count">0</span>
          </a>
        </li>
        <li class="tolbar-item">
          <a href="{{ route('customer.dashboard', getParam()) }}" target="_self">
            <i class="fal fa-user"></i>
            <span>{{ $keywords['Account'] ?? __('Account') }}</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</div>
