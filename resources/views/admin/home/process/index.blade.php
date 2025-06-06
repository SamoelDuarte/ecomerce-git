@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Work Process') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
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
        <a href="#">{{ __('Work Process') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-5">
              <div class="card-title d-inline-block">{{ __('Work Process') }}</div>
            </div>
            <div class="col-lg-2">
              @include('admin.partials.languages')
            </div>
            <div class="col-lg-5 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add Work Process') }}</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($processes) == 0)
                <h3 class="text-center">{{ __('NO WORK PROCESS FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Text') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($processes as $key => $process)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td><i class="{{ $process->icon }}"></i></td>
                          <td>{{ $process->title }}</td>
                          <td>{{ $process->text }}</td>
                          <td>{{ $process->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm mb-1"
                              href="{{ route('admin.process.edit', $process->id) . '?language=' . request()->input('language') }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>

                            </a>
                            <form class="deleteform d-inline-block" action="{{ route('admin.process.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="process_id" value="{{ $process->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn  mb-1">
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
      </div>
    </div>
  </div>


  <!-- Create Feature Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Work Process') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxForm" class="modal-form" action="{{ route('admin.process.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Language') }} <span class="text-danger">**</span></label>
              <select name="language_id" class="form-control">
                <option value="" selected disabled>{{ __('Select a Language') }}</option>
                @foreach ($langs as $lang)
                  <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                @endforeach
              </select>
              <p id="errlanguage_id" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Icon') }}</label>
              <div class="btn-group d-block">
                <button type="button" class="btn btn-primary iconpicker-component"><i
                    class="fa fa-fw fa-heart"></i></button>
                <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car"
                  data-toggle="dropdown">
                </button>
                <div class="dropdown-menu"></div>
              </div>
              <input id="inputIcon" type="hidden" name="icon" value="fas fa-heart">
              @if ($errors->has('icon'))
                <p class="mb-0 text-danger">{{ $errors->first('icon') }}</p>
              @endif
              <div class="mt-2">
                <small>{{ __('NB: click on the dropdown sign to select a icon.') }}</small>
              </div>
              <p id="erricon" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
              <input class="form-control" name="title" placeholder="{{ __('Enter title') }}">
              <p id="errtitle" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control jscolor" name="color" value="">
              <p id="errcolor" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Text') }}</label>
              <textarea class="form-control" name="text" placeholder="{{ __('Enter text') }}"></textarea>
              <p id="errtitle" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Serial Number') }}</label>
              <input type="number" class="form-control ltr" name="serial_number" value=""
                placeholder="{{ __('Enter Serial Number') }}">
              <p id="errserial_number" class="mb-0 text-danger em"></p>
              <p class="text-warning">
                <small>{{ __('The higher the serial number is, the later the process will be shown.') }}</small>
              </p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>

@endsection
