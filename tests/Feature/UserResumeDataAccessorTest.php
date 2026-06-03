<?php

namespace Tests\Feature;

use App\Models\Basic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

pest()->group('fast');

test('user model can access resume data through lazy loading accessor', function () {
    $user = User::factory()->create();
    Basic::factory()->for($user)->create();

    DB::enableQueryLog();

    // Access basics via accessor
    $basics = $user->resumeBasics();
    expect($basics)->toBeInstanceOf(Basic::class);
    $queriesAfterFirst = count(DB::getQueryLog());
    expect($queriesAfterFirst)->toBe(3); // One for basics, one for location, one for profiles

    // Access again (should be cached in the loader singleton)
    $basics2 = $user->resumeBasics();
    expect($basics2)->toBe($basics);
    expect(count(DB::getQueryLog()))->toBe($queriesAfterFirst);
});
