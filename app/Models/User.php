<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'slug'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $email
 * @property-read string $slug
 * @property-read Carbon|null $email_verified_at
 * @property-read string $password
 * @property-read string|null $remember_token
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read string|null $two_factor_secret
 * @property-read string|null $two_factor_recovery_codes
 * @property-read Carbon|null $two_factor_confirmed_at
 * @property-read Collection<int, Award> $awards
 * @property-read Collection<int, Certificate> $certificates
 * @property-read Collection<int, Publication> $publications
 * @property-read Collection<int, Skill> $skills
 * @property-read Collection<int, Project> $projects
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function basics(): HasOne
    {
        return $this->hasOne(Basic::class);
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    public function volunteers(): HasMany
    {
        return $this->hasMany(Volunteer::class);
    }

    public function education(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function awards(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function languages(): HasMany
    {
        return $this->hasMany(Language::class);
    }

    public function interests(): HasMany
    {
        return $this->hasMany(Interest::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(Reference::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
