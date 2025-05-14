@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where([
      ['code', \Illuminate\Support\Facades\Session::get('currentLangCode')],
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
  ])->first();
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Posts') }}</h4>
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
        <a href="#">{{ __('Blog') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Posts') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-warning text-big {{ $total_post > $post_limit ? 'd-block' : 'd-none' }}" role="alert">
        <ul>
          <li>{{ __('Currently, You have') }}
            {{ $total_post }}
            {{ $total_post > 1 ? __('Posts') : __('Post') }}.
          </li>
          <li>
            {{ __('Your package supports') }}
            {{ ' ' . $post_limit . ' ' }}
            {{ $post_limit > 1 ? __('Posts') : __('Post') }}.
          </li>
          <li>
            {{ __('Delete') . ' ' }}
            {{ $total_post - $post_limit }}
            {{ $total_post - $post_limit > 1 ? __('Posts') : __('Post') }}
            {{ __('to Enable Edit Button.') }}
          </li>
        </ul>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Posts') }}</div>
            </div>
            <div class="col-lg-3">
              @if (!is_null($userDefaultLang))
                @if (!empty($userLanguages))
                  <select name="userLanguage" class="form-control"
                    onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                    <option value="" selected disabled>
                      {{ __('Select a Language') }}</option>
                    @foreach ($userLanguages as $lang)
                      <option value="{{ $lang->code }}"
                        {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}</option>
                    @endforeach
                  </select>
                @endif
              @endif
            </div>
            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              @if (!is_null($userDefaultLang))
                <a href="{{ route('user.blog.create', ['language' => $userDefaultLang->code]) }}"
                  class="btn btn-primary float-right btn-sm"><i class="fas fa-plus"></i>
                  {{ __('Add Post') }}</a>
                <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                  data-href="{{ route('user.blog.bulk.delete') }}"><i class="flaticon-interface-5"></i>
                  {{ __('Delete') }}</button>
              @endif
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (is_null($userDefaultLang))
                <h3 class="text-center">{{ __('NO LANGUAGE FOUND') }}</h3>
              @else
                @if (count($blogs) == 0)
                  <h3 class="text-center">{{ __('NO BLOG CONTENT FOUND') }}</h3>
                @else
                  <div class="table-responsive">
                    <table class="table table-striped mt-3" id="basic-datatables">
                      <thead>
                        <tr>
                          <th scope="col">
                            <input type="checkbox" class="bulk-check" data-val="all">
                          </th>
                          <th scope="col">{{ __('Image') }}</th>
                          <th scope="col">{{ __('Category') }}</th>
                          <th scope="col">{{ __('Title') }}</th>
                          <th scope="col">{{ __('Serial Number') }}</th>
                          <th scope="col">{{ __('Status') }}</th>
                          <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($blogs as $key => $blog)
                          <tr>
                            <td>
                              <input type="checkbox" class="bulk-check" data-val="{{ $blog->id }}">
                            </td>
                            <td><img src="{{ asset('assets/front/img/user/blogs/' . $blog->image) }}" alt=""
                                width="80"></td>
                            <td>{{ $blog->name }}</td>
                            <td>
                              {{ strlen($blog->title) > 30 ? mb_substr($blog->title, 0, 30, 'UTF-8') . '...' : $blog->title }}
                            </td>
                            <td>{{ $blog->serial_number }}</td>
                            <td>
                              <form action="{{ route('user.blog.status.update') }}" id="StatusForm{{ $blog->id }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $blog->id }}">
                                <select
                                  class="form-control-sm text-white ltr border-0
                                    @if ($blog->status == 1) bg-success
                                    @elseif($blog->status == 0) bg-warning @endif "
                                  name="status"
                                  onchange="document.getElementById('StatusForm{{ $blog->id }}').submit();">
                                  <option value="1" {{ $blog->status == 1 ? 'selected' : '' }}>
                                    {{ __('Active') }}
                                  </option>
                                  <option value="0" {{ $blog->status == 0 ? 'selected' : '' }}>
                                    {{ __('Deactive') }}</option>

                                </select>
                              </form>
                            </td>
                            <td>
                              <a class="btn btn-secondary btn-sm  mb-1" href="{{ route('user.blog.edit', ['id'=>$blog->id,'language'=>request()->input('language')]) }}">
                                <span class="btn-label">
                                  <i class="fas fa-edit"></i>
                                </span></a>
                              <form class="deleteform d-inline-block" action="{{ route('user.blog.delete') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                <button type="submit" class="btn btn-danger btn-sm deletebtn mb-1">
                                  <span class="btn-label">
                                    <i class="fas fa-trash"></i>
                                  </span></button>
                              </form>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @endif
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
