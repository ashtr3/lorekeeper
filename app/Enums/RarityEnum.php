<?php

namespace App\Enums;

enum RarityEnum: int {
    case COMMON = 1;
    case UNCOMMON = 2;
    case RARE = 3;

    public function getValues(): array
    {
        return match ($this) {
            self::COMMON => ['name' => 'Common'],
            self::UNCOMMON => ['name' => 'Uncommon'],
            self::RARE => ['name' => 'Rare']
        };
    }

    public function getPattern(): Pattern | null
    {
        return Pattern::RARITY;
    }
}