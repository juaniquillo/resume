<?php

namespace App\Actions\Resume\Export\Markdown\Contracts;

interface MarkdownSectionRenderer
{
    /**
     * ResumeSection value this renderer handles (e.g. 'work', 'summary').
     */
    public function section(): string;

    /**
     * @param  array<string, mixed>  $resume  BuildResumeArray output.
     * @return list<string>
     */
    public function render(array $resume): array;
}
