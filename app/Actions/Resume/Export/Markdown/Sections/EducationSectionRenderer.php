<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class EducationSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'education';
    }

    public function render(array $resume): array
    {
        $education = $resume['education'] ?? null;

        if (! is_array($education) || $education === []) {
            return [];
        }

        $lines = ['', '## Education', ''];

        foreach ($education as $entry) {
            $parts = array_filter([
                $this->text($entry['studyType'] ?? null),
                $this->text($entry['area'] ?? null),
            ]);
            $heading = $parts !== [] ? implode(' in ', $parts) : $this->text($entry['institution'] ?? 'Education');

            if (! empty($entry['institution']) && $parts !== []) {
                $institution = ! empty($entry['website'])
                    ? "[{$this->text($entry['institution'])}]({$entry['website']})"
                    : $this->text($entry['institution']);
                $heading .= ', '.$institution;
            }

            $lines[] = '### '.$heading;
            $lines[] = '';
            $lines[] = '*'.$this->dateRange($entry['startDate'] ?? null, $entry['endDate'] ?? null).'*';

            if (! empty($entry['score'])) {
                $lines[] = '';
                $lines[] = 'Score: '.$this->text($entry['score']);
            }

            $lines = [...$lines, ...$this->bullets($entry['courses'] ?? [])];
            $lines[] = '';
        }

        return $lines;
    }
}
