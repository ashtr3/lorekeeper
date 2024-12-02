<?php

namespace App\Models\Feature;

use Illuminate\Database\Eloquent\Model;

class FeatureLocus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'default_allele', 'sort', 'description', 'parsed_description', 'default_allele_leads', 'is_visible',
    ];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feature_loci';

    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'name'           => 'required|unique:feature_loci|between:1,100',
        'default_allele' => 'required|between:1,5',
        'description'    => 'nullable',
    ];

    /**
     * Validation rules for updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name'           => 'required|between:1,100',
        'default_allele' => 'required|between:1,5',
        'description'    => 'nullable',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the alleles associated with the locus.
     */
    public function alleles() {
        return $this->hasMany(FeatureAllele::class, 'feature_locus_id');
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
        return '<a href="'.$this->url.'" class="display-locus">'.$this->name.'</a>';
    }

    /**
     * Gets the URL of the model's encyclopedia page.
     *
     * @return string
     */
    public function getUrlAttribute() {
        return url('world/trait-loci?name='.$this->name);
    }

    /**
     * Gets the admin edit URL.
     *
     * @return string
     */
    public function getAdminUrlAttribute() {
        return url('admin/data/trait-loci/edit/'.$this->id);
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
