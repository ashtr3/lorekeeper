<?php

namespace App\Enums;

enum SubtypeEnum: int {
    public function getValues(): array {
        return match ($this) {
            self::SUBTYPE => ['name' => 'Subtype'],
        };
    }

    public function getSpecies(): SpeciesEnum {
        return match ($this) {
            self::SUBTYPE => SpeciesEnum::SPECIES,
        };
    }

    public function getPattern(): ?Pattern {
        return Pattern::SUBTYPE;
    }
    case SUBTYPE = 1;
}
