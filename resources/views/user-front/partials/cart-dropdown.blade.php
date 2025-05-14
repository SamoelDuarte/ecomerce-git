@php
  $user_currency = user_currency(Session::get('user_curr'));
@endphp
@if (!empty(Session::get('cart')))
  <ul class="cart-dropdown-list">
    @php
      $cart = Session::get('cart');
      $user_id = getUser()->id;
      if (!is_null($cart) && is_array($cart)) {
          $cart = array_filter($cart, function ($item) use ($user_id) {
              return $item['user_id'] == $user_id;
          });
      }
    @endphp
    @foreach ($cart as $key => $item)
      @php
        $id = $item['id'];
        $product = App\Models\User\UserItem::where('id', $item['id'])->first();
        $content = $product
            ->itemContents()
            ->where('language_id', $userCurrentLang->id)
            ->select('title', 'slug')
            ->first();
      @endphp
      @if ($product)
        <li class="cart-dropdown-list-item">
          <div class="cart-img radius-md">
            <a target="_blank"
              href="{{ route('front.user.productDetails', [getParam(), 'slug' => $content->slug]) }}"><img class="lazyload" alt="Nest"
                src="{{ asset('assets/front/images/placeholder.png') }}"
                data-src="{{ asset('assets/front/img/user/items/thumbnail/' . $product->thumbnail) }}"
                ></a>
          </div>
          <div class="cart-title">
            <h5 class="lc-1 product-title mb-1"><a target="_blank"
                href="{{ route('front.user.productDetails', [getParam(), 'slug' => $content->slug]) }}">{{ convertUtf8($content->title) }}</a>
            </h5>
            <span class="mb-1 d-inline-block">
              <span class="fw-medium me-1">{{ $keywords['Item Price'] ?? __('Item Price') }}:</span>
              <span class="m-0">
                {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $item['product_price']) }}
              </span>
            </span>
            <br>
            <span class="mb-1 d-inline-block">
              <span class="fw-medium me-1">{{ $keywords['Quantity'] ?? __('Quantity') }}:</span>
              <span class="m-0">
                {{ $item['qty'] }}
              </span>
            </span>

            @php
              $variation_total = 0;
            @endphp
            @if (!empty($item['variations']))
              @foreach ($item['variations'] as $k => $itm)
                @php
                  $variation_total = $variation_total + $itm['price'];

                  //show variations name
                  $vNameId = App\Models\User\ProductVariationContent::where(
                      'product_variation_id',
                      $itm['variation_id'],
                  )
                      ->pluck('variation_name')
                      ->first();

                  $variant_id = App\Models\VariantContent::where('id', $vNameId)->pluck('variant_id')->first();
                  $variation_name = App\Models\VariantContent::where([
                      ['variant_id', $variant_id],
                      ['language_id', $userCurrentLang->id],
                  ])
                      ->pluck('name')
                      ->first();

                  //show variation options name
                  $vOptionId = App\Models\User\ProductVariantOptionContent::where([
                      ['language_id', $userCurrentLang->id],
                      ['product_variant_option_id', $itm['option_id']],
                  ])
                      ->pluck('option_name')
                      ->first();
                  $vOptionName = App\Models\VariantOptionContent::where([
                      ['language_id', $userCurrentLang->id],
                      ['id', $vOptionId],
                  ])
                      ->pluck('option_name')
                      ->first();
                @endphp
                <table class="variation-table mb-1">
                  <tr>
                    <td class="">
                      <span class="fw-medium me-1">{{ $variation_name }}:</span>
                    </td>
                    <td class="cart_variants_price">
                      <small>{{ $vOptionName }}</small>
                      -(<i
                        class="fas fa-plus"></i>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, $itm['price']) }})
                    </td>
                  </tr>
                </table>
              @endforeach
            @endif


          </div>
          <div class="cart-delete">
            <span class="fas fa-times cursor-pointer item-remove btn-remove btn-icon" rel="{{ $id }}"
              data-href="{{ route('front.cart.item.remove', ['uid' => $key, getParam()]) }}"></span>
          </div>
        </li>
      @endif
    @endforeach
  </ul>
  <div class="cart-footer">
    <div class="cart-total">
      <h4>{{ $keywords['Total'] ?? __('Total') }}</h4>
      <h4><span class="color-primary">
          {{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, cartTotal()) }}
        </span></h4>
    </div>
    <div class="cart-button">
      <a href="{{ route('front.user.cart', getParam()) }}" class="btn btn-md btn-primary radius-sm"
        title="{{ $keywords['View_cart'] ?? __('View cart') }}">{{ $keywords['View_cart'] ?? __('View cart') }}</a>

      <a href="{{ route('front.user.checkout', getParam()) }}" class="btn btn-md btn-outline radius-sm"
        title="{{ $keywords['Checkout'] ?? __('Checkout') }}">{{ $keywords['Checkout'] ?? __('Checkout') }}</a>
    </div>
  </div>
@else
  <h4 class="text-center">{{ $keywords['Your Cart is Empty'] ?? __('Your Cart is Empty') }}</h4>
@endif
