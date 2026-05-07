<?php

namespace App\Enums;

enum SkillLevel: string
{
    case BEGINNER = 'Beginner';
    case INTERMEDIATE = 'Intermediate';
    case ADVANCED = 'Advanced';
    case EXPERT = 'Expert';

    public function labelColor(): string
    {
        return match ($this) {
            self::BEGINNER => 'orange',
            self::INTERMEDIATE => 'amber',
            self::ADVANCED => 'green',
            self::EXPERT => 'blue',
        };
    }
}
