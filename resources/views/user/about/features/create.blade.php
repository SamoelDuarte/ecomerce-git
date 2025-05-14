<!-- Create Service Category Modal -->
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
        <form id="ajaxForm" class="modal-form" enctype="multipart/form-data"
          action="{{ route('user.pages.about_us.features.store', ['language' => request()->input('language')]) }}"
          method="POST">
          @csrf
          <div class="form-group">
            <label for="">{{ __('Features Icon') }} <span class="text-danger">**</span></label>
            <div class="btn-group d-block">
              <button type="button" class="btn btn-primary iconpicker-component">
                <i class="fa fa-fw fa-heart"></i>
              </button>
              <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car"
                data-toggle="dropdown"></button>
              <div class="dropdown-menu"></div>
            </div>

            <input type="hidden" id="inputIcon" name="icon">
            <p id="erricon" class="mt-2 mb-0 text-danger em"></p>

            <div class="text-warning mt-2">
              <small>{{ __('Click on the dropdown icon to select an icon.') }}</small>
            </div>
          </div>

          <div class="form-group">
            <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control" name="title" value=""
              placeholder="{{ __('Enter Title') }}">
            <p id="errtitle" class="mb-0 text-danger em"></p>
          </div>
          <div class="form-group">
            <label for="">{{ __('Subtitle') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control" name="subtitle" value=""
              placeholder="{{ __('Enter Subtitle') }}">
            <p id="errsubtitle" class="mb-0 text-danger em"></p>
          </div>
          <div class="form-group">
            <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control jscolor" name="color" value="">
            <p id="errcolor" class="mb-0 text-danger em"></p>
          </div>
          <div class="form-group">
            <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
            <input type="number" class="form-control" name="serial_number" value="">
            <p id="errserial_number" class="mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
            <select class="form-control" name="status">
              <option value="" selected disabled>{{ __('Enter name') }}
              </option>
              <option value="1" selected>{{ __('Active') }}</option>
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
