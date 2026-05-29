<?php

use App\Models\Basic;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

pest()->group('fast');

test('images are served with cache headers', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $image = UploadedFile::fake()->image('avatar.jpg');
    $path = $image->store('avatars', 'local');

    $basic = Basic::factory()->for($user)->create([
        'image' => $path,
    ]);

    $response = $this->get(route('image.serve', $basic->uuid));

    $response->assertSuccessful();
    $response->assertHeader('Cache-Control', 'max-age=31536000, public');
});
