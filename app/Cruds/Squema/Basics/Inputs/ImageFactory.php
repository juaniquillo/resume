<?php

namespace App\Cruds\Squema\Basics\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class ImageFactory
{
    const NAME = 'image_basics';

    const LABEL = 'Image';

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
                            'type' => FluxComponentEnum::TEXT_FILE->value,
                        ])
                )
        );
    }
}
