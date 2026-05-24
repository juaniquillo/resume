# Testing CRUDs

We use Pest for testing. CRUD tests should verify validation and persistence.

## Example

```php
test('authenticated user can create an entity', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('dashboard.entity.store'), [
            'name' => 'Test Name',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($user->myEntities()->count())->toBe(1);
});

test('entity name is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('dashboard.entity'))
        ->post(route('dashboard.entity.store'), [
            'name' => '',
        ])
        ->assertRedirect(route('dashboard.entity'))
        ->assertSessionHasErrors(['name']);
});
```

## Tips

- Use `withoutMiddleware()` if CSRF is causing 419s in tests (as discussed in previous tasks).
- Use `fresh()` when asserting against the model state after an update.
