<?php

namespace App\Cruds\Schema\Basics\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\General\ModelToExportRecipe;
use App\Cruds\Actions\General\NameValueRecipe;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Schema\Basics\BasicsCrud;
use App\Models\Basic;
use App\Support\ImageHelpers;
use BackedEnum;
use Faker\Generator;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\ContentComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Input;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultDisableBag;
use Juaniquillo\InputComponentAction\Bags\DefaultHookBag;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class ImageFactory
{
    const NAME = 'image';

    const LABEL = 'Image';

    const JSON_KEY = 'image';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);
        self::import($input);
        self::export($input);

        return $input;
    }

    public static function import(InputInterface $input): void
    {
        $input->setRecipe(new NameValueRecipe(
            name: [self::NAME, self::JSON_KEY],
        ));
    }

    public static function export(InputInterface $input): void
    {
        $input->setRecipe(new ModelToExportRecipe(
            key: self::JSON_KEY,
            callback: fn ($value, Basic $model) => $value ? route('image.serve', $model->uuid) : null
        ));
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'nullable',
                'image',
                'max:1024',
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes($input->getName(), BasicsCrud::getLivewireGroup());

        $input->setRecipe(
            new InputComponentRecipe(
                disableBag: (new DefaultDisableBag)
                    ->setDisableInputValue(true),
                attributeBag: (new DefaultAttributeBag)
                    ->setInputAttributes([
                        'label' => self::LABEL,
                        'type' => FluxComponentEnum::TEXT_FILE->value,
                        ...$livewireAttributes,
                    ]),
                hookBag: (new DefaultHookBag)
                    ->setInputHook(function (BackendComponent|ContentComponent $component, Input $input, BackedEnum|FluxComponentEnum $type, ValueManager $valueManager): BackendComponent|ContentComponent {

                        $model = $valueManager->getModel();

                        if (! $model instanceof Basic) {
                            return $component;
                        }

                        return self::imageManagement($component, $model);

                    })
            )
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainer $output, Generator $faker) {
                    $output->{ $input->getName() } = $faker->imageUrl();
                }
            )
        );
    }

    public static function imageManagement(BackendComponent|ContentComponent $component, Basic $model): BackendComponent|ContentComponent
    {
        $wrapper = ComponentBuilder::make(ComponentEnum::DIV)
            ->setThemes([
                'display' => 'flex',
                'flex' => [
                    'gap-md',
                    'col',
                ],

            ]);

        $imageUrl = ImageHelpers::imageUrl($model->uuid, $model->updated_at?->timestamp);
        $image = ComponentBuilder::make(ComponentEnum::DIV)
            ->setThemes([
                'padding' => 'top-sm',
            ])
            ->setContent(
                ComponentBuilder::make(ComponentEnum::IMG)
                    ->setAttribute('src', $imageUrl)
            );

        return $wrapper->setContents([
            $component,
            TableHelpers::tableModal($model->id, $image, 'Image Preview', null, 'Preview', ['flex' => ['self-baseline']]),
        ]);
    }
}
