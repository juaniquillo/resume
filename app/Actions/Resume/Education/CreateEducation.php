<?php

namespace App\Actions\Resume\Education;

use App\Models\Education;
use App\Models\User;

class CreateEducation
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Education
    {
        /** @var Education */
        return $this->user->education()->create($this->data);
    }
}
