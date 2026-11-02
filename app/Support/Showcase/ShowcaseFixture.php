<?php

namespace App\Support\Showcase;

use App\Enums\ProcessStatus;
use App\Jobs\ProcessResumeImport;
use App\Models\ResumeImport;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class ShowcaseFixture
{
    public const EMAIL = 'showcase@example.com';

    public const JSON_PATH = 'storage/tests/user-test-resume.json';

    public const PHOTO_PATH = 'storage/tests/photo-test.jpg';

    public static function user(): ?User
    {
        return User::where('email', self::EMAIL)->first();
    }

    public static function isSeeded(): bool
    {
        return (bool) self::user()?->resumeBasics();
    }

    public static function seed(bool $fresh = false): User
    {
        $user = self::user();

        if ($user && $fresh) {
            $user->delete();
            $user = null;
        }

        if ($user && $user->resumeBasics()) {
            return $user->refresh();
        }

        $user ??= User::factory()->create([
            'name' => 'Showcase',
            'email' => self::EMAIL,
        ]);

        $data = json_decode(
            file_get_contents(base_path(self::JSON_PATH)),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $photo = file_get_contents(base_path(self::PHOTO_PATH));
        $data['basics']['image'] = 'data:image/jpeg;base64,'.base64_encode($photo);

        $filePath = 'showcase/resume.json';
        Storage::put($filePath, json_encode($data));

        $import = ResumeImport::create([
            'user_id' => $user->id,
            'name' => 'Showcase fixture',
            'file_path' => $filePath,
            'file_name' => 'user-test-resume.json',
            'status' => ProcessStatus::PENDING,
        ]);

        (new ProcessResumeImport($import))->handle();

        $import->refresh();

        if ($import->status !== ProcessStatus::COMPLETED) {
            throw new RuntimeException('Showcase fixture import failed: '.$import->error);
        }

        return $user->refresh();
    }
}
