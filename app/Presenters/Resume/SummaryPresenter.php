<?php

namespace App\Presenters\Resume;

use App\Models\Basic;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class SummaryPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private ?Basic $basics,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if (! $this->basics || ! $this->basics->summary) {
            return null;
        }

        return $this->section('Summary',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->summaryContainerThemes())
                ->setContents([
                    'paragraph' => $this->compose(ComponentEnum::PARAGRAPH)
                        ->setThemes($this->theme->summaryThemes())
                        ->setContent($this->basics->summary),
                ])
        );
    }
}



