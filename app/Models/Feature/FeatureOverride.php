<?php

namespace App\Models\Feature;

use Illuminate\Database\Eloquent\Model;

class FeatureOverride extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'override_id', 'hidden_id',
    ];
    public $timestamps = false;

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the overriding feature.
     */
    public function override() {
        return $this->belongsTo(Feature::class, 'override_id');
    }

    /**
     * Get the feature being overridden.
     */
    public function hidden() {
        return $this->belongsTo(Feature::class, 'hidden_id');
    }
}
