<?php

namespace App\Enums;

enum Pattern: string {
    case CATEGORY = '/(Category)/';
    case RARITY = '/(Rarity)/';
    case SPECIES = '/Species:\s*(\w+)/';
    case SUBTYPE = '/Type:\s*(\w+)/';
    case OWNER = '/Owner:\s*(\S+)/';
    case DESIGNER = '/Design by:\s*(\S+)/';

    case TRAIT_A = '/(Trait)/';
}
