<?php

namespace App\Actions\Resume\Work;

use App\Cruds\Helpers\FormHelpers;
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
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Highlight */
        return $this->work->highlights()->create($data);
    }
}
