<?php

namespace App\Cruds\Schema\Courses\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Education\Courses\DeleteCourse;
use App\Livewire\Resume\Education\Courses\EditCourse;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class CoursesLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
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
