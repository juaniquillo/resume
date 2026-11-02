<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class ReferencesSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'references';
    }

    public function render(array $resume): array
    {
        $references = $resume['references'] ?? null;

        if (! is_array($references) || $references === []) {
            return [];
        }

        $lines = ['', '## References', ''];

        foreach ($references as $reference) {
            $item = '**'.$this->text($reference['name'] ?? 'Reference').'**';

            if (! empty($reference['reference'])) {
                $item .= ': “'.$this->text($reference['reference']).'”';
            }

            $lines[] = '- '.$item;
        }

        return $lines;
    }
}
