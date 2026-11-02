<?php

namespace App\Actions\Resume\Export\Markdown;

use App\Actions\Resume\Export\BuildResumeArray;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\AwardsSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\BasicsSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\CertificatesSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\EducationSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\InterestsSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\LanguagesSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\ProjectsSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\PublicationsSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\ReferencesSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\SkillsSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\SummarySectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\VolunteersSectionRenderer;
use App\Actions\Resume\Export\Markdown\Sections\WorkSectionRenderer;
use App\Jobs\ProcessMarkdownExport;

/**
 * Renders a Markdown resume document from a {@see BuildResumeArray}
 * payload (JSON Resume shape).
 *
 * Pure string transformation: no database, no themes. Section visibility and
 * ordering are applied by the caller ({@see ProcessMarkdownExport});
 * this class renders `basics` first, then `$orderedKeys` in the given order
 * by dispatching to the matching {@see MarkdownSectionRenderer}.
 */
final class ResumeMarkdownRenderer
{
    /** @var array<string, MarkdownSectionRenderer> */
    private array $renderers;

    /**
     * @param  iterable<MarkdownSectionRenderer>|null  $renderers
     */
    public function __construct(?iterable $renderers = null)
    {
        $renderers ??= [
            new BasicsSectionRenderer,
            new SummarySectionRenderer,
            new WorkSectionRenderer,
            new VolunteersSectionRenderer,
            new EducationSectionRenderer,
            new AwardsSectionRenderer,
            new CertificatesSectionRenderer,
            new PublicationsSectionRenderer,
            new SkillsSectionRenderer,
            new LanguagesSectionRenderer,
            new InterestsSectionRenderer,
            new ReferencesSectionRenderer,
            new ProjectsSectionRenderer,
        ];

        $map = [];

        foreach ($renderers as $renderer) {
            $map[$renderer->section()] = $renderer;
        }

        $this->renderers = $map;
    }

    /**
     * @param  array<string, mixed>  $resume  BuildResumeArray output.
     * @param  list<string>  $orderedKeys  ResumeSection values in display order.
     */
    public function render(array $resume, array $orderedKeys): string
    {
        $lines = isset($this->renderers['basics']) ? $this->renderers['basics']->render($resume) : [];

        foreach ($orderedKeys as $section) {
            if ($section === 'basics' || ! isset($this->renderers[$section])) {
                continue;
            }

            $lines = [...$lines, ...$this->renderers[$section]->render($resume)];
        }

        return implode("\n", $lines)."\n";
    }
}
