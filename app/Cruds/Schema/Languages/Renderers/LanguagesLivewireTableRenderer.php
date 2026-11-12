<?php

namespace App\Cruds\Schema\Languages\Renderers;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Contracts\TableRenderer;
use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Resume\Languages\DeleteLanguage;
use App\Livewire\Resume\Languages\EditLanguage;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\CompoundComponent;

final class LanguagesLivewireTableRenderer implements TableRenderer
{
    public static function make(): static
    {
        return new self;
    }

    public function tableActionInstance(TableRowsAction $action): void
    {
        $action->setExtraCell('Settings', new TableRowsRecipe(
            value: fn ($value, $model) => $this->renderSettings($model)
        ));
    }

    public function renderSettings(Model $model): BackendComponent|CompoundComponent
    {
        /** @var Language $language */
        $language = $model;

        $helper = TableHelpers::make();

        $contents = [
            $helper->liveWireComponent(
                component: EditLanguage::class,
                id: "edit-language-{$language->id}",
                params: [$language->id]
            ),
            $helper->liveWireComponent(
                component: DeleteLanguage::class,
                id: "delete-language-{$language->id}",
                params: [$language->id]
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
