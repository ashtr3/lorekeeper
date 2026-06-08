@extends('admin.layout')

@section('admin-title')
    Deactivate User: {{ $user->name }}
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => route('admin.index'), 'User Index' => route('admin.users.index'), $user->name => route('admin.users.edit', $user->name), 'Deactivate User' => route('admin.users.deactivate', $user->name)]) !!}

    <h1>User: {!! $user->displayName !!}</h1>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ $user->adminUrl }}">Account</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.updates', $user->name) }}">Account Updates</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.ban', $user->name) }}">Ban</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.users.deactivate', $user->name) }}">Deactivate</a>
        </li>
    </ul>

    <h3>{{ $user->is_deactivated ? 'Edit Deactivation' : 'Deactivate' }}</h3>
    <p>Deactivating the user will remove their rank, cancel all of their queued submissions and transfers, and prevent them from using any other site features. The deactivate reason will be displayed on the blacklist.</p>

    {!! Form::open(['route' => ['admin.users.deactivate.store', $user->name], 'id' => 'deactivateForm']) !!}
    <div class="form-group">
        {!! Form::label('Reason (Optional; no HTML)') !!}
        {!! Form::textarea('deactivate_reason', $user->settings->deactivate_reason, ['class' => 'form-control']) !!}
    </div>
    <div class="text-right">
        {!! Form::submit($user->is_deactivated ? 'Edit' : 'Deactivate', ['class' => 'btn btn' . ($user->is_deactivated ? '' : '-outline') . '-danger deactivate-button']) !!}
    </div>
    {!! Form::close() !!}

    @if ($user->is_deactivated)
        <h3>Reactivate</h3>
        <p>Reactivating the user will grant them access to site features again. However, if they had a rank before being deactivatened, it will not be restored.</p>
        <div class="text-right">
            <a href="#" class="btn btn-outline-danger reactivate-button">Reactivate</a>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            @if (!$user->is_deactivated)
                $('.deactivate-button').on('click', function(e) {
                    e.preventDefault();
                    loadModal("{{ route('admin.users.deactivate.confirm', $user->name) }}", 'Deactivate User');
                });
            @else
                $('.reactivate-button').on('click', function(e) {
                    e.preventDefault();
                    loadModal("{{ route('admin.users.reactivate.confirm', $user->name) }}", 'Reactivate User');
                });
            @endif
        });
    </script>
@endsection
