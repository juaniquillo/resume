<?php

namespace App\Actions\Resume\CoverLetter;

use App\Models\CoverLetter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SaveCoverLetter
{
    public function handle(array $data): CoverLetter
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var CoverLetter $coverLetter */
        $coverLetter = $user->coverLetters()->updateOrCreate(
            ['user_id' => $user->id],
            ['content' => $data['content']]
        );

        return $coverLetter;
    }
}
