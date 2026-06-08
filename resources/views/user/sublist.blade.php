@extends('user.layout')

@section('profile-title')
    {{ $user->name }}'s {{ $sublist->name }}
@endsection

@section('profile-content')
    {!! breadcrumbs(['Users' => route('browse.users'), $user->name => $user->url, $sublist->name => route('browse.user.sublist', ['name' => $user->name, 'key' => $sublist->key])]) !!}

    <h1>
        {!! $user->displayName !!}'s {{ $sublist->name }}
    </h1>

    @include('user._characters', ['characters' => $characters, 'myo' => false])
@endsection
