<?php

namespace App\Presenters\Resume;

use App\Models\Language;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class LanguagesPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private Collection $languages,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->languages->isEmpty()) {
            return null;
        }

        return $this->section('Languages',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->languagesContainerThemes())
                ->setContents(
                    $this->languages->map(function (Language $lang) {
                        return $this->compose(ComponentEnum::DIV)
                            ->setThemes($this->theme->itemContainerThemes())
                            ->setContents([
                                'name' => $this->compose(ComponentEnum::SPAN)
                                    ->setThemes($this->theme->subTitleThemes())
                                    ->setContent($lang->language),
                                'fluency' => $this->compose(ComponentEnum::SPAN)
                                    ->setThemes($this->theme->badgeThemes())
                                    ->setContent($lang->fluency),
                            ]);
                    })->toArray()
                )
        );
    }
}
