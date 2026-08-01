<?php

namespace App\Livewire\Resume\Languages;

use App\Cruds\Schema\Languages\LanguagesCrud;
use App\Cruds\Schema\Languages\Renderers\LanguagesLivewireTableRenderer;
use App\Livewire\Concerns\IsLivewireTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class LanguagesTable extends Component
{
    use IsLivewireTable;

    /** @throws ModelNotFoundException */
    #[On('resume-updated')]
    #[Computed]
    public function getModels(): Collection
    {
        /** @var User $user */
        $user = Auth::user();

        $languages = $user->languages();

        return $languages->get();
    }

    private function crud()
    {
        return LanguagesCrud::build(
            tableRenderer: LanguagesLivewireTableRenderer::make(),
        );
    }

    private function table(): ?BackendComponent
    {
        $models = $this->getModels();
        if ($models->isEmpty()) {
            return null;
        }

        return $this->crud()->makeTable($models);
    }

    public function render()
    {
        return view('livewire.resume.languages.languages-table')
            ->with(['table' => $this->table()]);
    }
}
