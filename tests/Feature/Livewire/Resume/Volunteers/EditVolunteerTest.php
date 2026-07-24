<?php

use App\Livewire\Resume\Volunteers\EditVolunteer;
use App\Models\User;
use App\Models\Volunteer;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit volunteer component', function () {
    $volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditVolunteer::class, ['volunteerId' => $volunteer->id])
        ->assertSuccessful();
});

it('formats date fields for the UI', function () {
    $volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
        'starts_at' => '2020-01-01',
        'ends_at' => '2021-01-01',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditVolunteer::class, ['volunteerId' => $volunteer->id])
        ->assertSet('volunteers.starts_at', '2020-01')
        ->assertSet('volunteers.ends_at', '2021-01');
});

it('updates an existing volunteer record', function () {
    $volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
        'organization' => 'Old NGO',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditVolunteer::class, ['volunteerId' => $volunteer->id])
        ->set('volunteers.organization', 'New NGO')
        ->set('volunteers.position', 'Senior Volunteer')
        ->set('volunteers.starts_at', '2020-01-01')
        ->set('volunteers.ends_at', '2021-01-01')
        ->set('volunteers.summary', 'Updated summary')
        ->call('updateForm')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('volunteers', [
        'id' => $volunteer->id,
        'organization' => 'New NGO',
        'position' => 'Senior Volunteer',
    ]);
});

it('validates update form', function () {
    $volunteer = Volunteer::factory()->create([
        'user_id' => $this->user->id,
        'organization' => 'Old NGO',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditVolunteer::class, ['volunteerId' => $volunteer->id])
        ->set('volunteers.organization', '')
        ->call('updateForm')
        ->assertHasErrors(['organization']);
});
