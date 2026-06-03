<?php
namespace App\Http\Controllers;
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
        $path = "og-images/ogg-{$user->id}.png";
        
        if (!Storage::exists($path)) {
            Screenshot::url(route('resume.og.show', $user))
                ->width(1200)
                ->height(630)
                ->save(storage_path("app/private/og-images/{$path}"));
        }

        return response(Storage::get($path))->header('Content-Type', 'image/png');
    }
}
