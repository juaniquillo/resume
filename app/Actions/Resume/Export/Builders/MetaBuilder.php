<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Cruds\Schema\ResumeExport\Inputs\ExportThemeSelectFactory;
use App\Models\GeneralOption;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;

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

            $crud = GeneralOptionsCrud::build()
                ->make([
                    $this->getThemeInput(),
                    ...GeneralOptionsCrud::build()->optionsInputsArray(),
                ]);

            $exported = $crud->execute(
                new ModelToExportAction($generalOptions)
            )
                ->toArray();

            $meta['options'] = $exported;
        }

        return $meta;
    }

    public function getThemeInput(): InputInterface
    {
        return ExportThemeSelectFactory::make();
    }
}
