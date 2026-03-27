<?php

namespace App\Cruds\Squema\Basics;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxBackendComponent;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Squema\Basics\Inputs\ImageFactory;
use App\Cruds\Squema\Basics\Inputs\LabelFactory;
use App\Cruds\Squema\Basics\Inputs\NameFactory;
use App\Cruds\Squema\Basics\Inputs\PhoneFactory;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\CrudAssistant\InputCollection;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Contracts\ComponentBag;

class BasicsCrud
{
    public static function make(): InputCollection
    {
        return CrudAssistant::make([
            NameFactory::make(),
            LabelFactory::make(),
            PhoneFactory::make(),
            ImageFactory::make(),
        ]);
    }

    public static function form(array $inputs): MainBackendComponent
    {
        return ComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', route('dashboard.basics'))
            ->setThemes([
                'display' => 'grid',
                'grid' => 'gap-md',
            ])
            ->setContents($inputs)
            ->setContent(
                ComponentBuilder::make(ComponentEnum::DIV)
                    ->setTheme('margin', 'top-sm')
                    ->setContent(
                        FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                            ->setAttribute('type', 'submit')
                            ->setAttribute('variant', 'primary')
                            ->setAttribute('color', 'blue')
                            ->setContent('Save')
                    )
            );
    }

    public static function dashboardComponentBag(): ComponentBag
    {
        return (new DefaultComponentBag)
            ->setInputType(FluxComponentEnum::TEXT_INPUT)
            ->setInputComponent(FluxBackendComponent::class);
    }
}
