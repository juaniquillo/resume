<?php

namespace App\Actions\Resume\Volunteer;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Highlight;
use App\Models\Volunteer;

class StoreHighlight
{
    public function __construct(
        private array $data,
        private Volunteer $volunteer
    ) {}

    public function handle(): Highlight
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        /** @var Highlight */
        return $this->volunteer->highlights()->create($data);
    }
}
