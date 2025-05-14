@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where([
      ['user_id', Auth::guard('web')->user()->id],
      ['code', request()->input('language')],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)->get();
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Variations') }}</h4>
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
        <a href="#">{{ __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Variants') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Variations') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Variations') }}</div>
            </div>
            <div class="col-lg-3">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($userLanguages as $language)
                    <option value="{{ $language->code }}"
                      {{ $language->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $language->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('user.variant.create', ['language' => $selLang->code]) }}"
                class="btn btn-primary float-right btn-sm"><i class="fas fa-plus"></i>
                {{ __('Add Variation') }}</a>
              <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                data-href="{{ route('user.variant.bulk_delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($variants) == 0)
                <h3 class="text-center">{{ __('NO VARIANT FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Subcategory') }}</th>
                        <th scope="col">{{ __('Options') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($variants as $key => $item)
                        @php
                          $options = DB::table('variant_option_contents')
                              ->where([['language_id', $selLang->id], ['variant_id', $item->variant_id]])
                              ->get();
                        @endphp
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $item->variant_id }}">
                          </td>
                          <td>
                            {{ $item->name }}
                          </td>
                          <td>{{ @$item->category->name }}</td>
                          <td>{{ @$item->sub_category->name ?? '-' }}</td>
                          <td>
                            @if (count($options) > 0)
                              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#variation-modal_{{ $item->id }}">
                                {{ __('Show') }}
                              </button>
                            @else
                              {{ '-' }}
                            @endif
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Actions') }}
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item"
                                  href="{{ route('user.variant.edit', $item->variant_id) . '?language=' . $selLang->code }}">{{ __('Edit') }}</a>
                                <form class="deleteform d-block"
                                  action="{{ route('user.variant.delete', $item->variant_id) }}" method="post">
                                  @csrf
                                  <button type="submit" class="deletebtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>

                        @if (count($options) > 0)
                          <div class="modal fade" id="variation-modal_{{ $item->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">
                                    {{ __('Options') }}</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  @foreach ($options as $key => $option)
                                    <ul class="list-unstyled">
                                      <li>{{ $key + 1 }}. {{ $option->option_name }}</li>
                                    </ul>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
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
@endsection
