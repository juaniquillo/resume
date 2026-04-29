<?php

namespace App\Actions\Resume\Project;

use App\Models\Project;
use App\Models\User;

class CreateProject
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Project
    {
        /** @var Project */
        return $this->user->projects()->create($this->data);
    }
}
