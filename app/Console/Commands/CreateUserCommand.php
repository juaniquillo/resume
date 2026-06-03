<?php

namespace App\Console\Commands;

use App\Models\GeneralOption;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-user {email : User email} {--name= : User name} {--password= : User password} {--slug= : User slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user. Pass email as argument, --name, --password, and --slug as options.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $handle = Str::before($email, '@');

        $name = $this->option('name') ?? $handle;
        $slug = $this->option('slug') ?? Str::slug($handle);
        $password = $this->option('password') ?? Str::random(12);

        if (User::where('email', $email)->exists()) {
            $this->error('This email is already registered.');

            return self::FAILURE;
        }

        if (GeneralOption::where('slug', $slug)->exists()) {
            $this->error('This slug is already taken.');

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->generalOptions()->create([
            'slug' => $slug,
        ]);

        $this->info("User {$user->name} ({$user->email}) created successfully with slug: {$slug}");
        if (! $this->option('password')) {
            $this->info("Generated password: $password");
        }

        return self::SUCCESS;
    }
}
