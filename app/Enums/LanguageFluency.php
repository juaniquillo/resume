<?php

namespace App\Enums;

enum LanguageFluency: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
    case EXPERT = 'expert';

    public function label(): string
    {
        return match ($this) {
            self::BEGINNER => 'Beginner',
            self::INTERMEDIATE => 'Intermediate',
            self::ADVANCED => 'Advanced',
            self::EXPERT => 'Expert',
        };
    }

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
