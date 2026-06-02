<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Spatie\Browsershot\Browsershot;

class ResumeOgController extends Controller
{
    public function show(User $user)
    {
        return view('pages.resume-og', [
            'user' => $user,
            'basics' => $user->basics,
        ]);
    }

    public function image(User $user)
    {
        $cacheKey = "resume-og-image-{$user->id}-".($user->updated_at->timestamp ?? '0');

        $image = Cache::rememberForever($cacheKey, function () use ($user) {
            return Browsershot::url(route('resume.og.show', $user))
                ->windowSize(1200, 630)
                ->setExtraHttpHeaders([
                    'Authorization' => 'Bearer '.config('app.key'), // Simple internal bypass if needed
                ])
                ->waitUntilNetworkIdle()
                ->screenshot();
        });

        return response($image)->header('Content-Type', 'image/png');
    }
}
