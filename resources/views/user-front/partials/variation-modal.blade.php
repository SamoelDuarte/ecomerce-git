<div class="modal fade variation-modal" id="variationModal" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle">
  <div class="modal-dialog modal-lg modal-dialog-centered radius-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLongTitle">
          <span></span>
          <small class="ml-2">
            ({{ $user_currency->symbol_position == 'left' ? $user_currency->symbol : '' }}
            <span id="productPrice"></span>
            {{ $user_currency->symbol_position == 'right' ? $user_currency->symbol : '' }})
          </small>

        </h4>
        <button type="button" class="close variatmodal-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fal fa-times"></i></span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" id="variants">
          {{-- All variants will be appended here by jquery --}}
        </div>
      </div>
      <div class="modal-footer justify-content-center">
        <div class="variation-modal-groupbtn">
          <div class="modal-quantity">
            <span class="minus quantity-btn"><i class="fas fa-minus"></i></span>
            <input class="form-control" type="number" name="cart-amount" value="1" min="1">
            <span class="plus quantity-btn"><i class="fas fa-plus"></i></span>
          </div>
          <button type="button" class="btn btn-primary text-uppercase modal-cart-link">
            <i class="fas fa-cart-plus"></i>
            <span class="d-block">{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}</span>
            <i class="fas fa-spinner d-none"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
