<?php

namespace App\Cruds\Recipes;

use Closure;
use Juaniquillo\BackendComponents\Contracts\ThemeManager;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\CrudAssistant\Concerns\IsRecipe;
use Juaniquillo\CrudAssistant\Contracts\RecipeInterface;
use Juaniquillo\InputComponentAction\Bags\DefaultComponentBag;
use Juaniquillo\InputComponentAction\Bags\DefaultDisableBag;
use Juaniquillo\InputComponentAction\Concerns\IsInputComponentRecipe;
use Juaniquillo\InputComponentAction\Contracts\AttributeBag;
use Juaniquillo\InputComponentAction\Contracts\ComponentBag;
use Juaniquillo\InputComponentAction\Contracts\DisableBag;
use Juaniquillo\InputComponentAction\Contracts\ErrorAttributes;
use Juaniquillo\InputComponentAction\Contracts\ErrorComponent;
use Juaniquillo\InputComponentAction\Contracts\ErrorHook;
use Juaniquillo\InputComponentAction\Contracts\ErrorManager;
use Juaniquillo\InputComponentAction\Contracts\ErrorTheme;
use Juaniquillo\InputComponentAction\Contracts\HelpTextAttributes;
use Juaniquillo\InputComponentAction\Contracts\HelpTextComponent;
use Juaniquillo\InputComponentAction\Contracts\HelpTextHook;
use Juaniquillo\InputComponentAction\Contracts\HelpTextTheme;
use Juaniquillo\InputComponentAction\Contracts\HookBag;
use Juaniquillo\InputComponentAction\Contracts\InputComponentRecipeInterface;
use Juaniquillo\InputComponentAction\Contracts\InputGroup;
use Juaniquillo\InputComponentAction\Contracts\LabelAttributes;
use Juaniquillo\InputComponentAction\Contracts\LabelComponent;
use Juaniquillo\InputComponentAction\Contracts\LabelHook;
use Juaniquillo\InputComponentAction\Contracts\LabelTheme;
use Juaniquillo\InputComponentAction\Contracts\ThemeBag;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;
use Juaniquillo\InputComponentAction\Contracts\WrapperAttributes;
use Juaniquillo\InputComponentAction\Contracts\WrapperComponent;
use Juaniquillo\InputComponentAction\Contracts\WrapperHook;
use Juaniquillo\InputComponentAction\Contracts\WrapperTheme;
use Juaniquillo\InputComponentAction\Groups\SoleInputGroup;
use Juaniquillo\InputComponentAction\InputComponentAction;


final class SelectOptionComponentRecipe implements InputComponentRecipeInterface, RecipeInterface
{
    use IsInputComponentRecipe;
    use IsRecipe;

    /** @var class-string */
    protected $action = InputComponentAction::class;

    public function __construct(
        ?InputGroup $inputGroup = null,
        ?ThemeManager $themeManager = null,
        ComponentBag|WrapperComponent|LabelComponent|ErrorComponent|HelpTextComponent|null $componentBag = null,
        AttributeBag|WrapperAttributes|LabelAttributes|ErrorAttributes|HelpTextAttributes|null $attributeBag = null,
        ThemeBag|WrapperTheme|LabelTheme|ErrorTheme|HelpTextTheme|null $themeBag = null,
        HookBag|WrapperHook|LabelHook|ErrorHook|HelpTextHook|null $hookBag = null,
        ?ValueManager $valueBag = null,
        ?ErrorManager $errorBag = null,
        ?DisableBag $disableBag = null,
        string|int|Closure|null $inputValue = null,
        string|Closure|null $inputError = null,
        string|Closure|null $label = null,
        bool $emptyLabel = false,
        bool $valueAsInputContent = false,
        string|Closure|null $helpText = null,
    ) {
        
        /**
         * Defaults for the options
         */
        $this->useParentValue = true;
        $this->selectable = true;
        $this->labelAsInputContent = true;
        $this->inputGroup = $inputGroup ?? new SoleInputGroup();
        $this->componentBag = $componentBag ?? (new DefaultComponentBag)
            ->setInputType(ComponentEnum::OPTION);
        $this->disableBag = $disableBag ?? (new DefaultDisableBag)
            ->setDisableWrapper()
            ->setDisableDefaultNameAttribute();
        
        /**
         * Same as default recipe
         */
        $this->themeManager = $themeManager;
        $this->attributeBag = $attributeBag;
        $this->themeBag = $themeBag;
        $this->hookBag = $hookBag;
        $this->valueBag = $valueBag;
        $this->errorBag = $errorBag;
        $this->inputValue = $inputValue;
        $this->inputError = $inputError;
        $this->label = $label;
        $this->emptyLabel = $emptyLabel;
        $this->valueAsInputContent = $valueAsInputContent;
        $this->helpText = $helpText;

    }

}
