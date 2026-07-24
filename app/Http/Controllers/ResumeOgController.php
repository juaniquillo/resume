<?php

namespace App\Http\Controllers;

use App\Managers\Resume\OgImageManager;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelScreenshot\Facades\Screenshot;

class ResumeOgController extends Controller
{
    public function show(User $user)
    {
        return view('pages.resume-og', [
            'user' => $user,
            'basics' => $user->resumeBasics(),
        ]);
    }

    public function image(User $user)
    {
        $manager = new OgImageManager($user);
        $path = $manager->getPath();

        if (! $manager->imageExists()) {
            Screenshot::url(route('resume.og.show', $user))
                ->width(OgImageManager::WIDTH)
                ->height(OgImageManager::HEIGHT)
                ->disk(config('filesystems.default'))
                ->save($path);
        }

        return response(Storage::get($path))->header('Content-Type', 'image/png');
    }
}



