<?php

namespace App\Actions\Resume\Export\Builders;

use App\Cruds\Actions\General\ModelToExportAction;
use App\Cruds\Schema\Options\Inputs\HideAddressFactory;
use App\Cruds\Schema\Options\Inputs\HideEmailFactory;
use App\Cruds\Schema\Options\Inputs\HideImageFactory;
use App\Cruds\Schema\Options\Inputs\HidePhoneFactory;
use App\Cruds\Schema\Options\Inputs\IsDraftFactory;
use App\Cruds\Schema\Options\Inputs\SlugFactory;
use App\Cruds\Schema\Options\Inputs\ThemeSelectFactory;
use App\Models\GeneralOption;
use Juaniquillo\CrudAssistant\CrudAssistant;

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
            $inputs = CrudAssistant::make([
                SlugFactory::NAME => SlugFactory::make(),
                ThemeSelectFactory::NAME => ThemeSelectFactory::make(),
                IsDraftFactory::NAME => IsDraftFactory::make(),
                HidePhoneFactory::NAME => HidePhoneFactory::make(),
                HideAddressFactory::NAME => HideAddressFactory::make(),
                HideEmailFactory::NAME => HideEmailFactory::make(),
                HideImageFactory::NAME => HideImageFactory::make(),
            ]);
            $exported = $inputs->execute(new ModelToExportAction($generalOptions))->toArray();
            $meta['options'] = $exported;
        }

        return $meta;
    }
}
