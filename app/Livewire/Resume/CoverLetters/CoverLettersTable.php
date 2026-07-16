<?php

namespace App\Livewire\Resume\CoverLetters;

use App\Cruds\Squema\CoverLetters\CoverLettersCrud;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CoverLettersTable extends Component
{
    use WithPagination;

    #[On('resume-updated')]
    public function render()
    {
        /** @var User $user */
        $user = Auth::user();
        $coverLetters = $user->coverLetters()->paginate(10);

        $crud = CoverLettersCrud::build();

        return view('livewire.resume.cover-letters.cover-letters-table', [
            'table' => $coverLetters->isEmpty() ? null : $crud->makeTable($coverLetters),
            'paginator' => $coverLetters,
        ]);
    }
}
