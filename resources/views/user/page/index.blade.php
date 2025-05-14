@extends('user.layout')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-iconpicker.min.css') }}">
@endsection
@php
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();

  $user = Auth::guard('web')->user();
  $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($user->id);
  if (!empty($user)) {
      $permissions = \App\Http\Helpers\UserPermissionHelper::packagePermission($user->id);
      $permissions = json_decode($permissions, true);
  }
@endphp

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('All Pages') }}</h4>
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
        <a href="#">{{ __('Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Additional Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Pages') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('All Pages') }}</div>
            </div>
            <div class="col-lg-3">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>
                    {{ __('Select a Language') }}</option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('user.page.create') }}"
                class="btn btn-primary {{ $dashboard_language->rtl == 1 ? 'float-lg-left float-right' : 'float-lg-right float-left' }} btn-sm"><i
                  class="fas fa-plus"></i> {{ __('Add Page') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('user.page.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($apages) == 0)
                <h2 class="text-center">{{ __('NO PAGE FOUND') }}</h2>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('URL') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($apages as $key => $apage)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $apage->id }}">
                          </td>
                          <td>{{ $apage->title }}</td>
                          <td><a
                              href="{{ asset('/') . Auth::guard('web')->user()->username . '/p' . '/' . $apage->slug }}"
                              target="_blank">{{ asset('/') . Auth::guard('web')->user()->username . '/p' . '/' . $apage->slug }}</a>
                          </td>
                          <td>
                            @if ($apage->status == 1)
                              <span class="badge badge-success">{{ __('Active') }}</span>
                            @elseif ($apage->status == 0)
                              <span class="badge badge-danger">{{ __('Deactive') }}</span>
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mb-1" href="{{ route('user.page.edit', $apage->id) }}">
                              <i class="fas fa-edit"></i>
                            </a>
                            <form class="d-inline-block deleteform  " action="{{ route('user.page.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="pageid" value="{{ $apage->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn mb-1">
                                <i class="fas fa-trash"></i>
                              </button>
                            </form>
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
      </div>
    </div>
  </div>

@endsection
