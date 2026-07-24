<?php

namespace App\Cruds\Schema\Profiles\Renderers;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ProfilesTableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: function ($value, Model $model) {
                /** @var Profile $profile */
                $profile = $model;

                $helper = TableHelpers::make();

                $contents = [
                    $helper->editButton(route('dashboard.basics.profiles.edit', [$profile->id])),
                    $helper->deleteButton(route('dashboard.basics.profiles.destroy', [$profile->id])),
                ];

                return ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContents($contents)
                    ->setTheme('display', 'flex')
                    ->setTheme('flex', [
                        'gap-sm',
                    ]);
            }
        );

        $action->setExtraCell('Settings', $recipe);
    }
}
