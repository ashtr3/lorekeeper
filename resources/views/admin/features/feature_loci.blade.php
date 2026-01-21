@extends('admin.layout')

@section('admin-title')
    Trait Loci
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Trait Loci' => 'admin/data/trait-loci']) !!}

    <h1>Trait Loci</h1>

    <p>This is a list of trait loci that will be used to define trait genetics.</p>
    <p>The sorting order reflects the order in which the trait loci will be displayed in the genotype, as well as on the world pages.</p>

    <div class="text-right mb-3"><a class="btn btn-primary" href="{{ url('admin/data/trait-loci/create') }}"><i class="fas fa-plus"></i> Create New Trait Locus</a></div>
    @if (!count($loci))
        <p>No trait loci found.</p>
    @else
        <table class="table table-sm loci-table">
            <tbody id="sortable" class="sortable">
                @foreach ($loci as $locus)
                    <tr class="sort-item" data-id="{{ $locus->id }}">
                        <td>
                            <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                            @if (!$locus->is_visible)
                                <i class="fas fa-eye-slash mr-1"></i>
                            @endif
                            @if ($locus->is_required)
                                <i class="fas fa-asterisk mr-1"></i>
                            @endif
                            {!! $locus->displayName !!}
                        </td>
                        <td class="text-right">
                            <a href="{{ url('admin/data/trait-loci/edit/' . $locus->id) }}" class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        <div class="mb-4">
            {!! Form::open(['url' => 'admin/data/trait-loci/sort']) !!}
            {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
            {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    @endif

@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
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
