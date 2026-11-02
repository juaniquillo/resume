<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class LanguagesSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'languages';
    }

    public function render(array $resume): array
    {
        $languages = $resume['languages'] ?? null;

        if (! is_array($languages) || $languages === []) {
            return [];
        }

        $lines = ['', '## Languages', ''];

        foreach ($languages as $language) {
            $item = '**'.$this->text($language['language'] ?? 'Language').'**';

            if (! empty($language['fluency'])) {
                $item .= ': '.$this->text($language['fluency']);
            }

            $lines[] = '- '.$item;
        }

        return $lines;
    }
}
