@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Section Hide/Show') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('user-dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Pages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Section Hide/Show') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form class="" action="{{ route('user.sections.update') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Section Hide/Show') }}</div>
              </div>
            </div>
          </div>
          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-6 m-auto">
                @foreach ($sections as $section)
                  @php
                    //label for the section
                    if ($section == 'tab_section') {
                        $label = __('Tabs Section');
                    } elseif ($section == 'cta_section_status') {
                        $label = __('Call To Action Section');
                    } elseif ($section == 'categoryProduct_section') {
                        $label = __('Category Product Section');
                    } else {
                        $label = __(ucwords(str_replace('_', ' ', $section)));
                    }
                    // section status
                    $sectionValue = $ubs->{$section} ?? 0;
                  @endphp
                  <div class="form-group">
                    <label>{{ $label }} <span class="text-danger">**</span></label>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="{{ $section }}" value="1" class="selectgroup-input"
                          {{ $sectionValue == 1 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Active') }}</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="{{ $section }}" value="0" class="selectgroup-input"
                          {{ $sectionValue == 0 ? 'checked' : '' }}>
                        <span class="selectgroup-button">{{ __('Deactive') }}</span>
                      </label>
                    </div>
                  </div>
                @endforeach

                {{-- Handle additional sections --}}
                @if (count($additional_section_statuses) > 0)
                  @foreach ($additional_section_statuses as $key => $additional_section_status)
                    @php
                      $section_content = App\Models\User\AdditionalSectionContent::where([
                          ['language_id', $langid],
                          ['addition_section_id', $key],
                      ])->first();
                    @endphp
                    @if ($section_content)
                      <div class="form-group">
                        <label>{{ $section_content->section_name . ' ' . __('Section') }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="additional_sections[{{ $key }}]" value="1"
                              class="selectgroup-input" {{ $additional_section_status == 1 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Active') }}</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="additional_sections[{{ $key }}]" value="0"
                              class="selectgroup-input" {{ $additional_section_status == 0 ? 'checked' : '' }}>
                            <span class="selectgroup-button">{{ __('Deactive') }}</span>
                          </label>
                        </div>
                      </div>
                    @endif
                  @endforeach
                @endif
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" id="displayNotif" class="btn btn-success">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
