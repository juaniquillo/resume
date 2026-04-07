<?php

namespace App\Cruds\Squema\Basics;

use App\Components\ThirdParty\Flux\FluxBackendComponent;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Basics\Inputs\EmailFactory;
use App\Cruds\Squema\Basics\Inputs\ImageFactory;
use App\Cruds\Squema\Basics\Inputs\LabelFactory;
use App\Cruds\Squema\Basics\Inputs\NameFactory;
use App\Cruds\Squema\Basics\Inputs\PhoneFactory;
use App\Cruds\Squema\Basics\Inputs\SummaryFactory;
use App\Cruds\Squema\Basics\Inputs\UrlFactory;
use Juaniquillo\BackendComponents\Builders\LocalThemeComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\CrudAssistant\InputCollection;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Contracts\ComponentBag;

class BasicsCrud implements CrudInterface
{
    use IsCrud;

    public static function make(): InputCollection
    {
        return CrudAssistant::make([
            NameFactory::make(),
            LabelFactory::make(),
            EmailFactory::make(),
            PhoneFactory::make(),
            UrlFactory::make(),
            ImageFactory::make(),
            SummaryFactory::make(),
        ]);
    }

    public static function form(array $inputs): MainBackendComponent
    {
        return LocalThemeComponentBuilder::make(ComponentEnum::FORM)
            ->setAttribute('action', route('dashboard.basics'))
            ->setThemes([
                'forms' => 'two-column',
            ])
            ->setContents($inputs)
            ->setContent(
                LocalThemeComponentBuilder::make(ComponentEnum::DIV)
                    ->setTheme('forms', 'column-span-full')
                    ->setContent(
                        self::saveButton()
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
