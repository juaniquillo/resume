<?php

namespace App\Enums;

enum ResumeExportType: string
{
    case JSON = 'json';
    case PDF = 'pdf';

    public function label(): string
    {
        return match ($this) {
            self::JSON => 'JSON Format',
            self::PDF => 'PDF Document',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::JSON => 'blue',
            self::PDF => 'red',
        };
    }
}
