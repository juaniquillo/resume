<?php

use App\Models\User;

pest()->group('fast');

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('redirects guests to login from works index', function () {
    $this->get(route('dashboard.works'))
        ->assertRedirect(route('login'));
});
