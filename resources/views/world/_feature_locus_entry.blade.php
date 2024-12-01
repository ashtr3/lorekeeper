<div class="row world-entry">
    <div class="col-12">
        <x-admin-edit title="Locus" :object="$locus" />
        <h3>
            @if (!$locus->is_visible)
                <i class="fas fa-eye-slash mr-1"></i>
            @endif
            {!! $locus->displayName !!}
        </h3>
        @if (count($locus->alleles))
            <div>
                <strong>Alleles: </strong>
                @foreach ($locus->alleles as $count => $allele)
                    {!! $allele->displayName !!}{{ $count < $locus->alleles->count() - 1 ? ', ' : '' }}
                @endforeach
            </div>
        @endif
        <div class="world-entry-text parsed-text">
            {!! $locus->parsed_description !!}
        </div>
    </div>
</div>
