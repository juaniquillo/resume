<?php

namespace App\Livewire\Resume\CoverLetters;

use App\Components\Builders\FluxComponentBuilder;
use App\Components\ThirdParty\Flux\FluxComponentEnum;
use App\Livewire\Concerns\IsLivewireModal;
use App\Models\CoverLetter;
use App\Models\User;
use App\Presenters\Resume\CoverLetterPresenter;
use App\Presenters\Themes\ThemeFactory;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Builders\ComponentBuilder;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
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

            return ComponentBuilder::make(ComponentEnum::DIV)
                ->setTheme('margin', 'top-sm')
                ->setContent(
                    (new CoverLetterPresenter($model, $theme))->present()
                );
        }

        return ComponentBuilder::make(ComponentEnum::DIV)
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

        return ComponentBuilder::make(ComponentEnum::COLLECTION)
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
                    heading: FluxComponentBuilder::make(FluxComponentEnum::HEADING)
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
