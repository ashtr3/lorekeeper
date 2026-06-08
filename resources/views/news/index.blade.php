@extends('news.layout')

@section('title')
    Site News
@endsection

@section('news-content')
    {!! breadcrumbs(['Site News' => route('browse.news.index')]) !!}
    <h1>Site News</h1>
    @if (count($newses))
        {!! $newses->render() !!}
        @foreach ($newses as $news)
            @include('news._news', ['news' => $news, 'page' => false])
        @endforeach
        {!! $newses->render() !!}
    @else
        <div>No news posts yet.</div>
    @endif
@endsection
