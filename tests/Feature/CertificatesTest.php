<?php

use App\Models\Certificate;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from certificates index', function () {
    $this->get(route('dashboard.certificates'))
        ->assertRedirect(route('login'));
});

it('renders the certificates index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.certificates'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.certificates.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the certificates table when records exist', function () {
    Certificate::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.certificates'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new certificate record', function () {
    $data = [
        'name' => 'Certified Laravel Developer',
        'date' => '2023-10-26',
        'url' => 'https://example.com/certificate',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.certificates.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('certificates', [
        'user_id' => $this->user->id,
        'name' => 'Certified Laravel Developer',
    ]);
});

it('validates certificate data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.certificates.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['name', 'date']);
});

it('renders the edit certificate page', function () {
    $certificate = Certificate::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.certificates.edit', $certificate->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.certificates.edit')
        ->assertViewHas('form');
});

it('updates an existing certificate record', function () {
    $certificate = Certificate::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Old Certificate',
    ]);

    $data = [
        'name' => 'New Certificate Name',
        'date' => '2024-01-15',
        'url' => 'https://new-url.com/cert',
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.certificates.update', $certificate->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('certificates', [
        'id' => $certificate->id,
        'name' => 'New Certificate Name',
    ]);
});

it('deletes a certificate record', function () {
    $certificate = Certificate::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.certificates.destroy', $certificate->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('certificates', [
        'id' => $certificate->id,
    ]);
});
