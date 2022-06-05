<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Location;
use App\Models\PhysicalAddress;

class PhysicalAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhysicalAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'location_id' => Location::factory(),
            'address_1' => $this->faker->regexify('[A-Za-z0-9]{500}'),
            'city' => $this->faker->city,
            'state_province' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'postal_code' => $this->faker->postcode,
            'country' => $this->faker->country,
        ];
    }
}
