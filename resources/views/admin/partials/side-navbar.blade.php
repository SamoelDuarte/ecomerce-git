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
        {{-- Dashboard --}}
        <li class="nav-item @if (request()->path() == 'admin/ecommerce') active @endif">
          <a href="{{ route('admin.ecommerce') }}">
            <i class="la flaticon-paint-palette"></i>
            <p>{{ __('Ecommerce') }}</p>
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

              <li class="@if (request()->routeIs('admin.subscriber.index')) active @endif" style="display:none">
                <a href="{{ route('admin.subscriber.index') }}">
                  <span class="sub-item">{{ __('Subscribers') }}</span>
                </a>
              </li>
              <li class="@if (request()->routeIs('admin.mailsubscriber')) active @endif" style="display:none">
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
                  <span class="sub-item">Configuração de e-mail e notificação</span>
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
                        <span class="sub-item">Configuração de e-mail</span>
                      </a>
                    </li>
                    <li class="@if (request()->path() == 'admin/mail-to-admin') active @endif">
                      <a href="{{ route('admin.mailToAdmin') }}">
                        <span class="sub-item">Notificação de e-mail e telefone</span>
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