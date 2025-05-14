@php
  $user_currency = user_currency(Session::get('user_curr'));
@endphp

@foreach ($product_variations as $product_variation)
  @php
    $product_variation_contents = App\Models\User\ProductVariationContent::where([
        ['product_variation_id', $product_variation->id],
        ['language_id', $language_id],
    ])->get();
    $variant_content_options = App\Models\User\ProductVariantOption::where([
        ['product_variation_id', $product_variation->id],
    ])->get();
  @endphp
  @if (count($product_variation_contents) > 0)
    <div class="col-lg-6 mb-20">
      @foreach ($product_variation_contents as $product_variation_content)
        @php
          $variant_content = App\Models\VariantContent::where(
              'id',
              $product_variation_content->variation_name,
          )->first();
        @endphp
        <h4 class="mb-1 color-primary">{{ @$variant_content->name }}</h4>
        @foreach ($variant_content_options as $variant_content_option)
          <div class="d-flex justify-content-between py-1">
            @php
              $variant_option_contents = App\Models\User\ProductVariantOptionContent::where([
                  ['product_variant_option_id', $variant_content_option->id],
                  ['language_id', $language_id],
              ])->first();

              $sup_option_content = make_input_name(@$variant_option_contents->option_content->option_name);

              $id_name = make_input_name(@$variant_option_contents->option_content->option_name);
              $main_id = $sup_option_content . '_' . $id_name;
            @endphp
            <div>
              <input class="voptions form-check-input" data-option_id="{{ $variant_content_option->id }}"
                data-variation_id="{{ $product_variation->id }}" data-option="{{ @$variant_content->name }}"
                data-name="{{ $variant_option_contents->option_content->option_name }}"
                data-price="{{ currency_converter($variant_content_option->price) }}"
                data-stock="{{ $variant_content_option->stock }}" type="radio"
                name="{{ make_input_name(@$variant_content->name) }}[]" value=""
                id="voptions_{{ $main_id }}">
              <label for="voptions_{{ $main_id }}">
                {{ $variant_option_contents->option_content->option_name }}</label>
            </div>

            <span class="variants_price">(<i
                class="fas fa-plus"></i>{{ symbolPrice($user_currency->symbol_position, $user_currency->symbol, currency_converter($variant_content_option->price)) }})</span>
          </div>
        @endforeach
      @endforeach
    </div>
  @endif
@endforeach
