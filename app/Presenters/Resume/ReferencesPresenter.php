<?php

namespace App\Presenters\Resume;

use App\Models\Reference;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Illuminate\Database\Eloquent\Collection;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;

final class ReferencesPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private Collection $references,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if ($this->references->isEmpty()) {
            return null;
        }

        return $this->section('References',
            $this->compose(ComponentEnum::DIV)
                ->setThemes($this->theme->referencesContainerThemes())
                ->setContents(
                    $this->references->map(function (Reference $ref) {
                        return $this->compose(ComponentEnum::DIV)
                            ->setThemes($this->theme->itemContainerThemes())
                            ->setContents(array_filter([
                                'name' => $this->compose(ComponentEnum::H3)
                                    ->setThemes($this->theme->itemTitleThemes())
                                    ->setContent($ref->name),
                                'reference' => $ref->reference
                                    ? $this->compose(ComponentEnum::PARAGRAPH)
                                        ->setThemes($this->theme->summaryThemes())
                                        ->setContent($ref->reference)
                                    : null,
                            ]));
                    })->toArray()
                )
        );
    }
}
