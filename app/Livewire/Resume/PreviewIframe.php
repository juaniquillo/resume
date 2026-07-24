<?php

namespace App\Livewire\Resume;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewIframe extends Component
{
    private ?string $cacheBuster = null;

    #[On('resume-updated')]
    public function refreshIframe()
    {
        $this->cacheBuster = Str::random(10);
        $this->render();
    }

    public function render()
    {
        return view('livewire.resume.preview-iframe')
            ->with('src', route('dashboard.resume.preview', ['v' => $this->cacheBuster]));
    }
}



