@if ($user->is_deactivated)
    <p>This will reactivate the user, allowing them to use the site features again. Are you sure you want to do this?</p>
    {!! Form::open(['route' => ['admin.users.reactivate.store', $user->name]]) !!}
    <div class="text-right">
        {!! Form::submit('Reactivate', ['class' => 'btn btn-danger']) !!}
    </div>
    {!! Form::close() !!}
@else
    <p>This user is not deactivated.</p>
@endif
