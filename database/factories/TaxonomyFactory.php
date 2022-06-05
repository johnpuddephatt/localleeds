<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Taxonomy;

class TaxonomyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Taxonomy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parent_id' => Taxonomy::factory(),
            'name' => $this->faker->name,
            'vocabulary' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'type' => $this->faker->regexify('[A-Za-z0-9]{50}'),
        ];
    }
}
