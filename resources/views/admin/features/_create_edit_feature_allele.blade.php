@if ($allele && $locus)
    {!! Form::open(['url' => $allele->id ? 'admin/data/trait-loci/edit/' . $locus->id . '/allele/' . $allele->id : 'admin/data/trait-loci/edit/' . $locus->id . '/allele']) !!}
    {!! Form::hidden('feature_locus_id', $locus->id) !!}

    <div class="form-group">
        {!! Form::label('Allele') !!}
        {!! Form::text('allele', $allele->allele, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Description (Optional)') !!}
        {!! Form::textarea('description', $allele->description, ['class' => 'form-control wysiwyg']) !!}
    </div>

    <div class="form-group">
        {!! Form::checkbox('is_visible', 1, $allele->id ? $locus->is_visible : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
        {!! Form::label('is_visible', 'Is Visible', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned off, the allele will not be visible in the allele list or available for selection in search. Permissioned staff will still be able to assign traits to them, however.') !!}
    </div>

    <div class="text-right">
        {!! Form::submit($allele->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@else
    Invalid allele selected.
@endif

<script>
    $(document).ready(function() {
        @include('js._modal_wysiwyg')
    });
</script>
