@if ($category)
    {!! Form::open(['url' => $category->id ? 'admin/data/map-categories/edit/' . $category->id : 'admin/data/map-categories/create']) !!}

    <div class="form-group">
        {!! Form::label('Name') !!}
        {!! Form::text('name', $category->name, ['class' => 'form-control']) !!}
    </div>

    <div class="text-right">
        {!! Form::submit($category->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid category selected.
@endif
