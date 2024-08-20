<?php

namespace App\Enums;

enum FeatureEnum: int {
    case TRAIT = 1;

    public function getValues(): array
    {
        return match ($this) {
            self::TRAIT => ['name' => 'Trait'],
        };
    }

    public function getCategory(): FeatureCategoryEnum | null
    {
        return match ($this) {
            self::TRAIT => FeatureCategoryEnum::CATEGORY,
        };
    }

    public function getRarity(): RarityEnum
    {
        return match ($this) {
            self::TRAIT => RarityEnum::COMMON,
        };
    }

    public function getPattern(): Pattern | null
    {
        return match ($this) {
            self::TRAIT => Pattern::TRAIT_A,
        };
    }
}