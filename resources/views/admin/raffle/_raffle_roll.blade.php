@if ($raffle->is_active < 2)
    <div class="text-center">
        <p>This will roll {{ $raffle->winner_count }} winner(s) for the raffle <b>{{ $raffle->name }}</b>.</p>
        {!! Form::open(['route' => ['admin.raffles.roll.store', $raffle->id]]) !!}
        {!! Form::submit('Roll!', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@else
    <div class="text-center">This raffle has already been completed.</div>
@endif
