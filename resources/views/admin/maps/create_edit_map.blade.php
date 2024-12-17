@extends('admin.layout')

@section('admin-title')
    {{ $map->id ? 'Edit' : 'Create' }} Character Map
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Character Maps' => 'admin/data/maps', ($map->id ? 'Edit' : 'Create') . ' Character Map' => $map->id ? 'admin/data/maps/edit/' . $map->id : 'admin/data/maps/create']) !!}

    <h1>{{ $map->id ? 'Edit' : 'Create' }} Character Map
        @if ($map->id)
            <a href="#" class="btn btn-danger float-right delete-map-button">Delete Map</a>
        @endif
    </h1>

    {!! Form::open(['url' => $map->id ? 'admin/data/maps/edit/' . $map->id : 'admin/data/maps/create']) !!}

    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="form-group">
                {!! Form::label('Name') !!}
                {!! Form::text('name', $map->name, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="form-group">
                {!! Form::label('Category') !!}
                {!! Form::select('category_id', $categories, $map->category_id, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <hr>

    <div class="d-flex align-items-end justify-content-between mb-3">
        <div>
            <h3>Conversions</h3>
            <p class="mb-0">These are the strings that the map will match against. A match is found if ALL strings from any line are present.</p>
        </div>
        <a id="add-conversion-button" class="btn btn-primary" href="#"><i class="fas fa-plus"></i> Add Conversion</a>
    </div>
    <div id="conversion-list">
        @foreach($map->conversions as $index => $conversion)
            <div class="d-flex mb-2" data-id="{{ $index }}">
                <select name="conversions[{{ $index }}][]" class="conversion-input form-control mr-2" placeholder="Enter Source Genotypes" multiple>
                    @foreach($conversion as $gene)
                        <option value="{{ $gene }}" selected>{{ $gene }}</option>
                    @endforeach
                </select>
                <a href="#" class="remove-conversion-button btn btn-danger mb-2">×</a>
            </div>
        @endforeach
        <div class="conversion-row hide d-flex mb-2">
            {!! Form::select('conversions[][]', [], null, ['class' => 'conversion-input form-control mr-2', 'placeholder' => 'Enter Source Genotypes', 'multiple' ]) !!}
            <a href="#" class="remove-conversion-button btn btn-danger mb-2">×</a>
        </div>
    </div>

    <hr>

    <div class="d-flex align-items-end justify-content-between mb-3">
        <div>
            <h3>Genes</h3>
            <p class="mb-0">These are the genes that can be assigned to a character if the match is found.</p>
        </div>
        <a id="add-gene-button" class="btn btn-primary" href="#"><i class="fas fa-plus"></i> Add Gene</a>
    </div>
    <table id="genetics-list" class="table table-sm">
        <thead>
            <tr>
                <th width="50%">Locus</th>
                <th width="25%">Allele 1</th>
                <th width="25%" colspan="2">Allele 2</th>
            </tr>
        </thead>
        <tbody>
            @foreach($map->genetics as $index => $gene)
                <tr data-id="{{ $index }}">
                    <td>{!! Form::select("genetics[$index][locus_id]", $loci, $gene->locus_id, ['class' => 'form-control locus-input']) !!}</td>
                    <td>{!! Form::select("genetics[$index][primary_allele_id]", ['0' => 'Select Allele'] + ($gene->locus->alleles->pluck('allele', 'id')->toArray()), $gene->primary_allele_id, ['class' => 'form-control allele-input']) !!}</td>
                    <td>{!! Form::select("genetics[$index][secondary_allele_id]", ['0' => 'Select Allele'] + ($gene->locus->alleles->pluck('allele', 'id')->toArray()), $gene->secondary_allele_id, ['class' => 'form-control allele-input']) !!}</td>
                    <td class="d-flex">
                        <a href="#" class="remove-gene-button btn btn-danger">×</a>
                    </td>
                </tr>
            @endforeach
            <tr class="gene-row hide">
                <td>{!! Form::select("genetics[][locus_id]", $loci, null, ['class' => 'form-control locus-input']) !!}</td>
                <td>{!! Form::select("genetics[][primary_allele_id]", ['0' => 'Select Allele'], null, ['class' => 'form-control allele-input']) !!}</td>
                <td>{!! Form::select("genetics[][secondary_allele_id]", ['0' => 'Select Allele'], null, ['class' => 'form-control allele-input']) !!}</td>
                <td class="d-flex">
                    <a href="#" class="remove-gene-button btn btn-danger">×</a>
                </td>
            </tr>
        </tbody>
    </table>

    <hr>

    <div class="text-right">
        {!! Form::submit(($map->id ? 'Edit' : 'Create') . ' Character Map', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('[data-id] .conversion-input').each(function() {
                $(this).selectize({
                    delimiter: ",",
                    persist: false,
                    create: function (input) {
                        return { value: input, text: input };
                    }
                });
            });
            $('#add-conversion-button').on('click', function(e) {
                e.preventDefault();
                const clone = $('.conversion-row').clone();
                const list = $('#conversion-list');
                list.append(clone);

                const nextIndex = getNextIndex(list);
                clone.attr('data-id', nextIndex);
                clone.removeClass('hide conversion-row');

                clone.find('.remove-conversion-button').on('click', function(e) {
                    e.preventDefault();
                    clone.remove();
                });

                clone.find('select').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace('[][]', `[${nextIndex}][]`);
                    $(this).attr('name', newName);
                });
                
                clone.find('.conversion-input').each(function() {
                    $(this).selectize({
                        delimiter: ",",
                        persist: false,
                        create: function (input) {
                            return { value: input, text: input };
                        }
                    });
                });
            });

            $('#add-gene-button').on('click', function(e) {
                e.preventDefault();
                const clone = $('.gene-row').clone();
                const list = $('#genetics-list tbody');
                list.append(clone);

                const nextIndex = getNextIndex(list);
                clone.attr('data-id', nextIndex);
                clone.removeClass('hide gene-row');

                clone.find('.remove-gene-button').on('click', function(e) {
                    e.preventDefault();
                    clone.remove();
                });

                clone.find('select').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace('[]', `[${nextIndex}]`);
                    $(this).attr('name', newName);
                });

                addLocusListener(clone);
            });

            $('#feature-override-select').selectize();
            $('.is-genetic-check').on('change', function(e) {
                $('.genetic-settings').toggleClass('hide');
            });
            $('#genetic-requirement-list [data-id]').each(function() {
                addLocusListener($(this));
            });
            $('#add-genetic-requirement').on('click', function(e) {
                e.preventDefault();
                addRow('genetic-requirement-row', 'genetic-requirement-list', 'remove-genetic-requirement');
            });
            $('.remove-genetic-requirement').on('click', function(e) {
                e.preventDefault();
                removeRow($(this));
            });
            $('.delete-feature-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/traits/delete') }}/{{ $map->id }}", 'Delete Trait');
            });
            refreshSubtype();
        });

        $("#species").change(function() {
            refreshSubtype();
        });

        function refreshSubtype() {
            var species = $('#species').val();
            var subtype_id = {{ $map->id ?: 'null' }};
            $.ajax({
                type: "GET",
                url: "{{ url('admin/data/traits/check-subtype') }}?species=" + species + "&subtype_id=" + subtype_id,
                dataType: "text"
            }).done(function(res) {
                $("#subtypes").html(res);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert("AJAX call failed: " + textStatus + ", " + errorThrown);
            });
        };

        function getNextIndex(list) {
            const rows = list.find('[data-id]');
            const indexes = $.map(rows, function(element) {
                const dataId = $(element).data('id');
                return !isNaN(dataId) ? parseInt(dataId, 10) : null;
            }).filter(function(id) {
                return id !== null;
            });
            return indexes.length > 0 ? Math.max(...indexes) + 1 : 0;
        }

        function addLocusListener(row) {
            row.find('.locus-input').on('change', async function(e) {
                const id = $(this).val();
                const select = row.find('.allele-input');

                if (id) {
                    const alleles = await refreshAlleles(id);
                    select.empty();
                    $.each(alleles, function(key, value) {
                        select.append(`<option value="${key}">${value}</option>`);
                    });
                } else {
                    select.empty();
                    select.append('<option>Select Allele</option>');
                }
            });
        }

        function refreshAlleles(id) {
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
        };

        function addRow(row, list, remove_btn) {
            var clone = $(`.${row}:last`).clone();
            $(`#${list}`).append(clone);

            const nextIndex = getNextIndex();
            clone.attr('data-id', nextIndex);
            clone.removeClass(`hide ${row}`);

            clone.find(`.${remove_btn}`).on('click', function(e) {
                e.preventDefault();
                removeRow($(this));
            });

            clone.find('select, input').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace('[]', `[${nextIndex}]`);
                $(this).attr('name', newName);
            });

            clone.find('input.form-check-input').each(function() {
                $(this).bootstrapToggle();
            });

            addLocusListener(clone);
        }

        function removeRow(trigger) {
            trigger.parent().remove();
        }
    </script>
@endsection
