@extends('prompts.layout')

@section('prompts-title')
    Home
@endsection

@section('content')
    {!! breadcrumbs(['Prompts' => route('browse.prompts.index')]) !!}

    <h1>Prompts</h1>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ asset('images/inventory.png') }}" alt="Prompts" />
                    <h5 class="card-title">Prompts</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('browse.prompts.categories') }}">Prompts Categories</a></li>
                    <li class="list-group-item"><a href="{{ route('browse.prompts.list') }}">All Prompts</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
