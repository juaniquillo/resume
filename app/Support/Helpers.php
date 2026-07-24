<?php

namespace App\Support;

use App\Models\GeneralOption;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Helpers
{
    public static function isClosure(mixed $callback)
    {
        return is_object($callback) && ($callback instanceof \Closure);
    }

    public static function isResumeInDraftState(User $user): bool
    {
        $options = $user->generalOptions;

        return ($options->is_draft ?? true) || ! $user->resumeBasics();
    }

    public static function incrementViews(GeneralOption $options): void
    {
        defer(fn () => DB::table('general_options')
            ->where('id', $options->id)
            ->increment('views'));
    }

    /**
     * Convert an asset path to a base64 encoded SVG data URI.
     */
    public static function svgToBase64(string $path): string
    {
        $fullPath = public_path($path);

        if (! file_exists($fullPath)) {
            return '';
        }

        $content = file_get_contents($fullPath);

        return 'data:image/svg+xml;base64,'.base64_encode($content);
    }
}



