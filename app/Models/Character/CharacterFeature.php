<?php

namespace App\Models\Character;

use App\Models\Feature\Feature;
use App\Models\Model;

class CharacterFeature extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'character_id', 'feature_id', 'data', 'character_type',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_features';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['feature'];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the character associated with this record.
     */
    public function character() {
        return $this->belongsTo(Character::class, 'character_id');
    }

    /**
     * Get the feature (character trait) associated with this record.
     */
    public function feature() {
        return $this->belongsTo(Feature::class, 'feature_id');
    }
}
