<?php

namespace App\Cruds\Actions\Presenters;

use BackedEnum;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\BackendComponents\Themes\DefaultThemeManager;

class TableComponentUtil
{
    public function __construct(
        private ThemeManager $themeManager = new DefaultThemeManager,
        /** @var class-string<BackendComponent|CompoundComponent> */
        private string $component = MainBackendComponent::class,
    ) {}

    public function header(
        string $header,
        string|BackedEnum $type = ComponentEnum::TH,
        array $themes = [],
        array $attributes = [],
    ): BackendComponent|CompoundComponent {
        return $this->resolveComponent(
            contents: [$header],
            themeManager: $this->themeManager,
            themes: $themes,
            attributes: $attributes,
            component: $this->component,
            type: $type
        );

    }

    public function rows(
        array $cells,
        string|BackedEnum $type = ComponentEnum::TR,
        array $themes = [],
        array $attributes = [],
    ): BackendComponent|CompoundComponent {
        return $this->resolveComponent(
            contents: $cells,
            themeManager: $this->themeManager,
            themes: $themes,
            attributes: $attributes,
            component: $this->component,
            type: $type
        );
    }

    public function table(
        array $contents,
        string|BackedEnum $type = ComponentEnum::TABLE,
        array $themes = [],
        array $attributes = [],
    ): BackendComponent|CompoundComponent {
        return $this->resolveComponent(
            contents: $contents,
            type: $type,
            themeManager: $this->themeManager,
            themes: $themes,
            attributes: $attributes,
            component: $this->component,
        );
    }

    public function tHead(
        array $headers,
        string|BackedEnum $type = ComponentEnum::THEAD,
        array $themes = [],
        array $attributes = [],
    ): BackendComponent|CompoundComponent {
        return $this->resolveComponent(
            contents: $headers,
            type: $type,
            themeManager: $this->themeManager,
            themes: $themes,
            attributes: $attributes,
            component: $this->component,
        );

    }

    public function headCell(
        string $header,
        string|BackedEnum $type = ComponentEnum::TH,
        array $themes = [],
        array $attributes = [],
    ): BackendComponent|CompoundComponent {
        return $this->resolveComponent(
            contents: [$header],
            type: $type,
            themeManager: $this->themeManager,
            themes: $themes,
            attributes: $attributes,
            component: $this->component,
        );
    }

    public function tBody(
        array $rows,
        string|BackedEnum $type = ComponentEnum::TBODY,
        array $themes = [],
        array $attributes = [],
    ): BackendComponent|CompoundComponent {
        return $this->resolveComponent(
            contents: $rows,
            type: $type,
            themeManager: $this->themeManager,
            themes: $themes,
            attributes: $attributes,
            component: $this->component,
        );

    }

    public function resolveComponent(
        array $contents,
        string|BackedEnum $type,
        array $themes = [],
        array $attributes = [],
        ThemeManager $themeManager = new DefaultThemeManager,
        /** @var class-string<BackendComponent|CompoundComponent> */
        string $component = MainBackendComponent::class,
    ): BackendComponent|CompoundComponent {
        $component = new $component($type, $this->themeManager);

        $component->setAttributes($attributes)
            ->setContents($contents);

        if (! empty($themes)) {
            $component->setThemes($themes);
        }

        return $component;
    }
}
