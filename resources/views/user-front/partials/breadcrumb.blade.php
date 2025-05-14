@if (
    !request()->routeIs('customer.itemcheckout.offline.success') &&
        !request()->routeIs('customer.success.page') &&
        !request()->routeIs('front.user.productDetails') &&
        !request()->routeIs('user-front.blog_details'))
  <div class="page-title-area header-next">
    @if (@$userBe->breadcrumb)
      <img class=" bg-img " src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/front/img/user/' . $userBe->breadcrumb) }}"
        alt="Banner">
    @endif
    <div class="container">
      <div class="content text-start">
        <h2>@yield('breadcrumb_title') </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb justify-content-start">
            <li class="breadcrumb-item"><a
                href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb_title') </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
@endif
