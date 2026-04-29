<?php

namespace App\Actions\Resume\Basics;

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
        /** @var Location */
        return $this->basics->location()->updateOrCreate(
            ['basic_id' => $this->basics->id],
            $this->data
        );
    }
}
