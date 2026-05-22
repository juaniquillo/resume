<?php

namespace App\Cruds\Squema\Interests\Inputs;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\General\ModelToExportRecipe;
use App\Cruds\Actions\General\NameValueRecipe;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Models\Interest;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\DataContainer;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class KeywordsFactory
{
    const NAME = 'keywords';

    const LABEL = 'Keywords';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);
        self::table($input);
        self::import($input);
        self::export($input);

        return $input;
    }

    public static function import(InputInterface $input): void
    {
        $input->setRecipe(new NameValueRecipe(
            callback: fn (array $values) => isset($values['keywords'])
                ? (is_array($values['keywords']) ? implode(', ', $values['keywords']) : $values['keywords'])
                : null,
        ));
    }

    public static function export(InputInterface $input): void
    {
        $input->setRecipe(new ModelToExportRecipe(
            key: self::NAME,
            callback: fn ($value) => is_string($value) ? array_map('trim', explode(',', $value)) : $value
        ));
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
                            'placeholder' => 'Enter keywords separated by commas...',
                        ])
                )
        );
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelValidationRulesRecipe(
                rules: [
                    'nullable',
                    'array',
                ]
            )
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(
                callback: function (InputInterface $input, DataContainer $output, Generator $faker) {
                    $output->{ $input->getName() } = $faker->words(3);
                }
            )
        );
    }

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: function (string|array|null $value, Model|Interest $model) {

                    if (is_array($value)) {
                        $keywords = ComponentBuilder::make(ComponentEnum::DIV)
                            ->setThemes([
                                'display' => 'flex',
                                'flex' => [
                                    'gap-sm',
                                    'wrap',
                                ],
                            ]);
                        foreach ($value as $keyword) {
                            $keywords->setContent(
                                FluxComponentBuilder::make(FluxComponentEnum::BADGE)
                                    ->setAttributes([
                                        'color' => 'cyan',
                                    ])
                                    ->setContent($keyword)
                            );
                        }

                        return $keywords;
                    }

                    if (! $value) {
                        return FluxComponentBuilder::make(FluxComponentEnum::BADGE)
                            ->setAttributes([
                                'color' => 'red',
                            ])
                            ->setContent('no keywords');
                    }

                    return $value;
                }
            )
        );
    }
}
