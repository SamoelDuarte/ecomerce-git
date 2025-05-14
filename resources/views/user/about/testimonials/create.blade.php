<!-- Create Testimonial Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Testimonial') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
          action="{{ route('user.about_us.testimonial.store') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="">{{ __('Language') }} <span class="text-danger">**</span></label>
            <select name="user_language_id" id="" class="form-control">
              <option value="" selected disabled>{{ __('Select Language') }}
              </option>
              @foreach ($userLanguages as $language)
                <option value="{{ $language->id }}">{{ $language->name }}</option>
              @endforeach
            </select>
            <p id="erruser_language_id" class="mb-0 text-danger em"></p>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <div class="col-12 pl-0">
                  <label for="image"><strong>{{ __('Image') }} <span class="text-danger">**</span></strong></label>
                </div>
                <div class="col-md-12 showImage mb-3 pl-0  pr-0">
                  <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="img-thumbnail">
                </div>
                <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                  {{ __('Choose Image') }}
                  <input type="file" class="img-input" name="image">
                </div>
                <p class="mb-0 text-warning">
                  {{ __('Recommended Image size : 50X70') }}</p>

                <p id="errimage" class="mb-0 text-danger em"></p>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control" name="name" placeholder="{{ __('Enter name') }}"
              value="">
            <p id="errname" class="mb-0 text-danger em"></p>
          </div>
          <div class="form-group">
            <label for="">{{ __('Designation') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control" name="designation" placeholder="{{ __('Enter designation') }}"
              value="">
            <p id="errdesignation" class="mb-0 text-danger em"></p>
          </div>
          <div class="form-group">
            <label for="">{{ __('Rating') }} <span class="text-danger">**</span></label>
            <input type="number" class="form-control" name="rating" placeholder="{{ __('Enter rating') }}"
              value="" min="1" max="5">
            <p class="text-warning mb-0">
              {{ __('Rating must be between 1 to 5') }}</p>
            <p id="errrating" class="mb-0 text-danger em"></p>
          </div>
          <div class="form-group">
            <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control jscolor ltr" name="color" value="" min="1"
              max="5">
            <p id="errcolor" class="mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Comment') }}</label>
            <textarea class="form-control" name="comment" rows="4" cols="80" placeholder="{{ __('Enter comment') }}"></textarea>
            <p id="errcomment" class="mb-0 text-danger em"></p>
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
