<?php

namespace App\Presenters\Resume;

use App\Models\Basic;
use App\Models\User;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class SummaryPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private User $user,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        /** @var Basic|null $basics */
        $basics = $this->user->basics()->first();

        if (! $basics || ! $basics->summary) {
            return null;
        }

        return $this->section('Summary',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->summaryContainerThemes())
                ->setContents([
                    'paragraph' => $this->compose(ComponentEnum::PARAGRAPH)
                        ->setThemes($this->theme->summaryThemes())
                        ->setContent($basics->summary),
                ])
        );
    }
}
