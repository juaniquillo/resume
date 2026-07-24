<?php

namespace App\Actions\Resume\Volunteer;

use App\Models\Highlight;
use App\Models\Volunteer;

class CreateHighlight
{
    public function __construct(
        private array $data,
        private Volunteer $volunteer
    ) {}

    public function handle(): Highlight
    {
        /** @var Highlight */
        return $this->volunteer->highlights()->create($this->data);
    }
}
