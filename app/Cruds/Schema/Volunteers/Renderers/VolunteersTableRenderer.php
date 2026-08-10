<?php

namespace App\Cruds\Schema\Volunteers\Renderers;

use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class VolunteersTableRenderer implements TableRenderer
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
            $helper->editButton(route('dashboard.certificates.edit', [$volunteer->id])),
            $helper->deleteButton(route('dashboard.certificates.destroy', [$volunteer->id])),
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
        return [
            'Highlights' => new TableRowsRecipe(
                value: function ($value, Model $model) {
                    /** @var Volunteer $volunteer */
                    $volunteer = $model;

                    return TableHelpers::highlightsButton(route('dashboard.volunteers.highlights', [$volunteer->id]));
                },
            )
        ];
    }
}
