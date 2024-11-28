<?php

namespace App\Enums;

enum Fertility: string {
    public function getThreshold(): int {
        return match ($this) {
            self::Perfect => 90,
            self::Good    => 70,
            self::Average => 40,
            self::Low     => 10,
            self::Sterile => 0
        };
    }
    case Perfect = 'perfect';
    case Good = 'good';
    case Average = 'average';
    case Low = 'low';
    case Sterile = 'sterile';
}
