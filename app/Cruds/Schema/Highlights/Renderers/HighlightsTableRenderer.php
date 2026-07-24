<?php

namespace App\Cruds\Schema\Highlights\Renderers;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Highlights\DeleteHighlight;
use App\Livewire\Resume\Highlights\EditHighlight;
use App\Models\Highlight;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class HighlightsTableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Highlight $highlight */
        $highlight = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditHighlight::class,
                id: "edit-highlight-{$highlight->id}",
                params: [$highlight->id]
            ),
            $helper->liveWireComponent(
                component: DeleteHighlight::class,
                id: "delete-highlight-{$highlight->id}",
                params: [$highlight->id]
            ),
        ];

        return ComponentBuilder::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }
}
