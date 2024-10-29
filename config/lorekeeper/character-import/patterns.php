<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Patterns
    |--------------------------------------------------------------------------
    |
    | A list of patterns that will be used during the character data import
    | to parse values from the input.
    |
    */

    'category' => '/(Category)/',
    'rarity'   => '/(Rarity)/',
    'species'  => '/Species:\s*(\w+)/',
    'subtype'  => '/Type:\s*(\w+)/',
    'owner'    => '/Owner:\s*(\S+)/',
    'designer' => '/Design by:\s*(\S+)/',

    // Traits

    'trait_a' => '/(Trait)/',

];
