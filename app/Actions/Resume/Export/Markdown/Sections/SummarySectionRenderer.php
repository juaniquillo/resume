<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class SummarySectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'summary';
    }

    public function render(array $resume): array
    {
        $summary = $resume['basics']['summary'] ?? null;

        if (! is_string($summary) || trim($summary) === '') {
            return [];
        }

        return ['', '## Summary', '', $this->text($summary)];
    }
}
