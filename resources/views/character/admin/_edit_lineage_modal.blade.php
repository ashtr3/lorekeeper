{!! Form::open(['url' => $isMyo ? 'admin/myo/' . $character->id . '/lineage' : 'admin/character/' . $character->slug . '/lineage']) !!}
<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group">
            {!! Form::label(__('lineage.sire')) !!}
            {!! Form::select('ancestor_sire', $ancestorOptions, $character->sire->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.sire')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-group">
            {!! Form::label(__('lineage.dam')) !!}
            {!! Form::select('ancestor_dam', $ancestorOptions, $character->dam->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.dam')]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.ss')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.sire') . '\'s ' . __('lineage.sire') . '.') !!}
            {!! Form::select('ancestor_ss', $ancestorOptions, $character->ss->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.ss')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.sd')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.sire') . '\'s ' . __('lineage.dam') . '.') !!}
            {!! Form::select('ancestor_sd', $ancestorOptions, $character->sd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.sd')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.ds')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.dam') . '\'s ' . __('lineage.sire') . '.') !!}
            {!! Form::select('ancestor_ds', $ancestorOptions, $character->ds->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.ds')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.dd')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.dam') . '\'s ' . __('lineage.dam') . '.') !!}
            {!! Form::select('ancestor_dd', $ancestorOptions, $character->dd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.dd')]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.sss')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.sire') . '\'s ' . __('lineage.sire') . '\'s ' . __('lineage.sire') . '.') !!}
            {!! Form::select('ancestor_sss', $ancestorOptions, $character->sss->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.sss')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.ssd')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.sire') . '\'s ' . __('lineage.sire') . '\'s ' . __('lineage.dam') . '.') !!}
            {!! Form::select('ancestor_ssd', $ancestorOptions, $character->ssd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.ssd')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.sds')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.sire') . '\'s ' . __('lineage.dam') . '\'s ' . __('lineage.sire') . '.') !!}
            {!! Form::select('ancestor_sds', $ancestorOptions, $character->sds->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.sds')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.sdd')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.sire') . '\'s ' . __('lineage.dam') . '\'s ' . __('lineage.dam') . '.') !!}
            {!! Form::select('ancestor_sdd', $ancestorOptions, $character->sdd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.sdd')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.dss')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.dam') . '\'s ' . __('lineage.sire') . '\'s ' . __('lineage.sire') . '.') !!}
            {!! Form::select('ancestor_dss', $ancestorOptions, $character->dss->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.dss')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.dsd')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.dam') . '\'s ' . __('lineage.sire') . '\'s ' . __('lineage.dam') . '.') !!}
            {!! Form::select('ancestor_dsd', $ancestorOptions, $character->dsd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.dsd')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.dds')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.dam') . '\'s ' . __('lineage.dam') . '\'s ' . __('lineage.sire') . '.') !!}
            {!! Form::select('ancestor_dds', $ancestorOptions, $character->dds->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.dds')]) !!}
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="form-group">
            {!! Form::label(__('lineage.ddd')) !!}
            {!! add_help('This ancestor is the ' . __('lineage.dam') . '\'s ' . __('lineage.dam') . '\'s ' . __('lineage.dam') . '.') !!}
            {!! Form::select('ancestor_ddd', $ancestorOptions, $character->ddd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select ' . __('lineage.ddd')]) !!}
        </div>
    </div>
</div>
<div class="text-right">
    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}

<script>
    $(document).ready(function() {
        $('.selectize').selectize();
    });
</script>
