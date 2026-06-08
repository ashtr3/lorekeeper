@extends('admin.layout')

@section('admin-title')
    Ban User: {{ $user->name }}
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => route('admin.index'), 'User Index' => route('admin.users.index'), $user->name => route('admin.users.edit', $user->name), 'Ban User' => route('admin.users.ban', $user->name)]) !!}

    <h1>User: {!! $user->displayName !!}</h1>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ $user->adminUrl }}">Account</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.updates', $user->name) }}">Account Updates</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.users.ban', $user->name) }}">Ban</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.deactivate', $user->name) }}">Deactivate</a>
        </li>
    </ul>

    <h3>{{ $user->is_banned ? 'Edit ' : '' }}Ban</h3>
    <p>Banning the user will remove their rank, cancel all of their queued submissions and transfers, and prevent them from using any other site features. The ban reason will be displayed on the blacklist.</p>

    {!! Form::open(['route' => ['admin.users.ban.store', $user->name], 'id' => 'banForm']) !!}
    <div class="form-group">
        {!! Form::label('Reason (Optional; no HTML)') !!}
        {!! Form::textarea('ban_reason', $user->settings->ban_reason, ['class' => 'form-control']) !!}
    </div>
    <div class="text-right">
        {!! Form::submit($user->is_banned ? 'Edit' : 'Ban', ['class' => 'btn btn' . ($user->is_banned ? '' : '-outline') . '-danger ban-button']) !!}
    </div>
    {!! Form::close() !!}

    @if ($user->is_banned)
        <h3>Unban</h3>
        <p>Unbanning the user will grant them access to site features again. However, if they had a rank before being banned, it will not be restored.</p>
        <div class="text-right">
            <a href="#" class="btn btn-outline-danger unban-button">Unban</a>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            @if (!$user->is_banned)
                $('.ban-button').on('click', function(e) {
                    e.preventDefault();
                    loadModal("{{ route('admin.users.ban.confirm', $user->name) }}", 'Ban User');
                });
            @else
                $('.unban-button').on('click', function(e) {
                    e.preventDefault();
                    loadModal("{{ route('admin.users.unban.confirm', $user->name) }}", 'Unban User');
                });
            @endif
        });
    </script>
@endsection
