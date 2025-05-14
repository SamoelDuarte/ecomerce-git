  <!-- Create Service Category Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
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

          <form id="ajaxEditForm" class="modal-form" action="{{ route('user.coupon.update') }}" method="POST">
            @csrf
            <input type="hidden" name="coupon_id" id="inid">
            <div class="row no-gutters">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="name" id="inname"
                    placeholder="{{ __('Enter name') }}">
                  <p id="eerrname" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Code') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="code" id="incode"
                    placeholder="{{ __('Enter code') }}">
                  <p id="eerrcode" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>
            <div class="row no-gutters">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Type') }} <span class="text-danger">**</span></label>
                  <select name="type" id="intype" class="form-control">
                    <option value="percentage">{{ __('Percentage') }}</option>
                    <option value="fixed">{{ __('Fixed') }}</option>
                  </select>
                  <p id="eerrtype" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Value') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="value" id="invalue"
                    placeholder="{{ __('Enter value') }}" autocomplete="off">
                  <p id="eerrvalue" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>

            <div class="row no-gutters">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Start Date') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control datepicker" name="start_date" id="instart_date"
                    placeholder="{{ __('Enter start date') }}" autocomplete="off">
                  <p id="eerrstart_date" class="mb-0 text-danger em"></p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('End Date') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control datepicker" name="end_date" id="inend_date"
                    placeholder="{{ __('Enter end date') }}" autocomplete="off">
                  <p id="eerrend_date" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>

            <div class="row no-gutters">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Minimum Spend') }}
                    ({{ $default_currency->text }})</label>
                  <input type="number" class="form-control" name="minimum_spend" id="inminimum_spend"
                    placeholder="{{ __('Enter amount of minimum spend') }}" autocomplete="off">
                  <p class="mb-0 text-warning">
                    {{ __('Keep it blank, if you do not want to keep any minimum spend limit') }}
                  </p>
                  <p id="eerrminimum_spend" class="mb-0 text-danger em"></p>
                </div>
              </div>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="updateBtn" type="button" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
      </div>
    </div>
  </div>
