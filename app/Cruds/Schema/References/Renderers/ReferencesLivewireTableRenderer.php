<?php

namespace App\Cruds\Schema\References\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\References\DeleteReference;
use App\Livewire\Resume\References\EditReference;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ReferencesLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Reference $reference */
        $reference = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditReference::class,
                id: "edit-reference-{$reference->id}",
                params: [$reference->id]
            ),
            $helper->liveWireComponent(
                component: DeleteReference::class,
                id: "delete-reference-{$reference->id}",
                params: [$reference->id]
            ),
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
