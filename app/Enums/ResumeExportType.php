<?php

namespace App\Enums;

enum ResumeExportType: string
{
    case JSON = 'json';
    case PDF = 'pdf';
    case COVER_LETTER_PDF = 'cover-letter-pdf';

    public function extension(): string
    {
        return match ($this) {
            self::JSON => 'json',
            self::PDF, self::COVER_LETTER_PDF => 'pdf',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::JSON => 'JSON Format',
            self::PDF => 'PDF Document',
            self::COVER_LETTER_PDF => 'Cover Letter PDF',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::JSON => 'blue',
            self::PDF => 'red',
            self::COVER_LETTER_PDF => 'green',
        };
    }

    public function themeable(): bool
    {
        return match ($this) {
            self::JSON => false,
            self::PDF => true,
            self::COVER_LETTER_PDF => true,
        };
    }
}
