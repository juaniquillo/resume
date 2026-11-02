<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class ProjectsSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'projects';
    }

    public function render(array $resume): array
    {
        $projects = $resume['projects'] ?? null;

        if (! is_array($projects) || $projects === []) {
            return [];
        }

        $lines = ['', '## Projects', ''];

        foreach ($projects as $project) {
            $name = $this->text($project['name'] ?? 'Project');
            $lines[] = ! empty($project['website']) ? "### [{$name}]({$project['website']})" : '### '.$name;
            $lines[] = '';
            $lines[] = '*'.$this->dateRange($project['startDate'] ?? null, $project['endDate'] ?? null).'*';

            if (! empty($project['description'])) {
                $lines[] = '';
                $lines[] = $this->text($project['description']);
            }

            $lines = [...$lines, ...$this->bullets($project['highlights'] ?? [])];
            $lines[] = '';
        }

        return $lines;
    }
}
