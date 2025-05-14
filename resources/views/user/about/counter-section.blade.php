@extends('user.layout')
@includeIf('user.partials.rtl-style')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Counter Section') }}</h4>
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
        <a href="#">{{ __('Counter Section') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">
                {{ __('Update Counter Section') }}</div>
            </div>
            <div class="col-lg-4">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="about"
                action="{{ route('user.pages.counter_section.update', ['language' => request()->input('language')]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="col-12 mb-2 pl-0">
                        <label for="image">{{ __('Image') }}</label>
                      </div>
                      <div class="col-md-12 showImage mb-3 pl-0  pr-0">
                        <img
                          src="{{ !is_null(@$data->image) ? asset('assets/front/img/user/about/' . @$data->image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                      </div>

                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="image">
                      </div>
                      <p class="text-warning p-0 mb-1">
                        {{ __('Recommended Image size : 670X480') }}
                      </p>
                      @if ($errors->has('image'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('slider_img') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="title">{{ __('Title') }}</label>
                      <input type="text" value="{{ @$data->title }}" class="form-control" name="title"
                        placeholder="{{ __('Enter title') }}">
                      @error('title')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="subtitle">{{ __('Text') }}</label>
                      <input type="text" value="{{ @$data->text }}" class="form-control" name="text"
                        placeholder="{{ __('Enter text') }}">
                      @error('text')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success" form="about">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('Counter Information') }}</div>
            </div>

            <div class="col-lg-4">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>
                    {{ __('Select a Language') }}</option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>

            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm {{ $dashboard_language->rtl == 1 ? 'float-lg-left float-right' : 'float-lg-right float-left' }}"><i
                  class="fas fa-plus"></i>
                {{ __('Add') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('user.pages.counter_section.bulk_delete_counter') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              @if (count($counters) == 0)
                <h3 class="text-center mt-2">
                  {{ __('NO COUNTER INFORMATION FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($counters as $counter)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $counter->id }}">
                          </td>
                          <td><i class="{{ $counter->icon }}"></i></td>
                          <td>{{ $counter->amount }}</td>
                          <td>
                            {{ strlen($counter->title) > 30 ? mb_substr($counter->title, 0, 30, 'UTF-8') . '...' : $counter->title }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1  mt-1 editbtn"
                              href="{{ route('user.pages.counter_section.counter.edit', $counter->id) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('user.pages.counter_section.delete_counter', ['id' => $counter->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger mt-1 btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
        <div class="card-footer"></div>
      </div>
    </div>
  </div>
  @includeIf('user.about.counter.create')
@endsection
