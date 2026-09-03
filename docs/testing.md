# Testing Strategy & Pest

Resume Manager uses **Pest 4** for testing, enforcing comprehensive test coverage across features, Livewire components, caching, and imports/exports.

## Running Tests
- **Run all tests**: `php artisan test --compact` (or `vendor/bin/pest`)
- **Run specific file**: `php artisan test tests/Feature/ResumeCacheManagementTest.php`
- **Run by group**: `vendor/bin/pest --group=cache`

## Testing Best Practices in this Project
- **Feature Tests over Unit Tests**: Most tests are feature tests verifying end-to-end HTTP requests, Livewire actions, and database state.
- **Model Factories**: Always use Eloquent factories and custom states when setting up test data.
- **Dependency Isolation**: Use `RefreshDatabase` trait across tests to ensure a clean database state.
