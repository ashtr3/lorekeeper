<?php

namespace App\Enums;

enum FeatureEnum: int {
    public function getValues(): array {
        return match ($this) {
            self::TRAIT => ['name' => 'Trait'],
        };
    }

    public function getCategory(): ?FeatureCategoryEnum {
        return match ($this) {
            self::TRAIT => FeatureCategoryEnum::CATEGORY,
        };
    }

    public function getRarity(): RarityEnum {
        return match ($this) {
            self::TRAIT => RarityEnum::COMMON,
        };
    }

    public function getPattern(): ?Pattern {
        return match ($this) {
            self::TRAIT => Pattern::TRAIT_A,
        };
    }
    case TRAIT = 1;
}
