<?php

namespace App\Models\Feature;

use Illuminate\Database\Eloquent\Model;

class FeatureGene extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feature_id', 'feature_allele_id', 'allow_homozygous', 'allow_heterozygous', 'allow_absent',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feature_genetics';

    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'feature_id'         => 'required|exists:features,id',
        'feature_allele_id'  => 'required|exists:feature_alleles,id',
        'allow_homozygous'   => 'nullable',
        'allow_heterozygous' => 'nullable',
        'allow_absent'       => 'nullable'
    ];

    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'feature_id'         => 'required|exists:features,id',
        'feature_allele_id'  => 'required|exists:feature_alleles,id',
        'allow_homozygous'   => 'nullable',
        'allow_heterozygous' => 'nullable',
        'allow_absent'       => 'nullable'
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the feature associated with this record.
     */
    public function feature() {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    /**
     * Get the allele associated with this record.
     */
    public function allele() {
        return $this->belongsTo(FeatureAllele::class, 'feature_allele_id');
    }
}
