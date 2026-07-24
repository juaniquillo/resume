<?php

namespace App\Actions\Resume\Volunteer;

use App\Models\User;
use App\Models\Volunteer;

class CreateVolunteer
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Volunteer
    {
        /** @var Volunteer */
        return $this->user->volunteers()->create($this->data);
    }
}
