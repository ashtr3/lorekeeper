@extends('prompts.layout')

@section('title')
    {{ $prompt->name }}
@endsection

@section('content')
    {!! breadcrumbs(['Prompts' => route('browse.prompts.index'), 'All Prompts' => route('browse.prompts.list'), $prompt->name => route('browse.prompts.show', $prompt->id)]) !!}
    @include('prompts._prompt_entry', ['prompt' => $prompt, 'isPage' => true])
@endsection
