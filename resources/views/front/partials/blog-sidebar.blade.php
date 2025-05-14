<div class="blog-sidebar">

  <div class="search-box mb-30">
    <div class="search-box-inner">
      <input type="text" class="search-input" placeholder=" {{ __('Search') }}..." id="sidebarSearch">
      <button class="search-button" id="searchButton">
        <i class="fa fa-search"></i>
      </button>
    </div>
  </div>

  <!-- categoryes -->
  <div class="blog-box mb-30">
    <div class="blog-title pl-45">
      <h4 class="title"><i class="fa fa-list {{ $rtl == 1 ? 'mr-20 ml-10' : 'mr-10' }}"></i>{{ __('All Categories') }}
      </h4>
    </div>
    <div class="blog-cat-list pl-45 pr-45">
      <ul>
        <li class="single-category @if (empty(request()->input('category'))) active @endif"><a
            href="{{ route('front.blogs') }}"><i class="fal fa-folder"></i> {{ __('All') }} <span></span></a></li>
        @foreach ($bcats as $key => $bcat)
          <li class="single-category @if (request()->input('category') == $bcat->id) active @endif">
            <a href="{{ route('front.blogs', ['category' => $bcat->id]) }}"><i
                class="fal fa-folder"></i>{{ $bcat->name }} <span></span></a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  <!-- article-item-wrapper -->
  <div class="article">
    <h3 class="title">{{ __('Related Posts') }}</h3>
    <div class="article-item-wrapper">
      @if (count($related_blogs) > 0)
        @foreach ($related_blogs as $blog)
          <!-- article-item -->
          <article class="article-item">
            <div class="image">
              <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}"
                class="lazy-container ratio ratio-1-1">
                <img class="lazy-image ls-is-cached lazyload"
                  src="{{ asset('assets/front/img/blogs/' . $blog->main_image) }}"
                  data-src="{{ asset('assets/front/img/blogs/' . $blog->main_image) }}" alt="">
              </a>
            </div>

            <div class="content">
              <h6><a
                  href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}">{{ $blog->title }}</a>
              </h6>
              <div class="time">{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</div>
            </div>
          </article>
        @endforeach
      @else
        <h6>{{ __('No Related Blog Found') }}</h6>
      @endif
    </div>
  </div>
</div>
