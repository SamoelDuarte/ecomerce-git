<div class="blog-sidebar-wrapper">

  <div class="d-flex align-items-center gap-3">
    <h3 class="mb-0">{{ $keywords['Share'] ?? __('Share') }} :</h3>
    <ul class="list-unstyled social-link">
      <li><a href="//www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&src=sdkpreparse"><i
            class="fab fa-facebook-f"></i>
        </a>
      </li>
      <li><a href="//twitter.com/intent/tweet?text={{ urlencode('Check this out! ' . url()->current()) }}">
          <i class="fab fa-twitter"></i></a>
      </li>
      <li>
        <a href="//www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($blog->title) }}"
          target="_blank">
          <i class="fab fa-linkedin-in"></i></a>
      </li>
    </ul>
  </div>

  <div class="blog-sidebar">
    <div class="blog-box blog-border">
      <div class="blog-title pl-45">
        <h4 class="title"><i
            class="fa fa-list {{ $rtl == 1 ? 'mr-20 ml-10' : 'mr-10' }}"></i>{{ $keywords['All Categories'] ?? __('All Categories') }}
        </h4>
      </div>
      <div class="blog-cat-list pl-45 pr-45">
        <ul>
          <li class="single-category @if (empty(request()->input('category'))) active @endif">
            <a href="{{ route('front.user.blogs', getParam()) }}">
              <i class="fal fa-folder"></i> {{ $keywords['All'] ?? __('All') }}</a>
          </li>
          @foreach ($blog_categories as $key => $bcat)
            <li class="single-category @if (request()->input('category') == $bcat->id) active @endif">
              <a href="{{ route('front.user.blogs', [getParam(), 'category' => $bcat->id]) }}">
                <i class="fal fa-folder"></i> {{ $bcat->name }}</a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <div class="blog-sidebar">
    <!-- article-item-wrapper -->
    <div class="article">
      <h3 class="title">{{ $keywords['Recent Posts'] ?? __('Recent Posts') }}</h3>
      <div class="article-item-wrapper">
        <!-- article-item -->
        @foreach ($latestBlogs as $latestBlog)
          <article class="article-item">
            <div class="image">
              <a href="{{ route('user-front.blog_details', ['slug' => $latestBlog->slug, getParam()]) }}"
                class="lazy-container ratio ratio-1-1">
                <img class="lazy-image ls-is-cached lazyload"
                src="{{ asset('assets/front/images/placeholder.png') }}"
                  data-src="{{ asset('assets/front/img/user/blogs/' . @$latestBlog->blog->image) }}" alt="Blog Image">
              </a>
            </div>
            <div class="content">
              <h4 class="article-item-title lc-2"><a
                  href="{{ route('user-front.blog_details', ['slug' => $latestBlog->slug, getParam()]) }}">{{ @$latestBlog->title }}</a>
              </h4>
              <h5 class="time">{{ \Carbon\Carbon::parse(@$latestBlog->created_at)->format('d-m-Y h:i A') }}</h5>
            </div>
          </article>
        @endforeach


      </div>
    </div>
  </div>

</div>
