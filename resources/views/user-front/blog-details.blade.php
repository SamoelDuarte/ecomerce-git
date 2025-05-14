@extends('user-front.layout')

@section('page-title', $blog->title ?? ($keywords['Blog_Details'] ?? __('Blog Details')))

@section('meta-description', !empty($blog) ? $blog->meta_keywords : '')
@section('meta-keywords', !empty($blog) ? $blog->meta_description : '')

@section('og-meta')
  <!--- For Social Media Share Thumbnail --->
  <meta property="og:title" content="{{ $blog->title }}">
  <meta property="og:image" content="{{ asset('assets/front/img/user/blogs/' . $blog->blog->image) }}">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
  <meta name="twitter:card" content="summary_large_image">
  <!--- For Social Media Share Thumbnail --->
@endsection

@section('breadcrumb-title')
  {{ strlen($blog->title) > 30 ? mb_substr($blog->title, 0, 30) . '...' : $blog->title }}
@endsection
@section('breadcrumb-link')
  {{ $keywords['Blog_Details'] ?? __('Blog Details') }}
@endsection
@section('content')

  <div class="page-title-area header-next">
    @if (@$userBe->breadcrumb)
      <img class="bg-img lazyload"
      src="{{ asset('assets/front/images/placeholder.png') }}"
      data-src="{{ asset('assets/front/img/user/' . $userBe->breadcrumb) }}"
        alt="Banner">
    @endif
    <div class="container">
      <div class="content text-start">
        <h2>
          {{ truncateString($blog->title, 40) ??($keywords['Blog_Details'] ?? __('Blog Details')) }}
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb justify-content-start">
            <li class="breadcrumb-item"><a
                href="{{ route('front.user.detail.view', getParam()) }}">{{ $keywords['Home'] ?? __('Home') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">
              {{ $keywords['Blog_Details'] ?? __('Blog Details') }} </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <!--====== BLOG DETAILS PART START ======-->
  <section class="blog-details-area ptb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="blog-details-items">
            <div class="blog-thumb lazy-container ratio ratio-16-9 mb-20">
              <img class="lazyload h-100 w-100" src="{{ asset('assets/front/img/user/blogs/' . $blog->blog->image) }}"
                alt="blog">
            </div>
            <div class="blog-details-content">
              <ul class="info-list">
                <li class="list-item">
                  <i class="fal fa-user"></i><a href="javascript:void(0)">{{ $blog->author }}</a>
                </li>
                <li class="list-item">
                  <i class="fal fa-calendar"></i><a
                    href="javascript:void(0)">{{ \Carbon\Carbon::parse($blog->created_at)->format('jS M ,Y') }}</a>
                </li>
                <li class="list-item">
                  <i class="fal fa-tag"></i><a
                    href="{{ route('front.user.blogs', [getParam(), 'category' => $blog->category_id]) }}">{{ $blog->categoryName }}</a>
                </li>
              </ul>
              <h3 class="title">{{ $blog->title }}</h3>
              <div class="tinymce-content">
                {!! replaceBaseUrl($blog->content ?? null) !!}
              </div>
            </div>



            <!-- blog details comment -->
            <div class="blog-details-comment mt-5">
              <div class="comment-lists">
                <div id="disqus_thread"></div>
              </div>
            </div> <!-- blog details comment -->
          </div>
        </div>
        <div class="col-lg-4">
          @includeIf('user-front.blog-sidebar', [
              'blog_categories' => $blog_categories,
              'latestBlogs' => $latestBlogs,
          ])
        </div>
      </div>
    </div>
  </section>
  <!--====== BLOG DETAILS PART ENDS ======-->
@endsection
@if ($userBs->is_disqus == 1 && in_array('Disqus', $packagePermissions))
  @section('scripts')
    <script>
      "use strict";
      (function() {
        var d = document,
          s = d.createElement('script');
        s.src = 'https://{{ $userBs->disqus_shortname }}.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
      })();
    </script>
  @endsection
@endif
