<?php

namespace App\Cruds\Actions\Model;

use Faker\Generator;
use IteratorAggregate;
use Juaniquillo\CrudAssistant\Action;
use Juaniquillo\CrudAssistant\Contracts\ActionInterface;
use Juaniquillo\CrudAssistant\Contracts\InputInterface;
use Juaniquillo\CrudAssistant\InputCollection;

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
    public function execute(InputCollection|InputInterface|IteratorAggregate $input)
    {
        /** @var ?LaravelFactoryRecipe $recipe */
        $recipe = $input->getRecipe($this->getIdentifier());

        if ($recipe?->isIgnored()) {
            return $this->getOutput();
        }

        $output = $this->getOutput();

        $name = $input->getName();

        $faker = $this->faker;

        if ($recipe) {
            $callback = $recipe->callback;

            if ($callback) {
                $callback($input, $output, $faker);

                return $output;
            }

            $type = $recipe->type;

            if ($type) {
                $output->$name = $faker->{ $type };

                return $output;
            }
        }

        $output->$name = $faker->sentence();

        return $output;

    }
}
