<?php

namespace App\Livewire\Resume\Highlights;

use App\Cruds\Helpers\TableHelpers;
use App\Models\Contracts\HighlightModel;
use App\Models\Highlight;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteHighlight extends Component
{
    #[Locked]
    public ?int $highlightId = null;

    public function mount(int $highlightId): void
    {
        $this->highlightId = $highlightId;
    }

    /**
     * @throws AuthenticationException
     */
    public function deleteWHighlight(): void
    {
        $user = $this->getUser();

        $highlight = Highlight::findOrFail($this->highlightId);

        /** @var Model|HighlightModel $parent */
        $parent = $highlight->highlightable;

        if ($parent->getUserId() !== $user->id) {
            throw new AuthenticationException('You are not authorized to delete this highlight');
        }

        $highlight->delete();

        $this->dispatch('resume-updated');
    }

    private function getUser(): User
    {
        return Auth::user();
    }

    public function getComponent(): BackendComponent|CompoundComponent
    {
        return TableHelpers::livewireDeleteButton(
            action: 'deleteWHighlight',
            confirmMessage: 'Are you sure you want to delete this highlight?',
        );
    }

    public function render()
    {
        return view('livewire.resume.highlights.delete_highlight')
            ->with('component', $this->getComponent());
    }
}



