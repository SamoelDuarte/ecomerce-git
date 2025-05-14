  <!-- Create Service Category Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">
            {{ __('Edit shipping charge') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <form id="ajaxEditForm" action="{{ route('user.shipping.update') }}" method="POST">
            @csrf
            <input type="hidden" name="shipping_id" id="inid">
            <div class="form-group">
              <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="title" id="intitle"
                placeholder="{{ __('Enter title') }}">
              <p id="eerrtitle" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Sort Text') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="text" id="intext"
                placeholder="{{ __('Enter text') }}">
              <p id="eerrtext" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Charge') }} ({{ $default_currency->text }})
                <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="charge" id="incharge"
                placeholder="{{ __('Enter charge') }}">
              <p id="eerrcharge" class="mb-0 text-danger em"></p>
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
