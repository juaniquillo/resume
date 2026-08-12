<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Cruds\Schema\Options\Inputs\SlugFactory;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

#[Description('Create a new user. Pass email as argument, --name, --password, and --slug as options.')]
#[Signature('user:make-user {email : User email} {--name= : User name} {--password= : User password} {--slug= : User slug}')]
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
        $slug = $this->option('slug') ?? Str::slug($handle);
        $password = $this->option('password') ?? Str::random(12);

        /**
         * Validate
         */
        $userRules = (new CreateNewUser)->validationRules();

        $crud = GeneralOptionsCrud::build()->make(GeneralOptionsCrud::slugInput());

        $slugRules = $crud->execute(
            new LaravelValidationRulesAction,
        )->toArray();

        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'password_confirmation' => $password,
            SlugFactory::NAME => $slug,
        ],
            [
                ...$userRules,
                ...$slugRules,
            ],
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
