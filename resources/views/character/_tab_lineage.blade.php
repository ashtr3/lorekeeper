<div class="row justify-content-center mb-3">
    <div class="col-12 col-md-6 text-center">
        <h5>Sire</h5>
        @if ($character->sire)
            <a href="{{ $character->sire->ancestor->url }}">
                {!! $character->sire->ancestor->slug !!}:
                {!! $character->sire->ancestor->name !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-6 text-center">
        <h5>Dam</h5>
        @if ($character->dam)
            <a href="{{ $character->dam->ancestor->url }}">
                {!! $character->dam->ancestor->slug !!}:
                {!! $character->dam->ancestor->name !!}
            </a>
        @else
            Wild
        @endif
    </div>
</div>

<div class="row justify-content-center mb-3">
    <div class="col-12 col-md-3 text-center">
        <h5>SS</h5>
        @if ($character->ss)
            <a href="{{ $character->ss->ancestor->url }}">
                {!! $character->ss->ancestor->slug !!}:
                {!! $character->ss->ancestor->name !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-3 text-center">
        <h5>SD</h5>
        @if ($character->sd)
            <a href="{{ $character->sd->ancestor->url }}">
                {!! $character->sd->ancestor->slug !!}:
                {!! $character->sd->ancestor->name !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-3 text-center">
        <h5>DS</h5>
        @if ($character->ds)
            <a href="{{ $character->ds->ancestor->url }}">
                {!! $character->ds->ancestor->slug !!}:
                {!! $character->ds->ancestor->name !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-3 text-center">
        <h5>DD</h5>
        @if ($character->dd)
            <a href="{{ $character->dd->ancestor->url }}">
                {!! $character->dd->ancestor->slug !!}:
                {!! $character->dd->ancestor->name !!}
            </a>
        @else
            Wild
        @endif
    </div>
</div>

<div class="row justify-content-around">
    <div class="col-12 col-md-1 text-center">
        <h5>SSS</h5>
        @if ($character->sss)
            <a href="{{ $character->sss->ancestor->url }}">
                {!! $character->sss->ancestor->slug !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-1 text-center">
        <h5>SSD</h5>
        @if ($character->ssd)
            <a href="{{ $character->ssd->ancestor->url }}">
                {!! $character->ssd->ancestor->slug !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-1 text-center">
        <h5>SDS</h5>
        @if ($character->sds)
            <a href="{{ $character->sds->ancestor->url }}">
                {!! $character->sds->ancestor->slug !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-1 text-center">
        <h5>SDD</h5>
        @if ($character->sdd)
            <a href="{{ $character->sdd->ancestor->url }}">
                {!! $character->sdd->ancestor->slug !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-1 text-center">
        <h5>DSS</h5>
        @if ($character->dss)
            <a href="{{ $character->dss->ancestor->url }}">
                {!! $character->dss->ancestor->slug !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-1 text-center">
        <h5>DSD</h5>
        @if ($character->dsd)
            <a href="{{ $character->dsd->ancestor->url }}">
                {!! $character->dsd->ancestor->slug !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-1 text-center">
        <h5>DDS</h5>
        @if ($character->dds)
            <a href="{{ $character->dds->ancestor->url }}">
                {!! $character->dds->ancestor->slug !!}
            </a>
        @else
            Wild
        @endif
    </div>
    <div class="col-12 col-md-1 text-center">
        <h5>DDD</h5>
        @if ($character->ddd)
            <a href="{{ $character->ddd->ancestor->url }}">
                {!! $character->ddd->ancestor->slug !!}
            </a>
        @else
            Wild
        @endif
    </div>
</div>

@if (Auth::check() && Auth::user()->hasPower('manage_characters'))
    <div class="mt-3">
        <a href="#" class="btn btn-outline-info btn-sm edit-lineage" data-{{ $character->is_myo_slot ? 'id' : 'slug' }}="{{ $character->is_myo_slot ? $character->id : $character->slug }}"><i class="fas fa-cog"></i> Edit</a>
    </div>
@endif
