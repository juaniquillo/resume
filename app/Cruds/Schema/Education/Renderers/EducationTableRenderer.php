<?php

namespace App\Cruds\Schema\Education\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Models\Education;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class EducationTableRenderer implements TableRenderer
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
            $helper->editButton(route('dashboard.certificates.edit', [$education->id])),
            $helper->deleteButton(route('dashboard.certificates.destroy', [$education->id])),
        ];

        return ComponentBuilder::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }
}
