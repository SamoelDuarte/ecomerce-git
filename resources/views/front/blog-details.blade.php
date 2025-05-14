@extends('front.layout')

@section('pagename')
  - {{ $blog->title ?? __('Blog Details') }}
@endsection

@section('meta-description', !empty($blog) ? $blog->meta_keywords : '')
@section('meta-keywords', !empty($blog) ? $blog->meta_description : '')

@section('og-meta')
  <!--- For Social Media Share Thumbnail --->
  <meta property="og:title" content="{{ $blog->title }}">
  <meta property="og:image" content="{{ asset('assets/front/img/blogs/' . $blog->main_image) }}">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
  <!--- For Social Media Share Thumbnail --->
@endsection

@section('breadcrumb-title')
  {{ $blog->title }}
@endsection
@section('breadcrumb-link')
  {{ __('Blog Details') }}
@endsection

@section('content')

  <!--====== BLOG DETAILS PART START ======-->

  <section class="blog-details-area pt-120 pb-120">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="blog-details-items mb-30">
            <div class="blog-thumb mb-3">
              <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}" data-src="{{ asset('assets/front/img/blogs/' . $blog->main_image) }}"
                data-src="{{ asset('assets/front/img/blogs/' . $blog->main_image) }}" alt="blog">
            </div>
            <div class="blog-details-content">
              <ul class="info-list">
                <li class="list-item">
                  <i class="fal fa-user"></i><a href="javascript:void(0)"> {{ __('Admin') }}</a>
                </li>
                <li class="list-item">
                  <i class="fal fa-calendar"></i><a
                    href="javascript:void(0)">{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</a>
                </li>
                <li class="list-item">
                  <i class="fal fa-tag"></i><a
                    href="{{ route('front.blogs', ['category' => $blog->bcategory_id]) }}">{{ $blog->bcategory->name }}</a>
                </li>
              </ul>
              <h3 class="title">{{ $blog->title }}</h3>
              <div class="tinymce-content">
                <p>{!! replaceBaseUrl($blog->content) !!}</p>
              </div>
            </div>

            <div class="blog-social">
              <div class="shop-social d-flex align-items-center">
                <span>{{ __('Share') }} :</span>
                <ul class="ml-3 d-flex social-icons list-unstyled ">
                  <li class="p-1"><a
                      href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                        class="fab fa-facebook-f"></i></a></li>
                  <li class="p-1"><a
                      href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"><i
                        class="fab fa-twitter"></i></a></li>
                  <li class="p-1"><a
                      href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $blog->title }}"><i
                        class="fab fa-linkedin-in"></i></a></li>
                </ul>
              </div>
            </div>

            <div class="blog-details-comment mt-5">
              <div class="comment-lists">
                <div id="disqus_thread"></div>
              </div>
            </div> <!-- blog details comment -->
          </div>
        </div>
        <div class="col-lg-4 col-md-10 col-sm-12">
          @includeIf('front.partials.blog-sidebar')
        </div>
      </div>
    </div>
  </section>

  <!--====== BLOG DETAILS PART ENDS ======-->


@endsection

@if ($bs->is_disqus == 1)
  @section('scripts')
    <script>
      "use strict";
      (function() {
        var d = document,
          s = d.createElement('script');
        s.src = '//{{ $bs->disqus_shortname }}.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
      })();
    </script>
  @endsection
@endif
