<?php

namespace App\Cruds\Squema\Profiles\Inputs;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Enums\Network;
use App\Models\Profile;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultHookBag;
use Juaniquillo\InputComponentAction\Groups\SoleInputGroup;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class NetworkFactory
{
    const NAME = 'network';

    const LABEL = 'Network';

    const LIST_ID = 'network_data';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);

        self::validation($input);
        self::factory($input);
        self::table($input);

        /** Adds just a div wrapper */
        return $input;

    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                'string',
                'max:255',
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            new InputComponentRecipe(
                inputGroup: new SoleInputGroup,
                attributeBag: (new DefaultAttributeBag)
                    ->setInputAttributes([
                        'label' => self::LABEL,
                        'badge' => 'required',
                        'list' => self::LIST_ID,
                    ]),
                hookBag: (new DefaultHookBag)
                    ->setWrapperHook(function (BackendComponent|CompoundComponent $component, InputInterface $input) {
                        /** @phpstan-ignore-next-line */
                        $component->setContent(
                            NetworkFactory::dataList()
                        );

                        return $component;
                    })

            )
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainer $output, Generator $faker) {
                    $output->{ $input->getName() } = $faker->randomElement(Network::cases())->value;
                }
            )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: function (?string $value, Model|Profile $model) {
                    if (! $value) {
                        return '';
                    }

                    $color = 'zinc';

                    $enum = Network::tryFrom($value);

                    if ($enum) {
                        $color = $enum->colors();
                    }

                    return FluxComponentBuilder::make(FluxComponentEnum::BADGE)
                        ->setAttributes([
                            'color' => $color,
                        ])
                        ->setContent($value);
                }
            )
        );
    }

    public static function optionsArray(): array
    {
        $options = [];

        $options[] = [
            'name' => 'choose',
            'value' => '',
        ];

        foreach (Network::cases() as $network) {
            $options[] = [
                'name' => $network->name,
                'value' => $network->value,
            ];
        }

        return $options;
    }

    public static function dataList(): BackendComponent|CompoundComponent
    {
        $datalist = ComponentBuilder::make(ComponentEnum::DATALIST)
            ->setAttribute('id', self::LIST_ID)
            ->setContents(self::options());

        return $datalist;
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::optionsArray() as $optionArray) {

            $option = ComponentBuilder::make(ComponentEnum::OPTION)
                ->setAttribute('value', $optionArray['value']);

            $options[] = $option;
        }

        return $options;
    }
}
