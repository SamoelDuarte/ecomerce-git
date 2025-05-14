<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Tab Image') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('user.tabImage.update') }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="inid" name="tabImage_id">
          <div class="form-group">
            <div class="col-12 mb-2 pl-0">
              <label for="image">{{ __('Background Image') }} <span class="text-danger">**</span></label>
            </div>
            <div class="col-md-12 showEditImage mb-3 ml-0 pl-0">
              <img id="intabimage_img" src="" alt="..." class="tabImage-img img-thumbnail image">
            </div>
            <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
              {{ __('Choose Image') }}
              <input type="file" class="img-input" name="tabImage_img">
            </div>

            <p id="eerrtabImage_img" class="mb-0 text-danger em"></p>
            <p class="text-warning p-0 mb-1">
              {{ __('Recommended Image size : 860X1320') }}
            </p>
          </div>

          <div class="form-group">
            <label for="">{{ __("Tab Image's URL") }} <span class="text-danger">**</span></label>
            <input type="url" id="intabimage_url" class="form-control" name="tabImage_url"
              placeholder="{{ __('Enter Tab Image URL') }}">
            <p id="eerrtabImage_url" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __("Tab Image's Title") }} <span class="text-danger">**</span></label>
            <input type="url" id="intitle" class="form-control" name="title"
              placeholder="{{ __('Enter Tab Image Title') }}">
            <p id="eerrsubtitle" class="mt-2 mb-0 text-danger em"></p>
          </div>

          @if ($userBs->theme != 'electronics')
            <div class="form-group">
              <label for="">{{ __("Tab Image's Subttle") }} <span class="text-danger">**</span></label>
              <input type="url" id="insubtitle" class="form-control" name="subtitle"
                placeholder="{{ __('Enter Tab Image subtitle') }}">
              <p id="eerrsubtitle" class="mt-2 mb-0 text-danger em"></p>
            </div>
          @endif

          <div class="form-group">
            <label for="">{{ __("Tab Image's Button Text") }} <span class="text-danger">**</span></label>
            <input type="url" id="inbutton_text" class="form-control" name="button_text"
              placeholder="{{ __('Enter button text') }}">
            <p id="eerrbutton_text" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
            <input type="number" id="inserial_number" class="form-control" name="serial_number"
              placeholder="{{ __('Enter Serial Number') }}">
            <p id="eerrserial_number" class="mt-2 mb-0 text-danger em"></p>
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
        <button id="updateBtn" type="button" class="btn btn-primary">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
