<?php

use App\Enums\Network;
use Tests\TestCase;

uses(TestCase::class);

test('every network has a matching svg asset', function () {
    foreach (Network::cases() as $network) {
        expect(public_path("images/networks/{$network->slug()}.svg"))->toBeFile();
    }
});

test('every network defines all of its attributes', function () {
    foreach (Network::cases() as $network) {
        expect($network->colors())->toBeString()
            ->and($network->hex())->toMatch('/^[0-9A-F]{6}$/')
            ->and($network->slug())->toBeString()
            ->and($network->icon())->toBeString();
    }
});
