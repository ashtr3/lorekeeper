<div class="row no-gutters">
    <div class="col-lg-3 col-5">
        <h5>Genotype</h5>
    </div>
    <div class="col-lg-9 col-7">{!! $character->genotype !!}</div>
</div>
@if (Auth::check() && Auth::user()->hasPower('manage_characters'))
    <div class="mt-3">
        <a href="#" class="btn btn-outline-info btn-sm edit-genetics" data-{{ $character->is_myo_slot ? 'id' : 'slug' }}="{{ $character->is_myo_slot ? $character->id : $character->slug }}"><i class="fas fa-cog"></i> Edit</a>
    </div>
@endif
