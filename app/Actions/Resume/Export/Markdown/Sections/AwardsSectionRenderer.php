<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class AwardsSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'awards';
    }

    public function render(array $resume): array
    {
        $awards = $resume['awards'] ?? null;

        if (! is_array($awards) || $awards === []) {
            return [];
        }

        $lines = ['', '## Awards', ''];

        foreach ($awards as $award) {
            $heading = $this->text($award['title'] ?? 'Award');

            if (! empty($award['awarder'])) {
                $heading .= ' — '.$this->text($award['awarder']);
            }

            $lines[] = '### '.$heading;

            if (! empty($award['date'])) {
                $lines[] = '';
                $lines[] = '*'.$this->formatDate($award['date']).'*';
            }

            if (! empty($award['summary'])) {
                $lines[] = '';
                $lines[] = $this->text($award['summary']);
            }

            $lines[] = '';
        }

        return $lines;
    }
}
