<?php

namespace App\Presenters\Resume;

use App\Models\Interest;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
                    $this->interests->map(function (Model $model) {
                        /** @var Interest $interest */
                        $interest = $model;

                        $keywords = array_map(fn ($kw) => $this->compose(ComponentEnum::SPAN)
                            ->setThemes($this->theme->badgeThemes())
                            ->setContent($kw), $interest->keywords ?? []);

                        return $this->compose(ComponentEnum::DIV)
                            ->setThemes($this->theme->itemContainerThemes())
                            ->setContents(array_filter([
                                'name' => $this->compose(ComponentEnum::H3)
                                    ->setThemes($this->theme->itemTitleThemes())
                                    ->setContent($interest->name),
                                'keywords' => ! empty($keywords)
                                    ? $this->compose(ComponentEnum::DIV)
                                        ->setThemes($this->theme->contactContainerThemes())
                                        ->setContents($keywords)
                                    : null,
                            ]));
                    })->toArray()
                )
        );
    }
}
