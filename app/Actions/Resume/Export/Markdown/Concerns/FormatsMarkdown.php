<?php

namespace App\Actions\Resume\Export\Markdown\Concerns;

use DateTimeInterface;
use Illuminate\Support\Facades\Date;
use Throwable;

trait FormatsMarkdown
{
    protected function bullets(mixed $items): array
    {
        if (! is_array($items) || $items === []) {
            return [];
        }

        $lines = [''];

        foreach ($items as $item) {
            if (! is_string($item) || trim($item) === '') {
                continue;
            }

            $lines[] = '- '.$this->text($item);
        }

        return count($lines) > 1 ? $lines : [];
    }

    protected function dateRange(mixed $start, mixed $end): string
    {
        $from = $this->formatDate($start);

        if ($from === '') {
            return $this->formatDate($end) !== '' ? $this->formatDate($end) : 'Undated';
        }

        return $from.' – '.($this->formatDate($end) !== '' ? $this->formatDate($end) : 'Present');
    }

    protected function formatDate(mixed $value): string
    {
        // BuildResumeArray hands us raw model attributes, so dates
        // arrive as Carbon instances (json_encode masks this in JSON).
        if ($value instanceof DateTimeInterface) {
            return $value->format('M Y');
        }

        if (! is_string($value) || trim($value) === '') {
            return '';
        }

        try {
            return Date::parse($value)->format('M Y');
        } catch (Throwable) {
            return $value;
        }
    }

    protected function text(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return (string) preg_replace('/([\\\\`*_{}\\[\\]()#+!|>])/', '\\\\$1', $value);
    }
}
