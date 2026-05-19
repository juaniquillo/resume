<?php

namespace App\Cruds\Squema\Options\Inputs;

use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Models\User;
use Illuminate\Validation\Rule;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class SlugFactory
{
    public const NAME = 'slug';

    public const LABEL = 'Resume Slug';

    public static function make(?int $userId = null): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input, $userId);

        return $input;
    }

    public static function validation(InputInterface $input, ?int $userId = null): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                'string',
                'alpha_dash:ascii',
                'max:255',
                $userId === null
                    ? Rule::unique(User::class)
                    : Rule::unique(User::class)->ignore($userId),
            ]))
        );
    }

    public static function form(InputInterface $input): void
    {
        $input->setRecipe(
            (new InputComponentRecipe)
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
                            'badge' => 'required',
                            'icon' => 'link',
                            'placeholder' => 'E.g. john-doe',
                        ])
                )
        );
    }
}
