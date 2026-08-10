<?php

namespace App\Cruds\Schema\Courses\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class CoursesTableRenderer implements TableRenderer
{
    public function __construct(protected ?string $baseRoute = null) {}

    public static function make(?string $baseRoute = null): static
    {
        return new self($baseRoute);
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Course $course */
        $course = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->editButton(route('dashboard.education.courses.edit', [$course->courseable_id, $course->id])),
            $helper->deleteButton(route('dashboard.education.courses.destroy', [$course->courseable_id, $course->id])),
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
