<?php

namespace App\Presenters\Resume;

use App\Models\Skill;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
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
                    $this->skills->map(function (Skill $skill) {
                        return $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->badgeThemes())
                            ->setContent($skill->name);
                    })->toArray()
                )
        );
    }
}
