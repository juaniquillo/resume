<?php

namespace App\Actions\Resume\Basics;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Profile;

class UpdateProfile
{
    public function __construct(
        private array $data,
        private Profile $profile
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->profile->update($data);
    }
}
