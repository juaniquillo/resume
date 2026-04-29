<?php

namespace App\Actions\Resume\Basics;

use App\Models\Basic;
use App\Models\Profile;

class CreateProfile
{
    public function __construct(
        private array $data,
        private Basic $basics
    ) {}

    public function handle(): Profile
    {
        /** @var Profile */
        return $this->basics->profiles()->create($this->data);
    }
}
