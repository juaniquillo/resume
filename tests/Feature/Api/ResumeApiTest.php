<?php

use App\Models\Basic;
use App\Models\User;

function createPublishedResumeUser(string $slug = 'api-slug'): User
{
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => $slug, 'is_draft' => false]);

    Basic::factory()->create([
        'user_id' => $user->id,
        'name' => 'API User',
        'email' => 'api@example.com',
    ]);

    return $user;
}

test('returns the whole resume as json when accepting json', function () {
    createPublishedResumeUser();

    $response = $this->getJson(route('api.resume', ['user' => 'api-slug']));

    $response->assertOk()
        ->assertJsonStructure(['meta', 'basics'])
        ->assertJsonPath('basics.name', 'API User');
});

test('returns a single section when the section query parameter is used', function () {
    createPublishedResumeUser();

    $response = $this->getJson(route('api.resume', ['user' => 'api-slug', 'section' => 'basics']));

    $response->assertOk()
        ->assertJsonPath('name', 'API User')
        ->assertJsonMissingPath('meta');
});

test('rejects requests that do not accept json', function () {
    createPublishedResumeUser();

    $this->get(route('api.resume', ['user' => 'api-slug']), ['Accept' => 'text/html'])
        ->assertStatus(406);
});

test('returns 403 when the resume is a draft', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'draft-slug', 'is_draft' => true]);
    Basic::factory()->create(['user_id' => $user->id]);

    $this->getJson(route('api.resume', ['user' => 'draft-slug']))
        ->assertStatus(403)
        ->assertJsonPath('message', 'This resume is not publicly available.');
});

test('returns 404 for a section that does not exist', function () {
    createPublishedResumeUser();

    $this->getJson(route('api.resume', ['user' => 'api-slug', 'section' => 'nope']))
        ->assertStatus(404);
});

test('rate limits requests to five per minute', function () {
    createPublishedResumeUser();

    foreach (range(1, 5) as $attempt) {
        $this->getJson(route('api.resume', ['user' => 'api-slug']))->assertOk();
    }

    $this->getJson(route('api.resume', ['user' => 'api-slug']))->assertStatus(429);
});

test('returns json when the client accepts any content type', function () {
    createPublishedResumeUser();

    $this->get(route('api.resume', ['user' => 'api-slug']), ['Accept' => '*/*'])
        ->assertOk()
        ->assertJsonPath('basics.name', 'API User');
});

test('renders errors as json when the client accepts any content type', function () {
    $user = User::factory()->create();
    $user->generalOptions()->update(['slug' => 'draft-slug', 'is_draft' => true]);
    Basic::factory()->create(['user_id' => $user->id]);

    $this->get(route('api.resume', ['user' => 'draft-slug']), ['Accept' => '*/*'])
        ->assertStatus(403)
        ->assertJsonPath('message', 'This resume is not publicly available.');
});
