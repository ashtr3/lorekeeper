<?php

namespace App\Enums;

enum RarityEnum: int {
    public function getValues(): array {
        return match ($this) {
            self::COMMON   => ['name' => 'Common'],
            self::UNCOMMON => ['name' => 'Uncommon'],
            self::RARE     => ['name' => 'Rare']
        };
    }

    public function getPattern(): ?Pattern {
        return Pattern::RARITY;
    }
    case COMMON = 1;
    case UNCOMMON = 2;
    case RARE = 3;
}
