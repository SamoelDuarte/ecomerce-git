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
    <h4 class="page-title">{{ __('Features') }}</h4>
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
        <a href="#">
          @if (request()->routeIs('user.pages.about_us.features.index'))
            {{ __('About Us') }}
          @else
            {{ __('Home Page') }}
          @endif
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Features') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Features') }}</div>
            </div>
            <div class="col-lg-3">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i>
                {{ __('Add Feature') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('user.faq.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($collection) == 0)
                <h3 class="text-center">{{ __('NO STEP FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Text') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($collection as $key => $item)
                        <tr>
                          <td>
                            {{ $loop->iteration }}
                          </td>
                          <td>
                            <i class="{{ $item->icon }}"></i>
                          </td>
                          <td>{{ $item->title }}</td>
                          <td>{{ $item->text }}</td>
                          <td>
                            <a class="mb-1 btn btn-secondary btn-sm editbtn" href="#editModal" data-toggle="modal"
                              data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                              data-text="{{ $item->text }}" data-icon="{{ $item->icon }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>
                            <form class="deleteform d-inline-block"
                              action="{{ route('user.home_page.heroSec.how_it_work.delete') }}" method="post">
                              @csrf
                              <input type="hidden" name="faq_id" value="{{ $item->id }}">
                              <button type="submit" class="mb-1 btn btn-danger btn-sm deletebtn">
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


  <!-- Create Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Feature') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxForm" class="modal-form create" action="{{ route('user.home_page.heroSec.how_it_work.store') }}"
            method="POST">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Language') }} <span class="text-danger">**</span></label>
              <select name="user_language_id" class="form-control">
                <option value="" selected disabled>{{ __('Select a language') }}
                </option>
                @foreach ($userLanguages as $lang)
                  <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                @endforeach
              </select>
              <p id="erruser_language_id" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Icon') }} <span class="text-danger">**</span></label>
              <div class="btn-group d-block">
                <button type="button" class="btn btn-primary iconpicker-component"><i
                    class="fa fa-fw fa-heart"></i></button>
                <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car"
                  data-toggle="dropdown">
                </button>
                <div class="dropdown-menu"></div>
              </div>
              <input id="inputIcon" type="hidden" name="icon" value="fas fa-heart">
              @if ($errors->has('icon'))
                <p class="mb-0 text-danger">{{ $errors->first('icon') }}</p>
              @endif
              <div class="mt-2">
                <small>{{ __('NB: click on the dropdown sign to select a icon.') }}</small>
              </div>
              <p id="erricon" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
              <input class="form-control" name="title" placeholder="{{ __('Enter Title') }}"></input>
              <p id="errtitle" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Text') }} <span class="text-danger">**</span></label>
              <input class="form-control" name="text" placeholder="{{ __('Enter text') }}"></input>
              <p id="errtext" class="mb-0 text-danger em"></p>
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

  <!-- Edit  Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Feature') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxEditForm" class="" action="{{ route('user.home_page.heroSec.how_it_work.update') }}"
            method="POST">
            @csrf
            <input id="inid" type="hidden" name="id" value="">
            <div class="form-group">
              <label for="">{{ __('Icon') }} <span class="text-danger">**</span></label>
              <div class="btn-group d-block">
                <button type="button" class="btn btn-primary iconpicker-component edit-iconpicker-component">
                  <i class="" id="inicon"></i>
                </button>
                <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle icp-update"
                  data-selected="fa-car" data-toggle="dropdown"></button>
                <div class="dropdown-menu"></div>
              </div>

              <input type="hidden" id="editInputIcon" name="icon">
              <p id="eerricon" class="mt-1 mb-0 text-danger em"></p>

              <div class="text-warning mt-2">
                <small>{{ __('Click on the dropdown icon to select an icon') }}</small>
              </div>
            </div>
            <div class="form-group">
              <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
              <input id="intitle" class="form-control" name="title" value=""></input>
              <p id="eerrtitle" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Text') }} <span class="text-danger">**</span></label>
              <input id="intext" class="form-control" name="text" value=""></input>
              <p id="eerrtext" class="mb-0 text-danger em"></p>
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
