<?php

namespace App\Cruds\Squema\Basics\Inputs;

use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class PhoneFactory
{
    const NAME = 'phone_basics';

    const LABEL = 'Phone';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);

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
                            'type' => 'tel',
                        ])
                )
        );
    }
}
