@php
  $default = \App\Models\User\Language::where('is_default', 1)
      ->where('user_id', Auth::user()->id)
      ->first();
  $user = Auth::guard('web')->user();
  $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
  if (!empty($user)) {
      $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
      $permissions = json_decode($permissions, true);
  }
@endphp
<div class="sidebar sidebar-style-2" @if (request()->cookie('user-theme') == 'dark') data-background-color="dark2" @endif>
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (!empty(Auth::user()->photo))
            <img src="{{ asset('assets/front/img/user/' . Auth::user()->photo) }}" alt="..."
              class="avatar-img rounded">
          @else
            <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..." class="avatar-img rounded">
          @endif
        </div>
        <div class="info">
          <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
            <span>
              {{ auth()->user()->first_name . ' ' . auth()->user()->last_name }}
              <span class="user-level">{{ auth()->user()->username }}</span>
              <span class="caret"></span>
            </span>
          </a>
          <div class="clearfix"></div>
          <div class="collapse in" id="collapseExample">
            <ul class="nav">
              @if (!is_null($package))
                <li>
                  <a href="{{ route('user-profile-update', ['language' => $defaultLang]) }}">
                    <span class="link-collapse">{{ __('Edit Profile') }}</span>
                  </a>
                </li>
              @endif
              <li>
                <a href="{{ route('user.changePass') }}">
                  <span class="link-collapse">{{ __('Change Password') }}</span>
                </a>
              </li>
              <li>
                <a href="{{ route('user-logout') }}">
                  <span class="link-collapse">{{ __('Logout') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <ul class="nav nav-primary">
        <div class="row mb-2">
          <div class="col-12">
            <form action="">
              <div class="form-group py-0">
                <input name="term" type="text" class="form-control sidebar-search ltr" value=""
                  placeholder="{{ __('Search Menu Here') . '...' }}">
              </div>
            </form>
          </div>
        </div>
        <li class="nav-item
                @if (request()->path() == 'user/dashboard') active @endif">
          <a href="{{ route('user-dashboard') }}">
            <i class="la flaticon-paint-palette"></i>
            <p>{{ __('Dashboard') }}</p>
          </a>
        </li>

        @if (!is_null($package))
          {{-- START SHOP MANAGEMENT --}}
          <li
            class="nav-item
              @if (request()->routeIs('user.itemcategory.index')) active
              @elseif (request()->routeIs('user.itemcategory.edit')) active
              @elseif(request()->routeIs('user.itemsubcategory.index')) active
              @elseif(request()->routeIs('user.itemsubcategory.edit')) active
              @elseif(request()->routeIs('user.item.index')) active
              @elseif(request()->routeIs('user.item.type')) active
              @elseif(request()->routeIs('user.item.edit')) active
                  @elseif(request()->routeIs('user.all.item.orders')) active
                @elseif(request()->routeIs('user.pending.item.orders')) active
                @elseif(request()->routeIs('user.processing.item.orders')) active
                @elseif(request()->routeIs('user.completed.item.orders')) active
                @elseif(request()->routeIs('user.rejected.item.orders')) active
              @elseif(request()->routeIs('user.item.variations')) active
              @elseif(request()->routeIs('user.item.create')) active
              @elseif(request()->routeIs('user.item.details')) active
              @elseif(request()->routeIs('user.category.variations')) active
              @elseif(request()->routeIs('user.subcategory.variations')) active
              @elseif(request()->routeIs('user.orders.report')) active
              @elseif(request()->routeIs('user.product.label.index')) active
              @elseif(request()->routeIs('user.variant.index')) active
              @elseif(request()->routeIs('user.variant.create')) active
              @elseif(request()->routeIs('user.variant.edit')) active @endif">
            <a data-toggle="collapse" href="#category">
              <i class="fas fa-store-alt"></i>
              <p>{{ __('Shop Management') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
           @if (request()->routeIs('user.itemcategory.index')) show
              @elseif (request()->routeIs('user.itemcategory.edit')) show
                @elseif(request()->routeIs('user.itemsubcategory.index')) show
                @elseif(request()->routeIs('user.itemsubcategory.edit')) show
                  @elseif(request()->routeIs('user.item.index')) show
              @elseif(request()->routeIs('user.item.type')) show
              @elseif(request()->routeIs('user.item.edit')) show
                @elseif(request()->routeIs('user.all.item.orders')) show
                @elseif(request()->routeIs('user.pending.item.orders')) show
                @elseif(request()->routeIs('user.processing.item.orders')) show
                @elseif(request()->routeIs('user.completed.item.orders')) show
                @elseif(request()->routeIs('user.rejected.item.orders')) show
                @elseif(request()->routeIs('user.item.create')) show
                @elseif(request()->routeIs('user.item.details')) show
                @elseif(request()->routeIs('user.item.variations')) show
                @elseif(request()->routeIs('user.category.variations')) show
                @elseif(request()->routeIs('user.subcategory.variations')) show
                            @elseif(request()->routeIs('user.orders.report')) show
                @elseif(request()->routeIs('user.product.label.index')) show
                @elseif(request()->routeIs('user.variant.index')) show
                @elseif(request()->routeIs('user.variant.create')) show
                @elseif(request()->routeIs('user.variant.edit')) show @endif"
              id="category">
              <ul class="nav nav-collapse">
                <li class="submenu">
                  <a data-toggle="collapse" href="#productManagement"
                    aria-expanded="{{ request()->routeIs('user.itemcategory.index') ||
                    request()->routeIs('user.itemcategory.edit') ||
                    request()->routeIs('user.itemsubcategory.index') ||
                    request()->routeIs('user.itemsubcategory.edit') ||
                    request()->routeIs('user.item.type') ||
                    request()->routeIs('user.item.variations') ||
                    request()->routeIs('user.item.create') ||
                    request()->routeIs('user.item.index') ||
                    request()->routeIs('user.category.variations') ||
                    request()->routeIs('user.subcategory.variations') ||
                    request()->routeIs('user.item.edit') ||
                    request()->routeIs('user.product.label.index') ||
                    request()->routeIs('user.variant.index') ||
                    request()->routeIs('user.variant.create') ||
                    request()->routeIs('user.variant.edit')
                        ? 'true'
                        : 'false' }}">
                    <span class="sub-item">{{ __('Products') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse
                      @if (request()->routeIs('user.itemcategory.index')) show
                      @elseif (request()->routeIs('user.itemcategory.edit')) show
                      @elseif(request()->routeIs('user.itemsubcategory.index')) show
                      @elseif(request()->routeIs('user.itemsubcategory.edit')) show
                      @elseif(request()->routeIs('user.item.type')) show
                      @elseif(request()->routeIs('user.item.variations')) show
                      @elseif(request()->routeIs('user.item.create')) show
                      @elseif(request()->routeIs('user.item.index')) show
                      @elseif(request()->routeIs('user.category.variations')) show
                      @elseif(request()->routeIs('user.subcategory.variations')) show
                      @elseif(request()->routeIs('user.item.edit')) show
                      @elseif(request()->routeIs('user.product.label.index')) show
                      @elseif(request()->routeIs('user.variant.index')) show
                      @elseif(request()->routeIs('user.variant.create')) show
                      @elseif(request()->routeIs('user.variant.edit')) show @endif"
                    id="productManagement">
                    <ul class="nav nav-collapse subnav">
                      <li
                        class="@if (request()->routeIs('user.itemcategory.index')) active
                          @elseif (request()->routeIs('user.category.variations')) active
                          @elseif(request()->routeIs('user.itemcategory.edit')) active @endif">
                        <a href="{{ route('user.itemcategory.index') . '?language=' . $defaultLang }}">
                          <span class="sub-item">{{ __('Categories') }}</span>
                        </a>
                      </li>
                      <li
                        class="@if (request()->routeIs('user.itemsubcategory.index')) active
                          @elseif (request()->routeIs('user.subcategory.variations')) active
                          @elseif(request()->routeIs('user.itemsubcategory.edit')) active @endif">
                        <a href="{{ route('user.itemsubcategory.index') . '?language=' . $defaultLang }}">
                          <span class="sub-item">{{ __('Subcategories') }}</span>
                        </a>
                      </li>
                      <li class="@if (request()->routeIs('user.product.label.index')) active @endif">
                        <a href="{{ route('user.product.label.index') . '?language=' . $defaultLang }}">
                          <span class="sub-item">{{ __('Labels') }}</span>
                        </a>
                      </li>

                      <li class="submenu">
                        <a data-toggle="collapse" href="#manageVariants"
                          aria-expanded="{{ request()->routeIs('user.variant.create') || request()->routeIs('user.variant.index') || request()->routeIs('user.variant.edit') ? 'true' : 'false' }}">
                          <span class="sub-item">{{ __('Variants') }}</span>
                          <span class="caret"></span>
                        </a>
                        <div
                          class="collapse
                            @if (request()->routeIs('user.variant.create')) show
                            @elseif(request()->routeIs('user.variant.index')) show
                            @elseif(request()->routeIs('user.variant.edit')) show @endif"
                          id="manageVariants">
                          <ul class="nav nav-collapse subnav">
                            <li class="@if (request()->routeIs('user.variant.create')) active @endif">
                              <a href="{{ route('user.variant.create') . '?language=' . $defaultLang }}">
                                <span class="sub-item">{{ __('Add Variant') }}</span>
                              </a>
                            </li>
                            <li
                              class="@if (request()->routeIs('user.variant.index')) active @elseif(request()->routeIs('user.variant.edit')) active @endif">
                              <a href="{{ route('user.variant.index') . '?language=' . $defaultLang }}">
                                <span class="sub-item">{{ __('Variations') }}</span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>

                      <li class="submenu">
                        <a data-toggle="collapse" href="#MangeItems"
                          aria-expanded="{{ request()->routeIs('user.item.type') || request()->routeIs('user.item.create') || request()->routeIs('user.item.edit') || request()->routeIs('user.item.variations') || request()->routeIs('user.item.index') ? 'true' : 'false' }}">
                          <span class="sub-item">{{ __('Items') }}</span>
                          <span class="caret"></span>
                        </a>
                        <div
                          class="collapse
                            @if (request()->routeIs('user.item.type')) show
                            @elseif(request()->routeIs('user.item.index')) show
                            @elseif(request()->routeIs('user.item.create')) show
                            @elseif(request()->routeIs('user.item.edit')) show
                            @elseif(request()->routeIs('user.item.variations')) show @endif"
                          id="MangeItems">
                          <ul class="nav nav-collapse subnav">
                            <li
                              class="@if (request()->routeIs('user.item.type')) active
                              @elseif(request()->routeIs('user.item.create')) active @endif">
                              <a href="{{ route('user.item.type') }}">
                                <span class="sub-item">{{ __('Add Item') }}</span>
                              </a>
                            </li>
                            <li
                              class="@if (request()->routeIs('user.item.index')) active
                              @elseif(request()->routeIs('user.item.edit')) active
                              @elseif(request()->routeIs('user.item.variations')) active @endif">
                              <a href="{{ route('user.item.index') . '?language=' . $defaultLang }}">
                                <span class="sub-item">{{ __('Items') }}</span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                  </div>
                </li>


                <li class="submenu">
                  <a data-toggle="collapse" href="#manageOrders"
                    aria-expanded="{{ request()->routeIs('user.all.item.orders') || request()->routeIs('user.pending.item.orders') || request()->routeIs('user.processing.item.orders') || request()->routeIs('user.completed.item.orders') || request()->routeIs('user.rejected.item.orders') || request()->routeIs('user.item.details') || request()->routeIs('user.orders.report') ? 'true' : 'false' }}">
                    <span class="sub-item">{{ __('Orders') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse
                      @if (request()->routeIs('user.all.item.orders')) show
                      @elseif(request()->routeIs('user.pending.item.orders')) show
                      @elseif(request()->routeIs('user.processing.item.orders')) show
                      @elseif(request()->routeIs('user.completed.item.orders')) show
                      @elseif(request()->routeIs('user.rejected.item.orders')) show
                      @elseif(request()->routeIs('user.item.details')) show
                      @elseif(request()->routeIs('user.orders.report')) show @endif"
                    id="manageOrders">
                    <ul class="nav nav-collapse subnav">
                      <li class="@if (request()->routeIs('user.all.item.orders')) active @endif">
                        <a href="{{ route('user.all.item.orders') }}">
                          <span class="sub-item">{{ __('All Orders') }}</span>
                        </a>
                      </li>
                      <li class="@if (request()->routeIs('user.pending.item.orders')) active @endif">
                        <a href="{{ route('user.pending.item.orders') }}">
                          <span class="sub-item">{{ __('Pending Orders') }}</span>
                        </a>
                      </li>
                      <li class="@if (request()->routeIs('user.processing.item.orders')) active @endif">
                        <a href="{{ route('user.processing.item.orders') }}">
                          <span class="sub-item">{{ __('Processing Orders') }}</span>
                        </a>
                      </li>
                      <li class="@if (request()->routeIs('user.completed.item.orders')) active @endif">
                        <a href="{{ route('user.completed.item.orders') }}">
                          <span class="sub-item">{{ __('Completed Orders') }}</span>
                        </a>
                      </li>
                      <li class="@if (request()->routeIs('user.rejected.item.orders')) active @endif">
                        <a href="{{ route('user.rejected.item.orders') }}">
                          <span class="sub-item">{{ __('Rejected Orders') }}</span>
                        </a>
                      </li>
                      <li class="@if (request()->routeIs('user.orders.report')) active @endif">
                        <a href="{{ route('user.orders.report') }}">
                          <span class="sub-item">{{ __('Sales Report') }}</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
              </ul>
            </div>
          </li>
          {{-- END SHOP MANAGEMENT --}}
        @endif
        <li class=" nav-item @if (request()->routeIs('user.coupon.index')) active @endif">
          <a href="{{ route('user.coupon.index') }}">
            <i class="fas fa-tags"></i>
            <p class="sub-item">{{ __('Coupons') }}</p>
          </a>
        </li>
        <li class="nav-item @if (request()->routeIs('user.shipping.index')) active @endif">
          <a href="{{ route('user.shipping.index') . '?language=' . $defaultLang }}">
            <i class="fas fa-shipping-fast"></i>
            <p class="sub-item">{{ __('Shipping Charges') }}</p>
          </a>
        </li>
        <li class="nav-item @if (request()->routeIs('user-currency-index')) active @endif">
          <a href="{{ route('user-currency-index') }}">
            <i class="fas fa-money-bill-wave"></i>
            <p class="sub-item">{{ __('Currencies') }}</p>
          </a>
        </li>
        <li class="nav-item @if (request()->routeIs('user.item.settings')) active @endif">
          <a href="{{ route('user.item.settings') }}">
            <i class="fas fa-tools"></i>
            <p class="sub-item">{{ __('Shop Settings') }}</p>
          </a>
        </li>
        {{-- Registered Users --}}
        @if (!is_null($package))
          <li
            class="nav-item
         @if (request()->path() == 'user/register/users') active
         @elseif(request()->is('user/register/user/details/*')) active
         @elseif (request()->routeIs('user.register.user.changePass')) active @endif">
            <a href="{{ route('user.register.user') }}">
              <i class="la flaticon-users"></i>
              <p>{{ __('Registered Customers') }}</p>
            </a>
          </li>
        @endif

        @if (!is_null($package))
          @if (empty($admin->role) || (!empty($permissions) && in_array('Pages', $permissions)))
            {{-- Dynamic Pages --}}
            <li
              class="nav-item
          @if (request()->routeIs('user.home_page.hero.slider_version')) active
                    @elseif (request()->routeIs('user.home_page.hero.create_slider')) active
                    @elseif (request()->routeIs('user.home_page.hero.edit_slider')) active
                    @elseif (request()->routeIs('user.home_page.banner_section')) active
                    @elseif (request()->routeIs('user.tab.index')) active
                    @elseif (request()->routeIs('user.tab.edit')) active
                    @elseif (request()->routeIs('user.tab.feature')) active
                    @elseif (request()->routeIs('user.tab.products')) active
                    @elseif (request()->routeIs('user.home_page.subscriber.edit')) active
                    @elseif (request()->routeIs('user.home.section.index')) active
                    @elseif (request()->routeIs('user.home_page.featuredImage.edit')) active
                    @elseif (request()->routeIs('user.sections.index')) active
                    @elseif (request()->routeIs('user.home_page.herosec.bacground_img')) active
                    @elseif (request()->routeIs('user.home_page.heroSec.how_it_work')) active
                    @elseif (request()->routeIs('user.home_page.heroSec.product_slider')) active
                    @elseif (request()->routeIs('user.blog.category.index')) active
                    @elseif (request()->routeIs('user.blog.category.edit')) active
                    @elseif(request()->routeIs('user.blog.index')) active
                    @elseif(request()->routeIs('user.blog.create')) active
                    @elseif(request()->routeIs('user.blog.edit')) active
                    @elseif(request()->routeIs('user.faq.index')) active
                    @elseif(request()->routeIs('user.contact')) active
                    @elseif(request()->routeIs('user.header.index')) active
                    @elseif (request()->routeIs('user.footer.index')) active
                    @elseif(request()->routeIs('user.ulink.index')) active
                    @elseif (request()->routeIs('user.page.index')) active
                    @elseif(request()->routeIs('user.page.create')) active
                    @elseif(request()->routeIs('user.page.edit')) active
                    @elseif(request()->routeIs('user.page.edit')) active
                    @elseif (request()->routeIs('user.additional_sections')) active
                    @elseif (request()->routeIs('user.additional_section.create')) active
                    @elseif (request()->routeIs('user.additional_section.edit')) active
                    @elseif (request()->routeIs('user.pages.aboutus.about')) active
                    @elseif (request()->routeIs('user.pages.about_us.features.edit')) active
                    @elseif (request()->routeIs('user.pages.counter_section.index')) active
                    @elseif (request()->routeIs('user.pages.counter_section.counter.edit')) active
                    @elseif (request()->routeIs('user.about_us.testimonial.index')) active
                    @elseif (request()->routeIs('user.about_us.testimonial.edit')) active
                    @elseif (request()->routeIs('user.about.additional_sections')) active
                    @elseif(request()->routeIs('user.about.additional_section.create')) active
                    @elseif(request()->routeIs('user.about.additional_section.edit')) active
                    @elseif(request()->routeIs('user.about.sections.index')) active
                    @elseif(request()->routeIs('user.not_found_page')) active
                    @elseif(request()->routeIs('user.breadcrumb')) active
                    @elseif(request()->routeIs('user.breadcrumb.heading')) active
                    @elseif(request()->routeIs('user.image_text_content.section')) active
                    @elseif(request()->routeIs('user.pages.about_us.features.index')) active
                    @elseif(request()->routeIs('user.menu_builder.index')) active
                    @elseif(request()->routeIs('user.sections.item_highlight')) active
                    @elseif(request()->routeIs('user.basic_settings.seo')) active
                    @elseif(request()->routeIs('user.cta_section.index')) active @endif">
              <a data-toggle="collapse" href="#user-pages">
                <i class="la flaticon-file"></i>
                <p>{{ __('Pages') }}</p>
                <span class="caret"></span>
              </a>
              <div
                class="collapse
              @if (request()->routeIs('user.home_page.hero.slider_version')) show
                    @elseif (request()->routeIs('user.home_page.hero.create_slider')) show
                    @elseif (request()->routeIs('user.home_page.hero.edit_slider')) show
                    @elseif (request()->routeIs('user.home_page.banner_section')) show
                    @elseif (request()->routeIs('user.tab.index')) show
                    @elseif (request()->routeIs('user.tab.edit')) show
                    @elseif (request()->routeIs('user.tab.feature')) show
                    @elseif (request()->routeIs('user.not_found_page')) show
                    @elseif (request()->routeIs('user.tab.products')) show
                    @elseif (request()->routeIs('user.home_page.subscriber.edit')) show
                    @elseif (request()->routeIs('user.home.section.index')) show
                    @elseif (request()->routeIs('user.home_page.featuredImage.edit')) show
                    @elseif (request()->routeIs('user.sections.index')) show
                    @elseif (request()->routeIs('user.home_page.herosec.bacground_img')) show
                    @elseif (request()->routeIs('user.home_page.heroSec.how_it_work')) show
                    @elseif (request()->routeIs('user.home_page.heroSec.product_slider')) show
                    @elseif (request()->routeIs('user.blog.category.index')) show
                               @elseif (request()->routeIs('user.blog.category.edit')) show
                    @elseif(request()->routeIs('user.blog.index')) show
                    @elseif(request()->routeIs('user.blog.create')) show
                    @elseif(request()->routeIs('user.blog.edit')) show
                    @elseif(request()->routeIs('user.faq.index')) show
                    @elseif(request()->routeIs('user.contact')) show
                    @elseif(request()->routeIs('user.header.index')) show
                    @elseif (request()->routeIs('user.footer.index')) show
                    @elseif(request()->routeIs('user.ulink.index')) show
                   @elseif (request()->routeIs('user.page.index')) show
                    @elseif(request()->routeIs('user.page.create')) show
                    @elseif(request()->routeIs('user.page.edit')) show
                    @elseif (request()->routeIs('user.additional_sections')) show
                    @elseif (request()->routeIs('user.additional_section.create')) show
                    @elseif (request()->routeIs('user.additional_section.edit')) show
                    @elseif (request()->routeIs('user.pages.aboutus.about')) show
                    @elseif (request()->routeIs('user.pages.about_us.features.edit')) show
                    @elseif (request()->routeIs('user.pages.counter_section.index')) show
                    @elseif (request()->routeIs('user.pages.counter_section.counter.edit')) show
                    @elseif (request()->routeIs('user.about_us.testimonial.index')) show
                    @elseif (request()->routeIs('user.about_us.testimonial.edit')) show
                    @elseif (request()->routeIs('user.about.additional_sections')) show
                    @elseif(request()->routeIs('user.about.additional_section.create')) show
                    @elseif(request()->routeIs('user.about.additional_section.edit')) show
                    @elseif(request()->routeIs('user.about.sections.index')) show
                    @elseif(request()->routeIs('user.breadcrumb')) show
                    @elseif(request()->routeIs('user.breadcrumb.heading')) show
                    @elseif(request()->routeIs('user.image_text_content.section')) show
                    @elseif(request()->routeIs('user.pages.about_us.features.index')) show
                    @elseif(request()->routeIs('user.menu_builder.index')) show
                    @elseif(request()->routeIs('user.sections.item_highlight')) show
                    @elseif(request()->routeIs('user.basic_settings.seo')) show
                    @elseif(request()->routeIs('user.cta_section.index')) show @endif"
                id="user-pages">
                <ul class="nav nav-collapse">
                  <li
                    class="submenu
                    @if (request()->routeIs('user.home_page.hero.slider_version')) selected
                      @elseif (request()->routeIs('user.home_page.hero.create_slider')) selected
                      @elseif (request()->routeIs('user.home_page.hero.edit_slider')) selected
                      @elseif (request()->routeIs('user.home_page.banner_section')) selected
                      @elseif (request()->routeIs('user.tab.index')) selected
                      @elseif (request()->routeIs('user.tab.edit')) selected
                      @elseif (request()->routeIs('user.tab.feature')) selected
                      @elseif (request()->routeIs('user.tab.products')) selected
                      @elseif (request()->routeIs('user.home_page.subscriber.edit')) selected
                      @elseif (request()->routeIs('user.home.section.index')) selected
                      @elseif (request()->routeIs('user.home_page.featuredImage.edit')) selected
                      @elseif (request()->routeIs('user.sections.index')) selected
                      @elseif (request()->routeIs('user.home_page.herosec.bacground_img')) selected
                      @elseif (request()->routeIs('user.home_page.heroSec.how_it_work')) selected
                      @elseif (request()->routeIs('user.home_page.heroSec.product_slider')) selected
                      @elseif (request()->routeIs('user.additional_sections')) selected
                      @elseif (request()->routeIs('user.additional_section.create')) selected
                      @elseif (request()->routeIs('user.additional_section.edit')) selected
                      @elseif (request()->routeIs('user.image_text_content.section')) selected
                      @elseif (request()->routeIs('user.sections.item_highlight')) selected
                      @elseif (request()->routeIs('user.cta_section.index')) selected @endif
                    ">
                    <a data-toggle="collapse" href="#homepage"
                      aria-expanded="
                      @if (request()->routeIs('user.home_page.hero.slider_version')) true
                      @elseif (request()->routeIs('user.home_page.hero.create_slider')) true
                      @elseif (request()->routeIs('user.home_page.hero.edit_slider')) true
                      @elseif (request()->routeIs('user.home_page.banner_section')) true
                      @elseif (request()->routeIs('user.tab.index')) true
                      @elseif (request()->routeIs('user.tab.edit')) true
                      @elseif (request()->routeIs('user.tab.feature')) true
                      @elseif (request()->routeIs('user.tab.products')) true
                      @elseif (request()->routeIs('user.home_page.subscriber.edit')) true
                      @elseif (request()->routeIs('user.home.section.index')) true
                      @elseif (request()->routeIs('user.home_page.featuredImage.edit')) true
                      @elseif (request()->routeIs('user.sections.index')) true
                      @elseif (request()->routeIs('user.home_page.herosec.bacground_img')) true
                      @elseif (request()->routeIs('user.home_page.heroSec.how_it_work')) true
                      @elseif (request()->routeIs('user.home_page.heroSec.product_slider')) true
                      @elseif (request()->routeIs('user.additional_sections')) true
                      @elseif (request()->routeIs('user.additional_section.create')) true
                      @elseif (request()->routeIs('user.additional_section.edit')) true
                      @elseif (request()->routeIs('user.image_text_content.section')) true
                      @elseif (request()->routeIs('user.sections.item_highlight')) true
                      @else false @endif">
                      <span class="sub-item">{{ __('Home Page') }}</span>
                      <span class="caret"></span>
                    </a>
                    <div
                      class="collapse
                      @if (request()->routeIs('user.home_page.hero.slider_version')) show
                      @elseif (request()->routeIs('user.home_page.hero.create_slider')) show
                      @elseif (request()->routeIs('user.home_page.hero.edit_slider')) show
                      @elseif (request()->routeIs('user.home_page.banner_section')) show
                      @elseif (request()->routeIs('user.tab.index')) show
                      @elseif (request()->routeIs('user.tab.edit')) show
                      @elseif (request()->routeIs('user.tab.feature')) show
                      @elseif (request()->routeIs('user.tab.products')) show
                      @elseif (request()->routeIs('user.home_page.subscriber.edit')) show
                      @elseif (request()->routeIs('user.home.section.index')) show
                      @elseif (request()->routeIs('user.home_page.featuredImage.edit')) show
                      @elseif (request()->routeIs('user.sections.index')) show
                      @elseif (request()->routeIs('user.home_page.herosec.bacground_img')) show
                      @elseif (request()->routeIs('user.home_page.heroSec.how_it_work')) show
                      @elseif (request()->routeIs('user.home_page.heroSec.product_slider')) show
                      @elseif (request()->routeIs('user.additional_sections')) show
                      @elseif (request()->routeIs('user.additional_section.create')) show
                      @elseif (request()->routeIs('user.additional_section.edit')) show
                      @elseif (request()->routeIs('user.image_text_content.section')) show
                      @elseif (request()->routeIs('user.sections.item_highlight')) show
                      @elseif (request()->routeIs('user.cta_section.index')) show @endif"
                      id="homepage">
                      <ul class="nav nav-collapse subnav">

                        <li class="
                            @if (request()->routeIs('user.image_text_content.section')) active @endif">
                          <a href="{{ route('user.image_text_content.section', ['language' => $defaultLang]) }}">
                            <span class="sub-item">{{ __('Images & Texts') }}</span>
                          </a>
                        </li>
                        <li class="submenu">
                          <a data-toggle="collapse" href="#hero-section"
                            aria-expanded="{{ request()->routeIs('user.home_page.hero.create_slider') ||
                            request()->routeIs('user.home_page.heroSec.product_slider') ||
                            request()->routeIs('user.home_page.hero.slider_version') ||
                            request()->routeIs('user.home_page.herosec.bacground_img') ||
                            request()->routeIs('user.home_page.hero.edit_slider')
                                ? 'true'
                                : 'false' }}">
                            <span class="sub-item">{{ __('Hero Section') }}</span>
                            <span class="caret"></span>
                          </a>
                          <div id="hero-section"
                            class="collapse
                            @if (request()->routeIs('user.home_page.hero.slider_version') ||
                                    request()->routeIs('user.home_page.heroSec.product_slider') ||
                                    request()->routeIs('user.home_page.herosec.bacground_img') ||
                                    request()->routeIs('user.home_page.hero.create_slider') ||
                                    request()->routeIs('user.home_page.hero.edit_slider')) show @endif pl-3">
                            <ul class="nav nav-collapse subnav">
                              @if ($userBs->theme == 'fashion')
                                <li
                                  class="{{ request()->routeIs('user.home_page.heroSec.product_slider') ? 'active' : '' }}">
                                  <a href="{{ route('user.home_page.heroSec.product_slider') }}">
                                    <span class="sub-item">{{ __('Sliders') }}</span>
                                  </a>
                                </li>
                              @else
                                <li
                                  class="
                              @if (request()->routeIs('user.home_page.hero.slider_version')) active
                              @elseif (request()->routeIs('user.home_page.hero.create_slider')) active
                              @elseif (request()->routeIs('user.home_page.hero.edit_slider')) active @endif">
                                  <a
                                    href="{{ route('user.home_page.hero.slider_version', ['language' => $defaultLang]) }}">
                                    <span class="sub-item">{{ __('Sliders') }}</span>
                                  </a>
                                </li>
                              @endif
                              @if ($userBs->theme == 'kids' || $userBs->theme == 'furniture' || $userBs->theme == 'fashion')
                                <li
                                  class="
                            @if (request()->routeIs('user.home_page.herosec.bacground_img')) active @endif">
                                  <a
                                    href="{{ route('user.home_page.herosec.bacground_img', ['language' => $defaultLang, getParam()]) }}">
                                    <span class="sub-item">{{ __('Background Image') }}</span>
                                  </a>
                                </li>
                              @endif
                            </ul>
                          </div>
                        </li>

                        @if (
                            $userBs->theme == 'vegetables' ||
                                $userBs->theme == 'fashion' ||
                                $userBs->theme == 'electronics' ||
                                $userBs->theme == 'manti')
                          <li
                            class="
                                @if (request()->routeIs('user.home_page.heroSec.how_it_work')) active @endif">
                            <a href="{{ route('user.home_page.heroSec.how_it_work', ['language' => $defaultLang]) }}">
                              <span class="sub-item">{{ __('Features') }}</span>
                            </a>
                          </li>
                        @endif

                        <li class="@if (request()->routeIs('user.home_page.banner_section')) active @endif">
                          <a href="{{ route('user.home_page.banner_section', ['language' => $defaultLang]) }}">
                            <span class="sub-item">{{ __('Banners') }}</span>
                          </a>
                        </li>

                        <li
                          class="
                          @if (request()->routeIs('user.tab.index')) active
                          @elseif (request()->routeIs('user.tab.edit')) active
                          @elseif (request()->routeIs('user.tab.feature')) active
                          @elseif (request()->routeIs('user.tab.products')) active @endif">
                          <a href="{{ route('user.tab.index', ['language' => $defaultLang]) }}">
                            <span class="sub-item">
                              {{ $userBs->theme === 'manti' ? __('Sections') : __('Tabs') }}
                            </span>
                          </a>
                        </li>

                        <!-- additional sections -->
                        <li class="submenu">
                          <a data-toggle="collapse" href="#hoem-addi-section"
                            aria-expanded="{{ request()->routeIs('user.additional_sections') ||
                            request()->routeIs('user.additional_section.create') ||
                            request()->routeIs('user.additional_section.edit')
                                ? 'true'
                                : 'false' }}">
                            <span class="sub-item">{{ __('Additional Sections') }}</span>
                            <span class="caret"></span>
                          </a>
                          <div id="hoem-addi-section"
                            class="collapse
                            @if (request()->routeIs('user.additional_sections') ||
                                    request()->routeIs('user.additional_section.create') ||
                                    request()->routeIs('user.additional_section.edit')) show @endif pl-3">
                            <ul class="nav nav-collapse subnav">
                              <li class="{{ request()->routeIs('user.additional_section.create') ? 'active' : '' }}">
                                <a href="{{ route('user.additional_section.create') }}">
                                  <span class="sub-item">{{ __('Add Section') }}</span>
                                </a>
                              </li>
                              <li
                                class="{{ request()->routeIs('user.additional_sections') || request()->routeIs('user.additional_section.edit') ? 'active' : '' }}">
                                <a href="{{ route('user.additional_sections', ['language' => $defaultLang]) }}">
                                  <span class="sub-item">{{ __('Sections') }}
                                  </span>
                                </a>
                              </li>
                            </ul>
                          </div>
                        </li>

                        <li class="
                          @if (request()->routeIs('user.sections.index')) active @endif">
                          <a href="{{ route('user.sections.index') }}">
                            <span class="sub-item">
                              {{ __('Section Hide/Show') }}
                            </span>
                          </a>
                        </li>
                        <li class="
                          @if (request()->routeIs('user.sections.item_highlight')) active @endif">
                          <a href="{{ route('user.sections.item_highlight') }}">
                            <span class="sub-item">
                              {{ __('Item Highlights') }}
                            </span>
                          </a>
                        </li>

                      </ul>
                    </div>
                  </li>

                  <li
                    class="submenu
                    @if (request()->routeIs('user.pages.aboutus.about')) selected
                      @elseif (request()->routeIs('user.pages.about_us.features.edit'))
                      @elseif (request()->routeIs('user.pages.counter_section.index')) selected
                      @elseif (request()->routeIs('user.pages.counter_section.counter.edit')) selected
                      @elseif (request()->routeIs('user.about_us.testimonial.index')) selected
                      @elseif (request()->routeIs('user.about_us.testimonial.edit')) selected
                      @elseif (request()->routeIs('user.about.additional_sections')) selected
                      @elseif(request()->routeIs('user.about.additional_section.create')) selected
                      @elseif(request()->routeIs('user.about.additional_section.edit')) selected
                      @elseif(request()->routeIs('user.pages.about_us.features.index')) selected
                      @elseif(request()->routeIs('user.about.sections.index')) selected @endif
                    ">
                    <a data-toggle="collapse" href="#aboutuspage"
                      aria-expanded="
                      @if (request()->routeIs('user.pages.aboutus.about')) true
                      @elseif (request()->routeIs('user.pages.about_us.features.edit')) true
                      @elseif (request()->routeIs('user.pages.counter_section.index')) true
                      @elseif (request()->routeIs('user.pages.counter_section.counter.edit')) true
                      @elseif (request()->routeIs('user.about_us.testimonial.index')) true
                      @elseif (request()->routeIs('user.about_us.testimonial.edit')) true
                      @elseif (request()->routeIs('user.about.additional_sections')) true
                      @elseif(request()->routeIs('user.about.additional_section.create')) true
                      @elseif(request()->routeIs('user.about.additional_section.edit')) true
                      @elseif(request()->routeIs('user.about.sections.index')) true
                      @elseif(request()->routeIs('user.pages.about_us.features.index')) true
                      @else false @endif">
                      <span class="sub-item">{{ __('About Us') }}</span>
                      <span class="caret"></span>
                    </a>
                    <div
                      class="collapse
                      @if (request()->routeIs('user.pages.aboutus.about')) show
                      @elseif (request()->routeIs('user.pages.about_us.features.edit'))
                      @elseif (request()->routeIs('user.pages.counter_section.index')) show
                    @elseif (request()->routeIs('user.pages.counter_section.counter.edit')) show
                    @elseif (request()->routeIs('user.about_us.testimonial.index')) show
                    @elseif (request()->routeIs('user.about_us.testimonial.edit')) show
                    @elseif (request()->routeIs('user.about.additional_sections')) show
                    @elseif(request()->routeIs('user.about.additional_section.create')) show
                    @elseif(request()->routeIs('user.about.additional_section.edit')) show
                    @elseif(request()->routeIs('user.pages.about_us.features.index')) show
                    @elseif(request()->routeIs('user.about.sections.index')) show @endif"
                      id="aboutuspage">
                      <ul class="nav nav-collapse subnav">
                        @if ($userBs->theme == 'furniture' || $userBs->theme == 'kids')
                          <li
                            class="
                                @if (request()->routeIs('user.pages.about_us.features.index')) active @endif">
                            <a href="{{ route('user.pages.about_us.features.index', ['language' => $defaultLang]) }}">
                              <span class="sub-item">{{ __('Features') }}</span>
                            </a>
                          </li>
                        @endif
                        <li
                          class="
                          @if (request()->routeIs('user.pages.aboutus.about')) active
                          @elseif (request()->routeIs('user.pages.about_us.features.edit')) active @endif">
                          <a href="{{ route('user.pages.aboutus.about', ['language' => $defaultLang]) }}">
                            <span class="sub-item">{{ __('About') }}</span>
                          </a>
                        </li>
                        <li
                          class="
                          @if (request()->routeIs('user.pages.counter_section.index')) active
                          @elseif (request()->routeIs('user.pages.counter_section.counter.edit')) active @endif">
                          <a href="{{ route('user.pages.counter_section.index', ['language' => $defaultLang]) }}">
                            <span class="sub-item">{{ __('Counter Section') }}</span>
                          </a>
                        </li>
                        <li
                          class="
                          @if (request()->routeIs('user.about_us.testimonial.index')) active
                          @elseif (request()->routeIs('user.about_us.testimonial.edit')) active @endif">
                          <a href="{{ route('user.about_us.testimonial.index', ['language' => $defaultLang]) }}">
                            <span class="sub-item">{{ __('Testimonials') }}</span>
                          </a>
                        </li>

                        <!-- additional sections -->
                        <li class="submenu">
                          <a data-toggle="collapse" href="#about-additional-section"
                            aria-expanded="{{ request()->routeIs('user.about.additional_sections') ||
                            request()->routeIs('user.about.additional_section.create') ||
                            request()->routeIs('user.about.additional_section.edit')
                                ? 'true'
                                : 'false' }}">
                            <span class="sub-item">{{ __('Additional Sections') }}</span>
                            <span class="caret"></span>
                          </a>
                          <div id="about-additional-section"
                            class="collapse
                            @if (request()->routeIs('user.about.additional_sections') ||
                                    request()->routeIs('user.about.additional_section.create') ||
                                    request()->routeIs('user.about.additional_section.edit')) show @endif pl-3">
                            <ul class="nav nav-collapse subnav">

                              <li
                                class="{{ request()->routeIs('user.about.additional_section.create') ? 'active' : '' }}">
                                <a href="{{ route('user.about.additional_section.create') }}">
                                  <span class="sub-item">{{ __('Add Section') }}</span>
                                </a>
                              </li>
                              <li
                                class="{{ request()->routeIs('user.about.additional_sections') || request()->routeIs('user.about.additional_section.edit') ? 'active' : '' }}">
                                <a
                                  href="{{ route('user.about.additional_sections', ['language' => $defaultLang]) }}">
                                  <span class="sub-item">{{ __('Sections') }}
                                  </span>
                                </a>
                              </li>
                            </ul>
                          </div>
                        </li>


                        <li class="@if (request()->routeIs('user.about.sections.index')) active @endif">
                          <a href="{{ route('user.about.sections.index') }}">
                            <span class="sub-item">{{ __('Section Hide/Show') }}</span>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </li>

                  <li
                    class="submenu
                  @if (request()->routeIs('user.blog.category.index')) selected
                  @elseif(request()->routeIs('user.blog.category.edit')) selected
                    @elseif(request()->routeIs('user.blog.index')) selected
                    @elseif(request()->routeIs('user.blog.create')) selected
                    @elseif(request()->routeIs('user.blog.edit')) selected @endif">
                    <a data-toggle="collapse" href="#blog_sidebar"
                      aria-expanded="
                    @if (request()->routeIs('user.blog.category.index')) true
                    @elseif(request()->routeIs('user.blog.category.edit')) true
                    @elseif(request()->routeIs('user.blog.index')) true
                    @elseif(request()->routeIs('user.blog.create')) true
                    @elseif(request()->routeIs('user.blog.edit')) true @else false @endif
                    ">
                      <span class="sub-item">{{ __('Blog') }}</span>
                      <span class="caret"></span>
                    </a>
                    <div
                      class="collapse
                    @if (request()->routeIs('user.blog.category.index')) show
                    @elseif (request()->routeIs('user.blog.category.edit')) show
                    @elseif(request()->routeIs('user.blog.index')) show
                    @elseif(request()->routeIs('user.blog.create')) show
                    @elseif(request()->routeIs('user.blog.edit')) show @endif
                    "
                      id="blog_sidebar">
                      <ul class="nav nav-collapse subnav">
                        <li
                          class="
                      @if (request()->routeIs('user.blog.category.index')) active
                      @elseif (request()->routeIs('user.blog.category.edit')) active @endif
                      ">
                          <a href="{{ route('user.blog.category.index') . '?language=' . $defaultLang }}">
                            <span class="sub-item">{{ __('Categories') }}</span>
                          </a>
                        </li>
                        <li
                          class="@if (request()->routeIs('user.blog.index')) active
                    @elseif(request()->routeIs('user.blog.create')) active
                    @elseif(request()->routeIs('user.blog.edit')) active @endif">
                          <a href="{{ route('user.blog.index') . '?language=' . $defaultLang }}">
                            <span class="sub-item">{{ __('Posts') }}</span>
                          </a>
                        </li>

                      </ul>
                    </div>
                  </li>

                  <li class="@if (request()->routeIs('user.faq.index')) active @endif">
                    <a href="{{ route('user.faq.index') . '?language=' . $defaultLang }}">
                      <span class="sub-item">{{ __('FAQs') }}</span>
                    </a>
                  </li>

                  <li class="@if (request()->routeIs('user.contact')) active @endif">
                    <a href="{{ route('user.contact', ['language' => $defaultLang]) }}">
                      <span class="sub-item">{{ __('Contact Page') }}</span>
                    </a>
                  </li>

                  <li class="@if (request()->routeIs('user.not_found_page')) active @endif">
                    <a href="{{ route('user.not_found_page', ['language' => $defaultLang]) }}">
                      <span class="sub-item">{{ __('404 Page') }}</span>
                    </a>
                  </li>

                  <li class="@if (request()->routeIs('user.header.index')) active @endif">
                    <a href="{{ route('user.header.index') . '?language=' . $defaultLang }}">
                      <span class="sub-item">{{ __('Top Header Section') }}</span>
                    </a>
                  </li>

                  @if (!empty($permissions) && in_array('Custom Page', $permissions))
                    <li
                      class="submenu
                      @if (request()->routeIs('user.page.index')) selected
                      @elseif(request()->routeIs('user.page.create')) selected
                      @elseif(request()->routeIs('user.page.edit')) selected @endif">
                      <a data-toggle="collapse" href="#additionPagesSidebar"
                        aria-expanded="
                          @if (request()->routeIs('user.page.index')) true @endif
                      @if (request()->routeIs('user.page.create')) true @endif
                        @if (request()->routeIs('user.page.edit')) true @endif
                    ">
                        <span class="sub-item">{{ __('Additional Pages') }}</span>
                        <span class="caret"></span>
                      </a>
                      <div
                        class="collapse
                      @if (request()->routeIs('user.page.index')) show
                      @elseif(request()->routeIs('user.page.create')) show
                      @elseif(request()->routeIs('user.page.edit')) show @endif
                    "
                        id="additionPagesSidebar">
                        <ul class="nav nav-collapse subnav">
                          <li class="@if (request()->routeIs('user.page.create')) active @endif">
                            <a href="{{ route('user.page.create') }}">
                              <span class="sub-item">{{ __('Add Page') }}</span>
                            </a>
                          </li>
                          <li
                            class="@if (request()->routeIs('user.page.index')) active
                          @elseif(request()->routeIs('user.page.edit')) active @endif">
                            <a href="{{ route('user.page.index') . '?language=' . $defaultLang }}">
                              <span class="sub-item">{{ __('All Pages') }}</span>
                            </a>
                          </li>

                        </ul>
                      </div>
                    </li>
                  @endif

                  <li class="
                    @if (request()->routeIs('user.menu_builder.index')) selected @endif">
                    <a href="{{ route('user.menu_builder.index') . '?language=' . $defaultLang }}">
                      <span class="sub-item">{{ __('Menu Builder') }}</span>
                    </a>
                  </li>

                  <li
                    class="submenu
                  @if (request()->routeIs('user.footer.index')) selected
                    @elseif(request()->routeIs('user.ulink.index')) selected @endif">
                    <a data-toggle="collapse" href="#footerSection"
                      aria-expanded="
                    @if (request()->routeIs('user.footer.index')) true
                    @elseif(request()->routeIs('user.ulink.index')) true @else false @endif
                    ">
                      <span class="sub-item">{{ __('Footer') }}</span>
                      <span class="caret"></span>
                    </a>
                    <div
                      class="collapse
                    @if (request()->routeIs('user.footer.index')) show
                    @elseif(request()->routeIs('user.ulink.index')) show @endif
                    "
                      id="footerSection">
                      <ul class="nav nav-collapse subnav">
                        <li class="@if (request()->routeIs('user.footer.index')) active @endif">
                          <a href="{{ route('user.footer.index') . '?language=' . $defaultLang }}">
                            <span class="sub-item">{{ __('Footer Informations') }}</span>
                          </a>
                        </li>
                        <li class="@if (request()->routeIs('user.ulink.index')) active @endif">
                          <a href="{{ route('user.ulink.index') . '?language=' . $defaultLang }}">
                            <span class="sub-item">{{ __('Useful Links') }}</span>
                          </a>
                        </li>

                      </ul>
                    </div>
                  </li>


                  <li
                    class="submenu
                  @if (request()->routeIs('user.breadcrumb')) selected
                    @elseif(request()->routeIs('user.breadcrumb.heading')) selected @endif">
                    <a data-toggle="collapse" href="#Breadcrumbs"
                      aria-expanded="
                    @if (request()->routeIs('user.breadcrumb')) true
                    @elseif(request()->routeIs('user.breadcrumb.heading')) true @else false @endif
                    ">
                      <span class="sub-item">{{ __('Breadcrumbs') }}</span>
                      <span class="caret"></span>
                    </a>
                    <div
                      class="collapse
                    @if (request()->routeIs('user.breadcrumb')) show
                    @elseif(request()->routeIs('user.breadcrumb.heading')) show @endif
                    "
                      id="Breadcrumbs">
                      <ul class="nav nav-collapse subnav">
                        <li class="@if (request()->routeIs('user.breadcrumb')) active @endif">
                          <a href="{{ route('user.breadcrumb', ['language' => $defaultLang]) }}">
                            <span class="sub-item">{{ __('Image') }}</span>
                          </a>
                        </li>

                        <li class="@if (request()->routeIs('user.breadcrumb.heading')) active @endif">
                          <a href="{{ route('user.breadcrumb.heading') . '?language=' . $defaultLang }}">
                            <span class="sub-item">{{ __('Headings') }}</span>
                          </a>
                        </li>

                      </ul>
                    </div>
                  </li>

                  <li class="@if (request()->routeIs('user.basic_settings.seo')) active @endif">
                    <a href="{{ route('user.basic_settings.seo', ['language' => $defaultLang]) }}">
                      <span class="sub-item">{{ __('SEO Informations') }}</span>
                    </a>
                  </li>
                </ul>
              </div>
            </li>
          @endif
        @endif

        @if (!is_null($package))
          {{-- Subscribers --}}
          <li
            class="nav-item
          @if (request()->path() == 'user/subscribers') active
          @elseif(request()->path() == 'user/mailsubscriber') active @endif">
            <a data-toggle="collapse" href="#subscribers">
              <i class="la flaticon-envelope"></i>
              <p>{{ __('Subscribers') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
            @if (request()->path() == 'user/subscribers') show
            @elseif(request()->path() == 'user/mailsubscriber') show @endif"
              id="subscribers">
              <ul class="nav nav-collapse">
                <li class="@if (request()->path() == 'user/subscribers') active @endif">
                  <a href="{{ route('user.subscriber.index') }}">
                    <span class="sub-item">{{ __('Subscribers') }}</span>
                  </a>
                </li>
                <li class="@if (request()->path() == 'user/mailsubscriber') active @endif">
                  <a href="{{ route('user.mailsubscriber') }}">
                    <span class="sub-item">{{ __('Mail to Subscribers') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif



        {{-- membership --}}
        <li
          class="nav-item
          @if (request()->routeIs('user.payment-log.index')) active
          @elseif(request()->routeIs('user.plan.extend.index')) active
          @elseif(request()->routeIs('user.plan.extend.checkout')) active @endif">
          <a data-toggle="collapse" href="#Membership">
            <i class="fas fa-file-invoice-dollar"></i>
            <p>{{ __('Membership') }}</p>
            <span class="caret"></span>
          </a>
          <div
            class="collapse
            @if (request()->routeIs('user.payment-log.index')) show
            @elseif(request()->routeIs('user.plan.extend.index')) show
            @elseif(request()->routeIs('user.plan.extend.checkout')) show @endif"
            id="Membership">
            <ul class="nav nav-collapse">
              <li class="@if (request()->routeIs('user.payment-log.index')) active @endif">
                <a href="{{ route('user.payment-log.index') }}">
                  <span class="sub-item">{{ __('Logs') }}</span>
                </a>
              </li>
              <li
                class="
              @if (request()->routeIs('user.plan.extend.index')) active @endif
              @if (request()->routeIs('user.plan.extend.checkout')) active @endif
              ">
                <a href="{{ route('user.plan.extend.index') }}">
                  <span class="sub-item">{{ __('Extend Membership') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        {{-- QR Builder --}}
        @if (!empty($permissions) && in_array('QR Builder', $permissions))
          <li
            class="nav-item
              @if (request()->routeIs('user.qrcode')) active
              @elseif(request()->routeIs('user.qrcode.index')) active @endif">
            <a data-toggle="collapse" href="#qrcode">
              <i class="fas fa-qrcode"></i>
              <p>{{ __('QR Codes') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
              @if (request()->routeIs('user.qrcode')) show
              @elseif(request()->routeIs('user.qrcode.index')) show @endif"
              id="qrcode">
              <ul class="nav nav-collapse">
                <li class="@if (request()->routeIs('user.qrcode')) active @endif">
                  <a href="{{ route('user.qrcode') }}">
                    <span class="sub-item">{{ __('Generate QR Code') }}</span>
                  </a>
                </li>
                <li class="@if (request()->routeIs('user.qrcode.index')) active @endif">
                  <a href="{{ route('user.qrcode.index') }}">
                    <span class="sub-item">{{ __('Saved QR Codes') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif


        @if (!is_null($package))
          <li
            class="nav-item
                    @if (request()->routeIs('user.theme.version')) active
                    @elseif (request()->routeIs('user.basic_settings.general-settings')) active
                    @elseif(request()->routeIs('user.social.edit')) active
                    @elseif(request()->routeIs('user.social.index')) active
                    @elseif(request()->routeIs('user.basic_settings.edit_mail_template')) active
                    @elseif(request()->routeIs('user.basic_settings.mail_templates')) active
                    @elseif(request()->routeIs('user.cookie.alert')) active
                    @elseif(request()->routeIs('user.mail.information')) active
                    @elseif(request()->routeIs('user.plugins')) active
                    @elseif(request()->routeIs('user.maintenance_mode')) active
                    @elseif(request()->routeIs('user-domains')) active
                    @elseif(request()->routeIs('user-subdomain')) active
                     @elseif (request()->routeIs('user.language.index')) active
                        @elseif(request()->routeIs('user.language.edit')) active
                        @elseif(request()->routeIs('user.mail.information')) active
                        @elseif(request()->routeIs('user.gateway.index')) active
                        @elseif(request()->routeIs('user.gateway.offline')) active
                        @elseif(request()->routeIs('user.language.editKeyword')) active @endif">
            <a data-toggle="collapse" href="#basic">
              <i class="fas fa-sliders-h"></i>
              <p>{{ __('Site Settings') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
                        @if (request()->routeIs('user.theme.version')) show
                        @elseif (request()->routeIs('user.basic_settings.general-settings')) show
                        @elseif(request()->routeIs('user.social.edit')) show
                        @elseif(request()->routeIs('user.social.index')) show
                        @elseif(request()->is('/home_page/hero-slider/*')) show
                        @elseif(request()->routeIs('user.basic_settings.edit_mail_template')) show
                        @elseif (request()->routeIs('user.basic_settings.mail_templates')) show
                        @elseif (request()->routeIs('user.cookie.alert')) show
                        @elseif (request()->routeIs('user.mail.information')) show
                        @elseif (request()->routeIs('user.plugins')) show
                        @elseif (request()->routeIs('user.maintenance_mode')) show
                        @elseif (request()->routeIs('user.language.index')) show
                        @elseif(request()->routeIs('user.language.edit')) show
                        @elseif(request()->routeIs('user.language.editKeyword')) show
                        @elseif(request()->routeIs('user-domains')) show
                         @elseif(request()->routeIs('user.gateway.index')) show
                        @elseif(request()->routeIs('user.gateway.offline')) show
                        @elseif(request()->routeIs('user.mail.information')) show
                    @elseif(request()->routeIs('user-subdomain')) show @endif"
              id="basic">
              <ul class="nav nav-collapse">
                <li class="@if (request()->routeIs('user.basic_settings.general-settings')) active @endif">
                  <a href="{{ route('user.basic_settings.general-settings') }}">
                    <span class="sub-item">{{ __('General Settings') }}</span>
                  </a>
                </li>

                <li class="@if (request()->routeIs('user.theme.version')) active @endif">
                  <a href="{{ route('user.theme.version') }}">
                    <span class="sub-item">{{ __('Themes') }}</span>
                  </a>
                </li>

                <li class="@if (request()->routeIs('user.plugins')) active @endif">
                  <a href="{{ route('user.plugins') }}">
                    <span class="sub-item">{{ __('Plugins') }}</span>
                  </a>
                </li>

                <li class="@if (request()->routeIs('user.maintenance_mode')) active @endif">
                  <a href="{{ route('user.maintenance_mode') }}">
                    <span class="sub-item">{{ __('Maintenance Mode') }}</span>
                  </a>
                </li>
                <li
                  class="@if (request()->routeIs('user.social.edit')) active
                                @elseif(request()->routeIs('user.social.index')) active @endif">
                  <a href="{{ route('user.social.index') }}">
                    <span class="sub-item">{{ __('Social Links') }}</span>
                  </a>
                </li>

                <li class="@if (request()->routeIs('user.cookie.alert')) active @endif">
                  <a href="{{ route('user.cookie.alert', ['language' => $defaultLang]) }}">
                    <span class="sub-item">{{ __('Cookie Alert') }}</span>
                  </a>
                </li>

                <li
                  class="submenu
                                @if (request()->routeIs('user.basic_settings.mail_templates')) selected
                                @elseif (request()->routeIs('user.basic_settings.edit_mail_template')) selected
                                @elseif (request()->routeIs('user.basic_settings.edit_mail_template')) selected
                                @elseif (request()->routeIs('user.mail.information')) selected @endif">
                  <a data-toggle="collapse" href="#emailset"
                    aria-expanded="{{ request()->routeIs('user.mail.information') || request()->routeIs('user.basic_settings.mail_templates') || request()->routeIs('user.basic_settings.edit_mail_template') ? 'true' : 'false' }}">
                    <span class="sub-item">{{ __('Email Settings') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse {{ request()->routeIs('user.basic_settings.mail_templates') || request()->routeIs('user.basic_settings.edit_mail_template') || request()->routeIs('user.mail.information') ? 'show' : '' }}"
                    id="emailset">
                    <ul class="nav nav-collapse subnav">
                      <li
                        class="
                        @if (request()->routeIs('user.basic_settings.mail_templates')) active
                        @elseif (request()->routeIs('user.basic_settings.edit_mail_template')) active @endif">
                        <a href="{{ route('user.basic_settings.mail_templates', ['language' => $defaultLang]) }}">
                          <span class="sub-item">{{ __('Mail Templates') }}</span>
                        </a>
                      </li>

                      <li class=" @if (request()->routeIs('user.mail.information')) active @endif">
                        <a href="{{ route('user.mail.information') }}">
                          <span class="sub-item">{{ __('Mail Information') }}</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>

                <li
                  class="
                  @if (request()->routeIs('user.language.index')) active
                        @elseif(request()->routeIs('user.language.edit')) active
                        @elseif(request()->routeIs('user.language.editKeyword')) active @endif">
                  <a href="{{ route('user.language.index') }}">
                    <span class="sub-item">{{ __('Languages') }}</span>
                  </a>
                </li>

                @if (!is_null($package))
                  {{-- Start Payment getway --}}
                  <li
                    class="submenu @if (request()->routeIs('user.gateway.index')) selected   @elseif(request()->routeIs('user.gateway.offline')) selected @endif">
                    <a data-toggle="collapse" href="#gateways"
                      aria-expanded="{{ request()->routeIs('user.gateway.index') || request()->routeIs('user.gateway.offline') ? 'true' : 'false' }}">
                      <span class="sub-item">{{ __('Payment Gateways') }}</span>
                      <span class="caret"></span>
                    </a>
                    <div
                      class="collapse  @if (request()->routeIs('user.gateway.index')) show   @elseif(request()->routeIs('user.gateway.offline')) show @endif"
                      id="gateways">
                      <ul class="nav nav-collapse subnav">
                        <li class="@if (request()->routeIs('user.gateway.index')) active @endif">
                          <a href="{{ route('user.gateway.index') }}">
                            <span class="sub-item">{{ __('Online Gateways') }}</span>
                          </a>
                        </li>
                        <li class="@if (request()->routeIs('user.gateway.offline')) active @endif">
                          <a href="{{ route('user.gateway.offline') . '?language=' . $defaultLang }}">
                            <span class="sub-item">{{ __('Offline Gateways') }}</span>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </li>
                  {{-- End Payment getway --}}
                @endif

                {{-- Domain & URL --}}
                @if (!is_null($package))
                  <li
                    class="submenu
              @if (request()->routeIs('user-domains')) selected
              @elseif(request()->routeIs('user-subdomain')) selected @endif">
                    <a data-toggle="collapse" href="#domains"
                      aria-expanded="{{ request()->routeIs('user-domains') || request()->routeIs('user-subdomain') ? 'true' : 'false' }}">
                      <span class="sub-item">{{ __('Domains & URLs') }}</span>
                      <span class="caret"></span>
                    </a>
                    <div
                      class="collapse @if (request()->routeIs('user-domains')) show
              @elseif(request()->routeIs('user-subdomain')) show @endif"
                      id="domains">
                      <ul class="nav nav-collapse subnav">
                        @if (!empty($permissions) && in_array('Custom Domain', $permissions))
                          <li class="@if (request()->routeIs('user-domains')) active @endif">
                            <a href="{{ route('user-domains') }}">
                              <span class="sub-item">{{ __('Custom Domain') }}</span>
                            </a>
                          </li>
                        @endif
                        @if (!empty($permissions) && in_array('Subdomain', $permissions))
                          <li class="@if (request()->routeIs('user-subdomain')) active @endif">
                            <a href="{{ route('user-subdomain') }}">
                              <span class="sub-item">{{ __('Subdomain & Path URL') }}</span>
                            </a>
                          </li>
                        @else
                          <li class="@if (request()->routeIs('user-subdomain')) active @endif">
                            <a href="{{ route('user-subdomain') }}">
                              <span class="sub-item">{{ __('Path Based URL') }}</span>
                            </a>
                          </li>
                        @endif
                      </ul>
                    </div>
                  </li>
                @endif
              </ul>
            </div>
          </li>
        @endif
      </ul>
    </div>
  </div>
</div>
