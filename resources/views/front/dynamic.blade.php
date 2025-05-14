@extends('front.layout')

@section('pagename')
  - {{ !empty($title) ? $title : $page->title }}
@endsection

@section('meta-description', @$meta_description)
@section('meta-keywords', @$meta_keywords)

@section('breadcrumb-title')
  {{ !empty($title) ? $title : $page->title }}
@endsection
@section('breadcrumb-link')
  {{ !empty($title) ? $title : $page->title }}
@endsection

@section('content')

  <!--====== Start faqs-section ======-->
  <section class="faqs-section pt-120 pb-120">
    <div class="container">
      <div class="tinymce-content">
        {!! replaceBaseUrl($page->body) !!}
      </div>
    </div>
  </section><!--====== End faqs-section ======-->
@endsection
