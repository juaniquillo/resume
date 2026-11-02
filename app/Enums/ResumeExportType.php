<?php

namespace App\Enums;

use App\Jobs\ProcessCoverLetterPdfExport;
use App\Jobs\ProcessJsonExport;
use App\Jobs\ProcessMarkdownExport;
use App\Jobs\ProcessPdfExport;
use App\Models\ResumeExport;
use Illuminate\Support\Str;

enum ResumeExportType: string
{
    case JSON = 'json';
    case PDF = 'pdf';
    case MARKDOWN = 'markdown';
    case COVER_LETTER_PDF = 'cover-letter-pdf';

    public function dispatchExportJob(ResumeExport $export): void
    {
        match ($this) {
            self::JSON => dispatch(new ProcessJsonExport($export)),
            self::PDF => dispatch(new ProcessPdfExport($export)),
            self::MARKDOWN => dispatch(new ProcessMarkdownExport($export)),
            self::COVER_LETTER_PDF => dispatch(new ProcessCoverLetterPdfExport($export)),
        };
    }

    public function extension(): string
    {
        return match ($this) {
            self::JSON => 'json',
            self::MARKDOWN => 'md',
            self::PDF, self::COVER_LETTER_PDF => 'pdf',
        };
    }

    public function filename(ResumeExport $export): string
    {
        $name = $export->user->basics->name ?? $export->user->name;
        $slug = Str::slug($name);

        $suffix = match ($this) {
            self::JSON => 'resume',
            self::MARKDOWN => 'resume',
            self::PDF => 'resume',
            self::COVER_LETTER_PDF => 'cover-letter',
        };

        if ($this === self::PDF || $this === self::COVER_LETTER_PDF) {
            return "{$slug}-{$suffix}.pdf";
        }

        return "{$slug}-{$suffix}.{$this->extension()}";
    }

    public function label(): string
    {
        return match ($this) {
            self::JSON => 'JSON Format',
            self::MARKDOWN => 'Markdown Document',
            self::PDF => 'PDF Document',
            self::COVER_LETTER_PDF => 'Cover Letter PDF',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::JSON => 'blue',
            self::MARKDOWN => 'purple',
            self::PDF => 'red',
            self::COVER_LETTER_PDF => 'green',
        };
    }

    public function themeable(): bool
    {
        return match ($this) {
            self::JSON => true,
            self::MARKDOWN => false,
            self::PDF => true,
            self::COVER_LETTER_PDF => true,
        };
    }
}
