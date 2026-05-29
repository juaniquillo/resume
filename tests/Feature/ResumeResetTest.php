<?php

use App\Livewire\Resume\ResetResume;
use App\Models\Basic;
use App\Models\Course;
use App\Models\Education;
use App\Models\Highlight;
use App\Models\User;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

test('authenticated user can reset their resume', function () {
    $user = User::factory()->create();

    // Seed some data
    $basic = Basic::factory()->create(['user_id' => $user->id]);
    $work = Work::factory()->create(['user_id' => $user->id]);
    $highlight = Highlight::factory()->create([
        'highlightable_type' => Work::class,
        'highlightable_id' => $work->id,
        'highlight' => 'Tested something',
    ]);
    $education = Education::factory()->create(['user_id' => $user->id]);
    $course = Course::factory()->create([
        'courseable_type' => Education::class,
        'courseable_id' => $education->id,
        'course' => 'Physics',
    ]);

    Auth::login($user);

    Livewire::test(ResetResume::class)
        ->call('resetResume')
        ->assertRedirect(route('dashboard'));

    expect(Basic::where('user_id', $user->id)->exists())->toBeFalse();
    expect(Work::where('user_id', $user->id)->exists())->toBeFalse();
    expect(Highlight::where('highlightable_id', $work->id)->exists())->toBeFalse();
    expect(Education::where('user_id', $user->id)->exists())->toBeFalse();
    expect(Course::where('courseable_id', $education->id)->exists())->toBeFalse();
});

test('resetting resume only affects the authenticated user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Basic::factory()->create(['user_id' => $user->id]);
    Basic::factory()->create(['user_id' => $otherUser->id]);

    Auth::login($user);

    Livewire::test(ResetResume::class)
        ->call('resetResume');

    expect(Basic::where('user_id', $user->id)->exists())->toBeFalse();
    expect(Basic::where('user_id', $otherUser->id)->exists())->toBeTrue();
});
