@php
  $layoutDirectory = null;
  $pageTitle = App\Models\Admin\Heading::where('language_id', $currentLang->id)->pluck('not_found_title')->first();
  $user = App\Models\User::where('username', getParam())->first();
  if ($user) {
      $userCurrentLang = App\Http\Helpers\Common::getUserCurrentLanguage($user->id);
      if ($userCurrentLang) {
          $pageTitle = App\Models\User\UserHeading::where([
              ['language_id', $userCurrentLang->id],
              ['user_id', $user->id],
          ])
              ->pluck('not_found_page')
              ->first();
      }
  }
  $layoutDirectory = !is_null($user) ? 'user-front.layout' : 'front.layout';
  $breadcrumb_title = !is_null($user) ? 'breadcrumb_title' : 'breadcrumb-title';
  $breadcrumb_link = !is_null($user) ? 'page-title' : 'breadcrumb-link';
@endphp
@extends($layoutDirectory)

@section('pagename')
  - {{ $pageTitle ?? __('404') }}
@endsection

@section($breadcrumb_title)
  {{ $pageTitle ?? __('404') }}
@endsection

@section($breadcrumb_link)
  {{ $pageTitle ?? __('404') }}
@endsection

@section('content')
  <!--    Error section start   -->
  <div class="error-section pt-100 pb-100 pb-90 pt-90">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="not-found">
            @if ($layoutDirectory == 'user-front.layout')
              @php
                $user = getUser();
                $userCurrentLang = null;
                $userCurrentLang = App\Http\Helpers\Common::getUserCurrentLanguage($user->id);
                $data = DB::table('user_basic_extendes')
                    ->where([['user_id', $user->id], ['language_id', $userCurrentLang->id]])
                    ->select('user_not_found_title', 'user_not_found_subtitle')
                    ->first();
                $image = DB::table('user_basic_settings')
                    ->where('user_id', $user->id)
                    ->pluck('page_not_found_image')
                    ->first();
              @endphp
              <img src="{{ asset('assets/user-front/images/' . @$image) }}" alt="">
            @else
              <img src="{{ asset('assets/front/img/404.png') }}" alt="">
            @endif
          </div>
        </div>
        <div class="col-lg-6">
          <div class="error-txt mb-20">
            @if ($layoutDirectory == 'user-front.layout')
              @php
                $keywords = App\Http\Helpers\Common::get_keywords($user->id);
              @endphp
              <h2>{{ $data->user_not_found_title ?? ($keywords['youare_lost'] ?? __("You're lost")) }}...</h2>
              <p>
                {{ $data->user_not_found_subtitle ?? ($keywords['The page you are looking for might have been moved, renamed, or might never have existed.'] ?? __('The page you are looking for might have been moved, renamed, or might never have existed.')) }}
              </p>

              <a href="{{ route('front.user.detail.view', getParam()) }}"
                class="btn btn-md btn-primary radius-sm">{{ $keywords['Back Home'] ?? __('Back Home') }}</a>
            @else
              @php
                if (session()->has('lang')) {
                    app()->setLocale(session()->get('lang'));
                } else {
                    $defaultLang = App\Models\Language::where('is_default', 1)->first();
                    if (!empty($defaultLang)) {
                        app()->setLocale($defaultLang->code);
                    }
                }
              @endphp
              <h2>{{ __("You're lost") }}...</h2>
              <p>{{ __('The page you are looking for might have been moved, renamed, or might never existed.') }}</p>
              <a href="{{ route('front.index') }}" class="btn btn-md btn-primary radius-sm">{{ __('Back Home') }}</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--    Error section end   -->
@endsection
