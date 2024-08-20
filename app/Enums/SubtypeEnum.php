<?php

namespace App\Enums;

enum SubtypeEnum: int {
    case SUBTYPE = 1;

    public function getValues(): array
    {
        return match ($this) {
            self::SUBTYPE => ['name' => 'Subtype'],
        };
    }

    public function getSpecies(): SpeciesEnum 
    {
        return match ($this) {
            self::SUBTYPE => SpeciesEnum::SPECIES,
        };
    }

    public function getPattern(): Pattern | null
    {
        return Pattern::SUBTYPE;
    }
}