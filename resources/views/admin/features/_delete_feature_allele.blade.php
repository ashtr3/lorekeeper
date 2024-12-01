@if ($allele)
    {!! Form::open(['url' => 'admin/data/trait-loci/edit/' . $allele->feature_locus_id . '/allele/' . $allele->id . '/delete']) !!}

    <p>You are about to delete the allele <strong>{{ $allele->allele }}</strong>. This is not reversible. If traits assigned to this allele exist, you will not be able to delete this allele.</p>
    <p>Are you sure you want to delete <strong>{{ $allele->allele }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Allele', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid allele selected.
@endif
