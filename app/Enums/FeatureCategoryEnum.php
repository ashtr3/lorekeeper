<?php

namespace App\Enums;

enum FeatureCategoryEnum: int {
    case CATEGORY = 1;

    public function getValues(): array
    {
        return match ($this) {
            self::CATEGORY => ['name' => 'Category'],
        };
    }
}