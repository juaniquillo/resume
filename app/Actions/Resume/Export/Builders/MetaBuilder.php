<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Cruds\Schema\Options\Inputs\IsDraftFactory;
use App\Cruds\Schema\Options\Inputs\ThemeSelectFactory;
use App\Models\GeneralOption;

class MetaBuilder
{
    public const SCHEMA_VERSION = '1.0.0';

    public function handle(?GeneralOption $generalOptions): array
    {
        $meta = [
            'version' => self::SCHEMA_VERSION,
            'generator' => [
                'name' => 'juaniquillo/resume',
                'url' => 'https://github.com/juaniquillo/resume',
            ],
            'exported_at' => now()->toIso8601String(),
        ];

        if ($generalOptions) {
            $crud = GeneralOptionsCrud::build(model: $generalOptions);
            $inputs = $crud->make([
                ThemeSelectFactory::NAME => ThemeSelectFactory::make(),
                IsDraftFactory::NAME => IsDraftFactory::make(),
                ...$crud->optionsInputsArray(),
            ]);
            $meta['options'] = $inputs->execute(new ModelToExportAction($generalOptions))->toArray();
        }

        return $meta;
    }
}
