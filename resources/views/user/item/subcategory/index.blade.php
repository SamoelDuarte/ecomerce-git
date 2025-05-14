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
    <h4 class="page-title">{{ __('Subcategories') }}</h4>
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
        <a href="#">{{ __('Subcategories') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Subcategories') }}</div>
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
                {{ __('Add Subcategory') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('user.itemsubcategory.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($itemsubcategories) == 0)
                <h3 class="text-center">{{ __('NO ITEM SUBCATEGORY FOUND') }}
                </h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($itemsubcategories as $key => $category)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $category->id }}">
                          </td>

                          <td>{{ convertUtf8($category->name) }}</td>
                          <td>
                            {{ convertUtf8($category->category->name) }}
                          </td>
                          <td>
                            @if ($category->status == 1)
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active') }}</span>
                              </h2>
                            @else
                              <h2 class="d-inline-block"><span class="badge badge-danger">{{ __('Deactive') }}</span>
                              </h2>
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm editbtn  mb-1"
                              href="{{ route('user.itemsubcategory.edit', $category->id) . '?language=' . request()->input('language') }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>
                            <form class="deleteform d-inline-block" action="{{ route('user.itemsubcategory.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="subcategory_id" value="{{ $category->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn mb-1">
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
              {{ $itemsubcategories->appends(['language' => request()->input('language')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Create Service Category Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Subcategory') }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxForm" class="modal-form" enctype="multipart/form-data"
            action="{{ route('user.itemsubcategory.store') }}" method="POST">
            @csrf
            <input type="hidden" name="language_code" value="{{ request()->input('language') }}">
            <div class="form-group">
              <label for="">{{ __('Category') }} <span class="text-danger">**</span></label>
              <select id="language" name="category_id" class="form-control item_category">
                <option value="" selected disabled>{{ __('Select Category') }}
                </option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
              <p id="errcategory_id" class="mb-0 text-danger em"></p>
            </div>
            @foreach ($userLanguages as $lang)
              <div class="form-group">
                <label for="">{{ __('Name') }} ({{ $lang->name }})
                  @if ($lang->is_default == 1)
                    <span class="text-danger">**</span>
                  @endif
                </label>
                <input type="text"
                  class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                  name="{{ $lang->code }}_name" placeholder="{{ __('Enter name') }}">
                <p id="err{{ $lang->code }}_name" class="mb-0 text-danger em"></p>
              </div>
            @endforeach

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

            <div class="form-group">
              <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
              <input type="number" class="form-control" name="serial_number"
                placeholder="{{ __('Enter Serial Number') }}">
              <p id="errserial_number" class="mb-0 text-danger em"></p>
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
@endsection
