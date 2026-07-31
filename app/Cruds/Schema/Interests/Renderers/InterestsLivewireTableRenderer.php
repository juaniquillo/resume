<?php

namespace App\Cruds\Schema\Interests\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Interests\DeleteInterest;
use App\Livewire\Resume\Interests\EditInterest;
use App\Models\Interest;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class InterestsLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Interest $interest */
        $interest = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditInterest::class,
                id: "edit-interest-{$interest->id}",
                params: [$interest->id]
            ),
            $helper->liveWireComponent(
                component: DeleteInterest::class,
                id: "delete-interest-{$interest->id}",
                params: [$interest->id]
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
