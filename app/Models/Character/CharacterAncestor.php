<?php

namespace App\Models\Character;

use Illuminate\Database\Eloquent\Model;

class CharacterAncestor extends Model {
    protected $fillable = [
        'character_id',
        'ancestor_id',
        'type',
    ];

    protected $table = 'character_ancestors';

    public static $createRules = [
        'character_id'      => 'required|exists:characters,id',
        'ancestor_id'       => 'required|exists:characters,id',
        'type'              => 'required|in:sire,dam,ss,sd,ds,dd,sss,ssd,sds,sdd,dss,dsd,dds,ddd',
        'character_id,type' => 'unique:character_ancestors,character_id,type',
    ];

    public static $updateRules = [
        'character_id'      => 'required|exists:characters,id',
        'ancestor_id'       => 'required|exists:characters,id',
        'type'              => 'required|in:sire,dam,ss,sd,ds,dd,sss,ssd,sds,sdd,dss,dsd,dds,ddd',
        'character_id,type' => 'unique:character_ancestors,character_id,type',
    ];

    /**********************************************************************************************

        RELATIONS

    **********************************************************************************************/

    /**
     * Get the character.
     */
    public function character() {
        return $this->belongsTo(Character::class, 'character_id');
    }

    /**
     * Get the ancestor.
     */
    public function ancestor() {
        return $this->belongsTo(Character::class, 'ancestor_id');
    }
}
