<?php

namespace App\Cruds\Schema\Education\Renderers;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Education\DeleteEducation;
use App\Livewire\Resume\Education\EditEducation;
use App\Models\Education;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class EducationTableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Education $education */
        $education = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditEducation::class,
                id: "edit-education-{$education->id}",
                params: [$education->id]
            ),
            $helper->liveWireComponent(
                component: DeleteEducation::class,
                id: "delete-education-{$education->id}",
                params: [$education->id]
            ),
        ];

        return ComponentBuilder::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }

    public function renderCourses(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Education $education */
        $education = $model;

        return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
            ->setContent(
                FluxComponentBuilder::make('icon.building-library')
                    ->setAttribute('variant', 'micro')
            )
            ->setContent('Courses')
            ->setTheme('cursor', 'pointer')
            ->setAttribute('variant', 'primary')
            ->setAttribute('color', 'blue')
            ->setAttribute('size', 'xs')
            ->setAttribute('href', route('dashboard.education.courses', [$education->id]));
    }
}
