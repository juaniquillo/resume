<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class InterestsSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'interests';
    }

    public function render(array $resume): array
    {
        $interests = $resume['interests'] ?? null;

        if (! is_array($interests) || $interests === []) {
            return [];
        }

        $lines = ['', '## Interests', ''];

        foreach ($interests as $interest) {
            $item = '**'.$this->text($interest['name'] ?? 'Interest').'**';

            if (! empty($interest['keywords']) && is_array($interest['keywords'])) {
                $item .= ': '.$this->text(implode(', ', $interest['keywords']));
            }

            $lines[] = '- '.$item;
        }

        return $lines;
    }
}
