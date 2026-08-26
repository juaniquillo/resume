<?php

use App\Livewire\Resume\Volunteers\CreateVolunteer;
use App\Models\User;
use App\Models\Volunteer;
use App\Support\ResumeLimit;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create volunteer component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateVolunteer::class)
        ->assertSuccessful();
});

it('creates a new volunteer successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateVolunteer::class)
        ->set('volunteers.organization', 'Red Cross')
        ->set('volunteers.position', 'Volunteer')
        ->set('volunteers.starts_at', '2023-01-01')
        ->set('volunteers.ends_at', '2023-12-31')
        ->set('volunteers.url', 'https://redcross.org')
        ->set('volunteers.summary', 'Great experience')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('volunteers', [
        'user_id' => $this->user->id,
        'organization' => 'Red Cross',
        'position' => 'Volunteer',
        'summary' => 'Great experience',
    ]);
});

it('volunteers records have a limit', function () {
    $this->actingAs($this->user);
    Volunteer::factory()->count(ResumeLimit::VOLUNTEERS)->create(['user_id' => $this->user->id]);

    Livewire::test(CreateVolunteer::class)
        ->set('volunteers.organization', 'Extra NGO')
        ->set('volunteers.position', 'Helper')
        ->call('createForm');

    $this->assertDatabaseCount('volunteers', ResumeLimit::VOLUNTEERS);
});
