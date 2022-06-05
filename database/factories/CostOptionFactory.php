<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CostOption;
use App\Models\Service;

class CostOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CostOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_id' => Service::factory(),
            'valid_from' => $this->faker->date(),
            'valid_to' => $this->faker->date(),
            'option' => $this->faker->regexify('[A-Za-z0-9]{200}'),
            'amount' => $this->faker->numberBetween(-8, 8),
            'amount_description' => $this->faker->regexify('[A-Za-z0-9]{500}'),
        ];
    }
}
