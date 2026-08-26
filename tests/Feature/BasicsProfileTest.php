<?php

use App\Livewire\Resume\Profiles\CreateProfile;
use App\Livewire\Resume\Profiles\DeleteProfile;
use App\Livewire\Resume\Profiles\EditProfile;
use App\Models\Basic;
use App\Models\Profile;
use App\Models\User;
use App\Support\ResumeLimit;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->basic = Basic::factory()->create([
        'user_id' => $this->user->id,
    ]);
});

it('redirects guests to login from profiles index', function () {
    $this->get(route('dashboard.basics.profiles'))
        ->assertRedirect(route('login'));
});

it('renders the profiles index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.basics.profiles'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.basics.profiles.index')
        ->assertViewHas('basics');
});

it('renders the profiles table when records exist', function () {
    Profile::factory()->count(3)->create([
        'basic_id' => $this->basic->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.basics.profiles'))
        ->assertSuccessful();
});

it('stores a new profile record', function () {
    Livewire::actingAs($this->user)
        ->test(CreateProfile::class)
        ->set('profiles.network', 'Twitter')
        ->set('profiles.username', 'johndoe')
        ->set('profiles.url', 'https://twitter.com/johndoe')
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('profiles', [
        'basic_id' => $this->basic->id,
        'network' => 'Twitter',
        'username' => 'johndoe',
    ]);
});

it('validates profile data', function () {
    Livewire::actingAs($this->user)
        ->test(CreateProfile::class)
        ->set('profiles.network', '')
        ->set('profiles.username', '')
        ->call('createForm')
        ->assertHasErrors(['network', 'username']);
});

it('updates an existing profile record', function () {
    $profile = Profile::factory()->create([
        'basic_id' => $this->basic->id,
        'network' => 'GitHub',
    ]);

    Livewire::actingAs($this->user)
        ->test(EditProfile::class, ['profileId' => $profile->id])
        ->set('profiles.network', 'LinkedIn')
        ->set('profiles.username', 'newuser')
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('profiles', [
        'id' => $profile->id,
        'network' => 'LinkedIn',
        'username' => 'newuser',
    ]);
});

it('deletes a profile record', function () {
    $profile = Profile::factory()->create([
        'basic_id' => $this->basic->id,
    ]);

    Livewire::actingAs($this->user)
        ->test(DeleteProfile::class, ['profileId' => $profile->id])
        ->call('deleteProfile')
        ->assertDispatched('resume-updated');

    $this->assertDatabaseMissing('profiles', [
        'id' => $profile->id,
    ]);
});

it('profiles records have a limit', function () {
    Profile::factory()->count(ResumeLimit::PROFILES)->create([
        'basic_id' => $this->basic->id,
    ]);

    Livewire::actingAs($this->user)
        ->test(CreateProfile::class)
        ->set('profiles.network', 'GitHub')
        ->set('profiles.username', 'extrauser')
        ->call('createForm');

    $this->assertDatabaseCount('profiles', ResumeLimit::PROFILES);
});
