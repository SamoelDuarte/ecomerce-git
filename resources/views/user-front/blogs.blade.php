@extends('user-front.layout')

@section('page-title', $pageHeading->blog_page ?? __('Blogs'))
@section('meta-description', !empty($seo) ? $seo->blogs_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->blogs_meta_keywords : '')

@section('breadcrumb_title')
  {{ $pageHeading->blog_page ?? __('Blogs') }}
@endsection
@section('content')
  <!--====== Start saas-blog section ======-->
  <section class="blog-area ptb-100">
    <div class="container">
      @if (count($blogs) > 0)
        <div class="row justify-content-center">
          @foreach ($blogs as $blog)
            <div class="col-md-6 col-sm-6 col-lg-4">
              <article class="card blog-card-style-1 mb-30" data-aos="fade-up" data-aos-delay="100">
                <div class="card-image">
                  <a class="lazy-container ratio ratio-5-3"
                    href="{{ route('user-front.blog_details', ['slug' => $blog->slug, getParam()]) }}">
                    <img class="lazyload w-100" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/front/img/user/blogs/' . $blog->image) }}"
                      alt="Blog Image">
                  </a>
                </div>

                <div class="content">
                  <ul class="info-list">
                    <li class="list-item">
                      <i class="fal fa-user"></i><a href="javascript:void(0)">{{ $blog->author }}</a>
                    </li>
                    <li class="list-item">
                      <i class="fal fa-calendar"></i><a
                        href="javascript:void(0)">{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</a>
                    </li>
                    {{-- <li class="list-item">
                      <i class="fal fa-tag"></i><a
                        href="{{ route('front.user.blogs', [getParam(), 'category' => $blog->categoryId]) }}">{{ $blog->categoryName }}</a>
                    </li> --}}
                  </ul>
                  <h3 class="card-title pb-1 lc-2">
                    <a href="{{ route('user-front.blog_details', ['slug' => $blog->slug, getParam()]) }}">
                      {{ $blog->title }}
                    </a>
                  </h3>
                  <p class="card-text">
                    {!! substr(strip_tags($blog->content), 0, 150) !!}

                  </p>
                  <a href="{{ route('user-front.blog_details', ['slug' => $blog->slug, getParam()]) }}"
                    class="card-btn">{{ $keywords['Read More'] ?? __('Read More') }}</a>
                </div>
              </article>
            </div>
          @endforeach
        </div>
        <div class="pagination mb-30 justify-content-center">
          {{ $blogs->appends(['category' => request()->input('category')])->links() }}
        </div>
      @else
        <h2 class="text-center">{{ $keywords['No Blog Found'] ?? __('No Blog Found') }}</h2>
      @endif
    </div>
  </section>
  <!--====== End saas-blog section ======-->

  <div class="mobile-menu-overlay"></div>
  <!-- Responsive Mobile Menu -->
  <div class="mobile-menu">
    <div class="mobile-menu-wrapper">
      <div class="mobile-menu-top">

        <div class="logo">
          <!-- logo -->
          <a href="{{ route('front.user.detail.view', getParam()) }}" class="logo">
            <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/front/img/user/' . $userBs->logo) }}" alt="logo">
          </a>
        </div>
        <span class="mobile-menu-close"><i class="fal fa-times"></i></span>

      </div>
    </div>
  </div>
  <!-- Responsive Mobile Menu -->

@endsection
