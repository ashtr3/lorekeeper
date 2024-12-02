{!! Form::open(['url' => $isMyo ? 'admin/myo/' . $character->id . '/genetics' : 'admin/character/' . $character->slug . '/genetics']) !!}
{!! Form::hidden('character_id', $character->id) !!}

<div class="text-right mb-3"><a class="btn btn-primary add-gene-button" href="#"><i class="fas fa-plus"></i> Add Gene</a></div>
<table class="table table-sm genetics-table">
    <thead>
        <tr>
            <th width="50%">Locus</th>
            <th width="25%">Allele 1</th>
            <th width="25%" colspan="2">Allele 2</th>
        </tr>
    </thead>
    <tbody>
        @if (!count($character->genetics))
            @include('widgets._character_gene_entry', ['index' => 0, 'gene' => null, 'loci' => $loci, 'count' => 1])        
        @else
            @foreach ($character->genetics as $index => $gene)
                @include('widgets._character_gene_entry', ['index' => $index, 'gene' => $gene, 'loci' => $loci, 'count' => count($character->genetics)])
            @endforeach
        @endif
    </tbody>
</table>

<div class="text-right">
    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

<script>
    $(document).ready(function () {
        $('.add-gene-button').click(function (e) {
            e.preventDefault();

            const newRow = $('.gene-row:last').clone();
            const lastIndex = newRow.data('id');
            const newIndex = lastIndex + 1;
            newRow.attr('data-id', newIndex);

            newRow.find('select').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(/\[\d+\]/, `[${newIndex}]`);
                $(this).attr('name', newName);
            });

            newRow.find('input').val('');
            $('tbody').append(newRow);

            const count = $('.gene-row').length;
            toggleDeleteButtons(count > 1);
        });
        $('tbody').on('click', '.delete-gene-button', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();

            const count = $('.gene-row').length;
            toggleDeleteButtons(count > 1);
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

    function toggleDeleteButtons(enabled = true) {
        $('.delete-gene-button').prop('disabled', !enabled);
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