<?php

namespace App\Enums;

enum EducationLevel: string
{
    case PRESCHOOL = 'preschool';
    case PRIMARY_SCHOOL = 'primary_school';
    case SECONDARY_SCHOOL = 'secondary_school';
    case VOCATIONAL = 'vocational';
    case ASSOCIATE_DEGREE = 'associate_degree';
    case BACHELOR_DEGREE = 'bachelor_degree';
    case MASTER_DEGREE = 'master_degree';
    case DOCTORATE = 'doctorate';

    public function label(): string
    {
        return match ($this) {
            self::PRESCHOOL => 'Preschool',
            self::PRIMARY_SCHOOL => 'Primary school',
            self::SECONDARY_SCHOOL => 'Secondary school',
            self::VOCATIONAL => 'Vocational',
            self::ASSOCIATE_DEGREE => 'Associate degree',
            self::BACHELOR_DEGREE => 'Bachelor degree',
            self::MASTER_DEGREE => 'Master degree',
            self::DOCTORATE => 'Doctorate',
        };
    }

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
