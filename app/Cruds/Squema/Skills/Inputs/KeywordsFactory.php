<?php

namespace App\Cruds\Squema\Skills\Inputs;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
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

        self::validation($input);
        self::form($input);
        self::table($input);

        return $input;
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
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

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: function (string|array|null $value, Model|Skill $model) {

                    if (is_array($value)) {
                        $keywords = ComponentBuilder::make(ComponentEnum::DIV)
                            ->setThemes([
                                'display' => 'flex',
                                'flex' => [
                                    'gap-sm',
                                ],
                            ]);
                        foreach ($value as $keyword) {
                            $keywords->setContent(
                                FluxComponentBuilder::make(FluxComponentEnum::BADGE)
                                    ->setAttributes([
                                        'color' => 'green',
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
