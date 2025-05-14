<section class="custom-section pb-70 {{ $possition }}">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="section-title title-inline mb-10">
          <h2 class="title mb-20">
            {{ @$data->section_name }} </h2>
        </div>
      </div>
      <div class="col-12">
        {!! replaceBaseUrl($data->content ?? null) !!}
      </div>
    </div>
  </div>
</section>
