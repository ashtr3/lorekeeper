@extends('world.layout')

@section('world-title')
    Home
@endsection

@section('content')
    {!! breadcrumbs(['Encyclopedia' => route('browse.world.index')]) !!}

    <h1>World</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ asset('images/characters.png') }}" alt="Characters" />
                    <h5 class="card-title">Characters</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('browse.world.species') }}">Species</a></li>
                    <li class="list-group-item"><a href="{{ route('browse.world.subtypes') }}">Subtypes</a></li>
                    <li class="list-group-item"><a href="{{ route('browse.world.rarities') }}">Rarities</a></li>
                    <li class="list-group-item"><a href="{{ route('browse.world.trait-categories') }}">Trait Categories</a></li>
                    <li class="list-group-item"><a href="{{ route('browse.world.traits') }}">All Traits</a></li>
                    <li class="list-group-item"><a href="{{ route('browse.world.character-categories') }}">Character Categories</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ asset('images/inventory.png') }}" alt="Items" />
                    <h5 class="card-title">Items</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('browse.world.item-categories') }}">Item Categories</a></li>
                    <li class="list-group-item"><a href="{{ route('browse.world.items') }}">All Items</a></li>
                    <li class="list-group-item"><a href="{{ route('browse.world.currencies') }}">Currencies</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
