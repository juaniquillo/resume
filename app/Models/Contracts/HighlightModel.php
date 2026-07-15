<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HighlightModel
{
    public function highlights(): MorphMany;

    public function getId(): int;

    public function getUuid(): string;

    public function getUserId(): int;
}
