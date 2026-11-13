<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ResumeResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resume array into a JSON response payload.
     *
     * The underlying export array mirrors the JSON Resume schema, except the
     * volunteer section uses the ResumeSection key `volunteers` (singular
     * `volunteer` in the schema). It is returned as-is to keep the API and
     * the JSON export in sync.
     *
     * @return array<array-key, mixed>
     */
    public function toArray(Request $request): array
    {
        return (array) $this->resource;
    }
}
