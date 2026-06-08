@if ($user->is_banned)
    <p>This will unban the user, removing them from the site blacklist and allowing them to use the site features again. Are you sure you want to do this?</p>
    {!! Form::open(['route' => ['admin.users.unban.store', $user->name]]) !!}
    <div class="text-right">
        {!! Form::submit('Unban', ['class' => 'btn btn-danger']) !!}
    </div>
    {!! Form::close() !!}
@else
    <p>This user is not banned.</p>
@endif
