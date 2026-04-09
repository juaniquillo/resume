<?php

namespace App\Cruds\Squema\Works;

use App\Components\ThirdParty\Flux\FluxBackendComponent;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Squema\Works\Inputs\EndsAtFactory;
use App\Cruds\Squema\Works\Inputs\NameFactory;
use App\Cruds\Squema\Works\Inputs\PositionFactory;
use App\Cruds\Squema\Works\Inputs\StartsAtFactory;
use App\Cruds\Squema\Works\Inputs\SummaryFactory;
use App\Cruds\Squema\Works\Inputs\UserFactory;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\MainBackendComponent;
use Juaniquillo\CrudAssistant\CrudAssistant;
use Juaniquillo\CrudAssistant\InputCollection;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Bags\DefaultThemeBag;
use Juaniquillo\InputComponentAction\Contracts\ComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class WorksCrud implements CrudInterface
{
    use IsCrud;

    public static function inputsArray(): array
    {
        return [
            'user' => UserFactory::make(),
            'name' => NameFactory::make(),
            'position' => PositionFactory::make(),
            'starts_at' => StartsAtFactory::make(),
            'ends_at' => EndsAtFactory::make(),
            'summary' => SummaryFactory::make(),
        ];
    }

    public static function formAction(): string
    {
        return route('dashboard.works');
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
