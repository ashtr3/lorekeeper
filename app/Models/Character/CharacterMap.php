<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterMap extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'name', 'conversions', 'sort',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_maps';

    /**
     * Validation rules for character map creation.
     *
     * @var array
     */
    public static $createRules = [
        'category_id' => 'required|exists:character_map_categories,id',
        'name'        => 'required|unique:character_maps|between:3,100',
        'conversions' => 'required',
    ];

    /**
     * Validation rules for character map updating.
     *
     * @var array
     */
    public static $updateRules = [
        'category_id' => 'required|exists:character_map_categories,id',
        'name'        => 'required|between:3,100',
        'conversions' => 'required',
    ];

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = false;

    protected $casts = [
        'conversions' => 'array',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the category associated with this record.
     */
    public function category() {
        return $this->belongsTo(CharacterMapCategory::class, 'category_id');
    }

    /**
     * Get the genes associated with this record.
     */
    public function genetics() {
        return $this->hasMany(CharacterMapGene::class, 'map_id');
    }

    /**********************************************************************************************

        ACCESSORS

    **********************************************************************************************/

    /**
     * Displays the model's name, linked to its admin edit page.
     *
     * @return string
     */
    public function getDisplayNameAttribute() {
        return '<a href="'.$this->adminUrl.'">'.$this->name.'</a> ('.$this->category->displayName.')';
    }

    /**
     * Gets the admin edit URL.
     *
     * @return string
     */
    public function getAdminUrlAttribute() {
        return url('admin/data/maps/edit/'.$this->id);
    }
}
