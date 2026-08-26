<?php

use App\Enums\LanguageFluency;
use App\Livewire\Resume\Languages\CreateLanguage;
use App\Models\Language;
use App\Models\User;
use App\Support\ResumeLimit;
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

it('languages records have a limit', function () {
    $this->actingAs($this->user);
    Language::factory()->count(ResumeLimit::LANGUAGES)->create(['user_id' => $this->user->id]);

    Livewire::test(CreateLanguage::class)
        ->set('languages.language', 'Spanish')
        ->set('languages.fluency', LanguageFluency::BEGINNER->value)
        ->call('createForm');

    $this->assertDatabaseCount('languages', ResumeLimit::LANGUAGES);
});
