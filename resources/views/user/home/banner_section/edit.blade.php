<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Banner') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('user.home_page.banner_section.update_banner') }}"
          method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="inid" name="banner_id">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="">{{ __("Banner's Position") }} <span class="text-danger">**</span></label>
                <select class="form-control " name="position" id="inposition">
                  @if ($userBs->theme == 'manti')
                    <option value='bottom_right'>{{ __('Bottom Right') }} </option>
                  @endif
                  @if ($userBs->theme === 'vegetables')
                    <option value='right'>{{ __('Top Right') }} </option>
                  @endif
                  @if ($userBs->theme === 'kids')
                    <option value='right'>{{ __('Right') }} </option>
                  @endif
                  @if ($userBs->theme == 'electronics')
                    <option value='top_right'>{{ __('Top Right') }} </option>
                  @endif
                  @if ($userBs->theme == 'furniture')
                    <option value='top_middle'>{{ __('Top Middle') }} </option>
                  @endif
                  @if ($userBs->theme === 'kids')
                    <option value='middle_right'>{{ __('Middle Right') }} </option>
                  @endif
                  @if ($userBs->theme === 'kids' || $userBs->theme === 'electronics')
                    <option value='left'> {{ __('Left') }} </option>
                  @endif
                  <option value='middle'>{{ __('Middle') }}
                  </option>
                  @if ($userBs->theme === 'electronics' || $userBs->theme === 'vegetables' || $userBs->theme === 'vegetables')
                    <option value='bottom_left'>{{ __('Bottom Left') }}</option>
                  @endif
                  @if ($userBs->theme === 'furniture')
                    <option value='bottom_middle'>{{ __('Bottom Middle') }} </option>
                  @endif
                </select>
                <p id="eerrbanner_url" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <div class="col-12 mb-2 pl-0">
                  <label for="image">{{ __('Image') }} <span class="text-danger">**</span></label>
                </div>
                <div class="col-md-12 showImage2 mb-3 pl-0 pr-0">
                  <img id="inbannerimg" src="" alt="..." class="banner-img img-thumbnail image">
                </div>
                <div role="button" class="btn btn-primary btn-sm upload-btn" id="image2">
                  {{ __('Choose Image') }}
                  <input type="file" class="img-input" name="banner_img">
                </div>
                <p id="eerrbanner_img" class="mb-0 text-danger em"></p>
                <p class="text-warning p-0 mb-1 note-text">
                  @if ($userBs->theme === 'kids')
                    {{ __('Recommended Image size : 860X1150') }}
                  @elseif ($userBs->theme === 'electronics')
                    {{ __('Recommended Image size : 445X195') }}
                  @elseif($userBs->theme === 'fashion')
                    {{ __('Recommended Image size : 700X365') }}
                  @elseif($userBs->theme === 'furniture')
                    {{ __('Recommended Image size : 700X280') }}
                  @elseif($userBs->theme === 'vegetables')
                    {{ __('Recommended Image size : 500X265') }}
                  @elseif($userBs->theme === 'manti')
                    {{ __('Recommended Image size : 625X570') }}
                  @endif
                </p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">{{ __("Banner's Title") }} </label>
                <input type="text" id="intitle" class="form-control" name="title"
                  placeholder="{{ __('Enter Banner Title') }}">
                <p id="eerrtitle" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            <div class="col-md-6 subtitle">
              <div class="form-group">
                <label for="">{{ __("Banner's Subtitle") }}</label>
                <input type="text" id="insubtitle" class="form-control" name="subtitle"
                  placeholder="{{ __('Enter Banner Subtitle') }}">
                <p id="eerrsubtitle" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>

            @if ($userBs->theme == 'manti')
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">{{ __("Banner's Text") }}</label>
                  <input type="text" id="intext" class="form-control" name="text"
                    placeholder="{{ __('Enter Banner text') }}">
                  <p id="eerrtext" class="mt-2 mb-0 text-danger em"></p>
                </div>
              </div>
            @endif

            <div class="col-md-6">
              <div class="form-group">
                <label for="">{{ __("Banner's Button Text") }}</label>
                <input type="text" id="inbutton_text" class="form-control" name="button_text"
                  placeholder="{{ __('Enter Button Text') }}">
                <p id="eerrbutton_text" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">{{ __("Banner's URL") }} <span class="text-danger">**</span></label>
                <input type="url" id="inbanner_url" class="form-control" name="banner_url"
                  placeholder="{{ __('Enter Banner URL') }}">
                <p id="eerrbanner_url" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                <input type="number" id="inserial_number" class="form-control" name="serial_number"
                  placeholder="{{ __('Enter Serial Number') }}">
                <p id="eerrserial_number" class="mt-2 mb-0 text-danger em"></p>
                <p class="text-warning mt-2">
                  <small>{{ __('The higher the serial number is, the later the Banner will be shown.') }}</small>
                </p>
              </div>
            </div>
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
