<?php

namespace App\Cruds\Schema\Options\Inputs;

use App\Cruds\Actions\Validation\LaravelValidationRulesRecipe;
use App\Cruds\Helpers\LivewireHelpers;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Enums\SlugBlacklist;
use App\Models\GeneralOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\Inputs\DefaultInput;
use Juaniquillo\InputComponentAction\Bags\DefaultAttributeBag;
use Juaniquillo\InputComponentAction\Recipes\InputComponentRecipe;

class SlugFactory
{
    public const NAME = 'slug';

    public const LABEL = 'Resume Slug';

    public static function make(?int $id = null): InputInterface
    {
        $input = new DefaultInput(self::NAME, self::LABEL);

        self::form($input);
        self::validation($input, $id);

        return $input;
    }

    public static function validation(InputInterface $input, ?int $id = null): void
    {

        $rules = [
            'required',
            'string',
            'alpha_dash:ascii',
            'max:191',
            'not_in:'.implode(',', SlugBlacklist::values()),
            'unique' => Rule::unique(GeneralOption::class),
        ];

        $id = $id ?? Auth::id();

        if ($id) {
            $rules['unique'] = Rule::unique(GeneralOption::class)->ignore($id, 'user_id');
        }

        $input->setRecipe(
            (new LaravelValidationRulesRecipe($rules))
        );
    }

    public static function form(InputInterface $input): void
    {
        $livewireAttributes = LivewireHelpers::getLivewireAttributes($input->getName(), GeneralOptionsCrud::getLivewireGroup());

        $input->setRecipe(
            (new InputComponentRecipe)
                ->setAttributeBag(
                    (new DefaultAttributeBag)
                        ->setInputAttributes([
                            'label' => self::LABEL,
                            'badge' => 'required',
                            'icon' => 'link',
                            'placeholder' => 'E.g. john-doe',
                            ...$livewireAttributes,
                        ])
                )
        );
    }
}




