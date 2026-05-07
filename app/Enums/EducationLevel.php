<?php

namespace App\Enums;

enum EducationLevel: string
{
    case PRESCHOOL = 'Preschool';
    case PRIMARY_SCHOOL = 'Primary school';
    case SECONDARY_SCHOOL = 'Secondary school';
    case VOCATIONAL = 'Vocational';
    case ASSOCIATE_DEGREE = 'Associate degree';
    case BACHELOR_DEGREE = 'Bachelor degree';
    case MASTER_DEGREE = 'Master degree';
    case DOCTORATE = 'Doctorate';

    public function colors(): string
    {
        return match ($this) {
            self::PRESCHOOL => 'red',
            self::PRIMARY_SCHOOL => 'orange',
            self::SECONDARY_SCHOOL => 'amber',
            self::VOCATIONAL => 'yellow',
            self::ASSOCIATE_DEGREE => 'lime',
            self::BACHELOR_DEGREE => 'green',
            self::MASTER_DEGREE => 'emerald',
            self::DOCTORATE => 'cyan',
        };
    }
}
