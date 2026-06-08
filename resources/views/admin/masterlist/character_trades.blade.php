@extends('admin.layout')

@section('admin-title')
    Character Trades
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => route('admin.index'), 'Character Trade Queue' => route('admin.masterlist.trades.index', 'incoming')]) !!}

    <h1>
        Character Trades
    </h1>

    @include('admin.masterlist._header', ['tradeCount' => $tradeCount, 'transferCount' => $transferCount])

    {!! $trades->render() !!}
    @foreach ($trades as $trade)
        @include('home.trades._trade', ['trade' => $trade, 'queueView' => true])
    @endforeach
    {!! $trades->render() !!}
@endsection


@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.trade-action-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ route('admin.masterlist.trade.act', ['id' => ':id', 'type' => ':type']) }}".replace(':id', $(this).data('id')).replace(':type', $(this).data('action')), 'Process Trade');
            });
        });
    </script>
@endsection
