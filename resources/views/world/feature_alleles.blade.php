@extends('world.layout')

@section('world-title')
    Trait Alleles
@endsection

@section('content')
    {!! breadcrumbs(['World' => 'world', 'Trait Loci' => 'world/trait-alleles']) !!}
    <h1>Trait Alleles</h1>

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

    {!! $alleles->render() !!}
    @foreach ($alleles as $allele)
        <div class="card mb-3">
            <div class="card-body">
                @include('world._feature_allele_entry', ['allele' => $allele])
            </div>
        </div>
    @endforeach
    {!! $alleles->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $alleles->total() }} result{{ $alleles->total() == 1 ? '' : 's' }} found.</div>
@endsection
