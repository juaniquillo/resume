<?php

use App\Models\Basic;`r`npest()->group('fast');
use App\Models\User;`r`npest()->group('fast');
use Illuminate\Http\UploadedFile;`r`npest()->group('fast');
use Illuminate\Support\Facades\Storage;`r`npest()->group('fast');

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


