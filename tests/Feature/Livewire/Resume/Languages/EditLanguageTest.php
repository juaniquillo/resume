<?php

use App\Enums\LanguageFluency;
use App\Livewire\Resume\Languages\EditLanguage;
use App\Models\Language;
use App\Models\User;
use Livewire\Livewire;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the edit language component', function () {
    $language = Language::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditLanguage::class, ['languageId' => $language->id])
        ->assertSuccessful();
});

it('updates an existing language record successfully', function () {
    $language = Language::factory()->create([
        'user_id' => $this->user->id,
        'language' => 'Spanish',
    ]);

    $this->actingAs($this->user);

    Livewire::test(EditLanguage::class, ['languageId' => $language->id])
        ->set('languages.language', 'French')
        ->set('languages.fluency', LanguageFluency::INTERMEDIATE->value)
        ->call('updateForm')
        ->assertHasNoErrors()
        ->assertDispatched('resume-updated');

    $this->assertDatabaseHas('languages', [
        'id' => $language->id,
        'language' => 'French',
        'fluency' => LanguageFluency::INTERMEDIATE->value,
    ]);
});
