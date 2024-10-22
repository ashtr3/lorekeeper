<?php

namespace App\Enums;

enum SpeciesEnum: int {
    public function getValues(): array {
        return match ($this) {
            self::SPECIES => ['name' => 'Species'],
        };
    }

    public function getPattern(): ?Pattern {
        return Pattern::SPECIES;
    }
    case SPECIES = 1;
}
