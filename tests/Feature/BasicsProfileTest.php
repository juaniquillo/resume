<?php

use App\Models\Basic;
use App\Models\Profile;
use App\Models\User;

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
        ->assertViewHas('form')
        ->assertViewHas('table', null)
        ->assertSee('list="network_data"', false)
        ->assertSee('<datalist id="network_data">', false)
        ->assertSee('<option value="Twitter">', false)
        ->assertSee('<option value="Github">', false);
});

it('renders the profiles table when records exist', function () {
    Profile::factory()->count(3)->create([
        'basic_id' => $this->basic->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.basics.profiles'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new profile record', function () {
    $data = [
        'network' => 'twitter',
        'username' => 'johndoe',
        'url' => 'https://twitter.com/johndoe',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.profiles.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('profiles', [
        'basic_id' => $this->basic->id,
        'network' => 'twitter',
        'username' => 'johndoe',
    ]);
});

it('stores a profile with a custom network', function () {
    $data = [
        'network' => 'My Custom Network',
        'username' => 'customuser',
        'url' => 'https://custom.com/customuser',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.profiles.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('profiles', [
        'basic_id' => $this->basic->id,
        'network' => 'My Custom Network',
        'username' => 'customuser',
    ]);
});

it('validates profile data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.profiles.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['network', 'username', 'url']);
});

it('renders the edit profile page', function () {
    $profile = Profile::factory()->create([
        'basic_id' => $this->basic->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.basics.profiles.edit', $profile->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.basics.profiles.edit')
        ->assertViewHas('form')
        ->assertSee('list="network_data"', false)
        ->assertSee('<datalist id="network_data">', false)
        ->assertSee('<option value="Twitter">', false)
        ->assertSee('<option value="Github">', false);
});

it('updates an existing profile record', function () {
    $profile = Profile::factory()->create([
        'basic_id' => $this->basic->id,
        'network' => 'github',
    ]);

    $data = [
        'network' => 'linkedin',
        'username' => 'newuser',
        'url' => 'https://linkedin.com/in/newuser',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.basics.profiles.update', $profile->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('profiles', [
        'id' => $profile->id,
        'network' => 'linkedin',
    ]);
});

it('deletes a profile record', function () {
    $profile = Profile::factory()->create([
        'basic_id' => $this->basic->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.basics.profiles.destroy', $profile->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('profiles', [
        'id' => $profile->id,
    ]);
});
