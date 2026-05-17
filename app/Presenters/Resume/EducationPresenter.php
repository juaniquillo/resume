<?php

namespace App\Presenters\Resume;

use App\Models\Course;
use App\Models\Education;
use App\Models\User;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class EducationPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private User $user,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
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
                ->setThemes($this->theme->educationContainerThemes())
                ->setContents($items)
        );
    }

    private function presentEducationEntry(Education $edu): BackendComponent|CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->itemContainerThemes())
            ->setContents(array_filter([
                'institution' => $this->compose(ComponentEnum::H3)
                    ->setThemes($this->theme->itemTitleThemes())
                    ->setContent($edu->institution),
                'details' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->itemDetailsThemes())
                    ->setContents(array_filter([
                        'area' => $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->subTitleThemes())
                            ->setContent($edu->area),
                        'study_type' => $edu->study_type
                            ? $this->compose(ComponentEnum::SPAN)
                                ->setThemes($this->theme->badgeThemes())
                                ->setContent($edu->study_type)
                            : null,
                        'dates' => $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->dateThemes())
                            ->setContent(sprintf('%s - %s',
                                $edu->starts_at->format('M Y'),
                                $edu->ends_at
                                    ? $edu->ends_at->format('M Y')
                                    : 'Present'
                            )),
                    ])),
                'courses' => $edu->courses->isNotEmpty()
                    ? $this->compose(ComponentEnum::UL)
                        ->setThemes($this->theme->listThemes())
                        ->setContents(
                            $edu->courses->map(function (Model $c) {
                                /** @var Course $c */
                                return $this->compose(ComponentEnum::LI)
                                    ->setThemes($this->theme->listItemThemes())
                                    ->setContent($c->course);
                            })->toArray()
                        )
                    : null,

            ]));
    }
}
