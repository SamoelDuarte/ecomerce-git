@extends('user.layout')

@php
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@includeIf('user.partials.rtl-style')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Labels') }}</h4>
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
        <a href="#">{{ __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Labels') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Labels') }}</div>
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
                data-target="#createModal"><i class="fas fa-plus"></i>
                {{ __('Add Label') }}</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">

              @if (count($labels) == 0)
                <h3 class="text-center">{{ __('NO PRODUCT LABEL FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($labels as $key => $label)
                        <tr>
                          <td>{{ $label->name }}</td>
                          <td>
                            <span
                              class="badge {{ $label->status == 1 ? 'badge-success' : 'badge-danger' }}">{{ $label->status == 1 ? 'Active' : 'Deactive' }}</span>
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm editbtn mb-1" href="#editModal" data-toggle="modal"
                              data-id="{{ $label->id }}" data-name="{{ $label->name }}"
                              data-status="{{ $label->status }}" data-color="{{ $label->color }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>
                            <form class="deleteform d-inline-block" action="{{ route('user.product.label.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="label_id" value="{{ $label->id }}">
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
      </div>
    </div>
  </div>

  <!-- Create Userful Link Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Label') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxForm" class="modal-form create" action="{{ route('user.product.label.store') }}" method="POST">
            @csrf

            <div class="form-group">
              <label for="">{{ __('Language') }} <span class="text-danger">**</span></label>
              <select id="language" name="user_language_id" class="form-control">
                <option value="" selected disabled>{{ __('Select a language') }}
                </option>
                @foreach ($userLanguages as $lang)
                  <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                @endforeach
              </select>
              <p id="erruser_language_id" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="name" value=""
                placeholder="{{ __('Enter name') }}">
              <input type="hidden" class="form-control" name="user_id" value="{{ $user_id }}">
              <p id="errname" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control jscolor" name="color">
              <p id="errcolor" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
              <select class="form-control" name="status">
                <option value="" selected disabled>{{ __('Enter name') }}
                </option>
                <option value="1">{{ __('Active') }}</option>
                <option value="0">{{ __('Deactive') }}</option>
              </select>
              <p id="errstatus" class="mb-0 text-danger em"></p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Userful Link Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Label') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxEditForm" action="{{ route('user.product.label.update') }}" method="POST">
            @csrf
            <input id="inid" type="hidden" name="id" value="">
            <div class="form-group">
              <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
              <input id="inname" type="text" class="form-control" name="name" value=""
                placeholder="{{ __('Enter name') }}">
              <p id="eerrname" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
              <input id="incolor" class="form-control jscolor" name="color">
              <p id="eerrcolor" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
              <select class="form-control" id="instatus" name="status">
                <option value="" selected disabled>{{ __('Enter name') }}
                </option>
                <option value="1">{{ __('Active') }}</option>
                <option value="0">{{ __('Deactive') }}</option>
              </select>
              <p id="eerrstatus" class="mb-0 text-danger em"></p>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="updateBtn" type="button" class="btn btn-primary">{{ __('Save Changes') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
