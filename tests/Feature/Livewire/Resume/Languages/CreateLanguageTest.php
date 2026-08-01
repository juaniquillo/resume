<?php

use App\Enums\LanguageFluency;
use App\Livewire\Resume\Languages\CreateLanguage;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the create language component', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateLanguage::class)
        ->assertSuccessful();
});

it('creates a new language record successfully', function () {
    $this->actingAs($this->user);

    Livewire::test(CreateLanguage::class)
        ->set('languages.language', 'English')
        ->set('languages.fluency', LanguageFluency::EXPERT->value)
        ->call('createForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('languages', [
        'user_id' => $this->user->id,
        'language' => 'English',
        'fluency' => LanguageFluency::EXPERT->value,
    ]);
});
