<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class SkillsSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'skills';
    }

    public function render(array $resume): array
    {
        $skills = $resume['skills'] ?? null;

        if (! is_array($skills) || $skills === []) {
            return [];
        }

        $lines = ['', '## Skills', ''];

        foreach ($skills as $skill) {
            $item = '**'.$this->text($skill['name'] ?? 'Skill').'**';

            if (! empty($skill['level'])) {
                $item .= ' ('.$this->text($skill['level']).')';
            }

            if (! empty($skill['keywords']) && is_array($skill['keywords'])) {
                $item .= ': '.$this->text(implode(', ', $skill['keywords']));
            }

            $lines[] = '- '.$item;
        }

        return $lines;
    }
}
