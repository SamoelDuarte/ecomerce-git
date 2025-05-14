 <!-- Create Service tab Modal -->
 <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
   aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Item') . ' ' }}
           {{ $userBs->theme === 'manti' ? __('Section') : __('Tab') }}</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <div class="modal-body">
         <form id="ajaxEditForm" class="modal-form" action="{{ route('user.tab.update') }}" method="POST">
           @csrf
           <input type="hidden" id="inid" name="tab_id">
           <div class="form-group">
             <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
             <input type="text" class="form-control" name="name" id="inname"
               placeholder="{{ __('Enter name') }}">
             <p id="eerrname" class="mb-0 text-danger em"></p>
           </div>

           <div class="form-group">
             <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
             <select class="form-control" name="status" id="instatus">
               <option value="" selected disabled>{{ __('Enter name') }}
               </option>
               <option value="1">{{ __('Active') }}</option>
               <option value="0">{{ __('Deactive') }}</option>
             </select>
             <p id="eerrstatus" class="mb-0 text-danger em"></p>
           </div>

           <div class="form-group">
             <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
             <input type="text" class="form-control" name="serial_number" id="inserial_number"
               placeholder="{{ __('Enter Serial Number') }}">
             <p id="eerrserial_number" class="mb-0 text-danger em"></p>
             <p class="text-warning">
               <small>
                 {{ __('The higher the serial number is, the later the tab will be shown.') }}</small>
             </p>
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
