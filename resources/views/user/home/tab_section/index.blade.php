@extends('user.layout')

@php
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where(
      'user_id',
      \Illuminate\Support\Facades\Auth::guard('web')->user()->id,
  )->get();
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Item') }}
      {{ $userBs->theme === 'manti' ? __('Section') : __('Tab') }}</h4>
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
        <a href="#">{{ __('Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Item') . ' ' }}
          {{ $userBs->theme === 'manti' ? __('Section') : __('Tab') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Item') . ' ' }}
                {{ $userBs->theme === 'manti' ? __('Section') : __('Tab') }}
              </div>
            </div>
            <div class="col-lg-3">
              @if (!is_null($userDefaultLang))
                @if (!empty($userLanguages))
                  <select name="userLanguage" class="form-control"
                    onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                    <option value="" selected disabled>
                      {{ __('Select a Language') }}</option>
                    @foreach ($userLanguages as $lang)
                      <option value="{{ $lang->code }}"
                        {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                        {{ $lang->name }}</option>
                    @endforeach
                  </select>
                @endif
              @endif
            </div>
            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add') }}
                {{ $userBs->theme === 'manti' ? __('Section') : __('Tab') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('user.tab.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">

            <div class="col-lg-12">
              @if (count($tabs) == 0)
                <h3 class="text-center">{{ __('NO ITEM TAB FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Products') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($tabs as $key => $tab)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $tab->id }}">
                          </td>

                          <td>{{ convertUtf8($tab->name) }}</td>

                          <td class="">
                            <a class="btn btn-secondary btn-sm"
                              href="{{ route('user.tab.products', $tab->id) . '?language=' . request()->input('language') }}">
                              <span class="btn-label">
                                {{ __('Manage') }}
                              </span>
                            </a>
                          </td>

                          <td>
                            @if ($tab->status == 1)
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active') }}</span>
                              </h2>
                            @else
                              <h2 class="d-inline-block"><span class="badge badge-danger">{{ __('Deactive') }}</span>
                              </h2>
                            @endif
                          </td>
                          <td>{{ $tab->serial_number }}</td>
                          <td>

                            <a class="btn btn-secondary btn-sm editbtn mb-1" href="#" data-id="{{ $tab->id }}"
                              data-name="{{ $tab->name }}" data-status="{{ $tab->status }}"
                              data-serial_number = "{{ $tab->serial_number }}" data-toggle="modal"
                              data-target="#editModal">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>


                            <form class="deleteform d-inline-block" action="{{ route('user.tab.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="tab_id" value="{{ $tab->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn  mb-1">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
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
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $tabs->appends(['language' => request()->input('language')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('user.home.tab_section.create')
  @include('user.home.tab_section.edit')
@endsection
