<?php

namespace App\Presenters\Resume\Concerns;

use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

trait CanComposeResumeComponents
{
    private function section(string $title, BackendComponent|CompoundComponent $content): BackendComponent|CompoundComponent
    {
        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->sectionThemes())
            ->setAttribute('tag', 'section')
            ->setContents([
                'title' => $this->compose(ComponentEnum::H2)
                    ->setThemes($this->theme->sectionTitleThemes())
                    ->setContent($title),
                'content' => $content,
            ]);
    }

    private function compose(ComponentEnum|string $case): CompoundComponent
    {
        /** @var CompoundComponent $component */
        $component = LocalThemeComponentBuilder::make($case);

        return $component;
    }
}
