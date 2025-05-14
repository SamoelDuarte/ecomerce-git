<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Tab Image') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajaxForm" class="modal-form" action="{{ route('user.tabImage.store') }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <div class="col-12 mb-2 pl-0">
              <label for="image">{{ __('Image') }} <span class="text-danger">**</span></label>
            </div>
            <div class="col-md-12 showImage mb-3 ml-0 pl-0 pr-0">
              <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="img-thumbnail">
            </div>
            <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
              {{ __('Choose Image') }}
              <input type="file" class="img-input" name="tabImage_img">
            </div>
            <p id="errtabImage_img" class="mb-0 text-danger em"></p>
            <p class="text-warning p-0 mb-1">
              {{ __('Recommended Image size : 860X1320') }}
            </p>
          </div>

          <div class="form-group">
            <label for="">{{ __("Tab Image's URL") }} <span class="text-danger">**</span></label>
            <input type="url" class="form-control" name="tabImage_url"
              placeholder="{{ __('Enter Tab Image URL') }}">
            <input type="hidden" class="form-control" name="language" value="{{ request()->input('language') }}">
            <p id="errtabImage_url" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __("Tab Image's Title") }} <span class="text-danger">**</span></label>
            <input type="url" class="form-control" name="title" placeholder="{{ __('Enter Tab Image Title') }}">
            <p id="errtabImage_title" class="mt-2 mb-0 text-danger em"></p>
          </div>

          @if ($userBs->theme != 'electronics')
            <div class="form-group">
              <label for="">{{ __("Tab Image's Subtitle") }} <span class="text-danger">**</span></label>
              <input type="url" class="form-control" name="subtitle"
                placeholder="{{ __('Enter Tab Image Subtitle') }}">
              <p id="errtabImage_subtitlr" class="mt-2 mb-0 text-danger em"></p>
            </div>
          @endif

          <div class="form-group">
            <label for="">{{ __("Tab Image's Button Text") }} <span class="text-danger">**</span></label>
            <input type="url" class="form-control" name="btn_text"
              placeholder="{{ __('Enter Tab Image Btn Text') }}">
            <p id="errtabImage_url" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
            <input type="number" class="form-control" name="serial_number"
              placeholder="{{ __('Enter Serial Number') }}">
            <p id="errserial_number" class="mt-2 mb-0 text-danger em"></p>
            <p class="text-warning mt-2">
              <small>{{ __('The higher the serial number is, the later the tabImage will be shown.') }}</small>
            </p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="submitBtn" type="button" class="btn btn-primary">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
