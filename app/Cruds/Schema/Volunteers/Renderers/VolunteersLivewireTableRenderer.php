<?php

namespace App\Cruds\Schema\Volunteers\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Volunteers\DeleteVolunteer;
use App\Livewire\Resume\Volunteers\EditVolunteer;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class VolunteersLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Volunteer $volunteer */
        $volunteer = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditVolunteer::class,
                id: "edit-volunteer-{$volunteer->id}",
                params: [$volunteer->id]
            ),
            $helper->liveWireComponent(
                component: DeleteVolunteer::class,
                id: "delete-volunteer-{$volunteer->id}",
                params: [$volunteer->id]
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
