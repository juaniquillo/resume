<?php

namespace App\Cruds\Schema\Profiles\Renderers;

use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Profiles\DeleteProfile;
use App\Livewire\Resume\Profiles\EditProfile;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ProfilesLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Profile $profile */
        $profile = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditProfile::class,
                id: "edit-profile-{$profile->id}",
                params: [$profile->id]
            ),
            $helper->liveWireComponent(
                component: DeleteProfile::class,
                id: "delete-profile-{$profile->id}",
                params: [$profile->id]
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
        return [
            'Highlights' => new TableRowsRecipe(
                value: function ($value, Model $model) {
                    /** @var Profile $profile */
                    $profile = $model;

                    return TableHelpers::highlightsButton(route('dashboard.profiles.highlights', [$profile->id]));
                },
            ),
        ];
    }
}
