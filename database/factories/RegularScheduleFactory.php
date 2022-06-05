<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Location;
use App\Models\RegularSchedule;
use App\Models\Service;

class RegularScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RegularSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_id' => Service::factory(),
            'location_id' => Location::factory(),
            'weekday' => $this->faker->numberBetween(-10000, 10000),
            'opens_at' => $this->faker->time(),
            'closes_at' => $this->faker->time(),
            'valid_from' => $this->faker->date(),
            'valid_to' => $this->faker->date(),
        ];
    }
}
