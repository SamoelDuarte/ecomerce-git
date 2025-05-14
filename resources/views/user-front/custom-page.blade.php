@extends('user-front.layout')
@section('breadcrumb_title', !empty($title) ? $title : $page->title)
@section('page-title', !empty($title) ? $title : $page->title)

@section('meta-description', @$meta_description)
@section('meta-keywords', @$meta_keywords)
@section('content')
  <!-- Compare Start -->
  <div class="compare-area ptb-100">
    <div class="container tinymce-content">
      {!! replaceBaseUrl($page->body ?? null) !!}
    </div>
  </div>
  <!-- Compare End -->
@endsection
