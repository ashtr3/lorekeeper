@extends('world.layout')

@section('world-title')
    Trait Loci
@endsection

@section('content')
    {!! breadcrumbs(['World' => 'world', 'Trait Loci' => 'world/trait-loci']) !!}
    <h1>Trait Loci</h1>

    <div>
        {!! Form::open(['method' => 'GET', 'class' => 'form-inline justify-content-end']) !!}
        <div class="form-group mr-3 mb-3">
            {!! Form::text('name', Request::get('name'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group mb-3">
            {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    {!! $loci->render() !!}
    @foreach ($loci as $locus)
        <div class="card mb-3">
            <div class="card-body">
                @include('world._feature_locus_entry', ['locus' => $locus])
            </div>
        </div>
    @endforeach
    {!! $loci->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $loci->total() }} result{{ $loci->total() == 1 ? '' : 's' }} found.</div>
@endsection
