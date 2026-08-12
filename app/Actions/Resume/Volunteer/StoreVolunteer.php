<?php

namespace App\Actions\Resume\Volunteer;

use App\Cruds\Helpers\FormHelpers;
use App\Models\User;
use App\Models\Volunteer;

class StoreVolunteer
{
    public function __construct(
        private array $data,
        private User $user
    ) {}

    public function handle(): Volunteer
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Volunteer */
        return $this->user->volunteers()->create($data);
    }
}
