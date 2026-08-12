<?php

namespace App\Actions\Resume\Project;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Project;
use App\Models\User;

class StoreProject
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Project
    {
        /** Brings back null for empty strings and trims whitespace for Livewire forms */
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Project */
        return $this->user->projects()->create($data);
    }
}
