@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Coupons') }}</h4>
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
        <a href="#">{{ __('Coupons') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">{{ __('Coupons') }}</div>
            </div>
            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add') }}</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($coupons) == 0)
                <h3 class="text-center">{{ __('NO COUPON FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('SL') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Code') }}</th>
                        <th scope="col">{{ __('Discount') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Created') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($coupons as $coupon)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $coupon->name }}</td>
                          <td>{{ $coupon->code }}</td>
                          <td>
                            {{ $coupon->type == 'fixed' && $default_currency->symbol_position == 'left' ? $default_currency->symbol : '' }}{{ $coupon->value }}{{ $coupon->type == 'percentage' ? '%' : '' }}

                            {{ $coupon->type == 'fixed' && $be->currency_position == 'right' ? $be->sign : '' }}
                          </td>
                          <td>
                            @php
                              $end = Carbon\Carbon::parse($coupon->end_date);
                              $start = Carbon\Carbon::parse($coupon->start_date);
                              $now = Carbon\Carbon::now();
                              $diff = $end->diffInDays($now);
                            @endphp
                            @if ($start->greaterThan($now))
                              <h3 class="d-inline-block badge badge-warning">{{ __('Pending') }}
                              </h3>
                            @else
                              @if ($now->lessThan($end))
                                <h3 class="d-inline-block badge badge-success">{{ __('Active') }}
                                </h3>
                              @else
                                <h3 class="d-inline-block badge badge-danger">{{ __('Expired') }}
                                </h3>
                              @endif
                            @endif
                          </td>
                          <td>
                            @php
                              $created = Carbon\Carbon::parse($coupon->created_at);
                              $diff = $created->diffInDays($now);
                            @endphp
                            {{ $created->subDays($diff)->diffForHumans() }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm editbtn  mb-1"href="#editModal" data-toggle="modal"
                              data-name="{{ $coupon->name }}" data-code="{{ $coupon->code }}"
                              data-type="{{ $coupon->type }}" data-id="{{ $coupon->id }}"
                              data-start_date="{{ $coupon->start_date }}" data-end_date="{{ $coupon->end_date }}"
                              data-minimum_spend="{{ $coupon->minimum_spend }}" data-value="{{ $coupon->value }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>
                            <form class="d-inline-block deleteform" action="{{ route('user.coupon.delete') }}"
                              method="POST">
                              @csrf
                              <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn  mb-1"> <i
                                  class="fas fa-trash"></i> </button>
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
              {{ $coupons->links() }}
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
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Coupon') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form id="ajaxForm" class="modal-form" action="{{ route('user.coupon.store') }}" method="POST">
            @csrf
            <div class="row no-gutters">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="name" value=""
                    placeholder="{{ __('Enter name') }}">
                  <p id="errname" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Code') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="code" value=""
                    placeholder="{{ __('Enter code') }}">
                  <p id="errcode" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>
            <div class="row no-gutters">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Type') }} <span class="text-danger">**</span></label>
                  <select name="type" id="" class="form-control">
                    <option value="percentage">{{ __('Percentage') }}</option>
                    <option value="fixed">{{ __('Fixed') }}</option>
                  </select>
                  <p id="errtype" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Value') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="value" value=""
                    placeholder="{{ __('Enter value') }}" autocomplete="off">
                  <p id="errvalue" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>

            <div class="row no-gutters">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Start Date') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control datepicker" name="start_date" value=""
                    placeholder="{{ __('Enter start date') }}" autocomplete="off">
                  <p id="errstart_date" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('End Date') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control datepicker" name="end_date" value=""
                    placeholder="{{ __('Enter end date') }}" autocomplete="off">
                  <p id="errend_date" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>

            <div class="row no-gutters">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Minimum Spend') }}
                    ({{ $default_currency->text }})</label>
                  <input type="number" class="form-control" name="minimum_spend" value=""
                    placeholder="{{ __('Enter amount of minimum spend') }}" autocomplete="off">
                  <p class="mb-0 text-warning">
                    {{ __('Keep it blank, if you do not want to keep any minimum spend limit') }}
                  </p>
                  <p id="errminimum_spend" class="mb-0 text-danger em"></p>
                </div>
              </div>
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

  @include('user.item.order.coupons.edit')

@endsection
