<?php

namespace App\Presenters\Resume;

use App\Models\Skill;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class SkillsPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private Collection $skills,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->skills->isEmpty()) {
            return null;
        }

        return $this->section('Skills',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->skillsContainerThemes())
                ->setContents(
                    $this->skills->map(function (Model $model) {
                        /** @var Skill $skill */
                        $skill = $model;

                        $keywords = array_map(fn ($kw) => $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->keywordBadgeThemes())
                            ->setContent($kw), $skill->keywords ?? []);

                        return $this->compose(ComponentEnum::DIV)
                            ->setThemes($this->theme->itemContainerThemes())
                            ->setContents(array_filter([
                                'header' => $this->compose(ComponentEnum::DIV)
                                    ->setThemes($this->theme->itemDetailsThemes())
                                    ->setContents(array_filter([
                                        'name' => $this->compose(ComponentEnum::H3)
                                            ->setThemes($this->theme->itemTitleThemes())
                                            ->setContent($skill->name),
                                        'level' => $skill->level
                                            ? $this->compose(ComponentEnum::SPAN)
                                                ->setThemes($this->theme->badgeThemes())
                                                ->setContent($skill->level)
                                            : null,
                                    ])),
                                'keywords' => ! empty($keywords)
                                    ? $this->compose(ComponentEnum::DIV)
                                        ->setThemes($this->theme->contactContainerThemes()) // Use contact container for flex-wrap badges
                                        ->setContents($keywords)
                                    : null,
                            ]));
                    })->toArray()
                )
        );
    }
}



