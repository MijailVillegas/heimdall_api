<?php

namespace Database\Factories;

use App\Models\Indicator;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class IndicatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Indicator::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'value' => $this->faker->randomNumber(2),
            'project_id' => \App\Models\Project::factory(),
        ];
    }
}
