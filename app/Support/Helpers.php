<?php

namespace App\Support;

use App\Models\User;

class Helpers
{
    public static function isClosure($callback)
    {
        return is_object($callback) && ($callback instanceof \Closure);
    }

    public static function isResumeInDraftState(User $user): bool
    {
        $options = $user->generalOptions;

        return ($options->is_draft ?? true) || ! $user->resumeBasics();
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
