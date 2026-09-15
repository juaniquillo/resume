<?php

namespace App\Cruds\Schema\ResumeExport\Inputs;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\Builders\FluxLocalThemeComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Helpers\FormHelpers;
use App\Cruds\Helpers\TableHelpers;
use App\Models\ResumeExport;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Stringable;

class CustomOptionsFactory
{
    public const NAME = 'custom_options';

    public const LABEL = 'Custom Options';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        /** Just for the table */
        $input->setType(FormHelpers::IGNORE_LIVEWIRE_BINDINGS);
        $input->onlyFor([
            TableRowsAction::class,
        ]);

        self::table($input);

        return $input;
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: function (Stringable|BackedEnum|array|null $value, Model $model): BackendComponent|CompoundComponent {

                    if (! $value) {
                        return TableHelpers::emptyValue();
                    }

                    /** @var ResumeExport $export */
                    $export = $model;

                    return TableHelpers::tableModal(
                        id: 'custom_options_'.$export->id,
                        content: self::modalContent($value),
                        heading: __('Custom Options'),
                        buttonLabel: 'options',
                        triggerType: 'ghost'
                    );
                }
            )
        );
    }

    public static function modalContent(array $value): BackendComponent|CompoundComponent
    {
        $wrapper = ComponentBuilder::make(ComponentEnum::DIV)
            ->setThemes([
                'margin' => 'top-xs',
            ]);
        $contents = [];

        foreach ($value as $key => $option) {
            $contents[] = ComponentBuilder::make(ComponentEnum::DIV)
                ->setThemes([
                    'display' => 'flex',
                    'border' => 'bottom-sm',
                    'flex' => [
                        'justify-center',
                        'gap-sm',
                        'items-center',
                        'wrap',
                    ],
                ])
                ->setContents([
                    // maybe an icon for later
                    FluxLocalThemeComponentBuilder::make(FluxComponentEnum::TEXT)
                        ->setContent(Str::headline($key))
                        ->setThemes([
                            'cards' => 'default-content',
                        ]),
                    FluxLocalThemeComponentBuilder::make(FluxComponentEnum::TEXT)
                        ->setContent(
                            FluxComponentBuilder::make(FluxComponentEnum::BADGE)
                                ->setContent('true')
                                ->setAttribute('color', 'lime')
                        )
                        ->setThemes([
                            'cards' => [
                                'default-content',
                                'default-content-right',
                            ],
                        ]),
                ]);
        }

        return $wrapper->setContents($contents);
    }
}

// <div class="flex items-center gap-3">
//     <flux:flag :country="$country['code']" size="md" class="w-7" />
//     <flux:text variant="strong" class="min-w-0 flex-1 truncate">{{ $country['name'] }}</flux:text>
//     <flux:text variant="strong" class="shrink-0 font-medium">{{ Number::abbreviate($country['visitors'], precision: 1) }}</flux:text>
// </div>
