<?php

namespace App\Enums;

enum CharacterCategoryEnum: int {
    case PLAYER_OWNED = 1;
    case STARTER = 2;

    public function getValues(): array
    {
        return match ($this) {
            self::PLAYER_OWNED => ['code' => 'P', 'name' => 'Player-Owned'],
            self::STARTER => ['code' => 'S', 'name' => 'Starters']
        };
    }

    public function getPattern(): Pattern | null
    {
        return Pattern::CATEGORY;
    }
}