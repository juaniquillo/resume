<?php

namespace App\Actions\Resume\Basics;

use App\Models\Profile;

class UpdateProfile
{
    public function __construct(
        private array $data,
        private Profile $profile
    ) {}

    public function handle(): bool
    {
        return $this->profile->update($this->data);
    }
}
