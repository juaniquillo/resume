<?php

namespace App\Actions\Resume\Project;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Project;

class UpdateProject
{
    public function __construct(
        private array $data,
        private Project $project
    ) {}

    public function handle(): bool
    {
        /** Brings back null for empty strings and trims whitespace for Livewire forms */
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        return $this->project->update($data);
    }
}
