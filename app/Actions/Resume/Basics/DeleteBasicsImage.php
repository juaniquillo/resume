<?php

namespace App\Actions\Resume\Basics;

use App\Models\Basic;
use Illuminate\Support\Facades\Storage;

class DeleteBasicsImage
{
    public function __construct(
        private Basic $basics
    ) {}

    public function handle(): void
    {
        $image = $this->basics->image;

        if ($image && Storage::exists($image)) {
            Storage::delete($image);
        }
    }
}
