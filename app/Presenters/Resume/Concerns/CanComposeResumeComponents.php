<?php

namespace App\Presenters\Resume\Concerns;

use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;

trait CanComposeResumeComponents
{
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
        $themeManager = self::getThemeManager();
        $component = new MainBackendComponent($case, $themeManager);

        return $component;
    }

    public static function getThemeManager(): ThemeManager
    {
        return (new DefaultThemeManager)
            ->setDefaultPath(resource_path('views/_themes/tailwind/resume/'));
    }
}
