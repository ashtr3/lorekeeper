@if ($locus)
    {!! Form::open(['url' => 'admin/data/trait-loci/delete/' . $locus->id]) !!}

    <p>You are about to delete the locus <strong>{{ $locus->name }}</strong>. This is not reversible. If alleles in this locus exist, you will not be able to delete this category.</p>
    <p>Are you sure you want to delete <strong>{{ $locus->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Locus', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid locus selected.
@endif
