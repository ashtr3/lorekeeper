@extends('admin.layout')

@section('admin-title')
    {{ $category->id ? 'Edit' : 'Create' }} Prompt Category
@endsection

@section('admin-content')
    {!! breadcrumbs([
        'Admin Panel' => route('admin.index'),
        'Prompts' => route('admin.data.prompts.index'),
        'Prompt Categories' => route('admin.data.prompt-categories.index'),
        ($category->id ? 'Edit' : 'Create') . ' Category' => $category->id ? route('admin.data.prompt-categories.edit', $category->id) : route('admin.data.prompt-categories.create'),
    ]) !!}

    <h1>{{ $category->id ? 'Edit' : 'Create' }} Prompt Category
        @if ($category->id)
            <a href="#" class="btn btn-danger float-right delete-category-button">Delete Category</a>
        @endif
    </h1>

    {!! Form::open(['url' => $category->id ? route('admin.data.prompt-categories.update', $category->id) : route('admin.data.prompt-categories.store'), 'files' => true]) !!}

    <h3>Basic Information</h3>

    <div class="form-group">
        {!! Form::label('Name') !!}
        {!! Form::text('name', $category->name, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('World Page Image (Optional)') !!} {!! add_help('This image is used only on the world information pages.') !!}
        <div class="custom-file">
            {!! Form::label('image', 'Choose file...', ['class' => 'custom-file-label']) !!}
            {!! Form::file('image', ['class' => 'custom-file-input']) !!}
        </div>
        <div class="text-muted">Recommended size: 200px x 200px</div>
        @if ($category->has_image)
            <div class="form-check">
                {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
                {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
            </div>
        @endif
    </div>

    <div class="form-group">
        {!! Form::label('Description (Optional)') !!}
        {!! Form::textarea('description', $category->description, ['class' => 'form-control wysiwyg']) !!}
    </div>

    <div class="text-right">
        {!! Form::submit($category->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

    @if ($category->id)
        <h3>Preview</h3>
        <div class="card mb-3">
            <div class="card-body">
                @include('prompts._entry', ['imageUrl' => $category->categoryImageUrl, 'name' => $category->displayName, 'description' => $category->parsed_description])
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            @if($category->id)
                $('.delete-category-button').on('click', function(e) {
                    e.preventDefault();
                    loadModal("{{ route('admin.data.prompt-categories.delete', $category->id) }}", 'Delete Category');
                });
            @endif
        });
    </script>
@endsection
