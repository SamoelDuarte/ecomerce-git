@php
  $admin = Auth::guard('admin')->user();
  if (!empty($admin->role)) {
      $permissions = $admin->role->permissions;
      $permissions = json_decode($permissions, true);
  }
@endphp

<div class="sidebar sidebar-style-2" @if (request()->cookie('admin-theme') == 'dark') data-background-color="dark2" @endif>
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (!empty(Auth::guard('admin')->user()->image))
            <img src="{{ asset('assets/admin/img/propics/' . Auth::guard('admin')->user()->image) }}" alt="..."
              class="avatar-img rounded">
          @else
            <img src="{{ asset('assets/admin/img/propics/blank_user.jpg') }}" alt="..." class="avatar-img rounded">
          @endif
        </div>
        <div class="info">
          <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
            <span>
              {{ Auth::guard('admin')->user()->first_name }}
              <span
                class="user-level">{{ is_null(@Auth::guard('admin')->user()->role->name) ? __('Super Admin') : @Auth::guard('admin')->user()->role->name }}</span>
              <span class="caret"></span>
            </span>
          </a>
          <div class="clearfix"></div>

          <div class="collapse in" id="collapseExample">
            <ul class="nav">
              <li>
                <a href="{{ route('admin.editProfile') }}">
                  <span class="link-collapse">{{ __('Edit Profile') }}</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.changePass') }}">
                  <span class="link-collapse">{{ __('Change Password') }}</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.logout') }}">
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

        {{-- Dashboard --}}
        <li class="nav-item @if (request()->path() == 'admin/dashboard') active @endif">
          <a href="{{ route('admin.dashboard') }}">
            <i class="la flaticon-paint-palette"></i>
            <p>{{ __('Dashboard') }}</p>
          </a>
        </li>
        {{-- Users Management --}}
        @if (empty($admin->role) || (!empty($permissions) && in_array('Users Management', $permissions)))
          <li
            class="nav-item
            @if (request()->routeIs('admin.register.user')) active
            @elseif(request()->routeIs('register.user.view')) active
            @elseif (request()->routeIs('register.user.changePass')) active
            @elseif (request()->routeIs('admin.subscriber.index')) active
            @elseif (request()->routeIs('register.user.category')) active
            @elseif (request()->routeIs('register.user.category_edit')) active
            @elseif(request()->routeIs('admin.mailsubscriber')) active @endif">
            <a data-toggle="collapse" href="#registerd-users">
              <i class="fas fa-users"></i>
              <p>{{ __('Users Management') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
            @if (request()->routeIs('admin.register.user')) show
            @elseif(request()->routeIs('register.user.view')) show
            @elseif (request()->routeIs('register.user.changePass')) show
            @elseif (request()->routeIs('admin.subscriber.index')) show
            @elseif (request()->routeIs('register.user.category')) show
            @elseif (request()->routeIs('register.user.category_edit')) show
            @elseif(request()->routeIs('admin.mailsubscriber')) show @endif"
              id="registerd-users">
              <ul class="nav nav-collapse">

                <li class="
                @if (request()->routeIs('register.user.category')) active @endif
                @if (request()->routeIs('register.user.category_edit')) active @endif
                ">
                  <a href="{{ route('register.user.category', ['language' => $default->code]) }}">
                    <span class="sub-item">{{ __('Categories') }}</span>
                  </a>
                </li>

                <li
                  class="
                @if (request()->routeIs('admin.register.user')) active
                @elseif (request()->routeIs('register.user.view')) active
                @elseif (request()->routeIs('register.user.changePass')) active @endif
                ">
                  <a href="{{ route('admin.register.user') }}">
                    <span class="sub-item">{{ __('Registered Users') }}</span>
                  </a>
                </li>

                <li class="@if (request()->routeIs('admin.subscriber.index')) active @endif">
                  <a href="{{ route('admin.subscriber.index') }}">
                    <span class="sub-item">{{ __('Subscribers') }}</span>
                  </a>
                </li>
                <li class="@if (request()->routeIs('admin.mailsubscriber')) active @endif">
                  <a href="{{ route('admin.mailsubscriber') }}">
                    <span class="sub-item">{{ __('Mail to Subscribers') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- Package Management --}}
        @if (empty($admin->role) || (!empty($permissions) && in_array('Package Management', $permissions)))
          <li
            class="nav-item
                    @if (request()->routeIs('admin.package.settings')) active
                    @elseif(request()->routeIs('admin.package.index')) active
                    @elseif(request()->routeIs('admin.package.features')) active
                    @elseif(request()->routeIs('admin.package.edit')) active @endif">
            <a data-toggle="collapse" href="#packageManagement">
              <i class="fas fa-receipt"></i>
              <p>{{ __('Package Management') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
                        @if (request()->routeIs('admin.package.settings')) show
                        @elseif(request()->routeIs('admin.package.index')) show
                        @elseif(request()->routeIs('admin.package.features')) show
                        @elseif(request()->routeIs('admin.package.edit')) show @endif"
              id="packageManagement">
              <ul class="nav nav-collapse">
                <li class="@if (request()->routeIs('admin.package.settings')) active @endif">
                  <a href="{{ route('admin.package.settings') }}">
                    <span class="sub-item">{{ __('Settings') }}</span>
                  </a>
                </li>
                <li class="@if (request()->routeIs('admin.package.features')) active @endif">
                  <a href="{{ route('admin.package.features') }}">
                    <span class="sub-item">{{ __('Package Features') }}</span>
                  </a>
                </li>
                <li
                  class="@if (request()->routeIs('admin.package.index')) active
                                @elseif(request()->routeIs('admin.package.edit')) active @endif">
                  <a href="{{ route('admin.package.index') . '?language=' . $default->code }}">
                    <span class="sub-item">{{ __('Packages') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Payment Logs', $permissions)))
          <li class="nav-item
                        @if (request()->path() == 'admin/payment-log') active @endif">
            <a href="{{ route('admin.payment-log.index') }}">
              <i class="fas fa-file-invoice-dollar"></i>
              <p>{{ __('Payment Logs') }}</p>
            </a>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Custom Domains', $permissions)))
          <li
            class="nav-item
                        @if (request()->path() == 'admin/domains') active
                        @elseif(request()->path() == 'admin/domain/texts') active @endif">
            <a data-toggle="collapse" href="#customDomains">
              <i class="fas fa-link"></i>
              <p>{{ __('Custom Domains') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
                            @if (request()->path() == 'admin/domains') show
                            @elseif(request()->path() == 'admin/domain/texts') show @endif"
              id="customDomains">
              <ul class="nav nav-collapse">
                <li class="@if (request()->path() == 'admin/domain/texts') active @endif">
                  <a href="{{ route('admin.custom-domain.texts') }}">
                    <span class="sub-item">{{ __('Request Page Texts') }}</span>
                  </a>
                </li>
                <li class="@if (request()->path() == 'admin/domains' && empty(request()->input('type'))) active @endif">
                  <a href="{{ route('admin.custom-domain.index') }}">
                    <span class="sub-item">{{ __('All Requests') }}</span>
                  </a>
                </li>
                <li class="@if (request()->path() == 'admin/domains' && request()->input('type') == 'pending') active @endif">
                  <a href="{{ route('admin.custom-domain.index', ['type' => 'pending']) }}">
                    <span class="sub-item">{{ __('Pending Requests') }}</span>
                  </a>
                </li>
                <li class="@if (request()->path() == 'admin/domains' && request()->input('type') == 'connected') active @endif">
                  <a href="{{ route('admin.custom-domain.index', ['type' => 'connected']) }}">
                    <span class="sub-item">{{ __('Connected Requests') }}</span>
                  </a>
                </li>
                <li class="@if (request()->path() == 'admin/domains' && request()->input('type') == 'rejected') active @endif">
                  <a href="{{ route('admin.custom-domain.index', ['type' => 'rejected']) }}">
                    <span class="sub-item">{{ __('Rejected Requests') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Subdomains', $permissions)))
          <li class="nav-item
                        @if (request()->path() == 'admin/subdomains') active @endif">
            <a data-toggle="collapse" href="#subDomains">
              <i class="far fa-link"></i>
              <p>{{ __('Subdomains') }}</p>
              <span class="caret"></span>
            </a>
            <div class="collapse
                            @if (request()->path() == 'admin/subdomains') show @endif"
              id="subDomains">
              <ul class="nav nav-collapse">
                <li class="@if (request()->path() == 'admin/subdomains' && empty(request()->input('type'))) active @endif">
                  <a href="{{ route('admin.subdomain.index') }}">
                    <span class="sub-item">{{ __('All Subdomains') }}</span>
                  </a>
                </li>
                <li class="@if (request()->path() == 'admin/subdomains' && request()->input('type') == 'pending') active @endif">
                  <a href="{{ route('admin.subdomain.index', ['type' => 'pending']) }}">
                    <span class="sub-item">{{ __('Pending Subdomains') }}</span>
                  </a>
                </li>
                <li class="@if (request()->path() == 'admin/subdomains' && request()->input('type') == 'connected') active @endif">
                  <a href="{{ route('admin.subdomain.index', ['type' => 'connected']) }}">
                    <span class="sub-item">{{ __('Connected Subdomains') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Menu Builder', $permissions)))
          {{-- Menu Builder --}}
          <li class="nav-item
            @if (request()->path() == 'admin/menu-builder') active @endif">
            <a href="{{ route('admin.menu_builder.index') . '?language=' . $default->code }}">
              <i class="fas fa-bars"></i>
              <p>{{ __('Menu Builder') }}</p>
            </a>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Pages', $permissions)))
          {{-- Pages --}}
          <li
            class="nav-item
              @if (request()->routeIs('admin.page.create')) active
              @elseif(request()->routeIs('admin.page.index')) active
              @elseif(request()->routeIs('admin.page.edit')) active
              @elseif (request()->path() == 'admin/bcategorys') active
              @elseif (request()->path() == 'admin/blog') active
              @elseif (request()->is('admin/blog/*/edit')) active
              @elseif (request()->routeIs('admin.contact.index')) active
          @elseif(request()->routeIs('admin.bcategory.edit')) active
              @elseif (request()->path() == 'admin/features') active
              @elseif(request()->routeIs('admin.herosection.imgtext')) active
              @elseif(request()->is('admin/feature/*/edit')) active
              @elseif(request()->is('admin/process')) active
              @elseif(request()->is('admin/process/*/edit')) active
              @elseif(request()->path() == 'admin/testimonials') active
              @elseif(request()->is('admin/testimonial/*/edit')) active
              @elseif(request()->path() == 'admin/menu/section') active
              @elseif(request()->path() == 'admin/special/section') active
              @elseif(request()->path() == 'admin/herosection/video') active
              @elseif(request()->path() == 'admin/home-page-text-section') active
              @elseif(request()->path() == 'admin/partners') active
              @elseif(request()->is('admin/partner/*/edit')) active
              @elseif(request()->path() == 'admin/sections') active
              @elseif(request()->routeIs('admin.faq.index')) active
              @elseif (request()->path() == 'admin/footers') active
              @elseif(request()->path() == 'admin/ulinks') active
              @elseif(request()->routeIs('admin.breadcrumb')) active
              @elseif(request()->routeIs('admin.seo')) active
              @elseif (request()->routeIs('admin.additional_sections')) active
              @elseif (request()->routeIs('admin.additional_section.create')) active
              @elseif (request()->routeIs('admin.additional_section.edit')) active
              @elseif(request()->routeIs('admin.additional_sections')) active
              @elseif(request()->routeIs('admin.additional_section.create')) active
              @elseif (request()->routeIs('admin.additional_section.edit')) active
              @elseif (request()->routeIs('admin.home_page.counter-section')) active
              @elseif (request()->routeIs('admin.abouts.section.hide_show')) active
              @elseif (request()->routeIs('admin.about_us.additional_sections')) active
              @elseif (request()->routeIs('admin.about_us.additional_section.create')) active
              @elseif (request()->routeIs('admin.breadcrumb.heading')) active
              @elseif (request()->routeIs('admin.about_us.additional_section.edit')) active @endif">
            <a data-toggle="collapse" href="#pages">
              <i class="la flaticon-file"></i>
              <p>{{ __('Pages') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
              @if (request()->routeIs('admin.page.create')) show
              @elseif(request()->routeIs('admin.page.index')) show
              @elseif(request()->routeIs('admin.page.edit')) show
              @elseif (request()->path() == 'admin/bcategorys') show
              @elseif (request()->path() == 'admin/blog') show
              @elseif (request()->is('admin/blog/*/edit')) show
              @elseif (request()->routeIs('admin.contact.index')) show
    @elseif(request()->routeIs('admin.bcategory.edit')) show
              @elseif (request()->path() == 'admin/features') show
              @elseif(request()->routeIs('admin.herosection.imgtext')) show
              @elseif(request()->is('admin/feature/*/edit')) show
              @elseif(request()->is('admin/process')) show
              @elseif(request()->is('admin/process/*/edit')) show
              @elseif(request()->path() == 'admin/testimonials') show
              @elseif(request()->is('admin/testimonial/*/edit')) show
              @elseif(request()->path() == 'admin/special/section') show
              @elseif(request()->path() == 'admin/home-page-text-section') show
              @elseif(request()->path() == 'admin/partners') show
              @elseif(request()->is('admin/partner/*/edit')) show
              @elseif(request()->path() == 'admin/sections') show
              @elseif(request()->routeIs('admin.faq.index')) show
              @elseif (request()->path() == 'admin/footers') show
              @elseif(request()->path() == 'admin/ulinks') show
              @elseif(request()->routeIs('admin.breadcrumb')) show
              @elseif(request()->routeIs('admin.seo')) show
              @elseif (request()->routeIs('admin.additional_sections')) show
              @elseif (request()->routeIs('admin.additional_section.create')) show
              @elseif (request()->routeIs('admin.additional_section.edit')) show
              @elseif(request()->routeIs('admin.additional_sections')) show
              @elseif(request()->routeIs('admin.additional_section.create')) show
              @elseif (request()->routeIs('admin.additional_section.edit')) show
              @elseif (request()->routeIs('admin.home_page.counter-section')) show
              @elseif (request()->routeIs('admin.abouts.section.hide_show')) show
              @elseif (request()->routeIs('admin.about_us.additional_sections')) show
              @elseif (request()->routeIs('admin.about_us.additional_section.create')) show
              @elseif (request()->routeIs('admin.breadcrumb.heading')) show
              @elseif (request()->routeIs('admin.about_us.additional_section.edit')) show @endif"
              id="pages">
              <ul class="nav nav-collapse">

                <li
                  class="submenu
                  @if (request()->path() == 'admin/features') selected
                  @elseif(request()->routeIs('admin.herosection.imgtext')) selected
                  @elseif(request()->is('admin/feature/*/edit')) selected
                  @elseif(request()->is('admin/process')) selected
                  @elseif(request()->is('admin/process/*/edit')) selected
                  @elseif(request()->path() == 'admin/testimonials') selected
                  @elseif(request()->is('admin/testimonial/*/edit')) selected
                  @elseif(request()->path() == 'admin/special/section') selected
                  @elseif(request()->path() == 'admin/home-page-text-section') selected
                  @elseif(request()->path() == 'admin/partners') selected
                  @elseif(request()->is('admin/partner/*/edit')) selected
                  @elseif(request()->path() == 'admin/sections') selected
                  @elseif (request()->routeIs('admin.additional_sections')) selected
                  @elseif (request()->routeIs('admin.additional_section.create')) selected
                  @elseif (request()->routeIs('admin.additional_section.edit')) selected
                  @elseif(request()->routeIs('admin.additional_sections')) selected
                  @elseif(request()->routeIs('admin.additional_section.create')) selected
                  @elseif (request()->routeIs('admin.additional_section.edit')) selected @endif
                  ">
                  <a data-toggle="collapse" href="#homepage"
                    aria-expanded="@if (request()->path() == 'admin/features') true
                          @elseif(request()->routeIs('admin.herosection.imgtext')) true
                          @elseif(request()->is('admin/feature/*/edit')) true
                          @elseif(request()->is('admin/process')) true
                          @elseif(request()->is('admin/process/*/edit')) true
                          @elseif(request()->path() == 'admin/testimonials') true
                          @elseif(request()->is('admin/testimonial/*/edit')) true
                          @elseif(request()->path() == 'admin/special/section') true
                          @elseif(request()->path() == 'admin/home-page-text-section') true
                          @elseif(request()->path() == 'admin/partners') true
                          @elseif(request()->is('admin/partner/*/edit')) true
                          @elseif(request()->path() == 'admin/sections') true
                          @elseif (request()->routeIs('admin.additional_sections')) true
                          @elseif (request()->routeIs('admin.additional_section.create')) true
                          @elseif (request()->routeIs('admin.additional_section.edit')) true
                          @elseif(request()->routeIs('admin.additional_sections')) true
                          @elseif(request()->routeIs('admin.additional_section.create')) true
                          @elseif (request()->routeIs('admin.additional_section.edit')) true @else false @endif">
                    <span class="sub-item">{{ __('Home Page') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse
                    @if (request()->path() == 'admin/features') show
                    @elseif(request()->routeIs('admin.herosection.imgtext')) show
                    @elseif(request()->is('admin/feature/*/edit')) show
                    @elseif(request()->is('admin/process')) show
                    @elseif(request()->is('admin/process/*/edit')) show
                    @elseif(request()->path() == 'admin/testimonials') show
                    @elseif(request()->is('admin/testimonial/*/edit')) show
                    @elseif(request()->path() == 'admin/special/section') show
                    @elseif(request()->path() == 'admin/home-page-text-section') show
                    @elseif(request()->path() == 'admin/partners') show
                    @elseif(request()->is('admin/partner/*/edit')) show
                    @elseif(request()->path() == 'admin/sections') show
                    @elseif (request()->routeIs('admin.additional_sections')) show
                    @elseif (request()->routeIs('admin.additional_section.create')) show
                    @elseif (request()->routeIs('admin.additional_section.edit')) show
                    @elseif(request()->routeIs('admin.additional_sections')) show
                    @elseif(request()->routeIs('admin.additional_section.create')) show
                    @elseif (request()->routeIs('admin.additional_section.edit')) show @endif"
                    id="homepage">
                    <ul class="nav nav-collapse subnav">
                      <li class="@if (request()->routeIs('admin.herosection.imgtext')) active @endif">
                        <a href="{{ route('admin.herosection.imgtext') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Images & Texts') }}</span>
                        </a>
                      </li>

                      <li
                        class="
                        @if (request()->path() == 'admin/partners') active
                        @elseif(request()->is('admin/partner/*/edit')) active @endif">
                        <a href="{{ route('admin.partner.index') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Partners') }}</span>
                        </a>
                      </li>

                      <li
                        class="
                          @if (request()->path() == 'admin/process') active
                          @elseif(request()->is('admin/process/*/edit')) active @endif">
                        <a href="{{ route('admin.process.index') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Work Process') }}</span>
                        </a>
                      </li>

                      <li
                        class="
                          @if (request()->path() == 'admin/features') active
                          @elseif(request()->is('admin/feature/*/edit')) active @endif">
                        <a href="{{ route('admin.feature.index') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Features') }}</span>
                        </a>
                      </li>

                      <li
                        class="
                          @if (request()->path() == 'admin/testimonials') active
                          @elseif(request()->is('admin/testimonial/*/edit')) active @endif">
                        <a href="{{ route('admin.testimonial.index') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Testimonials') }}</span>
                        </a>
                      </li>

                      <!-- additional sections -->
                      <li class="submenu">
                        <a data-toggle="collapse" href="#hoem-addi-section"
                          aria-expanded="{{ request()->routeIs('admin.additional_sections') ||
                          request()->routeIs('admin.additional_section.create') ||
                          request()->routeIs('admin.additional_section.edit')
                              ? 'true'
                              : 'false' }}">
                          <span class="sub-item">{{ __('Additional Sections') }}</span>
                          <span class="caret"></span>
                        </a>
                        <div id="hoem-addi-section"
                          class="collapse
                            @if (request()->routeIs('admin.additional_sections') ||
                                    request()->routeIs('admin.additional_section.create') ||
                                    request()->routeIs('admin.additional_section.edit')) show @endif pl-3">
                          <ul class="nav nav-collapse subnav">
                            <li class="{{ request()->routeIs('admin.additional_section.create') ? 'active' : '' }}">
                              <a href="{{ route('admin.additional_section.create') }}">
                                <span class="sub-item">{{ __('Add Section') }}</span>
                              </a>
                            </li>
                            <li
                              class="{{ request()->routeIs('admin.additional_sections') || request()->routeIs('admin.additional_section.edit') ? 'active' : '' }}">
                              <a href="{{ route('admin.additional_sections', ['language' => $default->code]) }}">
                                <span class="sub-item">{{ __('Sections') }}
                                </span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>
                      <li class="
                                @if (request()->path() == 'admin/sections') active @endif">
                        <a href="{{ route('admin.sections.index') }}">
                          <span class="sub-item">{{ __('Section Hide/Show') }}</span>
                        </a>
                      </li>

                    </ul>
                  </div>
                </li>

                <li
                  class="submenu
                    @if (request()->routeIs('admin.home_page.counter-section')) selected
                    @elseif (request()->routeIs('admin.abouts.section.hide_show')) selected
                    @elseif (request()->routeIs('admin.about_us.additional_sections')) selected
                    @elseif (request()->routeIs('admin.about_us.additional_section.create')) selected
                    @elseif (request()->routeIs('admin.about_us.additional_section.edit')) selected @endif">
                  <a data-toggle="collapse" href="#aboutUs_section"
                    aria-expanded="
                    @if (request()->routeIs('admin.home_page.counter-section')) true
                      @else false @endif
                    ">
                    <span class="sub-item">{{ __('About Us') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse
                      @if (request()->routeIs('admin.home_page.counter-section')) show
                      @elseif (request()->routeIs('admin.abouts.section.hide_show')) show
                      @elseif (request()->routeIs('admin.about_us.additional_sections')) show
                      @elseif (request()->routeIs('admin.about_us.additional_section.create')) show
                      @elseif (request()->routeIs('admin.about_us.additional_section.edit')) show @endif"
                    id="aboutUs_section">
                    <ul class="nav nav-collapse subnav">
                      <li class="@if (request()->routeIs('admin.home_page.counter-section')) active @endif">
                        <a href="{{ route('admin.home_page.counter-section') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Counter Section') }}</span>
                        </a>
                      </li>
                      <li class="submenu">
                        <a data-toggle="collapse" href="#about-addition-section"
                          aria-expanded="{{ request()->routeIs('admin.about_us.additional_sections') ||
                          request()->routeIs('admin.about_us.additional_section.create') ||
                          request()->routeIs('admin.about_us.additional_section.edit')
                              ? 'true'
                              : 'false' }}">
                          <span class="sub-item">{{ __('Additional Sections') }}</span>
                          <span class="caret"></span>
                        </a>
                        <div id="about-addition-section"
                          class="collapse
                            @if (request()->routeIs('admin.about_us.additional_sections') ||
                                    request()->routeIs('admin.about_us.additional_section.create') ||
                                    request()->routeIs('admin.about_us.additional_section.edit')) show @endif pl-3">
                          <ul class="nav nav-collapse subnav">
                            <li
                              class="{{ request()->routeIs('admin.about_us.additional_section.create') ? 'active' : '' }}">
                              <a href="{{ route('admin.about_us.additional_section.create') }}">
                                <span class="sub-item">{{ __('Add Section') }}</span>
                              </a>
                            </li>
                            <li
                              class="{{ request()->routeIs('admin.about_us.additional_sections') || request()->routeIs('admin.about_us.additional_section.edit') ? 'active' : '' }}">
                              <a
                                href="{{ route('admin.about_us.additional_sections', ['language' => $default->code]) }}">
                                <span class="sub-item">{{ __('Sections') }}
                                </span>
                              </a>
                            </li>
                          </ul>
                        </div>
                      </li>
                      <li class="@if (request()->routeIs('admin.abouts.section.hide_show')) active @endif">
                        <a href="{{ route('admin.abouts.section.hide_show') }}">
                          <span class="sub-item">{{ __('Section Hide/Show') }}</span>
                        </a>
                      </li>

                    </ul>
                  </div>
                </li>

                <li
                  class="submenu
                      @if (request()->path() == 'admin/bcategorys') selected
                          @elseif(request()->routeIs('admin.bcategory.edit')) selected
                      @elseif (request()->path() == 'admin/blog') selected
                      @elseif (request()->is('admin/blog/*/edit')) selected @endif">
                  <a data-toggle="collapse" href="#blog_sidebar"
                    aria-expanded="{{ request()->path() == 'admin/bcategorys' || request()->routeIs('admin.bcategory.edit') || request()->path() == 'admin/blog' || request()->is('admin/blog/*/edit') ? 'true' : 'false' }}">
                    <span class="sub-item">{{ __('Blogs') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse @if (request()->path() == 'admin/bcategorys') show
                      @elseif(request()->path() == 'admin/blog')
                      @elseif(request()->routeIs('admin.bcategory.edit'))
                    show
                    @elseif(request()->is('admin/blog/*/edit'))
                    show @endif"
                    id="blog_sidebar">
                    <ul class="nav nav-collapse subnav">
                      <li
                        class="
                      @if (request()->path() == 'admin/bcategorys') active @endif
                      @if (request()->routeIs('admin.bcategory.edit')) active @endif
                      ">
                        <a href="{{ route('admin.bcategory.index') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Category') }}</span>
                        </a>
                      </li>
                      <li
                        class="@if (request()->path() == 'admin/blog') active
                          @elseif(request()->is('admin/blog/*/edit')) active @endif">
                        <a href="{{ route('admin.blog.index') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Posts') }}</span>
                        </a>
                      </li>

                    </ul>
                  </div>
                </li>


                {{-- FAQ Management --}}
                @if (empty($admin->role) || (!empty($permissions) && in_array('FAQ Management', $permissions)))
                  <li class="@if (request()->routeIs('admin.faq.index')) active @endif">
                    <a href="{{ route('admin.faq.index') . '?language=' . $default->code }}">
                      <span class="sub-item">{{ __('FAQs') }}</span>
                    </a>
                  </li>
                @endif

                <li class="@if (request()->routeIs('admin.contact.index')) active @endif">
                  <a href="{{ route('admin.contact.index') . '?language=' . $default->code }}">
                    <span class="sub-item">{{ __('Contact Page') }}</span>
                  </a>
                </li>

                <li
                  class="submenu
                  @if (request()->routeIs('admin.page.create')) selected
                    @elseif(request()->routeIs('admin.page.index')) selected
                    @elseif(request()->routeIs('admin.page.edit'))
                    selected @endif">
                  <a data-toggle="collapse" href="#addition_page"
                    aria-expanded="
                    @if (request()->routeIs('admin.page.create')) true
                    @elseif(request()->routeIs('admin.page.index')) true
                    @elseif(request()->routeIs('admin.page.edit'))
                    true @else false @endif
                    ">
                    <span class="sub-item">{{ __('Additional Pages') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse @if (request()->routeIs('admin.page.create')) show
                    @elseif(request()->routeIs('admin.page.index')) show
                    @elseif(request()->routeIs('admin.page.edit'))
                    show @endif"
                    id="addition_page">
                    <ul class="nav nav-collapse subnav">
                      <li class="@if (request()->routeIs('admin.page.create')) active @endif">
                        <a href="{{ route('admin.page.create') }}">
                          <span class="sub-item">{{ __('Add Page') }}</span>
                        </a>
                      </li>

                      <li
                        class="
                        @if (request()->routeIs('admin.page.index')) active
                        @elseif(request()->routeIs('admin.page.edit')) active @endif">
                        <a href="{{ route('admin.page.index') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('All Pages') }}</span>
                        </a>
                      </li>

                    </ul>
                  </div>
                </li>

                {{-- Footer --}}
                <li
                  class="submenu
                  @if (request()->path() == 'admin/footers') selected
                  @elseif(request()->path() == 'admin/ulinks') selected @endif">
                  <a data-toggle="collapse" href="#footer-sidebar"
                    aria-expanded="
                    @if (request()->path() == 'admin/footers') true
                    @elseif(request()->path() == 'admin/ulinks') true
                    @else false @endif">
                    <span class="sub-item">{{ __('Footer') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse
                    @if (request()->path() == 'admin/footers') show
                    @elseif(request()->path() == 'admin/ulinks') show @endif"
                    id="footer-sidebar">
                    <ul class="nav nav-collapse subnav">
                      <li class="@if (request()->path() == 'admin/footers') active @endif">
                        <a href="{{ route('admin.footer.index') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Logo & Text') }}</span>
                        </a>
                      </li>
                      <li class="@if (request()->path() == 'admin/ulinks') active @endif">
                        <a href="{{ route('admin.ulink.index') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Useful Links') }}</span>
                        </a>
                      </li>

                    </ul>
                  </div>
                </li>

                <li
                  class="submenu
                  @if (request()->routeIs('admin.breadcrumb')) selected
                    @elseif(request()->routeIs('admin.breadcrumb.heading')) selected @endif">
                  <a data-toggle="collapse" href="#Breadcrumbs"
                    aria-expanded="
                    @if (request()->routeIs('admin.breadcrumb')) true
                    @elseif(request()->routeIs('admin.breadcrumb.heading')) true @else false @endif
                    ">
                    <span class="sub-item">{{ __('Breadcrumbs') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse
                    @if (request()->routeIs('admin.breadcrumb')) show
                    @elseif(request()->routeIs('admin.breadcrumb.heading')) show @endif
                    "
                    id="Breadcrumbs">
                    <ul class="nav nav-collapse subnav">
                      <li class="@if (request()->routeIs('admin.breadcrumb')) active @endif">
                        <a href="{{ route('admin.breadcrumb') }}">
                          <span class="sub-item">{{ __('Image') }}</span>
                        </a>
                      </li>

                      <li class="@if (request()->routeIs('admin.breadcrumb.heading')) active @endif">
                        <a href="{{ route('admin.breadcrumb.heading') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Headings') }}</span>
                        </a>
                      </li>

                    </ul>
                  </div>
                </li>

                {{-- <li class="@if (request()->path() == 'admin/breadcrumb') active @endif">
                  <a href="{{ route('admin.breadcrumb') }}">
                    <span class="sub-item">{{ __('Breadcrumb') }}</span>
                  </a>
                </li> --}}

                <li class="@if (request()->path() == 'admin/seo') active @endif">
                  <a href="{{ route('admin.seo', ['language' => $default->code]) }}">
                    <span class="sub-item">{{ __('SEO Information') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- Announcement Popup --}}
        @if (empty($admin->role) || (!empty($permissions) && in_array('Announcement Popup', $permissions)))
          <li
            class="nav-item
                    @if (request()->path() == 'admin/popup/create') active
                    @elseif(request()->path() == 'admin/popup/types') active
                    @elseif(request()->is('admin/popup/*/edit')) active
                    @elseif(request()->path() == 'admin/popups') active @endif">
            <a data-toggle="collapse" href="#announcementPopup">
              <i class="fas fa-bullhorn"></i>
              <p>{{ __('Announcement Popup') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
                        @if (request()->path() == 'admin/popup/create') show
                        @elseif(request()->path() == 'admin/popup/types') show
                        @elseif(request()->path() == 'admin/popups') show
                        @elseif(request()->is('admin/popup/*/edit')) show @endif"
              id="announcementPopup">
              <ul class="nav nav-collapse">
                <li
                  class="@if (request()->path() == 'admin/popup/types') active
                                @elseif(request()->path() == 'admin/popup/create') active @endif">
                  <a href="{{ route('admin.popup.types') }}">
                    <span class="sub-item">{{ __('Add Popup') }}</span>
                  </a>
                </li>
                <li
                  class="@if (request()->path() == 'admin/popups') active
                                @elseif(request()->is('admin/popup/*/edit')) active @endif">
                  <a href="{{ route('admin.popup.index') . '?language=' . $default->code }}">
                    <span class="sub-item">{{ __('Popups') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif


        @if (empty($admin->role) || (!empty($permissions) && in_array('Push Notification', $permissions)))
          {{-- Push Notification --}}
          <li
            class="nav-item
                  @if (request()->path() == 'admin/pushnotification/settings') active
                  @elseif(request()->path() == 'admin/pushnotification/send') active @endif">
            <a data-toggle="collapse" href="#pushNotification">
              <i class="far fa-bell"></i>
              <p>{{ __('Push Notification') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
                    @if (request()->path() == 'admin/pushnotification/settings') show
                    @elseif(request()->path() == 'admin/pushnotification/send') show @endif"
              id="pushNotification">
              <ul class="nav nav-collapse">
                <li class="@if (request()->path() == 'admin/pushnotification/settings') active @endif">
                  <a href="{{ route('admin.pushnotification.settings') }}">
                    <span class="sub-item">{{ __('Settings') }}</span>
                  </a>
                </li>
                <li class="@if (request()->path() == 'admin/pushnotification/send') active @endif">
                  <a href="{{ route('admin.pushnotification.send') }}">
                    <span class="sub-item">{{ __('Send Notification') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Settings', $permissions)))
          {{-- Basic Settings --}}
          <li
            class="nav-item
              @if (request()->path() == 'admin/general-settings') active
              @elseif(request()->path() == 'admin/social') active
              @elseif(request()->is('admin/social/*')) active
              @elseif(request()->path() == 'admin/heading') active
              @elseif(request()->path() == 'admin/script') active
              @elseif(request()->path() == 'admin/maintainance') active
              @elseif(request()->path() == 'admin/cookie-alert') active
              @elseif(request()->path() == 'admin/mail-from-admin') active
              @elseif(request()->path() == 'admin/mail-to-admin') active
              @elseif(request()->routeIs('admin.product.tags')) active
              @elseif (request()->routeIs('admin.mail_templates')) active
              @elseif (request()->routeIs('admin.edit_mail_template')) active
              @elseif (request()->path() == 'admin/gateways') active
              @elseif(request()->path() == 'admin/offline/gateways') active
              @elseif(request()->routeIs('admin.language.index')) active
              @elseif(request()->routeIs('admin.language.edit')) active
              @elseif(request()->routeIs('admin.language.editKeyword')) active
              @elseif(request()->routeIs('admin.language.admin_dashboard.editKeyword')) active
              @elseif(request()->routeIs('admin.language.user_dashboard.editKeyword')) active
              @elseif(request()->routeIs('admin.language.user_frontend.editKeyword')) active @endif">
            <a data-toggle="collapse" href="#basic">
              <i class="la flaticon-settings"></i>
              <p>{{ __('Settings') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
                @if (request()->path() == 'admin/general-settings') show
                @elseif(request()->path() == 'admin/social') show
                @elseif(request()->is('admin/social/*')) show
                @elseif(request()->path() == 'admin/heading') show
                @elseif(request()->path() == 'admin/script') show
                @elseif(request()->path() == 'admin/maintainance') show
                @elseif(request()->path() == 'admin/cookie-alert') show
                @elseif(request()->path() == 'admin/mail-from-admin') show
                @elseif(request()->path() == 'admin/mail-to-admin') show
                @elseif(request()->routeIs('admin.product.tags')) show
                @elseif (request()->routeIs('admin.mail_to_admin')) show
                @elseif (request()->routeIs('admin.mail_templates')) show
                @elseif (request()->routeIs('admin.edit_mail_template')) show
                @elseif (request()->path() == 'admin/gateways') show
                @elseif(request()->path() == 'admin/offline/gateways') show
                @elseif(request()->routeIs('admin.language.index')) show
                @elseif(request()->routeIs('admin.language.edit')) show
                @elseif(request()->routeIs('admin.language.editKeyword')) show
                @elseif(request()->routeIs('admin.language.admin_dashboard.editKeyword')) show
              @elseif(request()->routeIs('admin.language.user_dashboard.editKeyword')) show
              @elseif(request()->routeIs('admin.language.user_frontend.editKeyword')) show @endif"
              id="basic">
              <ul class="nav nav-collapse">
                <li class="@if (request()->path() == 'admin/general-settings') active @endif">
                  <a href="{{ route('admin.general-settings') }}">
                    <span class="sub-item">{{ __('General Settings') }}</span>
                  </a>
                </li>

                <li
                  class="submenu
                    @if (request()->routeIs('admin.mail_from_admin')) selected
                    @elseif (request()->routeIs('admin.mail_to_admin')) selected
                    @elseif (request()->routeIs('admin.mail_templates')) selected
                    @elseif (request()->routeIs('admin.edit_mail_template')) selected @endif">
                  <a data-toggle="collapse" href="#emailset"
                    aria-expanded="{{ request()->path() == 'admin/mail-from-admin' || request()->path() == 'admin/mail-to-admin' || request()->routeIs('admin.mail_templates') || request()->routeIs('admin.edit_mail_template') ? 'true' : 'false' }}">
                    <span class="sub-item">{{ __('Email Settings') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse {{ request()->path() == 'admin/mail-from-admin' || request()->path() == 'admin/mail-to-admin' || request()->routeIs('admin.mail_templates') || request()->routeIs('admin.edit_mail_template') ? 'show' : '' }}"
                    id="emailset">
                    <ul class="nav nav-collapse subnav">
                      <li class="@if (request()->path() == 'admin/mail-from-admin') active @endif">
                        <a href="{{ route('admin.mailFromAdmin') }}">
                          <span class="sub-item">{{ __('Mail from Admin') }}</span>
                        </a>
                      </li>
                      <li class="@if (request()->path() == 'admin/mail-to-admin') active @endif">
                        <a href="{{ route('admin.mailToAdmin') }}">
                          <span class="sub-item">{{ __('Mail to Admin') }}</span>
                        </a>
                      </li>
                      <li
                        class="
                        @if (request()->routeIs('admin.mail_templates')) active
                        @elseif (request()->routeIs('admin.edit_mail_template')) active @endif">
                        <a href="{{ route('admin.mail_templates') }}">
                          <span class="sub-item">{{ __('Mail Templates') }}</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>

                <li
                  class="submenu
                    @if (request()->path() == 'admin/gateways') selected
                    @elseif(request()->path() == 'admin/offline/gateways') selected @endif
                    ">
                  <a data-toggle="collapse" href="#payment-gateways"
                    aria-expanded="@if (request()->path() == 'admin/gateways') true
                    @elseif(request()->path() == 'admin/offline/gateways') true @else false @endif">
                    <span class="sub-item">{{ __('Payment Gateways') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse
                      @if (request()->path() == 'admin/gateways') show
                    @elseif(request()->path() == 'admin/offline/gateways') show @endif
                      "
                    id="payment-gateways">
                    <ul class="nav nav-collapse subnav">
                      <li class="@if (request()->path() == 'admin/gateways') active @endif">
                        <a href="{{ route('admin.gateway.index') }}">
                          <span class="sub-item">{{ __('Online Gateways') }}</span>
                        </a>
                      </li>
                      <li class="@if (request()->path() == 'admin/offline/gateways') active @endif">
                        <a href="{{ route('admin.gateway.offline') . '?language=' . $default->code }}">
                          <span class="sub-item">{{ __('Offline Gateways') }}</span>
                        </a>
                      </li>

                    </ul>
                  </div>
                </li>

                <li
                  class="
                    @if (request()->path() == 'admin/languages') active
                    @elseif(request()->is('admin/language/*/edit')) active
                    @elseif(request()->is('admin/language/*/edit/keyword')) active
                    @elseif(request()->routeIs('admin.language.admin_dashboard.editKeyword')) active
              @elseif(request()->routeIs('admin.language.user_dashboard.editKeyword')) active
              @elseif(request()->routeIs('admin.language.user_frontend.editKeyword')) active @endif">
                  <a href="{{ route('admin.language.index') }}">
                    <span class="sub-item">{{ __('Languages') }}</span>
                  </a>
                </li>

                <li class="@if (request()->path() == 'admin/script') active @endif">
                  <a href="{{ route('admin.script') }}">
                    <span class="sub-item">{{ __('Plugins') }}</span>
                  </a>
                </li>

                <li class="@if (request()->path() == 'admin/maintainance') active @endif">
                  <a href="{{ route('admin.maintainance') }}">
                    <span class="sub-item">{{ __('Maintenance Mode') }}</span>
                  </a>
                </li>
                <li class="@if (request()->path() == 'admin/cookie-alert') active @endif">
                  <a href="{{ route('admin.cookie.alert') . '?language=' . $default->code }}">
                    <span class="sub-item">{{ __('Cookie Alert') }}</span>
                  </a>
                </li>

                <li
                  class="@if (request()->path() == 'admin/social') active
                                @elseif(request()->is('admin/social/*')) active @endif">
                  <a href="{{ route('admin.social.index') }}">
                    <span class="sub-item">{{ __('Social Links') }}</span>
                  </a>
                </li>

              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Admins Management', $permissions)))
          {{-- Admins Management Page --}}

          <li
            class="nav-item
              @if (request()->path() == 'admin/users') active
              @elseif(request()->is('admin/user/*/edit')) active
              @elseif (request()->path() == 'admin/roles') active
              @elseif(request()->is('admin/role/*/permissions/manage')) active @endif">
            <a data-toggle="collapse" href="#admins_management">
              <i class="fas fa-users-cog"></i>
              <p>{{ __('Admins Management') }}</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
                @if (request()->path() == 'admin/users') show
                @elseif(request()->is('admin/user/*/edit')) show
                @elseif (request()->path() == 'admin/roles') show
                @elseif(request()->is('admin/role/*/permissions/manage')) show @endif"
              id="admins_management">
              <ul class="nav nav-collapse">

                <li
                  class="@if (request()->path() == 'admin/roles') active
                  @elseif(request()->is('admin/role/*/permissions/manage')) active @endif">
                  <a href="{{ route('admin.role.index') }}">
                    <span class="sub-item">{{ __('Role & Permissions') }}</span>
                  </a>
                </li>

                <li
                  class="@if (request()->path() == 'admin/users') active
                    @elseif(request()->is('admin/user/*/edit')) active @endif">
                  <a href="{{ route('admin.user.index') }}">
                    <span class="sub-item">{{ __('Registerd Admins') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Sitemaps', $permissions)))
          {{-- Sitemap --}}
          <li class="nav-item
            @if (request()->path() == 'admin/sitemap') active @endif">
            <a href="{{ route('admin.sitemap.index') . '?language=' . $default->code }}">
              <i class="fa fa-sitemap"></i>
              <p>{{ __('Sitemaps') }}</p>
            </a>
          </li>
        @endif

        {{-- Cache Clear --}}
        <li class="nav-item">
          <a href="{{ route('admin.cache.clear') }}">
            <i class="la flaticon-close"></i>
            <p>{{ __('Clear Cache') }}</p>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
