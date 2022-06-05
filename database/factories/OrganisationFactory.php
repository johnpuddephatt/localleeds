<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Organisation;

class OrganisationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organisation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'email' => $this->faker->safeEmail,
            'url' => $this->faker->url,
            'logo' => $this->faker->regexify('[A-Za-z0-9]{100}'),
        ];
    }
}
