<?php

namespace App\Presenters\Resume\Concerns;

use App\Presenters\Resume\ResumeThemeManager;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;

trait CanComposeResumeComponents
{
    private ?ThemeManager $themeManager = null;

    public function getThemeManager(): ThemeManager
    {
        if ($this->themeManager) {
            return $this->themeManager;
        }

        return new ResumeThemeManager;
    }

    public function setThemeManager(?ThemeManager $themeManager = null): static
    {
        $this->themeManager = $themeManager;

        return $this;
    }

    private function section(string $title, BackendComponent|CompoundComponent $content): BackendComponent|CompoundComponent
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

    private function compose(ComponentEnum|string $case): CompoundComponent
    {
        $themeManager = $this->getThemeManager();
        $component = new MainBackendComponent($case, $themeManager);

        return $component;
    }
}
