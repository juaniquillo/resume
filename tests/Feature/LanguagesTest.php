<?php

use App\Enums\LanguageFluency;
use App\Models\Language;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from languages index', function () {
    $this->get(route('dashboard.languages'))
        ->assertRedirect(route('login'));
});

it('renders the languages index page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.languages'))
        ->assertSuccessful()
        ->assertViewIs('dashboard.languages.index')
        ->assertViewHas('form')
        ->assertViewHas('table', null);
});

it('renders the languages table when records exist', function () {
    Language::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.languages'))
        ->assertSuccessful()
        ->assertViewHas('table', function ($table) {
            return $table !== null;
        });
});

it('stores a new language record', function () {
    $data = [
        'language' => 'English',
        'fluency' => LanguageFluency::EXPERT->value,
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.languages.store'), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('languages', [
        'user_id' => $this->user->id,
        'language' => 'English',
        'fluency' => LanguageFluency::EXPERT->value,
    ]);
});

it('validates language data', function () {
    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.languages.store'), ['_token' => 'test-token'])
        ->assertSessionHasErrors(['language', 'fluency']);
});

it('renders the edit language page', function () {
    $language = Language::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->get(route('dashboard.languages.edit', $language->id))
        ->assertSuccessful()
        ->assertViewIs('dashboard.languages.edit')
        ->assertViewHas('form');
});

it('updates an existing language record', function () {
    $language = Language::factory()->create([
        'user_id' => $this->user->id,
        'language' => 'Spanish',
    ]);

    $data = [
        'language' => 'French',
        'fluency' => LanguageFluency::INTERMEDIATE->value,
    ];

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->post(route('dashboard.languages.update', $language->id), array_merge($data, ['_token' => 'test-token']))
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('languages', [
        'id' => $language->id,
        'language' => 'French',
        'fluency' => LanguageFluency::INTERMEDIATE->value,
    ]);
});

it('deletes a language record', function () {
    $language = Language::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->withSession(['_token' => 'test-token'])
        ->delete(route('dashboard.languages.destroy', $language->id), ['_token' => 'test-token'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('languages', [
        'id' => $language->id,
    ]);
});
