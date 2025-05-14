<!-- Create Service Category Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">
          {{ __('Add Counter Information') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ajaxForm" class="modal-form" enctype="multipart/form-data"
          action="{{ route('user.pages.counter_section.counter.store', ['language' => request()->input('language')]) }}"
          method="POST">
          @csrf
          <input type="hidden" name="language_id" value="{{ $lang_id }}" id="">
          <div class="form-group">
            <label for="">{{ __('Icon') }} <span class="text-danger">**</span></label>
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
            <label for="">{{ __('Amount') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control" name="amount" value=""
              placeholder="{{ __('Enter Amount') }}">
            <p id="erramount" class="mb-0 text-danger em"></p>
          </div>
          <div class="form-group">
            <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control" name="title" value=""
              placeholder="{{ __('Enter Title') }}">
            <p id="errtitle" class="mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control jscolor" name="color" value="">
            <p id="errcolor" class="mb-0 text-danger em"></p>
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
