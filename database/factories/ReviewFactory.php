<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Review;
use App\Models\ReviewerOrganisation;
use App\Models\Service;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_id' => Service::factory(),
            'reviewer_organisation_id' => ReviewerOrganisation::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'date' => $this->faker->date(),
            'score' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'url' => $this->faker->url,
            'widget' => $this->faker->text,
        ];
    }
}
