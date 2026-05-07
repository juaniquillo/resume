<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user via interactive prompts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = text(
            label: 'What is the user\'s name?',
            placeholder: 'E.g. John Doe',
            required: true
        );

        $email = text(
            label: 'What is the user\'s email?',
            placeholder: 'E.g. john@example.com',
            required: true,
            validate: fn (string $value) => match (true) {
                ! filter_var($value, FILTER_VALIDATE_EMAIL) => 'The email address is invalid.',
                User::where('email', $value)->exists() => 'This email is already registered.',
                default => null,
            }
        );

        $slug = text(
            label: 'What is the user\'s slug?',
            placeholder: 'E.g. john-doe',
            default: Str::slug($name),
            required: true,
            validate: fn (string $value) => match (true) {
                User::where('slug', $value)->exists() => 'This slug is already taken.',
                default => null,
            }
        );

        $password = password(
            label: 'What is the user\'s password? (Leave blank to generate random)',
            placeholder: 'Optional password'
        );

        if (empty($password)) {
            $password = Str::random(12);
            info("Generated password: $password");
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'slug' => $slug,
            'password' => Hash::make($password),
        ]);

        info("User {$user->name} ({$user->email}) created successfully with slug: {$user->slug}");

        return self::SUCCESS;
    }
}
