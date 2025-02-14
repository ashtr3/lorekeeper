@extends('admin.layout')

@section('admin-title')
    Character Map Categories
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Character Maps' => 'admin/data/maps', 'Character Map Categories' => 'admin/data/map-categories']) !!}

    <h1>Character Map Categories</h1>

    <p>This is a list of character map categories that will be used to group genetic maps.</p>
    <p>The sorting order reflects the order in which the maps will be displayed in the genotype builder.</p>

    <div class="text-right mb-3"><a class="btn btn-primary add-map-category" href="#"><i class="fas fa-plus"></i> Create New Map Category</a></div>
    @if (!count($categories))
        <p>No trait categories found.</p>
    @else
        <table class="table table-sm category-table">
            <tbody id="sortable" class="sortable">
                @foreach ($categories as $category)
                    <tr class="sort-item" data-id="{{ $category->id }}">
                        <td>
                            <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                            {!! $category->displayName !!}
                        </td>
                        <td class="text-right">
                            <a href="#" class="btn btn-outline-primary edit-map-category" data-id="{{ $category->id }}">Edit</a>
                            <a href="#" class="btn btn-outline-danger delete-map-category" data-id="{{ $category->id }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        <div class="mb-4">
            {!! Form::open(['url' => 'admin/data/map-categories/sort']) !!}
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
            $('.add-map-category').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/map-categories/create') }}", 'Create Character Map Category');
            });
            $('.edit-map-category').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/map-categories/edit') }}/" + $(this).data('id'), 'Edit Character Map Category');
            });
            $('.delete-map-category').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/map-categories/delete') }}/" + $(this).data('id'), 'Delete Character Map Category');
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
