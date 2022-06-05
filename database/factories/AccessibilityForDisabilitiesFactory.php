<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\AccessibilityForDisabilities;
use App\Models\Location;

class AccessibilityForDisabilitiesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccessibilityForDisabilities::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'location_id' => Location::factory(),
            'accessibility' => $this->faker->regexify('[A-Za-z0-9]{200}'),
        ];
    }
}
