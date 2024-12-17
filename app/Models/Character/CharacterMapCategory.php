<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;

class CharacterMapCategory extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'sort',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'character_map_categories';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['maps'];

    /**
     * Validation rules for character map category creation.
     *
     * @var array
     */
    public static $createRules = [
        'name' => 'required|unique:character_map_categories|between:3,100',
    ];

    /**
     * Validation rules for character map category updating.
     *
     * @var array
     */
    public static $updateRules = [
        'name' => 'required|between:3,100',
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
     * Get the maps associated with this record.
     */
    public function maps() {
        return $this->hasMany(CharacterMap::class, 'category_id');
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
        return '<a href="'.$this->adminUrl.'">'.$this->name.'</a>';
    }

    /**
     * Gets the admin edit URL.
     *
     * @return string
     */
    public function getAdminUrlAttribute() {
        return url('admin/data/map-categories/edit/'.$this->id);
    }
}
