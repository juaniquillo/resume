<?php

namespace App\Actions\Resume\Project;

use App\Models\Project;

class UpdateProject
{
    public function __construct(
        private array $data,
        private Project $project
    ) {}

    public function handle(): bool
    {
        return $this->project->update($this->data);
    }
}
