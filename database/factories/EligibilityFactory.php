<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Eligibility;
use App\Models\Service;

class EligibilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Eligibility::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_id' => Service::factory(),
            'minimum_age' => $this->faker->numberBetween(-8, 8),
            'maximum_age' => $this->faker->numberBetween(-8, 8),
        ];
    }
}
