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
    public  static function headers(
        array $headers,
        ThemeManager $themeManager = new DefaultThemeManager(),
        array $themes = [],
        /**  @var class-string<BackendComponent, CompoundComponent> */
        string $component = MainBackendComponent::class,
        string|BackedEnum $type = ComponentEnum::TR,
    ): BackendComponent|CompoundComponent
    {
        return self::resolveComponent(
            contents: $headers,
            themeManager: $themeManager,
            themes: $themes,
            component: $component,
            type: $type
        );
        
    }

    public static function rows(
       array $cells,
       ThemeManager $themeManager = new DefaultThemeManager(),
       array $themes = [],
       array $attributes = [],
       /**  @var class-string<BackendComponent, CompoundComponent> */
       string $component = MainBackendComponent::class,
       string|BackedEnum $type = ComponentEnum::TR,
    ): BackendComponent|CompoundComponent
    {
        return self::resolveComponent(
            contents: $cells,
            themeManager: $themeManager,
            themes: $themes,
            attributes: $attributes,
            component: $component,
            type: $type
        );
    }

    public  static function table(
        array $contents,
        ThemeManager $themeManager = new DefaultThemeManager(),
        array $themes = [],
        array $attributes = [],
        /**  @var class-string<BackendComponent, CompoundComponent> */
        string $component = MainBackendComponent::class,
        string|BackedEnum $type = ComponentEnum::TABLE,
    ): BackendComponent|CompoundComponent
    {
        return self::resolveComponent(
            contents: $contents,
            type: $type,
            themeManager: $themeManager,
            themes: $themes,
            attributes: $attributes,
            component: $component,
        );
    }

    public  static function tHead(
        array $header,
        ThemeManager $themeManager = new DefaultThemeManager(),
        array $themes = [],
        /**  @var class-string<BackendComponent, CompoundComponent> */
        string $component = MainBackendComponent::class,
        string|BackedEnum $type = ComponentEnum::THEAD,
    ): BackendComponent|CompoundComponent
    {
        return self::resolveComponent(
            contents: $header,
            type: $type,
            themeManager: $themeManager,
            themes: $themes,
            component: $component,
        );
        
    }

    public  static function headCell(
        string $header,
        ThemeManager $themeManager = new DefaultThemeManager(),
        array $themes = [],
        /**  @var class-string<BackendComponent, CompoundComponent> */
        string $component = MainBackendComponent::class,
        string|BackedEnum $type = ComponentEnum::TH,
    ): BackendComponent|CompoundComponent
    {
        return  self::resolveComponent(
            contents: [$header],
            type: $type,
            themeManager: $themeManager,
            themes: $themes,
            component: $component,
        );
    }
    
    public  static function tBody(
        array $rows,
        ThemeManager $themeManager = new DefaultThemeManager(),
        array $themes = [],
        /**  @var class-string<BackendComponent, CompoundComponent> */
        string $component = MainBackendComponent::class,
        string|BackedEnum $type = ComponentEnum::TBODY,
    ): BackendComponent|CompoundComponent
    {
        return self::resolveComponent(
            contents: $rows,
            type: $type,
            themeManager: $themeManager,
            themes: $themes,
            component: $component,
        );
        
    }
    
    public  static function resolveComponent(
        array $contents,
        string|BackedEnum $type,
        array $themes = [],
        ThemeManager $themeManager = new DefaultThemeManager(),
        array $attributes = [],
        /**  @var class-string<BackendComponent, CompoundComponent> */
        string $component = MainBackendComponent::class,
    ) : BackendComponent|CompoundComponent
    {
        $component = new $component($type, $themeManager);
        $component ->setThemes($themes)
            ->setAttributes($attributes)
            ->setContents($contents);
        
        return $component;
    }

}
