<div class="row no-gutters">
    <div class="col-lg-3 col-5">
        <h5>Genotype</h5>
    </div>
    <div class="col-lg-9 col-7">{!! $character->displayGenotype !!}</div>
</div>
<div class="row no-gutters">
    <div class="col-lg-3 col-5">
        <h5>Phenotype</h5>
    </div>
    <div class="col-lg-9 col-7">
        @if (config('lorekeeper.extensions.traits_by_category'))
            <div>
                @php
                    $traitgroup = $character->image
                        ->features()
                        ->primary()
                        ->excludeOverridden()
                        ->get()
                        ->groupBy('feature_category_id');
                @endphp
                @if ($character->image->features()->count())
                    @foreach ($traitgroup as $key => $group)
                        <div class="mb-2">
                            @if ($key)
                                <strong>{!! $group->first()->category->displayName !!}:</strong>
                            @else
                                <strong>Miscellaneous:</strong>
                            @endif
                            @foreach ($group as $feature)
                                <div class="ml-md-2">{!! $feature->displayName !!}
                                    @if ($feature->data)
                                        ({{ $feature->data }})
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <div>No traits listed.</div>
                @endif
            </div>
        @else
            <div>
                <?php $features = $character->image
                    ->features()
                    ->primary()
                    ->excludeOverridden()
                    ->with('category')
                    ->get(); ?>
                @if ($features->count())
                    @foreach ($features as $feature)
                        <div>
                            @if ($feature->feature_category_id)
                                <strong>{!! $feature->category->displayName !!}:</strong>
                            @endif {!! $feature->displayName !!}
                            @if ($feature->data)
                                ({{ $feature->data }})
                            @endif
                        </div>
                    @endforeach
                @else
                    <div>No traits listed.</div>
                @endif
            </div>
        @endif
    </div>
</div>
@if ($character->isChimeric)
    <div class="row no-gutters">
        <div class="col-lg-3 col-5">
            <h5>Phenotype (Chimeric)</h5>
        </div>
        <div class="col-lg-9 col-7">
            @if (config('lorekeeper.extensions.traits_by_category'))
                <div>
                    @php
                        $traitgroup = $character->image
                            ->features()
                            ->secondary()
                            ->excludeOverridden()
                            ->get()
                            ->groupBy('feature_category_id');
                    @endphp
                    @if ($character->image->features()->count())
                        @foreach ($traitgroup as $key => $group)
                            <div class="mb-2">
                                @if ($key)
                                    <strong>{!! $group->first()->category->displayName !!}:</strong>
                                @else
                                    <strong>Miscellaneous:</strong>
                                @endif
                                @foreach ($group as $feature)
                                    <div class="ml-md-2">{!! $feature->displayName !!}
                                        @if ($feature->data)
                                            ({{ $feature->data }})
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div>No traits listed.</div>
                    @endif
                </div>
            @else
                <div>
                    <?php $features = $character->image
                        ->features()
                        ->secondary()
                        ->excludeOverridden()
                        ->with('category')
                        ->get(); ?>
                    @if ($features->count())
                        @foreach ($features as $feature)
                            <div>
                                @if ($feature->feature_category_id)
                                    <strong>{!! $feature->category->displayName !!}:</strong>
                                @endif {!! $feature->displayName !!}
                                @if ($feature->data)
                                    ({{ $feature->data }})
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div>No traits listed.</div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endif
@if (Auth::check() && Auth::user()->hasPower('manage_characters'))
    <div class="mt-3">
        <a href="#" class="btn btn-outline-info btn-sm edit-genetics" data-{{ $character->is_myo_slot ? 'id' : 'slug' }}="{{ $character->is_myo_slot ? $character->id : $character->slug }}"><i class="fas fa-cog"></i> Edit</a>
    </div>
@endif
