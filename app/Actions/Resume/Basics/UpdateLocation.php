<?php

namespace App\Actions\Resume\Basics;

use App\Cruds\Helpers\FormHelpers;
use App\Models\Basic;
use App\Models\Location;

class UpdateLocation
{
    public function __construct(
        private array $data,
        private Basic $basics
    ) {}

    public function handle(): Location
    {
        $data = FormHelpers::convertEmptyStringToNull($this->data);

        $basicsId = $this->basics->id;

        if (! $basicsId) {
            throw new \RuntimeException('Basic ID is required to update location.');
        }

        /** @var Location */
        return $this->basics->location()->updateOrCreate(
            ['basic_id' => $basicsId],
            $data
        );
    }
}



