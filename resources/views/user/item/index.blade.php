@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where('code', request()->input('language'))->first();
  $userLanguages = \App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)->get();
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Items') }}</h4>
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
        <a href="#">{{ __('Items') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Items') }}</div>
            </div>
            <div class="col-lg-3">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>
                    {{ __('Select a Language') }}
                  </option>
                  @foreach ($userLanguages as $language)
                    <option value="{{ $language->code }}"
                      {{ $language->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $language->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('user.item.type') }}" class="btn btn-primary float-right btn-sm"><i
                  class="fas fa-plus"></i> {{ __('Add Item') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('user.item.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($items) == 0)
                <h3 class="text-center">{{ __('NO ITEMS FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Price') }} ({{ $currency->text }})</th>
                        <th scope="col">{{ __('Type') }}</th>
                        <th scope="col">{{ __('SKU') }}</th>
                        <th scope="col">{{ __('Variations') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        @if ($userBs->theme == 'electronics' || $userBs->theme == 'kids' || $userBs->theme == 'manti')
                          <th>{{ __('Featured') }}</th>
                        @endif
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($items as $key => $item)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $item->item_id }}">
                          </td>
                          <td>
                            <a href="{{ route('front.user.productDetails', [Auth::user('web')->username, 'slug' => $item->slug]) }}"
                              target="_blank">
                              {{ truncateString($item->title, 50) }}
                            </a>
                          </td>
                          <td>{{ symbolPrice($currency->symbol_position, $currency->symbol, $item->current_price) }}</td>
                          <td class="text-capitalize">{{ $item->type }}</td>
                          <td> {{ $item->sku }} </td>
                          @if ($item->type != 'digital')
                            <td class="">
                              <a class="btn btn-secondary btn-sm"
                                href="{{ route('user.item.variations', $item->item_id) . '?language=' . request()->input('language') }}">
                                <span class="btn-label">
                                  {{ __('Manage') }}
                                </span>
                              </a>
                            </td>
                          @else
                            <td>-</td>
                          @endif
                          <td>
                            {{ convertUtf8($item->category ? $item->category : '') }}
                          </td>
                          @if ($userBs->theme == 'electronics' || $userBs->theme == 'kids' || $userBs->theme == 'manti')
                            <td>
                              <form class="d-inline-block" action="{{ route('user.item.feature') }}"
                                id="featureForm{{ $item->item_id }}" method="POST">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                <select name="is_feature" id=""
                                  class="form-control form-control-sm  @if ($item->is_feature == 1) bg-success @else bg-danger @endif"
                                  onchange="document.getElementById('featureForm{{ $item->item_id }}').submit();">
                                  <option value="1" {{ $item->is_feature == 1 ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                  </option>
                                  <option value="0" {{ $item->is_feature == 0 ? 'selected' : '' }}>
                                    {{ __('No') }}
                                  </option>
                                </select>
                              </form>
                            </td>
                          @endif
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#"
                                  @if ($total_item > $item_limit) @else data-toggle="modal"
                                  data-target="#flashmodal{{ $item->item_id }}" @endif>{{ __('Flash Sale') }}</a>

                                <a class="dropdown-item" {{ $total_item > $item_limit ? 'disabled' : '' }}
                                  href="{{ route('user.item.edit', $item->item_id) . '?language=' . request()->input('language') }}">{{ __('Edit') }}</a>

                                <form class="deleteForm d-block" action="{{ route('user.item.delete') }}" method="post">
                                  @csrf
                                  <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                  <input type="hidden" name="language_code" value={{ request()->input('language') }}>
                                  <button type="submit" class="itemdeletebtn deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                            <!-- Flash Sale Modal -->
                            <div class="modal fade" id="flashmodal{{ $item->item_id }}" tabindex="-1" role="dialog"
                              aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                      {{ __('Flash Sale Setting') }}
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <form class="modal-form" enctype="multipart/form-data"
                                      action="{{ route('user.item.setFlashSale', $item->item_id) }}" method="POST">
                                      @csrf
                                      <div class="form-group">
                                        <label>{{ __('Status') }}</label>
                                        <div class="selectgroup w-100">
                                          <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1"
                                              class="selectgroup-input" {{ $item->flash == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Active') }}</span>
                                          </label>
                                          <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0"
                                              class="selectgroup-input" {{ $item->flash == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                          </label>
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <label for="">
                                          {{ __('Discount') }} (%)</label>
                                        <input type="number" value="{{ $item->flash_amount }}" name="flash_amount"
                                          class="form-control " placeholder="{{ __('Enter flash deal percentage') }}">
                                        <p class="mb-0 text-danger em errflash_amount">
                                        </p>
                                      </div>

                                      <div class="form-group">
                                        <label for="">{{ __('Start Date') }} <span
                                            class="text-danger">**</span></label>
                                        <input type="text" value="{{ $item->start_date }}" name="start_date"
                                          class="form-control datepicker" autocomplete="off" placeholder="YYYY-MM-DD">
                                        <p id="" class="mb-0 text-danger em errstart_date">
                                        </p>
                                      </div>
                                      <div class="form-group">
                                        <label for="">{{ __('Start Time') }} <span
                                            class="text-danger">**</span></label>
                                        <input type="text" name="start_time" value="{{ $item->start_time }}"
                                          class="form-control flatpickr" autocomplete="off" placeholder="00:00">
                                        <p id="" class="mb-0 text-danger em errstart_time">
                                        </p>
                                      </div>
                                      <div class="form-group">
                                        <label for="">{{ __('End Date') }} <span
                                            class="text-danger">**</span></label>
                                        <input type="text" name="end_date" value="{{ $item->end_date }}"
                                          class="form-control datepicker" autocomplete="off" placeholder="YYYY-MM-DD">
                                        <p id="" class="mb-0 text-danger em errend_date">
                                        </p>
                                      </div>
                                      <div class="form-group">

                                        <label for="">{{ __('End Time') }} <span
                                            class="text-danger">**</span></label>
                                        <input type="text" name="end_time" value="{{ $item->end_time }}"
                                          class="form-control flatpickr" autocomplete="off" placeholder="00:00">
                                        <p id="" class="mb-0 text-danger em errend_time">
                                        </p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                          data-dismiss="modal">{{ __('Close') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                      </div>
                                    </form>
                                  </div>
                                </div>
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
      </div>
    </div>
  </div>
@endsection
