<?php

namespace App\Presenters\Resume;

use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

class ContainerPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private PresenterTheme $theme,
    ) {}

    /** @param array<int|string, string|BackendComponent|CompoundComponent|Htmlable> $contents */
    public function present(array $contents): BackendComponent|CompoundComponent|Htmlable|null
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->containerThemes())
            ->setContents($contents);

    }
}
