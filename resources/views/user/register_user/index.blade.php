@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Registered Customers') }}
    </h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('user-dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Customers') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-6">
              <div class="card-title">
                {{ __('Registered Customers') }}
              </div>
            </div>
            <div class="col-lg-6 mt-2 mt-lg-0">
              <button
                class="btn btn-danger {{ $dashboard_language->rtl == 1 ? 'float-lg-left mr-2' : 'float-lg-right ml-2' }} float-none btn-sm  mt-1 d-none bulk-delete"
                data-href="{{ route('user.register.user.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
              <button
                class="btn btn-primary {{ $dashboard_language->rtl == 1 ? 'float-lg-left mr-2' : 'float-lg-right ml-2' }} float-none btn-sm  mt-1"
                data-toggle="modal" data-target="#addUserModal"><i class="fas fa-plus"></i>
                {{ __('Add Customer') }}</button>
              <form action="{{ url()->full() }}"
                class="{{ $dashboard_language->rtl == 1 ? 'float-lg-left mr-2' : 'float-lg-right' }} float-none">
                <input type="text" name="term" class="form-control min-w-250"
                  value="{{ request()->input('term') }}" placeholder="{{ __('Search by Username/Email') }}">
              </form>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($users) == 0)
                <h3 class="text-center">{{ __('NO USER FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Email Status') }}</th>
                        <th scope="col">{{ __('Account') }}</th>
                        <td scope="col">{{ __('Actions') }}</td>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($users as $key => $user)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $user->id }}">
                          </td>
                          <td>{{ $user->username }}</td>
                          <td>{{ $user->email }}</td>

                          <td>
                            <form id="emailForm{{ $user->id }}" class="d-inline-block"
                              action="{{ route('user.register.user.email') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ strtolower($user->email_verified) == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="email_verified"
                                onchange="document.getElementById('emailForm{{ $user->id }}').submit();">
                                <option value="1" {{ strtolower($user->email_verified) == 1 ? 'selected' : '' }}>
                                  {{ __('Verified') }}</option>
                                <option value="0" {{ strtolower($user->email_verified) == 0 ? 'selected' : '' }}>
                                  {{ __('Unverified') }}</option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                          </td>

                          <td>
                            <form id="statusForm{{ $user->id }}" class="d-inline-block"
                              action="{{ route('user.register.user.ban') }}" method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="status"
                                onchange="document.getElementById('statusForm{{ $user->id }}').submit();">
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                              <input type="hidden" name="user_id" value="{{ $user->id }}">
                            </form>
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Actions') }}
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item"
                                  href="{{ route('user.register.user.view', $user->id) }}">{{ __('Details') }}</a>
                                <a class="dropdown-item"
                                  href="{{ route('user.register.user.changePass', $user->id) }}">{{ __('Change Password') }}</a>
                                <form class="deleteform d-block" action="{{ route('user.register.user.delete') }}"
                                  method="post">
                                  @csrf
                                  <input type="hidden" name="user_id" value="{{ $user->id }}">
                                  <button type="submit" class="deletebtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>

                                <a class="dropdown-item" target="_blank"
                                  href="{{ route('user.register.user.secret_login', $user->id) }}">{{ __('Secret Login') }}</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $users->appends(['term' => request()->input('term')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Customer') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('user.register.user.store') }}" method="POST" id="ajaxForm">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Username') }} <span class="text-danger">**</span></label>
              <input class="form-control" type="text" name="username">
              <p id="errusername" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Email') }} <span class="text-danger">**</span></label>
              <input class="form-control" type="email" name="email">
              <p id="erremail" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Password') }} <span class="text-danger">**</span></label>
              <input class="form-control" type="password" name="password">
              <p id="errpassword" class="text-danger mb-0 em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Confirm Password') }} <span class="text-danger">**</span></label>
              <input class="form-control" type="password" name="password_confirmation">
            </div>

          </form>
        </div>
        <div class="modal-footer text-center">
          <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Add User') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
