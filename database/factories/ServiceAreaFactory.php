<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\ServiceArea;

class ServiceAreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceArea::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_id' => Service::factory(),
            'service_area' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'extent' => '{}',
        ];
    }
}
