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
        <a href="#">{{ __('About Us') }}</a>
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
        <form class="" action="{{ route('user.about.sections.update') }}" method="post">
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
                @csrf
                <div class="form-group">
                  <label>{{ __('About Info Section') }} <span class="text-danger">**</span></label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="about_info_section" value="1" class="selectgroup-input"
                        {{ $ubs->about_info_section == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="about_info_section" value="0" class="selectgroup-input"
                        {{ $ubs->about_info_section == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{ __('Features Section') }} <span class="text-danger">**</span></label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="about_features_section" value="1" class="selectgroup-input"
                        {{ $ubs->about_features_section == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="about_features_section" value="0" class="selectgroup-input"
                        {{ $ubs->about_features_section == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{ __('Counter Section') }} <span class="text-danger">**</span></label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="about_counter_section" value="1" class="selectgroup-input"
                        {{ $ubs->about_counter_section == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="about_counter_section" value="0" class="selectgroup-input"
                        {{ $ubs->about_counter_section == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{ __('Testimonial Section') }} <span class="text-danger">**</span></label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="about_testimonial_section" value="1" class="selectgroup-input"
                        {{ $ubs->about_testimonial_section == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="about_testimonial_section" value="0" class="selectgroup-input"
                        {{ $ubs->about_testimonial_section == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                </div>

                {{-- custom section start --}}
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
                        <label>{{ $section_content->section_name . ' ' . __('Section') }} </label>
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
                {{-- custom section end --}}
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
