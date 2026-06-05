<?php

namespace App\Livewire\Resume;

use App\Managers\Resume\OgImageManager as OgManager;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class OgImageReset extends Component
{
    public string $slug = '';

    public string $width = '';

    public string $height = '';

    public string $version = '';

    public bool $test = false;

    public function mount(bool $test = false): void
    {
        $this->test = $test;

        $this->slug = $this->getUser()->getSlugAttribute();

        $this->width = (string) OgManager::WIDTH;
        $this->height = (string) OgManager::HEIGHT;

        $this->version = (string) time();

    }

    public function regenerate(): void
    {
        $this->src(true);
    }

    #[Computed]
    public function src($regenerate = false): string
    {
        if ($regenerate) {
            $user = $this->getUser();

            $manager = new OgManager($user);

            $manager->delete();

            if (! $this->test) {
                $manager->fetch();
            }

            $this->version = (string) time();

            $this->dispatch('notify',
                message: __('OG image regenerated successfully.'),
                variant: 'success'
            );
        }

        return route('resume.og.image', ['user' => $this->slug, 'v' => $this->version]);
    }

    private function getUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    public function render()
    {
        return view('livewire.resume.og-image-reset', [
            'slug' => $this->slug,
        ]);
    }
}
