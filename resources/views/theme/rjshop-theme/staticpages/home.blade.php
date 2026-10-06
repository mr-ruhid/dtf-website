@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $page->seo_title)
@section('meta_description', $page->seo_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

@foreach($widgets as $widget)
    @includeIf('theme.rjshop-theme.widgets.' . $widget->key, ['widget' => $widget])
@endforeach

@endsection
