<?php

namespace App\Models\Character;

use App\Models\Feature\FeatureAllele;
use App\Models\Feature\FeatureLocus;
use Illuminate\Database\Eloquent\Model;

class CharacterGene extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'character_id', 'locus_id', 'primary_allele_id', 'secondary_allele_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_genetics';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;

    /**
     * Validation rules for character gene creation.
     *
     * @var array
     */
    public static $createRules = [
        'character_id'        => 'required|exists:characters,id',
        'locus_id'            => 'required|exists:feature_loci,id',
        'primary_allele_id'   => 'nullable|exists:feature_alleles,id',
        'secondary_allele_id' => 'nullable|exists:feature_alleles,id',
    ];

    /**
     * Validation rules for character gene updating.
     *
     * @var array
     */
    public static $updateRules = [
        'character_id'        => 'required|exists:characters,id',
        'locus_id'            => 'required|exists:feature_loci,id',
        'primary_allele_id'   => 'nullable|exists:feature_alleles,id',
        'secondary_allele_id' => 'nullable|exists:feature_alleles,id',
    ];

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

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to show only homozygous character genes.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param \App\Models\Feature\FeatureAllele|null $allele
     *
     * @return \Illuminate\Database\Eloquent\Builder $allele
     */
    public function scopeHomozygous($query, $allele = null) {
        $query = $query->whereNotNull('primary_allele_id')->whereNotNull('secondary_allele_id')->whereColumn('primary_allele_id', 'secondary_allele_id');

        if ($allele) {
            $query->where(function ($query) use ($allele) {
                $query->where('primary_allele_id', $allele->id)->orWhere('secondary_allele_id', $allele->id);
            });
        }

        return $query;
    }

    /**
     * Scope a query to show only heterozygous character genes.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param \App\Models\Feature\FeatureAllele|null $allele
     *
     * @return \Illuminate\Database\Eloquent\Builder $allele
     */
    public function scopeHeterozygous($query, $allele = null) {
        $query = $query->whereColumn('primary_allele_id', '!=', 'secondary_allele_id');

        if ($allele) {
            $query->where(function ($query) use ($allele) {
                $query->where('primary_allele_id', $allele->id)->orWhere('secondary_allele_id', $allele->id);
            });
        }

        return $query;
    }

    /**
     * Scope a query to show only absent character genes.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param \App\Models\Feature\FeatureAllele|null $allele
     *
     * @return \Illuminate\Database\Eloquent\Builder $allele
     */
    public function scopeAbsent($query, $allele = null) {
        $query = $query->whereNull('primary_allele_id')->whereNull('secondary_allele_id');

        if ($allele) {
            $query->where('locus_id', $allele->feature_locus_id);
        }

        return $query;
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
