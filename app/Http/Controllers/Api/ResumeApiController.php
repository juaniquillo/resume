<?php

namespace App\Http\Controllers\Api;

use App\Actions\Resume\Export\BuildResumeArray;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResumeResource;
use App\Http\Resources\ResumeSectionResource;
use App\Models\User;
use App\Support\Helpers;
use Illuminate\Http\Request;

class ResumeApiController extends Controller
{
    /**
     * Display the specified resume as JSON.
     *
     * Pass a ?section= query parameter to receive a single resume section.
     */
    public function __invoke(Request $request, User $user): ResumeResource|ResumeSectionResource
    {
        abort_unless(
            Helpers::isResumePubliclyAvailable($user),
            403,
            'This resume is not publicly available.',
        );

        $resume = (new BuildResumeArray($user))->handle();

        $section = $request->query('section');

        if (is_string($section) && $section !== '') {
            abort_unless(
                array_key_exists($section, $resume),
                404,
                "Section '{$section}' not found in this resume.",
            );

            return new ResumeSectionResource($resume[$section]);
        }

        return new ResumeResource($resume);
    }
}
