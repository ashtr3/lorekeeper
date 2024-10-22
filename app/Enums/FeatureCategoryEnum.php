<?php

namespace App\Enums;

enum FeatureCategoryEnum: int {
    public function getValues(): array {
        return match ($this) {
            self::CATEGORY => ['name' => 'Category'],
        };
    }
    case CATEGORY = 1;
}
