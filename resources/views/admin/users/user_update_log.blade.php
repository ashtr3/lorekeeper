@extends('admin.layout')

@section('admin-title')
    User Account Updates: {{ $user->name }}
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => route('admin.index'), 'User Index' => route('admin.users.index'), $user->name => route('admin.users.edit', $user->name), 'Account Updates' => route('admin.users.updates', $user->name)]) !!}

    <h1>User: {!! $user->displayName !!}</h1>
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ $user->adminUrl }}">Account</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.users.updates', $user->name) }}">Account Updates</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.ban', $user->name) }}">Ban</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.deactivate', $user->name) }}">Deactivate</a>
        </li>
    </ul>

    <h3>Account Updates</h3>
    <p>This is a list of changes that have been made to this account's information, whether by the user or by a staff member.</p>

    {!! $logs->render() !!}
    <div class="mb-4 logs-table">
        <div class="logs-table-header">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="logs-table-cell">Staff Member</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="logs-table-cell">Type</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="logs-table-cell">Data</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="logs-table-cell">Date</div>
                </div>
            </div>
        </div>
        <div class="logs-table-body">
            @foreach ($logs as $log)
                <div class="row">
                    <div class="col-6 col-md-3">
                        <div class="logs-table-cell">{!! $log->staff_id ? $log->staff->displayName : '---' !!}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="logs-table-cell">{{ $log->type }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="logs-table-cell">
                            @foreach ($log->data as $key => $value)
                                <div>
                                    @if (is_string($value))
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}: </strong>{{ $value }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="logs-table-cell">{!! format_date($log->created_at) !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {!! $logs->render() !!}
@endsection
