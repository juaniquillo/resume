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
use App\Cruds\Squema\Basics\Inputs\UserFactory;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\CrudAssistant\InputCollection;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Bags\DefaultThemeBag;
use Juaniquillo\InputComponentAction\Contracts\ComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class BasicsCrud implements CrudInterface
{
    use IsCrud;

    public static function inputsArray(): array
    {
        return [
            'user' => UserFactory::make(),
            'name' => NameFactory::make(),
            'label' => LabelFactory::make(),
            'email' => EmailFactory::make(),
            'phone' => PhoneFactory::make(),
            'url' => UrlFactory::make(),
            'image' => ImageFactory::make(),
            'summary' => SummaryFactory::make(),
        ];
    }

    public static function formAction(): string
    {
        return route('dashboard.basics');
    }

    public static function formWithTextareaSpanFull(?array $values = null, ?array $errors = null, ?Model $model = null): MainBackendComponent
    {
        $inputs = self::inputsArray();
        $summary = $inputs['summary'] ?? null;

        // Textarea input with column span full theme
        $inputs['summary'] = self::spanFullContainer([
            $summary,
        ]);

        return self::form(
            inputs: $inputs,
            values: $values,
            errors: $errors,
            model: $model
        );
    }

    public static function dashboardComponentBag(): ComponentBag
    {
        return (new DefaultComponentBag)
            ->setInputType(FluxComponentEnum::TEXT_INPUT)
            ->setInputComponent(FluxBackendComponent::class);
    }

    public static function spanFullContainer(array $contents): InputCollection
    {
        return CrudAssistant::make($contents)
            ->setName('span_full_container')
            ->setRecipe(
                (new InputComponentRecipe)
                    ->setThemeBag(
                        (new DefaultThemeBag)
                            ->setWrapperTheme([
                                'forms' => 'column-span-full',
                            ])
                    )
            );
    }
}
