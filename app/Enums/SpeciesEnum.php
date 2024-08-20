<?php

namespace App\Enums;

enum SpeciesEnum: int {
    case SPECIES = 1;

    public function getValues(): array
    {
        return match ($this) {
            self::SPECIES => ['name' => 'Species'],
        };
    }

    public function getPattern(): Pattern | null
    {
        return Pattern::SPECIES;
    }
}