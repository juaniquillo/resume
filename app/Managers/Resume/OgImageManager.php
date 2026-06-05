<?php

namespace App\Managers\Resume;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OgImageManager
{
    public const WIDTH = 1200;

    public const HEIGHT = 630;

    public function __construct(
        private User $user
    ) {}

    public function delete(): static
    {
        $path = $this->getPath();

        if ($this->imageExists()) {
            Storage::delete($path);
        }

        return $this;
    }

    public function fetch(): static
    {
        Http::get(route('resume.og.image', $this->user));

        return $this;
    }

    public function getPath(): string
    {
        return "og-images/ogg-{$this->user->id}.png";
    }

    public function getStorePath(): string
    {
        return Storage::path($this->getPath());
    }

    public function imageExists(): bool
    {
        return Storage::exists($this->getPath());
    }
}
