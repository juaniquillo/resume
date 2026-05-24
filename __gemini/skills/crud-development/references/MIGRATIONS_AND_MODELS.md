# Migrations & Models

## Models

Models should implement basic relationships and use the `InvalidatesResumeCache` trait if their changes affect the resume frontend.

```php
namespace App\Models;

use App\Models\Concerns\InvalidatesResumeCache;
use App\Models\Concerns\Uuidable;

class MyEntity extends Model
{
    use Uuidable, InvalidatesResumeCache;

    protected $fillable = ['user_id', 'uuid', 'name', ...];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

## Migrations

Standard columns for our entities:

```php
Schema::create('my_entities', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->uuid('uuid')->unique();
    // ... custom columns
    $table->timestamps();
});
```
