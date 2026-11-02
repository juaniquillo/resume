<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class VolunteersSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'volunteers';
    }

    public function render(array $resume): array
    {
        // BuildResumeArray uses the singular key.
        $volunteers = $resume['volunteer'] ?? null;

        if (! is_array($volunteers) || $volunteers === []) {
            return [];
        }

        $lines = ['', '## Volunteering', ''];

        foreach ($volunteers as $entry) {
            $heading = $this->text($entry['position'] ?? $entry['organization'] ?? 'Volunteer');

            if (! empty($entry['organization']) && ! empty($entry['position'])) {
                $org = ! empty($entry['url'])
                    ? "[{$this->text($entry['organization'])}]({$entry['url']})"
                    : $this->text($entry['organization']);
                $heading .= ' at '.$org;
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
