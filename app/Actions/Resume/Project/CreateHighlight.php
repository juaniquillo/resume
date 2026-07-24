<?php

namespace App\Actions\Resume\Project;

use App\Models\Highlight;
use App\Models\Project;

class CreateHighlight
{
    public function __construct(
        private array $data,
        private Project $project
    ) {}

    public function handle(): Highlight
    {
        /** @var Highlight */
        return $this->project->highlights()->create($this->data);
    }
}
