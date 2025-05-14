@foreach ($variants as $variant)
  <div class="widget widget-color mb-40 mt-4">
    <h3 class="title color-primary">
      <button class="accordion-button" type="button" data-bs-toggle="collapse"
        data-bs-target="#variant_{{ $variant->id }}" aria-expanded="true" aria-controls="variant_{{ $variant->id }}">
        {{ $variant->name }}
      </button>
    </h3>
    @php
      $variant_option_contents = App\Models\VariantOptionContent::where([
          ['variant_id', $variant->variant_id],
          ['language_id', $uLang],
      ])->get();
    @endphp
    <div id="variant_{{ $variant->id }}" class="collapse show">
      <ul class="list-group custom-checkbox">
        @foreach ($variant_option_contents as $variant_option_content)
          <li>
            <input class="input-checkbox variants-input" type="checkbox" name="variations[]"
              id="{{ make_input_name($variant_option_content->option_name) }}_{{ $variant_option_content->id }}"
              value="{{ $variant_option_content->id }}:{{ $variant->variant_id }}">
            <label class="form-check-label"
              for="{{ make_input_name($variant_option_content->option_name) }}_{{ $variant_option_content->id }}"><span>{{ $variant_option_content->option_name }}</span>
              <span class="qty"></span>
            </label>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
@endforeach
