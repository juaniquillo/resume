<?php

namespace App\Presenters\Resume;

use App\Models\CoverLetter;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\LocalComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

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
                LocalComponentFactory::make('markdown')
                    ->setContent($this->coverLetter->content)
            );
    }
}
