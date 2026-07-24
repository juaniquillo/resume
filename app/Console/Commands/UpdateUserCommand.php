<?php

namespace App\Console\Commands;

use App\Actions\Fortify\UpdateUser;
use App\Cruds\Actions\Validation\LaravelValidationRulesAction;
use App\Cruds\Schema\Options\GeneralOptionsCrud;
use App\Cruds\Schema\Options\Inputs\SlugFactory;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class UpdateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update {id} {--name=} {--slug=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a user\'s name and/or slug';

    /**
     * Execute the console command.
     */
    public function handle(UpdateUser $updater): int
    {
        $user = User::findOrFail($this->argument('id'));

        $name = $this->option('name') ?? null;
        $slug = $this->option('slug') ?? null;

        if (! $name && ! $slug) {
            $this->error('No options provided to update.');

            return self::FAILURE;
        }

        try {

            if ($name) {
                $updater->update($user, ['name' => $name]);
            }

            if ($slug) {
                $crud = GeneralOptionsCrud::build()->make(
                    GeneralOptionsCrud::slugInput($user->id)
                );

                $slugRules = $crud->execute(
                    new LaravelValidationRulesAction,
                )->toArray();

                Validator::make(
                    [SlugFactory::NAME => $slug],
                    [...$slugRules],
                )->validate();

                $user->generalOptions()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['slug' => $slug]
                );
            }

            $this->info("User {$user->id} updated successfully.");

        } catch (ValidationException $e) {

            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->error("{$field}: {$message}");
                }
            }

            return self::FAILURE;
        } catch (Throwable $e) {
            $this->error('An unknown error has occurred');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}




