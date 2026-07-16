<?php

namespace App\Cruds\Squema\CoverLetters;

use App\Cruds\Actions\Presenters\TableRowsAction;
use App\Cruds\Actions\Presenters\TableRowsRecipe;
use App\Cruds\Concerns\HasHtmlForm;
use App\Cruds\Concerns\HasHtmlTable;
use App\Cruds\Concerns\IsCrud;
use App\Cruds\Contracts\CrudForm;
use App\Cruds\Contracts\CrudInterface;
use App\Cruds\Contracts\CrudTable;
use App\Cruds\Helpers\TableHelpers;
use App\Cruds\Squema\CoverLetters\Inputs\CompanyFactory;
use App\Cruds\Squema\CoverLetters\Inputs\ContentFactory;
use App\Cruds\Squema\CoverLetters\Inputs\TitleFactory;
use App\Livewire\Resume\CoverLetters\DeleteCoverLetter;
use App\Livewire\Resume\CoverLetters\EditCoverLetter;
use App\Models\CoverLetter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class CoverLettersCrud implements CrudForm, CrudInterface, CrudTable
{
    use HasHtmlForm,
        HasHtmlTable,
        IsCrud;

    public const NAME = 'cover-letters';

    public array $values = [];

    public array $errors = [];

    public ?Model $model = null;

    public function __construct(
        array $values = [],
        array $errors = [],
        ?Model $model = null,
    ) {
        $this->values = $values;
        $this->errors = $errors;
        $this->model = $model;
    }

    public static function build(array $values = [], array $errors = [], ?Model $model = null): static
    {
        return new self(
            values: $values,
            errors: $errors,
            model: $model,
        );
    }

    public function inputsArray(): array
    {
        return [
            TitleFactory::NAME => TitleFactory::make(),
            CompanyFactory::NAME => CompanyFactory::make(),
            ContentFactory::NAME => ContentFactory::make(),
        ];
    }

    public function formWithTextareaSpanFull(): BackendComponent|CompoundComponent
    {
        return $this->composeForm($this->inputsArray(), [
            'forms' => 'one-column',
        ]);
    }

    public static function getLivewireGroup(): string
    {
        return Str::camel(self::NAME);
    }

    protected function tableOptions(TableRowsAction $action): void
    {
        $recipe = new TableRowsRecipe(
            value: function ($value, Model $model) {

                /** @var CoverLetter $coverLetter */
                $coverLetter = $model;

                $helper = TableHelpers::make();

                $contents = [
                    $helper->liveWireComponent(
                        component: EditCoverLetter::class,
                        id: "edit-cover-letter-{$coverLetter->id}",
                        params: ['coverLetterId' => $coverLetter->id]
                    ),
                    $helper->liveWireComponent(
                        component: DeleteCoverLetter::class,
                        id: "delete-cover-letter-{$coverLetter->id}",
                        params: ['coverLetterId' => $coverLetter->id]
                    ),
                ];

                return ComponentBuilder::make(ComponentEnum::DIV)
                    ->setContents($contents)
                    ->setTheme('display', 'flex')
                    ->setTheme('flex', [
                        'gap-sm',
                    ]);
            }
        );

        $action->setExtraCell('Settings', $recipe);
    }
}
