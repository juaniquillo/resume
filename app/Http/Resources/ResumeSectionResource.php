<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ResumeSectionResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform a single resume section into a JSON response payload.
     *
     * @return array<array-key, mixed>
     */
    public function toArray(Request $request): array
    {
        return (array) $this->resource;
    }
}
