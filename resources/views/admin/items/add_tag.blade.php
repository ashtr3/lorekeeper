@extends('admin.layout')

@section('admin-title')
    Add Item Tag
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => route('admin.index'), 'Items' => route('admin.data.items.index'), 'Edit Item' => route('admin.data.items.edit', $item->id), 'Add Item Tag' => route('admin.data.items.tag.create', $item->id)]) !!}

    <h1>Add Item Tag</h1>

    <p>Select an item tag to add to the item. You cannot add duplicate tags to the same item (they are removed from the selection). You will be taken to the parameter editing page after adding the tag. </p>

    {!! Form::open(['route' => ['admin.data.items.tag.store', $item->id]]) !!}

    <div class="form-group">
        {!! Form::label('tag', 'Tag') !!}
        {!! Form::select('tag', [0 => 'Select a Tag'] + $tags, null, ['class' => 'form-control']) !!}
    </div>

    <div class="text-right">
        {!! Form::submit('Add Tag', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
