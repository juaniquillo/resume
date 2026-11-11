<?php

namespace App\Livewire\Resume\CoverLetters;

use App\Livewire\Concerns\IsLivewireModal;
use App\Models\CoverLetter;
use App\Models\User;
use App\Presenters\Resume\CoverLetterPresenter;
use App\Presenters\Themes\ThemeFactory;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Factories\ComponentFactory;
use Juaniquillo\FluxBackendComponents\Factories\FluxComponentFactory;
use Juaniquillo\FluxBackendComponents\FluxComponentEnum;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewCoverLetter extends Component
{
    use IsLivewireModal;

    public function getModalKey(): string
    {
        return 'preview-cover-letter-component';
    }

    private function getPresenterContent(): BackendComponent|CompoundComponent
    {
        $user = Auth::user();

        $model = $this->getModel($user);

        if ($model) {
            $theme = ThemeFactory::forUser($user);

            return ComponentFactory::make(ComponentEnum::DIV)
                ->setTheme('margin', 'top-sm')
                ->setContent(
                    (new CoverLetterPresenter($model, $theme))->present()
                );
        }

        return ComponentFactory::make(ComponentEnum::DIV)
            ->setContent('No content available yet')
            ->setTheme('margin', 'top-sm');
    }

    #[On('cover-letter-updated')]
    public function getModel(User $user): ?CoverLetter
    {
        /** @var ?CoverLetter $model */
        $model = $user->coverLetters()->first();

        return $model;
    }

    public ?string $content = null;

    public function getModal(): BackendComponent|CompoundComponent
    {
        $id = $this->getModalKey();

        return ComponentFactory::make(ComponentEnum::COLLECTION)
            ->setContents([
                // From trait
                'button' => $this->modalButton(
                    label: 'Preview',
                    id: $id,
                    variant: 'filled',
                    icon: 'eye',
                ),
                // From trait
                'modal' => $this->modalComponent(
                    id: $id,
                    heading: FluxComponentFactory::make(FluxComponentEnum::HEADING)
                        ->setAttribute('size', 'xl')
                        ->setContent('Cover Letter'),
                    content: $this->getPresenterContent(),
                    themes: ['modal' => '2xl']
                ),
            ]);
    }

    public function render()
    {
        return view('livewire.resume.cover-letters.preview-cover-letter', [
            'modal' => $this->getModal(),
        ]);
    }
}
