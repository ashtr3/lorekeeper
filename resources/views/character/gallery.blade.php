@extends('character.layout', ['isMyo' => $character->is_myo_slot])

@section('profile-title')
    {{ $character->fullName }}'s Gallery
@endsection

@section('meta-img')
    {{ $character->image->thumbnailUrl }}
@endsection

@section('profile-content')
    {!! breadcrumbs([
        $character->category->masterlist_sub_id ? $character->category->sublist->name . ' Masterlist' : 'Character masterlist' => $character->category->masterlist_sub_id ? route('browse.sublist', $character->category->sublist->key) : route('browse.masterlist'),
        $character->fullName => $character->url,
        'Gallery' => route('browse.character.gallery', $character->slug),
    ]) !!}

    @include('character._header', ['character' => $character])

    <p>These images are user-submitted and should not be confused with the official record of the character's design and history visible <a href="{{ route('browse.character.images', $character->slug) }}">here</a>.</p>

    @if ($character->gallerySubmissions->count())
        {!! $submissions->render() !!}

        <div class="d-flex align-content-around flex-wrap mb-2">
            @foreach ($submissions as $submission)
                @include('galleries._thumb', ['submission' => $submission, 'gallery' => false])
            @endforeach
        </div>

        {!! $submissions->render() !!}
    @else
        <p>No submissions found!</p>
    @endif

@endsection
