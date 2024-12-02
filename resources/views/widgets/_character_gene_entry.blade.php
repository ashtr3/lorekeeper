<tr class="gene-row" data-id="{{ $index }}">
    <td>{!! Form::select("genetics[$index][locus_id]", $loci, $gene->locus_id ?? null, ['class' => 'form-control locus-input']) !!}</td>
    <td>{!! Form::select("genetics[$index][primary_allele_id]", ['0' => 'Select Allele'] + ($gene ? $gene->locus->alleles->pluck('allele', 'id')->toArray() : []), $gene->primary_allele_id ?? null, ['class' => 'form-control allele-input']) !!}</td>
    <td>{!! Form::select("genetics[$index][secondary_allele_id]", ['0' => 'Select Allele'] + ($gene ? $gene->locus->alleles->pluck('allele', 'id')->toArray() : []), $gene->secondary_allele_id ?? null, ['class' => 'form-control allele-input']) !!}</td>
    <td class="d-flex">
        <button type="button" href="#" class="btn btn-sm btn-outline-danger delete-gene-button" {{ $count <= 1 ? 'disabled' : '' }}>
            <i class="fas fa-times"></i>
        </button>
    </td>
</tr>
