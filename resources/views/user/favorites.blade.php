@extends('user.layout')

@section('profile-title')
    {{ $user->name }}'s Favorites
@endsection

@section('profile-content')
    @if ($characters)
        {!! breadcrumbs(['Users' => route('browse.users'), $user->name => $user->url, 'Favorites' => route('browse.user.favorites', $user->name), 'Own Characters' => route('browse.user.favorites.own-characters', $user->name)]) !!}
    @else
        {!! breadcrumbs(['Users' => route('browse.users'), $user->name => $user->url, 'Favorites' => route('browse.user.favorites', $user->name)]) !!}
    @endif

    <h1>
        {{ $characters ? 'Own Character ' : '' }}Favorites
    </h1>

    @if ($characters)
        <p>These are {{ Auth::check() && Auth::user()->id == $user->id ? 'your' : $user->name . '\'s' }} favorites which feature <a href="{{ route('browse.user.characters', $user->name) }}">characters
                {{ Auth::check() && Auth::user()->id == $user->id ? 'you' : 'they' }} own</a>.</p>
    @endif

    @if (isset($favorites) && $favorites->count())
        {!! $favorites->render() !!}

        <div class="d-flex align-content-around flex-wrap mb-2">
            @foreach ($favorites as $submission)
                @include('galleries._thumb', ['submission' => $submission, 'gallery' => false])
            @endforeach
        </div>

        {!! $favorites->render() !!}
    @else
        <p>No favorites found!</p>
    @endif

@endsection
