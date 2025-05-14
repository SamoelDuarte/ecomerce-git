<div class="row">
  @foreach (Session::get('items') as $item)
    <div class="col-lg-4 col-md-6">
      <div class="product-default product-center radius-xl mb-30">
        <figure class="product-img">
          <a href="#" class="lazy-container ratio ratio-1-1">
            <img class="lazyload default-img" src="{{ asset('assets/front/images/placeholder.png') }}"
              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}" alt="Product">
            <img class="lazyload hover-img" src="{{ asset('assets/front/images/placeholder.png') }}"
              data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) }}" alt="Product">
          </a>
        </figure>
        <div class="product-details">
          <span class="product-category text-sm">{{ $item->category_name }}</span>
          <h3 class="product-title">
            <a href="#">{{ $item->title }}</a>
          </h3>
          <div class="product-ratings rate text-xsm">
            <div class="rating" style="width: {{ $avgreview * 20 }}%;"></div>
            <span class="ratings-total">({{ $avgreview }})</span>
          </div>

          @php
            $flash_info = flashAmountStatus($item->item_id, $item->current_price);
            $product_current_price = $flash_info['amount'];
            $flash_status = $flash_info['status'];
          @endphp

          <div class="product-price">
            @if ($item->flash == 1)
              <span class="new-price">
                {{ $item->current_price - $item->current_price * ($item->flash_amount / 100) }}
              </span>&nbsp;
              <span class="old-price">{{ $item->current_price }}</span>
              &nbsp;
              <span class="old-price">{{ $item->flash_amount }}% {{ $keywords['OFF'] ?? __('OFF') }}</span>
            @else
              <span class="new-price">{{ $item->current_price }}</span>
              <span class="old-price">{{ $item->previous_price }}</span>
            @endif
          </div>
        </div>
        <div class="btn-icon-group btn-inline">
          <button type="button" class="btn btn-icon hover-show radius-sm" data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ $keywords['Quick View'] ?? __('Quick View') }}" data-bs-toggle="modal"
            data-bs-target="#quickViewModal">
            <i class="fal fa-eye"></i>
          </button>
          <a href="#" class="btn btn-icon radius-sm" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ $keywords['Add_to_Cart'] ?? __('Add to Cart') }}"><i class="fal fa-shopping-bag"></i></a>
          <a href="#" class="btn btn-icon hover-show radius-sm" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ $keywords['Compare'] ?? __('Compare') }}"><i class="fal fa-random"></i></a>
        </div>
        <span class="label label-green">{{ $keywords['New'] ?? __('New') }}</span>
        <a href="#" class="btn btn-icon radius-sm btn-wishlist" data-bs-toggle="tooltip" data-bs-placement="top"
          title="{{ $keywords['Add to Wishlist'] ?? __('Add to Wishlist') }}"><i class="fal fa-heart"></i></a>
      </div> <!-- product-default -->
    </div>
  @endforeach
</div>
