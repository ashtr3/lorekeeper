<?php

namespace App\Enums;

enum Rank: string {
    case Celestial = 'Celestial';
    
    case AlphaT3 = 'Alpha Tier 3';
    case AlphaT2 = 'Alpha Tier 2';
    case AlphaT1 = 'Alpha Tier 1';
    case AlphaT0 = 'Alpha';

    case BetaT2 = 'Beta Tier 2';
    case BetaT1 = 'Beta Tier 1';
    case BetaT0 = 'Beta';

    case RuntT2 = 'Runt Tier 2';
    case RuntT1 = 'Runt Tier 1';
    case RuntT0 = 'Runt';

    public function getThreshold(): int {
        return match($this) {
            self::Celestial => 1000,
            self::AlphaT3 => 900,
            self::AlphaT2 => 800,
            self::AlphaT1 => 700,
            self::AlphaT0 => 600,
            self::BetaT2 => 500,
            self::BetaT1 => 400,
            self::BetaT0 => 300,
            self::RuntT2 => 200,
            self::RuntT1 => 100,
            self::RuntT0 => 0,
        };
    }
}