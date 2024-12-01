<div class="row world-entry">
    <div class="col-12">
        <x-admin-edit title="Allele" :object="$allele" />
        <h3>
            @if (!$allele->is_visible)
                <i class="fas fa-eye-slash mr-1"></i>
            @endif
            {!! $allele->displayName !!} ({!! $allele->locus->displayName !!} Locus)
        </h3>
        <div>
            <strong>Locus:</strong> {!! $allele->locus->displayName !!}
        </div>
        <div class="world-entry-text parsed-text">
            {!! $allele->parsed_description !!}
        </div>
    </div>
</div>
