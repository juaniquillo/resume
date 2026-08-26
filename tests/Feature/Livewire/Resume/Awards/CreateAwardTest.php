<?php

use App\Livewire\Resume\Awards\CreateAward;
use App\Models\Award;
use App\Models\User;
use App\Support\ResumeLimit;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create award component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateAward::class)
        ->assertSuccessful();
});

it('creates a new award record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateAward::class)
        ->set('awards.title', 'Best Employee of the Year')
        ->set('awards.awarder', 'Company Name')
        ->set('awards.awarded_at', '2023-10-26')
        ->set('awards.summary', 'Recognized for outstanding performance.')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('awards', [
        'user_id' => $this->user->id,
        'title' => 'Best Employee of the Year',
        'awarder' => 'Company Name',
    ]);
});

it('awards records have a limit', function () {
    $this->actingAs($this->user);
    Award::factory()->count(ResumeLimit::AWARDS)->create(['user_id' => $this->user->id]);

    Livewire::test(CreateAward::class)
        ->set('awards.title', 'Extra Award')
        ->set('awards.awarder', 'Extra Awarder')
        ->call('createForm');

    $this->assertDatabaseCount('awards', ResumeLimit::AWARDS);
});
