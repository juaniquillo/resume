<?php

namespace App\Http\Controllers\Api;

use App\Actions\Resume\Export\BuildResumeArray;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResumeApiController extends Controller
{
    /**
     * Display the specified resume as JSON.
     */
    public function __invoke(Request $request, User $user): JsonResponse
    {
        if (Helpers::isResumeInDraftState($user)) {
            return response()->json([
                'message' => 'This resume is currently in draft mode and cannot be accessed publicly.',
            ], 403);
        }

        try {
            $resume = (new BuildResumeArray($user))->handle();

            if ($request->has('section')) {
                $section = $request->query('section');

                if (! isset($resume[$section])) {
                    return response()->json([
                        'message' => "Section '{$section}' not found in this resume.",
                    ], 404);
                }

                return response()->json($resume[$section]);
            }

            return response()->json($resume);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error building resume data: '.$e->getMessage(),
            ], 500);
        }
    }
}



