@extends('user.layout')

@section('profile-title')
    {{ $user->name }}'s MYO Slots
@endsection

@section('profile-content')
    {!! breadcrumbs(['Users' => route('browse.users'), $user->name => $user->url, 'MYO Slots' => route('browse.user.myos', $user->name)]) !!}

    <h1>
        {!! $user->displayName !!}'s MYO Slots
    </h1>

    @include('user._characters', ['characters' => $myos, 'myo' => true])
@endsection
