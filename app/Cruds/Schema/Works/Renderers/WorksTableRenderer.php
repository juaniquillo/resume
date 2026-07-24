<?php

namespace App\Cruds\Schema\Works\Renderers;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Works\DeleteWork;
use App\Livewire\Resume\Works\EditWork;
use App\Models\Work;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class WorksTableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Work $work */
        $work = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditWork::class,
                id: "edit-work-{$work->id}",
                params: [$work->id]
            ),
            $helper->liveWireComponent(
                component: DeleteWork::class,
                id: "delete-work-{$work->id}",
                params: [$work->id]
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
