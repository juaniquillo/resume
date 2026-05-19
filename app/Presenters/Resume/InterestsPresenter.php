<?php

namespace App\Presenters\Resume;

use App\Models\Interest;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class InterestsPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private Collection $interests,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->interests->isEmpty()) {
            return null;
        }

        return $this->section('Interests',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->interestsContainerThemes())
                ->setContents(
                    $this->interests->map(function (Interest $interest) {
                        return $this->compose(ComponentEnum::DIV)
                            ->setThemes($this->theme->itemContainerThemes())
                            ->setContents(array_filter([
                                'name' => $this->compose(ComponentEnum::H3)
                                    ->setThemes($this->theme->itemTitleThemes())
                                    ->setContent($interest->name),
                                'keywords' => $interest->keywords
                                    ? $this->compose(ComponentEnum::SPAN)
                                        ->setThemes($this->theme->badgeThemes())
                                        ->setContent(implode(', ', $interest->keywords))
                                    : null,
                            ]));
                    })->toArray()
                )
        );
    }
}
