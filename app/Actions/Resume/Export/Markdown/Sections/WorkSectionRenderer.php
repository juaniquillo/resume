<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class WorkSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'work';
    }

    public function render(array $resume): array
    {
        $work = $resume['work'] ?? null;

        if (! is_array($work) || $work === []) {
            return [];
        }

        $lines = ['', '## Experience', ''];

        foreach ($work as $entry) {
            $heading = $this->text($entry['position'] ?? $entry['name'] ?? 'Role');

            if (! empty($entry['name']) && ! empty($entry['position'])) {
                $company = ! empty($entry['url'])
                    ? "[{$this->text($entry['name'])}]({$entry['url']})"
                    : $this->text($entry['name']);
                $heading .= ' at '.$company;
            }

            $lines[] = '### '.$heading;
            $lines[] = '';
            $lines[] = '*'.$this->dateRange($entry['startDate'] ?? null, $entry['endDate'] ?? null).'*';

            if (! empty($entry['summary'])) {
                $lines[] = '';
                $lines[] = $this->text($entry['summary']);
            }

            $lines = [...$lines, ...$this->bullets($entry['highlights'] ?? [])];
            $lines[] = '';
        }

        return $lines;
    }
}
