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
     * The underlying export array already follows the JSON Resume schema, so it
     * is returned as-is to keep the API and the JSON export in sync.
     *
     * @return array<array-key, mixed>
     */
    public function toArray(Request $request): array
    {
        return (array) $this->resource;
    }
}
