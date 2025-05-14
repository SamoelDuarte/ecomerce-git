@php
  $all_padding_top_bottom = [
      'hero_section',
      'partner_section',
      'work_process_section',
      'template_section',
      'testimonial_section',
      'counter_section',
  ];
@endphp
<section class="store-area {{ $possition }}  {{ in_array($possition, $all_padding_top_bottom) ? 'pt-90' : '' }}">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title mw-100" data-aos="fade-up" data-aos-delay="100">
          <h2 class="title">{{ $data->section_name }}</h2>
        </div>
        <div data-aos="fade-up" data-aos-delay="100">
          {!! replaceBaseUrl($data->content) !!}
        </div>
      </div>

    </div>
  </div>
</section>
