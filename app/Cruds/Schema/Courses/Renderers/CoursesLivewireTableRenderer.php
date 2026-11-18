<?php

namespace App\Cruds\Schema\Courses\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Education\Courses\DeleteCourse;
use App\Livewire\Resume\Education\Courses\EditCourse;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;
use Juaniquillo\TableComponentAction\TableCellsAction;
use Juaniquillo\TableComponentAction\TableCellsRecipe;

final class CoursesLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function tableActionInstance(TableCellsAction $action): void
    {
        $action->setExtraCell('Settings', new TableCellsRecipe(
            value: fn ($value, $model) => $this->renderSettings($model)
        ));
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Course $course */
        $course = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditCourse::class,
                id: "edit-course-{$course->id}",
                params: [$course->courseable_id, $course->id]
            ),
            $helper->liveWireComponent(
                component: DeleteCourse::class,
                id: "delete-course-{$course->id}",
                params: [$course->courseable_id, $course->id]
            ),
        ];

        return ComponentFactory::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }
}
