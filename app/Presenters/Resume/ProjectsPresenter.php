<?php

namespace App\Presenters\Resume;

use App\Models\Highlight;
use App\Models\Project;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ProjectsPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private Collection $projects,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->projects->isEmpty()) {
            return null;
        }

        return $this->section('Projects',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->projectsContainerThemes())
                ->setContents(
                    $this->projects->map(function (Project $project) {
                        return $this->presentProjectEntry($project);
                    })->toArray()
                )
        );
    }

    private function presentProjectEntry(Project $project): BackendComponent|CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->itemContainerThemes())
            ->setContents(array_filter([
                'name' => $project->url
                    ? $this->compose(ComponentEnum::LINK)
                        ->setAttribute('href', $project->url)
                        ->setAttribute('target', '_blank')
                        ->setContent(
                            $this->compose(ComponentEnum::H3)
                                ->setThemes($this->theme->itemTitleThemes())
                                ->setContent($project->name)
                        )
                    : $this->compose(ComponentEnum::H3)
                        ->setThemes($this->theme->itemTitleThemes())
                        ->setContent($project->name),
                'details' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->itemDetailsThemes())
                    ->setContents([
                        'dates' => $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->dateThemes())
                            ->setContent(sprintf('%s - %s',
                                $project->start_date->format('M Y'),
                                $project->end_date
                                    ? $project->end_date->format('M Y')
                                    : 'Present'
                            )),
                    ]),
                'description' => $project->description
                    ? $this->compose(ComponentEnum::PARAGRAPH)
                        ->setThemes($this->theme->summaryThemes())
                        ->setContent($project->description)
                    : null,
                'highlights' => $project->highlights->isNotEmpty()
                    ? $this->compose(ComponentEnum::UL)
                        ->setThemes($this->theme->listThemes())
                        ->setContents(
                            $project->highlights->map(function (Model $h) {
                                /** @var Highlight $h */
                                return $this->compose(ComponentEnum::LI)
                                    ->setThemes($this->theme->listItemThemes())
                                    ->setContent($h->highlight);
                            })->toArray()
                        )
                    : null,
            ]));
    }
}
