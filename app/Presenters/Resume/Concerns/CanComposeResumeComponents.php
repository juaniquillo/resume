<?php

namespace App\Presenters\Resume\Concerns;

use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

trait CanComposeResumeComponents
{
    use HasThemeManager;

    private function section(string $title, BackendComponent|CompoundComponent $content): CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->sectionThemes())
            // ->setAttribute('name', 'section')
            ->setContents([
                'title' => $this->compose(ComponentEnum::H2)
                    ->setThemes($this->theme->sectionTitleThemes())
                    ->setContent($title),
                'content' => $content,
            ]);
    }

    private function compose(ComponentEnum|string $case): MainBackendComponent
    {
        $themeManager = $this->getThemeManager();
        $component = new MainBackendComponent($case, $themeManager);

        return $component;
    }
}
