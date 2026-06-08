@extends('home.layout')

@section('home-title')
    Trades
@endsection

@section('home-content')
    {!! breadcrumbs(['Trades' => route('trades.index', 'open')]) !!}

    <h1>
        Trades
    </h1>

    <div class="text-right">
        <a href="{{ route('trades.create') }}" class="btn btn-primary">New Trade</a>
    </div>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ set_active('trades/open*') }}" href="{{ route('trades.index', 'open') }}">Open</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ set_active('trades/pending*') }}" href="{{ route('trades.index', 'pending') }}">Pending</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ set_active('trades/completed*') }}" href="{{ route('trades.index', 'completed') }}">Completed</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ set_active('trades/rejected*') }}" href="{{ route('trades.index', 'rejected') }}">Rejected</a>
        </li>
    </ul>

    {!! $trades->render() !!}
    @foreach ($trades as $trade)
        @include('home.trades._trade', ['trade' => $trade])
    @endforeach
    {!! $trades->render() !!}
@endsection
