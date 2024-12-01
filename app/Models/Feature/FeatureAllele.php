<?php

namespace App\Models\Feature;

use Illuminate\Database\Eloquent\Model;

class FeatureAllele extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feature_locus_id', 'allele', 'sort', 'description', 'parsed_description', 'is_visible',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feature_alleles';

    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'feature_locus_id' => 'required|exists:feature_loci,id',
        'allele'           => 'required|unique:feature_alleles|between:1,5',
        'description'      => 'nullable',
    ];

    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'feature_locus_id' => 'required|exists:feature_loci,id',
        'allele'           => 'required|between:1,5',
        'description'      => 'nullable',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the locus of this allele.
     */
    public function locus() {
        return $this->belongsTo(FeatureLocus::class, 'feature_locus_id');
    }

    /**********************************************************************************************

        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to show only visible loci.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed|null                            $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query, $user = null) {
        if ($user && $user->hasPower('edit_data')) {
            return $query;
        }

        return $query->where('is_visible', 1);
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Displays the model's name, linked to its encyclopedia page.
     *
     * @return string
     */
    public function getDisplayNameAttribute() {
        return '<a href="'.$this->url.'" class="display-allele">'.$this->allele.'</a>';
    }

    /**
     * Displays the model's name, linked to its encyclopedia page.
     *
     * @return string
     */
    public function getDisplayNameWithLocusAttribute() {
        return '<a href="'.$this->url.'" class="display-allele">'.$this->allele.'</a> ('.$this->locus->displayName.' Locus)';
    }

    /**
     * Gets the URL of the model's encyclopedia page.
     *
     * @return string
     */
    public function getUrlAttribute() {
        return url('world/trait-alleles?name='.$this->allele);
    }

    /**
     * Gets the admin edit URL.
     *
     * @return string
     */
    public function getAdminUrlAttribute() {
        return $this->locus->adminUrl;
    }

    /**
     * Gets the power required to edit this model.
     *
     * @return string
     */
    public function getAdminPowerAttribute() {
        return 'edit_data';
    }
}
