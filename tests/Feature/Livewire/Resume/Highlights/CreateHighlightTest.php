<?php

use App\Livewire\Resume\Highlights\CreateHighlight;
use App\Models\Highlight;
use App\Models\User;
use App\Models\Work;
use App\Support\ResumeLimit;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->work = Work::factory()->create(['user_id' => $this->user->id]);
});

it('renders the create highlight component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateHighlight::class, ['model' => $this->work])
        ->assertSuccessful();
});

it('creates a new highlight successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateHighlight::class, ['model' => $this->work])
        ->set('highlights.highlight', 'Great achievement')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('highlights', [
        'highlightable_id' => $this->work->id,
        'highlightable_type' => Work::class,
        'highlight' => 'Great achievement',
    ]);
});

it('highlights records have a limit', function () {
    $this->actingAs($this->user);
    Highlight::factory()->count(ResumeLimit::HIGHLIGHTS)->create([
        'highlightable_id' => $this->work->id,
        'highlightable_type' => Work::class,
    ]);

    Livewire::test(CreateHighlight::class, ['model' => $this->work])
        ->set('highlights.highlight', 'Extra highlight')
        ->call('createForm');

    $this->assertDatabaseCount('highlights', ResumeLimit::HIGHLIGHTS);
});
