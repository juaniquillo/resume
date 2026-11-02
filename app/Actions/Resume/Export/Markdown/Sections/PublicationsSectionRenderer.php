<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class PublicationsSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'publications';
    }

    public function render(array $resume): array
    {
        $publications = $resume['publications'] ?? null;

        if (! is_array($publications) || $publications === []) {
            return [];
        }

        $lines = ['', '## Publications', ''];

        foreach ($publications as $pub) {
            $name = $this->text($pub['name'] ?? 'Publication');
            $item = ! empty($pub['website']) ? "**[{$name}]({$pub['website']})**" : "**{$name}**";

            $details = array_filter([
                isset($pub['publisher']) ? $this->text($pub['publisher']) : null,
                ! empty($pub['releaseDate']) ? $this->formatDate($pub['releaseDate']) : null,
            ]);

            if ($details !== []) {
                $item .= ' — '.implode(', ', $details);
            }

            $lines[] = '- '.$item;

            if (! empty($pub['summary'])) {
                $lines[] = '  '.$this->text($pub['summary']);
            }
        }

        return $lines;
    }
}
