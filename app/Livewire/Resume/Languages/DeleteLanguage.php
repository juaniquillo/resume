<?php

namespace App\Livewire\Resume\Languages;

use App\Cruds\Helpers\TableHelpers;
use App\Livewire\Concerns\IsLivewireForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteLanguage extends Component
{
    use IsLivewireForm;

    #[Locked]
    public int $languageId;

    public function mount(int $languageId): void
    {
        $this->languageId = $languageId;
    }

    public function deleteLanguage(): void
    {
        $user = $this->getUser();
        $id = $this->languageId;
        $language = $user->languages()->findOrFail($id);
        $language->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteLanguage',
            confirmMessage: 'Are you sure you want to delete this language record?',
        );
    }

    public function render()
    {
        return view('livewire.resume.languages.delete-language')
            ->with('component', $this->getComponent());
    }
}
