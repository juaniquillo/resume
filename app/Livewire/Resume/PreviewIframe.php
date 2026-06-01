<?php

namespace App\Livewire\Resume;

use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class PreviewIframe extends Component
{
    #[On('resume-order-updated')]
    public function refreshIframe()
    {
        $this->render();
    }

    public function render()
    {
        return view('livewire.resume.preview-iframe')
            ->with('src', route('dashboard.resume.preview', ['v' => Str::random(10)]));
    }
}
