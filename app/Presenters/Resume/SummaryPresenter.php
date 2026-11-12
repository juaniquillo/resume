<?php

namespace App\Presenters\Resume;

use App\Models\Basic;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

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
                ->setThemes($this->theme->itemContainerThemes())
                ->setContent(
                    $this->compose(ComponentEnum::DIV)
                        ->setThemes($this->theme->summaryInnerContainerThemes())
                        ->setContent($this->basics->summary)
                )
        )
            ->setThemes($this->theme->summaryContainerThemes());
    }
}
