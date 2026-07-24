<?php

namespace App\Actions\Resume\Work;

use App\Cruds\Helpers\FormHelpers;
use App\Models\User;
use App\Models\Work;

class CreateWork
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Work
    {
        /** Brings back null for empty strings for Livewire forms */
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Work */
        return $this->user->works()->create($data);
    }
}
