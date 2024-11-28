{!! Form::open(['url' => $isMyo ? 'admin/myo/' . $character->id . '/lineage' : 'admin/character/' . $character->slug . '/lineage']) !!}
<div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {!! Form::label('Sire') !!}
                    {!! Form::select('ancestor_sire', $ancestorOptions, $character->sire->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select Sire']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {!! Form::label('Dam') !!}
                    {!! Form::select('ancestor_dam', $ancestorOptions, $character->dam->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select Dam']) !!}
                </div>    
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('SS') !!}
                    {!! add_help('This ancestor is the Sire\'s Sire.') !!}
                    {!! Form::select('ancestor_ss', $ancestorOptions, $character->ss->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select SS']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('SD') !!}
                    {!! add_help('This ancestor is the Sire\'s Dam.') !!}
                    {!! Form::select('ancestor_sd', $ancestorOptions, $character->sd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select SD']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('DS') !!}
                    {!! add_help('This ancestor is the Dam\'s Sire.') !!}
                    {!! Form::select('ancestor_ds', $ancestorOptions, $character->ds->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select DS']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('DD') !!}
                    {!! add_help('This ancestor is the Dam\'s Dam.') !!}
                    {!! Form::select('ancestor_dd', $ancestorOptions, $character->dd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select DD']) !!}
                </div>    
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('SSS') !!}
                    {!! add_help('This ancestor is the Sire\'s Sire\'s Sire.') !!}
                    {!! Form::select('ancestor_sss', $ancestorOptions, $character->sss->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select SSS']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('SSD') !!}
                    {!! add_help('This ancestor is the Sire\'s Sire\'s Dam.') !!}
                    {!! Form::select('ancestor_ssd', $ancestorOptions, $character->ssd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select SSD']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('SDS') !!}
                    {!! add_help('This ancestor is the Sire\'s Dam\'s Sire.') !!}
                    {!! Form::select('ancestor_sds', $ancestorOptions, $character->sds->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select SDS']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('SDD') !!}
                    {!! add_help('This ancestor is the Sire\'s Dam\'s Dam.') !!}
                    {!! Form::select('ancestor_sdd', $ancestorOptions, $character->sdd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select SDD']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('DSS') !!}
                    {!! add_help('This ancestor is the Dam\'s Sire\'s Sire.') !!}
                    {!! Form::select('ancestor_dss', $ancestorOptions, $character->dss->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select DSS']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('DSD') !!}
                    {!! add_help('This ancestor is the Dam\'s Sire\'s Dam.') !!}
                    {!! Form::select('ancestor_dsd', $ancestorOptions, $character->dsd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select DSD']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('DDS') !!}
                    {!! add_help('This ancestor is the Dam\'s Dam\'s Sire.') !!}
                    {!! Form::select('ancestor_dds', $ancestorOptions, $character->dds->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select DDS']) !!}
                </div>    
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="form-group">
                    {!! Form::label('DDD') !!}
                    {!! add_help('This ancestor is the Dam\'s Dam\'s Dam.') !!}
                    {!! Form::select('ancestor_ddd', $ancestorOptions, $character->ddd->ancestor_id ?? null, ['class' => 'form-control selectize', 'placeholder' => 'Select DDD']) !!}
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