<?php

namespace App\Actions\Resume\Volunteer;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Volunteer;

class UpdateVolunteer
{
    public function __construct(
        private array $data,
        private Volunteer $volunteer
    ) {}

    public function handle(): bool
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->volunteer->update($data);
    }
}
