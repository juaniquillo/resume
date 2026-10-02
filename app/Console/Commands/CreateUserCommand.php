<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

#[Description('Create a new user. Pass email as argument, --name, --password as options.')]
#[Signature('user:make-user {email : User email} {--name= : User name} {--password= : User password}')]
class CreateUserCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $handle = Str::before($email, '@');

        $name = $this->option('name') ?? $handle;
        $password = $this->option('password') ?? Str::random(12);

        /**
         * Validate
         */
        $userRules = (new CreateNewUser)->validationRules();

        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'password_confirmation' => $password,
        ],
            $userRules,
        );

        if ($validator->fails()) {
            $this->error(
                Arr::first($validator->errors()->all())
            );

            return self::FAILURE;
        }

        /**
         * Create
         */
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("User {$user->name} ({$user->email}) created successfully");
        if (! $this->option('password')) {
            $this->info("Generated password: $password");
        }

        return self::SUCCESS;
    }
}
