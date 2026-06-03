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
}
