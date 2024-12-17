<?php

namespace App\Models\Character;

use App\Models\Feature\FeatureAllele;
use App\Models\Feature\FeatureLocus;
use Illuminate\Database\Eloquent\Model;

class CharacterMapGene extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'map_id', 'locus_id', 'primary_allele_id', 'secondary_allele_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_map_genetics';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['map', 'locus', 'primary_allele', 'secondary_allele'];

    /**
     * Validation rules for character map gene creation.
     *
     * @var array
     */
    public static $createRules = [
        'map_id'              => 'required|exists:character_maps,id',
        'locus_id'            => 'required|exists:feature_loci,id',
        'primary_allele_id'   => 'nullable|exists:feature_alleles,id',
        'secondary_allele_id' => 'nullable|exists:feature_alleles,id',
    ];

    /**
     * Validation rules for character map gene updating.
     *
     * @var array
     */
    public static $updateRules = [
        'map_id'              => 'required|exists:character_maps,id',
        'locus_id'            => 'required|exists:feature_loci,id',
        'primary_allele_id'   => 'nullable|exists:feature_alleles,id',
        'secondary_allele_id' => 'nullable|exists:feature_alleles,id',
    ];

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the map associated with this record.
     */
    public function map() {
        return $this->belongsTo(CharacterMap::class, 'map_id');
    }

    /**
     * Get the locus associated with this record.
     */
    public function locus() {
        return $this->belongsTo(FeatureLocus::class, 'locus_id');
    }

    /**
     * Get the primary allele associated with this record.
     */
    public function primary_allele() {
        return $this->belongsTo(FeatureAllele::class, 'primary_allele_id');
    }

    /**
     * Get the primary allele associated with this record.
     */
    public function secondary_allele() {
        return $this->belongsTo(FeatureAllele::class, 'secondary_allele_id');
    }

    /**
     * Get the alleles associated with this record.
     */
    public function alleles() {
        return collect([$this->primary_allele, $this->secondary_allele])->filter()->sortByDesc('sort');
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Displays the genotype.
     *
     * @return string
     */
    public function getGenotypeAttribute() {
        $alleles = $this->alleles()->pluck('allele');
        switch (count($alleles)) {
            case 0:
                return sprintf(
                    '<a href="%s" class="display-locus">%s</a>',
                    $this->locus->url,
                    $this->locus->default_allele.$this->locus->default_allele
                );
            case 1:
                return sprintf(
                    '<a href="%s" class="display-locus">%s</a>',
                    $this->locus->url,
                    $this->locus->default_allele_leads ? $this->locus->default_allele.$alleles->implode('') : $alleles->implode('').$this->locus->default_allele
                );
            default:
                return sprintf(
                    '<a href="%s" class="display-locus">%s</a>',
                    $this->locus->url,
                    $alleles->implode('')
                );
        }
    }
}
