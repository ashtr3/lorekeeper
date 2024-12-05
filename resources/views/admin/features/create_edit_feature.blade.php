@extends('admin.layout')

@section('admin-title')
    {{ $feature->id ? 'Edit' : 'Create' }} Trait
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Traits' => 'admin/data/traits', ($feature->id ? 'Edit' : 'Create') . ' Trait' => $feature->id ? 'admin/data/traits/edit/' . $feature->id : 'admin/data/traits/create']) !!}

    <h1>{{ $feature->id ? 'Edit' : 'Create' }} Trait
        @if ($feature->id)
            <a href="#" class="btn btn-danger float-right delete-feature-button">Delete Trait</a>
        @endif
    </h1>

    {!! Form::open(['url' => $feature->id ? 'admin/data/traits/edit/' . $feature->id : 'admin/data/traits/create', 'files' => true]) !!}

    <h3>Basic Information</h3>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Name') !!}
                {!! Form::text('name', $feature->name, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('Rarity') !!}
                {!! Form::select('rarity_id', $rarities, $feature->rarity_id, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('World Page Image (Optional)') !!} {!! add_help('This image is used only on the world information pages.') !!}
        <div>{!! Form::file('image') !!}</div>
        <div class="text-muted">Recommended size: 200px x 200px</div>
        @if ($feature->has_image)
            <div class="form-check">
                {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
                {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Trait Category (Optional)') !!}
                {!! Form::select('feature_category_id', $categories, $feature->feature_category_id, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Species Restriction (Optional)') !!}
                {!! Form::select('species_id', $specieses, $feature->species_id, ['class' => 'form-control', 'id' => 'species']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group" id="subtypes">
                {!! Form::label('Subtype (Optional)') !!} {!! add_help('This is cosmetic and does not limit choice of traits in selections.') !!}
                {!! Form::select('subtype_id', $subtypes, $feature->subtype_id, ['class' => 'form-control', 'id' => 'subtype']) !!}
            </div>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('Trait Overrides (Optional)') !!} {!! add_help('These traits will be hidden if they appear on the same character as this trait.') !!}
        {!! Form::select('trait_overrides[]', $features, $feature->hides->pluck('id'), ['id' => 'feature-override-select', 'class' => 'form-control', 'placeholder' => 'Select Features', 'multiple']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Description (Optional)') !!}
        {!! Form::textarea('description', $feature->description, ['class' => 'form-control wysiwyg']) !!}
    </div>

    <div class="d-flex">
        <div class="form-group mr-3">
            {!! Form::checkbox('is_visible', 1, $feature->id ? $feature->is_visible : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_visible', 'Is Visible', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned off, the trait will not be visible in the trait list or available for selection in search and design updates. Permissioned staff will still be able to add them to characters, however.') !!}
        </div>
        <div class="form-group">
            {!! Form::checkbox('is_genetic', 1, $feature->id ? $feature->is_genetic : 1, ['class' => 'is-genetic-check form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_genetic', 'Is Genetic', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned on, the trait will not be manually assignable. It will automatically be applied to characters meeting the trait\'s genetic requirements.') !!}
        </div>
    </div>

    <div class="genetic-settings @if (!$feature->is_genetic) hide @endif">
        <hr>
        <div class="d-flex align-items-end justify-content-between mb-3">
            <div>
                <h3>Genetic Requirements</h3>
                <p>A character will be automatically assigned this trait if they meet the genetic requirements.</p>
            </div>
            <a id="add-genetic-requirement" class="btn btn-primary" href="#"><i class="fas fa-plus"></i> Add Genetic Requirement</a>
        </div>
        <div id="genetic-requirement-list">
            @foreach ($feature->genetics as $index => $gene)
                <div data-id="{{ $index }}" class="d-flex mb-2">
                    {!! Form::select("gene_requirements[$index][locus_id]", $loci, $gene->allele->feature_locus_id, ['class' => 'form-control mr-2 locus-select', 'placeholder' => 'Select Locus']) !!}
                    {!! Form::select("gene_requirements[$index][allele_id]", $gene->allele->locus->alleles->pluck('allele', 'id'), $gene->feature_allele_id, ['class' => 'form-control mr-2 allele-select', 'placeholder' => 'Select Allele']) !!}
                    <div class="mr-2">
                        {!! Form::checkbox("gene_requirements[$index][allow_homozygous]", 1, $gene->allow_homozygous ? 1 : 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'data-on' => 'Homozygous', 'data-off' => 'No Homozygous', 'data-width' => 150]) !!}
                    </div>
                    <div class="mr-2">
                        {!! Form::checkbox("gene_requirements[$index][allow_heterozygous]", 1, $gene->allow_heterozygous ? 1 : 0, [
                            'class' => 'form-check-input',
                            'data-toggle' => 'toggle',
                            'data-on' => 'Heterozygous',
                            'data-off' => 'No Heterozygous',
                            'data-width' => 150,
                        ]) !!}
                    </div>
                    <div class="mr-2">
                        {!! Form::checkbox("gene_requirements[$index][allow_absent]", 1, $gene->allow_absent ? 1 : 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'data-on' => 'Absent', 'data-off' => 'No Absent', 'data-width' => 100]) !!}
                    </div>
                    <a href="#" class="remove-genetic-requirement btn btn-danger mb-2">×</a>
                </div>
            @endforeach
            <div class="genetic-requirement-row hide d-flex mb-2">
                {!! Form::select('gene_requirements[][locus_id]', $loci, null, ['class' => 'form-control mr-2 locus-select', 'placeholder' => 'Select Locus']) !!}
                {!! Form::select('gene_requirements[][allele_id]', [], null, ['class' => 'form-control mr-2 allele-select', 'placeholder' => 'Select Allele']) !!}
                <div class="mr-2">
                    {!! Form::checkbox('gene_requirements[][allow_homozygous]', 1, 0, ['class' => 'form-check-input', 'data-on' => 'Homozygous', 'data-off' => 'No Homozygous', 'data-width' => 150]) !!}
                </div>
                <div class="mr-2">
                    {!! Form::checkbox('gene_requirements[][allow_heterozygous]', 1, 0, ['class' => 'form-check-input', 'data-on' => 'Heterozygous', 'data-off' => 'No Heterozygous', 'data-width' => 150]) !!}
                </div>
                <div class="mr-2">
                    {!! Form::checkbox('gene_requirements[][allow_absent]', 1, 0, ['class' => 'form-check-input', 'data-on' => 'Absent', 'data-off' => 'No Absent', 'data-width' => 100]) !!}
                </div>
                <a href="#" class="remove-genetic-requirement btn btn-danger mb-2">×</a>
            </div>
        </div>
    </div>

    <hr>
    <div class="text-right">
        {!! Form::submit(($feature->id ? 'Edit' : 'Create') . ' Feature', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

    @if ($feature->id)
        <h3>Preview</h3>
        <div class="card mb-3">
            <div class="card-body">
                @include('world._feature_entry', ['feature' => $feature])
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('#feature-override-select').selectize();
            $('.is-genetic-check').on('change', function(e) {
                $('.genetic-settings').toggleClass('hide');
            });
            $('#genetic-requirement-list [data-id]').each(function() {
                addLocusListener($(this));
            });
            $('#add-genetic-requirement').on('click', function(e) {
                e.preventDefault();
                addRow('genetic-requirement-row', 'genetic-requirement-list', 'remove-genetic-requirement');
            });
            $('.remove-genetic-requirement').on('click', function(e) {
                e.preventDefault();
                removeRow($(this));
            });
            $('.delete-feature-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/traits/delete') }}/{{ $feature->id }}", 'Delete Trait');
            });
            refreshSubtype();
        });

        $("#species").change(function() {
            refreshSubtype();
        });

        function refreshSubtype() {
            var species = $('#species').val();
            var subtype_id = {{ $feature->subtype_id ?: 'null' }};
            $.ajax({
                type: "GET",
                url: "{{ url('admin/data/traits/check-subtype') }}?species=" + species + "&subtype_id=" + subtype_id,
                dataType: "text"
            }).done(function(res) {
                $("#subtypes").html(res);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert("AJAX call failed: " + textStatus + ", " + errorThrown);
            });
        };

        function getNextIndex() {
            const rows = $('[data-id]');
            return rows.length > 0 ?
                Math.max(...$.map($('[data-id]'), function(element) {
                    return parseInt($(element).data('id'), 10);
                })) + 1 : 0;
        }

        function addLocusListener(row) {
            row.find('.locus-select').on('change', async function(e) {
                const id = $(this).val();
                const select = row.find('.allele-select');

                if (id) {
                    const alleles = await refreshAlleles(id);
                    select.empty();
                    $.each(alleles, function(key, value) {
                        select.append(`<option value="${key}">${value}</option>`);
                    });
                } else {
                    select.empty();
                    select.append('<option>Select Allele</option>');
                }
            });
        }

        function refreshAlleles(id) {
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
        };

        function addRow(row, list, remove_btn) {
            var clone = $(`.${row}`).clone();
            $(`#${list}`).append(clone);

            const nextIndex = getNextIndex();
            clone.attr('data-id', nextIndex);
            clone.removeClass(`hide ${row}`);

            clone.find(`.${remove_btn}`).on('click', function(e) {
                e.preventDefault();
                removeRow($(this));
            });

            clone.find('select, input').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace('[]', `[${nextIndex}]`);
                $(this).attr('name', newName);
            });

            clone.find('input.form-check-input').each(function() {
                $(this).bootstrapToggle();
            });

            addLocusListener(clone);
        }

        function removeRow(trigger) {
            trigger.parent().remove();
        }
    </script>
@endsection
