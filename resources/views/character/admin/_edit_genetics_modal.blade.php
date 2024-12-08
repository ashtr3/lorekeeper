{!! Form::open(['url' => $isMyo ? 'admin/myo/' . $character->id . '/genetics' : 'admin/character/' . $character->slug . '/genetics']) !!}
{!! Form::hidden('character_id', $character->id) !!}

<div>
    <div class="d-flex align-items-end justify-content-between mb-3">
        <div>
            <h5>Primary Genotype</h5>
            <p class="mb-0">This is the primary genotype available for all characters.</p>
        </div>
        <a class="btn btn-primary add-primary-gene-button" href="#"><i class="fas fa-plus"></i> Add Gene</a>
    </div>
    <table class="table table-sm genetics-primary-table">
        <thead>
            <tr>
                <th width="50%">Locus</th>
                <th width="25%">Allele 1</th>
                <th width="25%" colspan="2">Allele 2</th>
            </tr>
        </thead>
        <tbody>
            @if (
                !count(
                    $character->genetics()->primary()->get()))
                <tr class="gene-row" data-id="0">
                    <td>{!! Form::select('genetics[primary][0][locus_id]', $loci, null, ['class' => 'form-control locus-input']) !!}</td>
                    <td>{!! Form::select('genetics[primary][0][primary_allele_id]', ['0' => 'Select Allele'], null, ['class' => 'form-control allele-input']) !!}</td>
                    <td>{!! Form::select('genetics[primary][0][secondary_allele_id]', ['0' => 'Select Allele'], null, ['class' => 'form-control allele-input']) !!}</td>
                    <td class="d-flex">
                        <button type="button" href="#" class="btn btn-sm btn-outline-danger delete-gene-button" disabled>
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            @else
                @foreach ($character->genetics()->primary()->get() as $index => $gene)
                    <tr class="gene-row" data-id="{{ $index }}">
                        <td>{!! Form::select("genetics[primary][$index][locus_id]", $loci, $gene->locus_id ?? null, ['class' => 'form-control locus-input']) !!}</td>
                        <td>{!! Form::select("genetics[primary][$index][primary_allele_id]", ['0' => 'Select Allele'] + ($gene ? $gene->locus->alleles->pluck('allele', 'id')->toArray() : []), $gene->primary_allele_id ?? null, ['class' => 'form-control allele-input']) !!}</td>
                        <td>{!! Form::select("genetics[primary][$index][secondary_allele_id]", ['0' => 'Select Allele'] + ($gene ? $gene->locus->alleles->pluck('allele', 'id')->toArray() : []), $gene->secondary_allele_id ?? null, [
                            'class' => 'form-control allele-input',
                        ]) !!}</td>
                        <td class="d-flex">
                            <button type="button" href="#" class="btn btn-sm btn-outline-danger delete-gene-button" {{ count($character->genetics) <= 1 ? 'disabled' : '' }}>
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@if ($character->isChimeric)
    <div>
        <div class="d-flex align-items-end justify-content-between mb-3">
            <div>
                <h5>Secondary Genotype</h5>
                <p class="mb-0">This is the secondary genotype available only to characters with a trait that enables chimerism.</p>
            </div>
            <a class="btn btn-primary add-secondary-gene-button" href="#"><i class="fas fa-plus"></i> Add Gene</a>
        </div>
        <table class="table table-sm genetics-secondary-table">
            <thead>
                <tr>
                    <th width="50%">Locus</th>
                    <th width="25%">Allele 1</th>
                    <th width="25%" colspan="2">Allele 2</th>
                </tr>
            </thead>
            <tbody>
                @if (
                    !count(
                        $character->genetics()->secondary()->get()))
                    <tr class="gene-row" data-id="0">
                        <td>{!! Form::select('genetics[secondary][0][locus_id]', $loci_c, null, ['class' => 'form-control locus-input']) !!}</td>
                        <td>{!! Form::select('genetics[secondary][0][primary_allele_id]', ['0' => 'Select Allele'], null, ['class' => 'form-control allele-input']) !!}</td>
                        <td>{!! Form::select('genetics[secondary][0][secondary_allele_id]', ['0' => 'Select Allele'], null, ['class' => 'form-control allele-input']) !!}</td>
                        <td class="d-flex">
                            <button type="button" href="#" class="btn btn-sm btn-outline-danger delete-gene-button" disabled>
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                @else
                    @foreach ($character->genetics()->secondary()->get() as $index => $gene)
                        <tr class="gene-row" data-id="{{ $index }}">
                            <td>{!! Form::select("genetics[secondary][$index][locus_id]", $loci_c, $gene->locus_id ?? null, ['class' => 'form-control locus-input']) !!}</td>
                            <td>{!! Form::select("genetics[secondary][$index][primary_allele_id]", ['0' => 'Select Allele'] + ($gene ? $gene->locus->alleles->pluck('allele', 'id')->toArray() : []), $gene->primary_allele_id ?? null, ['class' => 'form-control allele-input']) !!}</td>
                            <td>{!! Form::select("genetics[secondary][$index][secondary_allele_id]", ['0' => 'Select Allele'] + ($gene ? $gene->locus->alleles->pluck('allele', 'id')->toArray() : []), $gene->secondary_allele_id ?? null, [
                                'class' => 'form-control allele-input',
                            ]) !!}</td>
                            <td class="d-flex">
                                <button type="button" href="#" class="btn btn-sm btn-outline-danger delete-gene-button" {{ count($character->genetics) <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endif

<div class="text-right">
    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

<script>
    $(document).ready(function() {
        const primary = $('.genetics-primary-table tbody');
        const secondary = $('.genetics-secondary-table tbody');

        $('.add-primary-gene-button').click(function(e) {
            e.preventDefault();

            const newRow = primary.find('.gene-row:last').clone();
            const lastIndex = newRow.data('id');
            const newIndex = lastIndex + 1;
            newRow.attr('data-id', newIndex);

            newRow.find('select').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(/\[\d+\]/, `[${newIndex}]`);
                $(this).attr('name', newName);
            });

            newRow.find('input').val('');
            primary.append(newRow);

            const count = primary.find('.gene-row').length;
            toggleDeleteButtons(primary, count > 1);
        });

        $('.add-secondary-gene-button').click(function(e) {
            e.preventDefault();

            const newRow = secondary.find('.gene-row:last').clone();
            const lastIndex = newRow.data('id');
            const newIndex = lastIndex + 1;
            newRow.attr('data-id', newIndex);

            newRow.find('select').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(/\[\d+\]/, `[${newIndex}]`);
                $(this).attr('name', newName);
            });

            newRow.find('input').val('');
            secondary.append(newRow);

            const count = secondary.find('.gene-row').length;
            toggleDeleteButtons(secondary, count > 1);
        });

        primary.on('click', '.delete-gene-button', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();

            const count = primary.find('.gene-row').length;
            toggleDeleteButtons(primary, count > 1);
        });

        secondary.on('click', '.delete-gene-button', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();

            const count = secondary.find('.gene-row').length;
            toggleDeleteButtons(secondary, count > 1);
        });

        $('tbody').on('change', '.locus-input', async function() {
            const id = $(this).val();
            const alleles = await getAlleles(id);

            const row = $(this).closest('tr');
            const select = row.find('.allele-input');

            select.empty();
            $.each(alleles, function(key, value) {
                select.append(`<option value="${key}">${value}</option>`);
            });
        });
    });

    function toggleDeleteButtons(tbody, enabled = true) {
        tbody.find('.delete-gene-button').prop('disabled', !enabled);
    }

    function getAlleles(id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "{{ url('admin/data/trait-loci') }}/" + id + "/alleles",
                type: 'GET',
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }
</script>
