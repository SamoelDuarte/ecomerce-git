@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where('code', request()->input('language'))->first();
  $langs = \App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)->get();
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Shipping Charges') }}</h4>
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
        <a href="#">{{ __('Shipping Charges') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Shipping Charges') }}</div>
            </div>
            <div class="col-lg-3">
              @if (!empty($langs))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($langs as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add') }}</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($shippings) == 0)
                <h3 class="text-center">{{ __('No Shipping Charge Found') }}
                </h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('SL') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Text') }}</th>
                        <th scope="col">{{ __('Charge') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($shippings as $key => $shipping)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            {{ $shipping->title }}
                          </td>
                          <td>
                            {{ $shipping->text }}
                          </td>

                          <td>
                            {{ symbolPrice($default_currency->symbol_position, $default_currency->symbol, $shipping->charge) }}
                          </td>

                          <td>
                            <a class="btn btn-secondary btn-sm editbtn  mb-1"href="#editModal" data-toggle="modal"
                              data-charge="{{ $shipping->charge }}" data-text="{{ $shipping->text }}"
                              data-title="{{ $shipping->title }}" data-id="{{ $shipping->id }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>
                            <form class="deleteform d-inline-block" action="{{ route('user.shipping.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="shipping_id" value="{{ $shipping->id }}">
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
              {{ $shippings->appends(['language' => request()->input('language')])->links() }}
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
          <h5 class="modal-title" id="exampleModalLongTitle">
            {{ __('Add Shipping Charge') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form id="ajaxForm" class="modal-form" action="{{ route('user.shipping.store') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Language') }} <span class="text-danger">**</span></label>
              <select id="language" name="user_language_id" class="form-control">
                <option value="" selected disabled>{{ __('Select a language') }}
                </option>
                @foreach ($langs as $lang)
                  <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                @endforeach
              </select>
              <p id="erruser_language_id" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="title" value=""
                placeholder="{{ __('Enter title') }}">
              <p id="errtitle" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Sort Text') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="text" value=""
                placeholder="{{ __('Enter text') }}">
              <p id="errtext" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Charge') }} ({{ $default_currency->text }})
                <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="charge" value=""
                placeholder="{{ __('Enter charge') }}">
              <p id="errcharge" class="mb-0 text-danger em"></p>
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
  @include('user.item.shop_setting.edit');

@endsection
