<?php

namespace App\Livewire\Resume\CoverLetters;

use App\Livewire\Concerns\IsLivewireModal;
use App\Models\CoverLetter;
use App\Models\User;
use App\Presenters\Resume\CoverLetterPresenter;
use App\Presenters\Themes\DefaultPresenterTheme;
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
                    content: $this->getPresenterContent(),
                    themes: ['modal' => '2xl']
                ),
            ]);
    }

    public function getModalKey(): string
    {
        return 'preview-cover-letter-component';
    }

    private function getPresenterContent(): BackendComponent|CompoundComponent
    {
        $model = $this->getModel();
        
        if($model) {
            return (new CoverLetterPresenter($model, new DefaultPresenterTheme))->present();
        }

        return ComponentBuilder::make(ComponentEnum::DIV)
            ->setContent('No content available yet')
            ->setTheme('margin', 'top-sm');
    }

    #[On('cover-letter-updated')]
    public function getModel(): ?CoverLetter
    {
        
        /** @var User $user */
        $user = Auth::user();

        /** @var ?CoverLetter $model */
        return $user->coverLetters()->first();

    }

    public function render()
    {
        return view('livewire.resume.cover-letters.preview-cover-letter', [
            'modal' => $this->getModal(),
        ]);
    }
}
