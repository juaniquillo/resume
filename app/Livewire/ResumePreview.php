<?php

namespace App\Livewire;

use App\Presenters\ResumePresenter;
use App\Presenters\Themes\ThemeFactory;
use Livewire\Component;

class ResumePreview extends Component
{
    public function render()
    {
        $user = auth()->user();
        $theme = ThemeFactory::forUser($user);
        $presenter = new ResumePresenter($user, $theme);

        return view('livewire.resume-preview', [
            'theme' => $theme,
            'resumeComponent' => $presenter->presentCached(),
        ]);
    }
}
