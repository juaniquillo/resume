<?php

namespace App\Cruds\Schema\ResumeImport\Inputs;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Cruds\Actions\Model\LaravelFactoryRecipe;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Enums\ProcessStatus;
use App\Models\ResumeImport;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;
use Stringable;

class JsonFileFactory
{
    const NAME = 'resume_file';

    const LABEL = 'Resume JSON File';

    public static function make(): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input);
        self::factory($input);
        self::table($input);

        return $input;
    }

    public static function factory(InputInterface $input): void
    {
        $input->setRecipe(
            new LaravelFactoryRecipe(callback: function ($input, $output, $faker) {
                $output->file_path = $faker->filePath();
                $output->file_name = $faker->word().'.json';
                $output->status = ProcessStatus::COMPLETED;
            })
        );
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

    public static function table(InputInterface $input): void
    {
        $input->setRecipe(
            new TableRowsRecipe(
                value: function (Stringable|BackedEnum|string|array|null $value, Model $model) {
                    /** @var ResumeImport $import */
                    $import = $model;

                    return FluxComponentBuilder::make(FluxComponentEnum::BUTTON)
                        ->setAttribute('href', route('dashboard.resume.import.download', [$import->id]))
                        ->setContent($import->file_name)
                        ->setAttribute('variant', 'ghost')
                        ->setAttribute('size', 'sm')
                        ->setAttribute('icon', 'document-arrow-down')
                        ->setTheme('cursor', 'pointer');
                }
            )
        );
    }
}
