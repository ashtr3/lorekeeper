{!! Form::open(['url' => $isMyo ? 'admin/myo/' . $character->id . '/genetics' : 'admin/character/' . $character->slug . '/genetics']) !!}
{!! Form::hidden('character_id', $character->id) !!}

<div class="text-right">
    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}