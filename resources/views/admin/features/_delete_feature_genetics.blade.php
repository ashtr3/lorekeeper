@if ($gene)
    {!! Form::open(['url' => 'admin/data/traits/edit/' . $gene->feature->id . '/genetics/' . $gene->allele->id . '/delete']) !!}

    <p>You are about to delete the genetic requirement for the <strong>{!! $gene->allele->displayName !!}</strong> allele. This is not reversible.</p>
    <p>Are you sure you want to delete the genetic requirement for the <strong>{!! $gene->allele->displayName !!}</strong> allele?</p>

    <div class="text-right">
        {!! Form::submit('Delete Locus', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid locus selected.
@endif
