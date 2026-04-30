<?php

namespace App\Cruds\Squema\ResumeImport\Inputs;

use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class JsonFileFactory
{
    const NAME = 'resume_file';

    const LABEL = 'Resume JSON File';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);

        return $input;
    }

    public static function validation(InputInterface $input): void
    {
        $input->setRecipe(
            (new LaravelValidationRulesRecipe([
                'required',
                'file',
                'mimetypes:application/json,text/plain',
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
                            'type' => FluxComponentEnum::TEXT_FILE->value,
                            'accept' => '.json',
                        ])
                )
        );
    }
}
