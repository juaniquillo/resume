<?php

namespace App\Cruds\Squema\Options;

use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Managers\SettingsValueManager;
use App\Cruds\Squema\Options\Inputs\SectionSwitchFactory;
use Illuminate\Database\Eloquent\Model;
use Juaniquillo\InputComponentAction\Contracts\ValueManager;
use Override;

final class SectionVisibilityCrud implements CrudForm, CrudInterface
{
    use HasHtmlForm,
        IsCrud;

    public const NAME = 'section_visibility';

    public const SECTIONS = [
        'summary' => 'Summary',
        'work' => 'Experience',
        'volunteers' => 'Volunteering',
        'education' => 'Education',
        'awards' => 'Awards',
        'certificates' => 'Certificates',
        'publications' => 'Publications',
        'skills' => 'Skills',
        'languages' => 'Languages',
        'interests' => 'Interests',
        'references' => 'References',
        'projects' => 'Projects',
    ];

    public function __construct(
        protected array $values = [],
        protected array $errors = [],
        protected ?Model $model = null,
    ) {}

    public static function build(array $values = [], array $errors = [], ?Model $model = null): static
    {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
        );
    }

    #[Override]
    public function inputsArray(): array
    {
        $inputs = [];
        foreach (self::SECTIONS as $key => $label) {
            $inputs[$key] = SectionSwitchFactory::make($key, $label);
        }

        return $inputs;
    }

    public function formThemes(): array
    {
        return [
            'forms' => 'one-column',
        ];
    }

    #[Override]
    public function valueManager(): ValueManager
    {
        return new SettingsValueManager;
    }
}
