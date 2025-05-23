<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Counter Information') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.home_page.update_counter') }}"
          method="post">
          @csrf
          <input type="hidden" name="id" id="inid">

          <div class="form-group">
            <label for="">{{ __('Icon') }} <span class="text-danger">**</span></label>
            <div class="btn-group d-block">
              <button type="button" class="btn btn-primary iconpicker-component edit-iconpicker-component">
                <i class="fas fa-users" id="inicon"></i>
              </button>
              <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle icp-update"
                data-selected="fa-car" data-toggle="dropdown"></button>
              <div class="dropdown-menu"></div>
            </div>

            <input type="hidden" id="editInputIcon" name="icon">
            <p id="eerricon" class="mt-1 mb-0 text-danger em"></p>

            <div class="text-warning mt-2">
              <small>{{ __('Click on the dropdown icon to select an icon.') }}</small>
            </div>
          </div>

          <div class="form-group">
            <label for="">{{ __('Amount') }} <span class="text-danger">**</span></label>
            <input type="number" class="form-control ltr" name="amount" placeholder="{{ __('Enter Amount') }}"
              id="inamount">
            <p id="eerramount" class="mt-2 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control jscolor" name="color" value="" id="incolor">
            <p id="eerrcolor" class="mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
            <input type="text" class="form-control" name="title" placeholder="{{ __('Enter Title') }}"
              id="intitle">
            <p id="eerrtitle" class="mt-2 mb-0 text-danger em"></p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="updateBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
