<?php

namespace App\Cruds\Actions\Model;

use Faker\Generator;
use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputCollectionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;

class LaravelFactoryAction extends Action implements ActionInterface
{
    private Generator $faker;

    public function __construct(?Generator $faker = null)
    {
        $this->faker = $faker ?? fake();
    }

    /**
     * Execute action on input.
     */
    public function execute(InputCollectionInterface|InputInterface $input): void
    {
        /** @var ?LaravelFactoryRecipe $recipe */
        $recipe = $input->getRecipe($this->getIdentifier());

        if ($recipe?->isIgnored()) {
            return;
        }

        $output = $this->getOutput();

        $name = $input->getName();

        $faker = $this->faker;

        if ($recipe) {
            $callback = $recipe->callback;

            if ($callback) {
                $callback($input, $output, $faker);

                return;
            }

            $type = $recipe->type;

            if ($type) {
                $output->$name = $faker->{$type}();

                return;
            }
        }

        $output->$name = $faker->sentence();
    }
}
