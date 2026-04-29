<?php

namespace App\Actions\Resume\Work;

use App\Models\Highlight;
use App\Models\Work;

class CreateHighlight
{
    public function __construct(
        private array $data,
        private Work $work
    ) {}

    public function handle(): Highlight
    {
        /** @var Highlight */
        return $this->work->highlights()->create($this->data);
    }
}
