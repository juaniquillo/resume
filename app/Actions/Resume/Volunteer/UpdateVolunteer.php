<?php

namespace App\Actions\Resume\Volunteer;

use App\Models\Volunteer;

class UpdateVolunteer
{
    public function __construct(
        private array $data,
        private Volunteer $volunteer
    ) {}

    public function handle(): bool
    {
        return $this->volunteer->update($this->data);
    }
}
