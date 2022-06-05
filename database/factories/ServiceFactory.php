<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Organisation;
use App\Models\Service;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'organisation_id' => Organisation::factory(),
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'url' => $this->faker->url,
            'email' => $this->faker->safeEmail,
            'status' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'fees' => $this->faker->text,
            'deliverable_type' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'assured_date' => $this->faker->date(),
            'attending_type' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'attending_access' => $this->faker->regexify('[A-Za-z0-9]{100}'),
        ];
    }
}
