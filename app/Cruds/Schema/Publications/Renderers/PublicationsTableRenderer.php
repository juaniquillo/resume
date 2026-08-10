<?php

namespace App\Cruds\Schema\Publications\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Publication;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class PublicationsTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Publication $publication */
        $publication = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->editButton(route('dashboard.publications.edit', [$publication->id])),
            $helper->deleteButton(route('dashboard.publications.destroy', [$publication->id])),
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
