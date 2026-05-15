<?php

namespace App\Presenters;

use App\Models\Basic;
use App\Models\Education;
use App\Models\Highlight;
use App\Models\Language;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Work;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Themes\DefaultPresenterTheme;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ResumePresenter
{
    public function __construct(
        private User $user,
        private ?PresenterTheme $theme = new DefaultPresenterTheme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|Htmlable
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->containerThemes())
            ->setContents(array_filter([
                'basics' => $this->presentBasics(),
                'summary' => $this->presentSummary(),
                'work' => $this->presentWork(),
                'volunteers' => $this->presentVolunteers(),
                'education' => $this->presentEducation(),
                'skills' => $this->presentSkills(),
                'languages' => $this->presentLanguages(),
                'projects' => $this->presentProjects(),
            ]));
    }

    public function presentCached(): string
    {
        $key = $this->getCacheKey();

        return Cache::rememberForever($key, fn () => (string) $this->present()->toHtml());
    }

    private function getCacheKey(): string
    {
        $version = Cache::get("resume:{$this->user->id}:v", 1);
        $themeHash = md5(get_class($this->theme));

        return "resume:{$this->user->id}:v{$version}:{$themeHash}";
    }

    private function presentBasics(): BackendComponent|CompoundComponent|null
    {
        /** @var Basic|null $basics */
        $basics = $this->user->basics()->with(['location', 'profiles'])->first();

        if (! $basics) {
            return null;
        }

        return $this->compose(ComponentEnum::DIV)
            ->setContents(array_filter([
                'image' => $basics->image
                    ? $this->compose(ComponentEnum::IMG)
                        ->setThemes($this->theme->imageThemes())
                        ->setAttributes([
                            'src' => route('image.serve', $basics->uuid),
                            'alt' => $basics->name,
                        ])
                    : null,
                'name' => $this->compose(ComponentEnum::H1)
                    ->setThemes($this->theme->nameThemes())
                    ->setContent($basics->name),
                'label' => $this->compose(ComponentEnum::H2)
                    ->setThemes($this->theme->labelThemes())
                    ->setContent($basics->label),
                'contact' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->contactContainerThemes())
                    ->setContents($this->basicsContactItems($basics)),
            ]));
    }

    /**
     * @return array<string, BackendComponent|CompoundComponent>
     */
    private function basicsContactItems(Basic $basics): array
    {
        $items = [];

        if ($basics->email) {
            $items['email'] = $this->compose(ComponentEnum::LINK)
                ->setThemes($this->theme->linkThemes())
                ->setAttribute('href', "mailto:{$basics->email}")
                ->setContent($basics->email);
        }

        if ($basics->phone) {
            $items['phone'] = $this->compose(ComponentEnum::SPAN)
                ->setThemes($this->theme->contactItemThemes())
                ->setContent($basics->phone);
        }

        if ($basics->url) {
            $items['url'] = $this->compose(ComponentEnum::LINK)
                ->setThemes($this->theme->linkThemes())
                ->setAttribute('href', $basics->url)
                ->setAttribute('target', '_blank')
                ->setContent($basics->url);
        }

        if ($basics->location) {
            $location = "{$basics->location->city}, {$basics->location->country_code}";
            $items['location'] = $this->compose(ComponentEnum::SPAN)
                ->setThemes($this->theme->contactItemThemes())
                ->setContent($location);
        }

        return $items;
    }

    private function presentSummary(): BackendComponent|CompoundComponent|null
    {
        /** @var Basic|null $basics */
        $basics = $this->user->basics()->first();

        if (! $basics || ! $basics->summary) {
            return null;
        }

        return $this->section('Summary',
            $this->compose(ComponentEnum::PARAGRAPH)
                ->setThemes($this->theme->summaryThemes())
                ->setContent($basics->summary)
        );
    }

    private function presentWork(): BackendComponent|CompoundComponent|null
    {
        /** @var Collection<int, Work> $works */
        $works = $this->user->works()->with('highlights')->orderByDesc('starts_at')->get();

        if ($works->isEmpty()) {
            return null;
        }

        $items = $works->map(function (Work $work) {
            return $this->presentWorkEntry($work);
        })->toArray();

        return $this->section('Experience',
            $this->compose(ComponentEnum::DIV)
                ->setContents($items)
        );
    }

    private function presentWorkEntry(Work $work): BackendComponent|CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->itemContainerThemes())
            ->setContents(array_filter([
                'position' => $this->compose(ComponentEnum::H3)
                    ->setThemes($this->theme->itemTitleThemes())
                    ->setContent($work->position),
                'details' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->itemDetailsThemes())
                    ->setContents([
                        'name' => $this->compose(ComponentEnum::SPAN)->setContent($work->name),
                        'dates' => $this->compose(ComponentEnum::SPAN)
                            ->setContent(sprintf('%s - %s',
                                $work->starts_at->format('M Y'),
                                $work->ends_at
                                    ? $work->ends_at->format('M Y')
                                    : 'Present'
                            )),
                    ]),
                'summary' => $work->summary
                    ? $this->compose(ComponentEnum::PARAGRAPH)
                        ->setThemes($this->theme->summaryThemes())
                        ->setContent($work->summary)
                    : null,
                'highlights' => $work->highlights->isNotEmpty()
                    ? $this->compose(ComponentEnum::UL)
                        ->setThemes($this->theme->listThemes())
                        ->setContents(
                            $work->highlights->map(function (Model $h) {
                                /** @var Highlight $h */
                                return $this->compose(ComponentEnum::LI)->setContent($h->highlight);
                            })->toArray()
                        )
                    : null,
            ]));
    }

    private function presentVolunteers(): BackendComponent|CompoundComponent|null
    {
        /** @var Collection<int, Volunteer> $volunteers */
        $volunteers = $this->user->volunteers()->with('highlights')->orderByDesc('starts_at')->get();

        if ($volunteers->isEmpty()) {
            return null;
        }

        $items = $volunteers->map(function (Volunteer $volunteer) {
            return $this->presentVolunteerEntry($volunteer);
        })->toArray();

        return $this->section('Volunteering',
            $this->compose(ComponentEnum::DIV)
                ->setContents($items)
        );
    }

    private function presentVolunteerEntry(Volunteer $volunteer): BackendComponent|CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->itemContainerThemes())
            ->setContents(array_filter([
                'position' => $this->compose(ComponentEnum::H3)
                    ->setThemes($this->theme->itemTitleThemes())
                    ->setContent($volunteer->position),
                'details' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->itemDetailsThemes())
                    ->setContents([
                        'organization' => $this->compose(ComponentEnum::SPAN)->setContent($volunteer->organization),
                        'dates' => $this->compose(ComponentEnum::SPAN)
                            ->setContent(sprintf('%s - %s',
                                $volunteer->starts_at->format('M Y'),
                                $volunteer->ends_at
                                    ? $volunteer->ends_at->format('M Y')
                                    : 'Present'
                            )),
                    ]),
                'summary' => $volunteer->summary
                    ? $this->compose(ComponentEnum::PARAGRAPH)
                        ->setThemes($this->theme->summaryThemes())
                        ->setContent($volunteer->summary)
                    : null,
                'highlights' => $volunteer->highlights->isNotEmpty()
                    ? $this->compose(ComponentEnum::UL)
                        ->setThemes($this->theme->listThemes())
                        ->setContents(
                            $volunteer->highlights->map(function (Model $h) {
                                /** @var Highlight $h */
                                return $this->compose(ComponentEnum::LI)->setContent($h->highlight);
                            })->toArray()
                        )
                    : null,
            ]));
    }

    private function presentEducation(): BackendComponent|CompoundComponent|null
    {
        /** @var Collection<int, Education> $education */
        $education = $this->user->education()->orderByDesc('starts_at')->get();

        if ($education->isEmpty()) {
            return null;
        }

        $items = $education->map(function (Education $edu) {
            return $this->presentEducationEntry($edu);
        })->toArray();

        return $this->section('Education',
            $this->compose(ComponentEnum::DIV)
                ->setContents($items)
        );
    }

    private function presentEducationEntry(Education $edu): BackendComponent|CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->itemContainerThemes())
            ->setContents([
                'institution' => $this->compose(ComponentEnum::H3)
                    ->setThemes($this->theme->itemTitleThemes())
                    ->setContent($edu->institution),
                'details' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->itemDetailsThemes())
                    ->setContents(array_filter([
                        'area' => $this->compose(ComponentEnum::SPAN)->setContent($edu->area),
                        'study_type' => $edu->study_type ? $this->compose(ComponentEnum::SPAN)->setContent($edu->study_type) : null,
                        'dates' => $this->compose(ComponentEnum::SPAN)
                            ->setContent(sprintf('%s - %s',
                                $edu->starts_at->format('M Y'),
                                $edu->ends_at
                                    ? $edu->ends_at->format('M Y')
                                    : 'Present'
                            )),
                    ])),
            ]);
    }

    private function presentSkills(): BackendComponent|CompoundComponent|null
    {
        /** @var Collection<int, Skill> $skills */
        $skills = $this->user->skills()->get();

        if ($skills->isEmpty()) {
            return null;
        }

        return $this->section('Skills',
            $this->compose(ComponentEnum::DIV)
                ->setContents(
                    $skills->map(function (Skill $skill) {
                        return $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->contactItemThemes())
                            ->setContent($skill->name);
                    })->toArray()
                )
        );
    }

    private function presentLanguages(): BackendComponent|CompoundComponent|null
    {
        /** @var Collection<int, Language> $languages */
        $languages = $this->user->languages()->get();

        if ($languages->isEmpty()) {
            return null;
        }

        return $this->section('Languages',
            $this->compose(ComponentEnum::DIV)
                ->setContents(
                    $languages->map(function (Language $lang) {
                        return $this->compose(ComponentEnum::DIV)
                            ->setThemes($this->theme->itemContainerThemes())
                            ->setContents([
                                'name' => $this->compose(ComponentEnum::SPAN)->setContent($lang->language),
                                'fluency' => $this->compose(ComponentEnum::SPAN)
                                    ->setThemes($this->theme->contactItemThemes())
                                    ->setContent($lang->fluency),
                            ]);
                    })->toArray()
                )
        );
    }

    private function presentProjects(): BackendComponent|CompoundComponent|null
    {
        /** @var Collection<int, Project> $projects */
        $projects = $this->user->projects()->with('highlights')->get();

        if ($projects->isEmpty()) {
            return null;
        }

        return $this->section('Projects',
            $this->compose(ComponentEnum::DIV)
                ->setContents(
                    $projects->map(function (Project $project) {
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
                                return $this->compose(ComponentEnum::LI)->setContent($h->highlight);
                            })->toArray()
                        )
                    : null,
            ]));
    }

    private function section(string $title, BackendComponent|CompoundComponent $content): BackendComponent|CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->sectionThemes())
            ->setAttribute('tag', 'section')
            ->setContents([
                'title' => $this->compose(ComponentEnum::H2)
                    ->setThemes($this->theme->sectionTitleThemes())
                    ->setContent($title),
                'content' => $content,
            ]);
    }

    private function compose(ComponentEnum|string $case): CompoundComponent
    {
        /** @var CompoundComponent $component */
        $component = LocalThemeComponentBuilder::make($case);

        return $component;
    }
}
