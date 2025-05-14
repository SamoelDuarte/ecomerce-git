@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Roles & Permissions') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Admins Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Roles & Permissions') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Permissions Management') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">"{{ $role->name }}" - {{ __('Permissions Management') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.role.index') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 m-auto">
              <form id="permissionsForm" class="" action="{{ route('admin.role.permissions.update') }}"
                method="post">
                {{ csrf_field() }}
                <input type="hidden" name="role_id" value="{{ Request::route('id') }}">

                @php
                  $permissions = $role->permissions;
                  if (!empty($role->permissions)) {
                      $permissions = json_decode($permissions, true);
                  }
                @endphp

                <div class="form-group">
                  <label for="">{{ __('Permissions') . ':' }} </label>
                  <div class="selectgroup selectgroup-pills mt-2">
                    <label class="selectgroup-item">
                      <input type="hidden" name="permissions[]" value="Dashboard" class="selectgroup-input">
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Package Management" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Package Management', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Package Management') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Payment Logs" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Payment Logs', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Payment Logs') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Custom Domains" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Custom Domains', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Custom Domains') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Subdomains" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Subdomains', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Subdomains') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Menu Builder" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Menu Builder', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Menu Builder') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Pages" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Pages', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Pages') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Announcement Popup" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Announcement Popup', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Announcement Popup') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Push Notification" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Push Notification', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Push Notification') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Users Management" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Users Management', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Users Management') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Settings" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Settings', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Settings') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Admins Management" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Admins Management', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Admins Management') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" name="permissions[]" value="Sitemaps" class="selectgroup-input"
                        @if (is_array($permissions) && in_array('Sitemaps', $permissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Sitemaps') }}</span>
                    </label>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" id="permissionBtn" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
