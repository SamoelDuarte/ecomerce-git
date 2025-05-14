@extends('front.layout')

@section('pagename')
  - {{ $pageHeading ?? __('Blogs') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->blogs_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->blogs_meta_keywords : '')

@section('breadcrumb-title')
  {{ $pageHeading ?? __('Blogs') }}
@endsection
@section('breadcrumb-link')
  {{ $pageHeading ?? __('Blogs') }}
@endsection
@section('content')
  <!--====== Start saas-blog section ======-->
  <section class="blog-area pt-120 pb-90">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          @if (count($blogs) == 0)
            <div class="bg-light text-center py-5 d-block w-100">
              <h3>{{ __('NO BLOG FOUND') }}</h3>
            </div>
          @else
            <div class="row justify-content-center">
              @foreach ($blogs as $blog)
                <div class="col-md-6 col-lg-6">
                  <article class="card mb-30" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-image">
                      <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}"
                        class="lazy-container aspect-ratio-16-9">
                        <img class="lazyload lazy-image"
                        src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/front/img/blogs/' . $blog->main_image) }}" alt="Banner Image">
                      </a>
                      <ul class="info-list">
                        <li><i class="fal fa-user"></i>{{ __('Admin') }}</li>
                        <li><i
                            class="fal fa-calendar"></i>{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}
                        </li>
                        <li><a href="{{ route('front.blogs', ['category' => $blog->bcategory_id]) }}"><i
                              class="fal fa-tag"></i>{{ $blog->bcategory->name }}</a></li>
                      </ul>
                    </div>
                    <div class="content">
                      <h3 class="card-title">
                        <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}">
                          {{ $blog->title }}
                        </a>
                      </h3>
                      <p class="card-text">
                        {!! substr(strip_tags($blog->content), 0, 150) !!}

                      </p>
                      <a href="{{ route('front.blogdetails', ['id' => $blog->id, 'slug' => $blog->slug]) }}"
                        class="card-btn">{{ __('Read More') }} <i class="fal fa-long-arrow-right"></i></a>
                    </div>
                  </article>
                </div>
              @endforeach
            </div>
            <div class="pagination mb-30 justify-content-center">
              {{ $blogs->appends(['category' => request()->input('category')])->links() }}
            </div>
          @endif
        </div>
        <div class="col-lg-4 col-md-10 col-sm-12">
          <div class="blog-sidebar">

            <div class="search-box mb-30">
              <div class="search-box-inner">
                <form action="{{ route('front.blogs') }}" method="get">
                  <input type="text" class="search-input" placeholder=" {{ __('Search') }}..." id="sidebarSearch"
                    name="title" value="{{ request()->input('title') }}">
                  <button class="search-button" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </form>
              </div>
            </div>

            <!-- categoryes -->
            <div class="blog-box mb-30">
              <div class="blog-title pl-45">
                <h4 class="title"><i
                    class="fa fa-list {{ $rtl == 1 ? 'mr-20 ml-10' : 'mr-10' }}"></i>{{ __('All Categories') }}
                </h4>
              </div>
              <div class="blog-cat-list pl-45 pr-45">
                <ul>
                  <li class="single-category @if (empty(request()->input('category'))) active @endif"><a
                      href="{{ route('front.blogs') }}"><i class="fal fa-folder"></i> {{ __('All') }}
                      <span></span></a></li>
                  @foreach ($bcats as $key => $bcat)
                    <li class="single-category @if (request()->input('category') == $bcat->id) active @endif">
                      <a href="{{ route('front.blogs', ['category' => $bcat->id]) }}"><i
                          class="fal fa-folder"></i>{{ $bcat->name }} <span></span></a>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Bg Overlay -->
    <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-2.png') }}" alt="Bg">
    <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-1.png') }}" alt="Bg">
    <!-- Bg Shape -->
    <div class="lazyload shape">
      <img class="lazyload shape-1" src="{{ asset('assets/front/images/shape/shape-10.png') }}" alt="Shape">
      <img class="lazyload shape-2" src="{{ asset('assets/front/images/shape/shape-6.png') }}" alt="Shape">
      <img class="lazyload shape-3" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
      <img class="lazyload shape-4" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
      <img class="lazyload shape-5" src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
      <img class="lazyload shape-6" src="{{ asset('assets/front/images/shape/shape-8.png') }}" alt="Shape">
    </div>
  </section>
  <!--====== End saas-blog section ======-->
@endsection
