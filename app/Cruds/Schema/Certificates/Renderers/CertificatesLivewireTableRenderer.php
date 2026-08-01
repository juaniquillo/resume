<?php

namespace App\Cruds\Schema\Certificates\Renderers;

use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Certificates\DeleteCertificate;
use App\Livewire\Resume\Certificates\EditCertificate;
use App\Models\Certificate;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class CertificatesLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Certificate $certificate */
        $certificate = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditCertificate::class,
                id: "edit-certificate-{$certificate->id}",
                params: [$certificate->id]
            ),
            $helper->liveWireComponent(
                component: DeleteCertificate::class,
                id: "delete-certificate-{$certificate->id}",
                params: [$certificate->id]
            ),
        ];

        return ComponentBuilder::make(ComponentEnum::DIV)
            ->setContents($contents)
            ->setTheme('display', 'flex')
            ->setTheme('flex', [
                'gap-sm',
            ]);
    }
}
