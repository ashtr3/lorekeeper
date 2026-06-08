@extends('user.layout')

@section('profile-title')
    {{ $user->name }}'s Characters
@endsection

@section('profile-content')
    {!! breadcrumbs(['Users' => route('browse.users'), $user->name => $user->url, 'Characters' => route('browse.user.characters', $user->name)]) !!}

    <h1>
        {!! $user->displayName !!}'s Characters
    </h1>

    @include('user._characters', ['characters' => $characters, 'myo' => false])
@endsection
