<?php

namespace App\Cruds\Schema\ResumeExport\Renderers;

use App\Cruds\Concerns\HasLivewireFormAttributes;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\FormRenderer;
use App\Cruds\Schema\ResumeExport\Inputs\AllowDownloadSwitchFactory;
use App\Cruds\Schema\ResumeExport\Inputs\ExportThemeSelectFactory;
use App\Cruds\Schema\ResumeExport\Inputs\ExportTypeSelectFactory;
use App\Cruds\Schema\ResumeExport\Inputs\NameFactory;
use App\Cruds\Schema\ResumeExport\Inputs\StatusFactory;
use App\Cruds\Schema\ResumeExport\ResumeExportCrud;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;

final class ResumeExportLivewireFormRenderer implements FormRenderer
{
    use HasLivewireFormAttributes;

    public static function make(): static
    {
        return new self;
    }

    public function getForm(CrudForm $crud): BackendComponent|CompoundComponent
    {
        /** @var ResumeExportCrud $crud */
        $inputs = [
            'name' => NameFactory::make(),
            'type' => ExportTypeSelectFactory::make(),
            'theme' => ExportThemeSelectFactory::make(),
            'options' => $crud->fieldsetWrap([
                'allow_download' => AllowDownloadSwitchFactory::make(),
            ], 'options', 'Options'),
            'status' => StatusFactory::make(),
        ];
        $this->addLivewireAttributes($inputs, ResumeExportCrud::getLivewireGroup());

        return $crud->composeForm(
            inputs: $inputs,
            themes: ['forms' => 'two-column']
        );
    }
}
