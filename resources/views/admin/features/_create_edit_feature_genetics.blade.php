@if ($gene)
    {!! Form::open(['url' => $gene->feature_allele_id ? 'admin/data/traits/edit/' . $gene->feature_id . '/genetics/' . $gene->feature_allele_id : 'admin/data/traits/edit/' . $gene->feature_id . '/genetics']) !!}
    {!! Form::hidden('feature_id', $gene->feature_id) !!}
    
    <div class="row">
        <div class="col-12 col-md-5">
            <div class="form-group">
                {!! Form::label('Locus') !!}
                {!! Form::select('feature_locus_id', $loci, 
                    $gene->feature_allele_id ? $gene->allele->feature_locus_id : null, 
                    ['id' => 'locus', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-12 col-md-7">
            <div class="form-group">
                {!! Form::label('Allele') !!}
                {!! Form::select('feature_allele_id', 
                    ['0' => 'Select Allele'] + ($gene->feature_allele_id ? $gene->allele->locus->alleles->pluck('allele', 'id')->toArray() : []),
                    $gene->feature_allele_id ? $gene->feature_allele_id : null,
                    ['id' => 'allele', 'class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    
    <div class="form-group">
        {!! Form::checkbox('allow_homozygous', 1, $gene->feature_allele_id ? $gene->allow_homozygous : 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
        {!! Form::label('allow_homozygous', 'Allow Homozygous', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned on, character genotypes with 2 instances of the given allele can receive the trait.') !!}
    </div>

    <div class="form-group">
        {!! Form::checkbox('allow_heterozygous', 1, $gene->feature_allele_id ? $gene->allow_heterozygous : 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
        {!! Form::label('allow_heterozygous', 'Allow Heterozygous', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned on, character genotypes with 1 instance of the given allele can receive the trait.') !!}
    </div>

    <div class="form-group">
        {!! Form::checkbox('allow_absent', 1, $gene->feature_allele_id ? $gene->allow_absent : 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
        {!! Form::label('allow_absent', 'Allow Absent', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned on, character genotypes with 0 instances of the given allele can receive the trait.') !!}
    </div>

    <div class="text-right">
        {!! Form::submit($gene->feature_allele_id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@else
    Invalid gene requirement selected.
@endif

<script>
    $(document).ready(function() {
        @include('js._modal_wysiwyg')

        $('#locus').on('change', async function() {
            const id = $(this).val();
            const alleles = await getAlleles(id);

            const select = $('#allele');
            select.empty();
            $.each(alleles, function(key, value) {
                select.append(`<option value="${key}">${value}</option>`);
            });
        });
    });

    function getAlleles(id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "{{ url('admin/data/trait-loci') }}/" + id + "/alleles",
                type: 'GET',
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }
</script>