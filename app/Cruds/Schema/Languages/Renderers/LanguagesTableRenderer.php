<?php

namespace App\Cruds\Schema\Languages\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class LanguagesTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Language $language */
        $language = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->editButton(route('dashboard.languages.edit', [$language->id])),
            $helper->deleteButton(route('dashboard.languages.destroy', [$language->id])),
        ];

        return ComponentBuilder::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }
    
    public function renderExtraCells(): array
    {
        // Implementation for rendering extra cells
        return [];
    }
}
