<?php

namespace App\Cruds\Squema\CoverLetters\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Squema\CoverLetters\CoverLettersCrud;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class ContentFactory
{
    public const NAME = 'content';

    public const LABEL = 'Cover Letter';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);

        return $input;
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                'string',
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes(self::NAME, CoverLettersCrud::getLivewireGroup());

        $input->setRecipe(
            (new InputComponentRecipe)
                ->setComponentBag(
                    (new DefaultComponentBag)
                        ->setInputType(FluxComponentEnum::TEXTAREA)
                )
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes(array_merge([
                            'label' => self::LABEL,
                            'name' => $input->getName(),
                            'rows' => 15,
                            'data-markdown' => 1,
                            'description' => 'Markdown is available',
                        ], $livewireAttributes))
                )
        );
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(callback: fn () => fake()->paragraph())
        );
    }
}
