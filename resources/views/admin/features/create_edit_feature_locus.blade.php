@extends('admin.layout')

@section('admin-title')
    {{ $locus->id ? 'Edit' : 'Create' }} Trait Locus
@endsection

@section('admin-content')
    {!! breadcrumbs([
        'Admin Panel' => 'admin',
        'Trait Loci' => 'admin/data/trait-loci',
        ($locus->id ? 'Edit' : 'Create') . ' Locus' => $locus->id ? 'admin/data/trait-loci/edit/' . $locus->id : 'admin/data/trait-loci/create',
    ]) !!}

    <h1>{{ $locus->id ? 'Edit' : 'Create' }} Trait Locus
        @if ($locus->id)
            <a href="#" class="btn btn-danger float-right delete-locus-button">Delete Locus</a>
        @endif
    </h1>

    {!! Form::open(['url' => $locus->id ? 'admin/data/trait-loci/edit/' . $locus->id : 'admin/data/trait-loci/create']) !!}

    <h3>Basic Information</h3>

    <div class="form-group">
        {!! Form::label('Name') !!}
        {!! Form::text('name', $locus->name, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Default Allele') !!}
        {!! Form::text('default_allele', $locus->default_allele ?? 'n', ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Description (Optional)') !!}
        {!! Form::textarea('description', $locus->description, ['class' => 'form-control wysiwyg']) !!}
    </div>

    <div class="d-flex">
        <div class="form-group mr-3">
            {!! Form::checkbox('default_allele_leads', 1, $locus->id ? $locus->default_allele_leads : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('default_allele_leads', 'Default Allele Leads', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned on, the default allele will always appear at the front of a heterozygous genotype, otherwise it will be trailing.') !!}
        </div>
        <div class="form-group mr-3">
            {!! Form::checkbox('restrict_chimeric', 1, $locus->id ? $locus->restrict_chimeric : 0, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('restrict_chimeric', 'Restrict Chimerism', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned on, this locus cannot be added to the secondary genotype caused by chimerism.') !!}
        </div>
        <div class="form-group">
            {!! Form::checkbox('is_visible', 1, $locus->id ? $locus->is_visible : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
            {!! Form::label('is_visible', 'Is Visible', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned off, the locus will not be visible in the locus list or available for selection in search. Permissioned staff will still be able to add traits to them, however.') !!}
        </div>
    </div>

    <div class="text-right">
        {!! Form::submit($locus->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

    @if ($locus->id)
        <hr>
        <h3>Alleles</h3>
        <div class="text-right mb-3"><a class="btn btn-primary create-allele-button" href="#"><i class="fas fa-plus"></i> Create New Allele</a></div>
        @if (!count($locus->alleles))
            <p>No alleles found.</p>
        @else
            <table class="table table-sm alleles-table">
                <tbody id="sortable" class="sortable">
                    @foreach ($locus->alleles as $allele)
                        <tr class="sort-item" data-id="{{ $allele->id }}">
                            <td>
                                <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                                @if (!$allele->is_visible)
                                    <i class="fas fa-eye-slash mr-1"></i>
                                @endif
                                {!! $allele->displayName !!}
                            </td>
                            <td class="text-right">
                                <a href="#" class="btn btn-primary edit-allele-button" data-id="{{ $allele->id }}">Edit</a>
                                <a href="#" class="btn btn-danger delete-allele-button" data-id="{{ $allele->id }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mb-4">
                {!! Form::open(['url' => 'admin/data/trait-loci/edit/' . $locus->id . '/allele/sort']) !!}
                {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
                {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        @endif
        <hr>
    @endif

    @if ($locus->id)
        <h3>Preview</h3>
        <div class="card mb-3">
            <div class="card-body">
                @include('world._entry', [
                    'imageUrl' => null,
                    'name' => $locus->displayName,
                    'description' => $locus->parsed_description,
                    'searchUrl' => $locus->searchUrl,
                    'visible' => $locus->is_visible,
                ])
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.delete-locus-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/trait-loci/delete') }}/{{ $locus->id }}", 'Delete Locus');
            });
            $('.create-allele-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/trait-loci/edit') }}/{{ $locus->id }}/allele", 'Create Allele');
            });
            $('.edit-allele-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/trait-loci/edit') }}/{{ $locus->id }}/allele/" + $(this).data('id'), 'Edit Allele');
            });
            $('.delete-allele-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/trait-loci/edit') }}/{{ $locus->id }}/allele/" + $(this).data('id') + "/delete", 'Delete Allele');
            });
            $('.handle').on('click', function(e) {
                e.preventDefault();
            });
            $("#sortable").sortable({
                items: '.sort-item',
                handle: ".handle",
                placeholder: "sortable-placeholder",
                stop: function(event, ui) {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                },
                create: function() {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                }
            });
            $("#sortable").disableSelection();
        });
    </script>
@endsection
