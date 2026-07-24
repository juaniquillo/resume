<?php

namespace App\Presenters\Resume;

use App\Models\CoverLetter;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Builders\LocalComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class CoverLetterPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private ?CoverLetter $coverLetter,
        private PresenterTheme $theme,
    ) {}

    public function getTheme(): PresenterTheme
    {
        return $this->theme;
    }

    public function present(): BackendComponent|CompoundComponent|Htmlable|null
    {
        if (! $this->coverLetter || ! $this->coverLetter->content) {
            return null;
        }

        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->coverLetterContainerThemes())
            ->setContent(
                LocalComponentBuilder::make('markdown')
                    ->setContent($this->coverLetter->content)
            );
    }
}
