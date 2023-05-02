<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Test::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => 'exam',
            'date' => $this->faker->dateTime(),
            'course_id' => \App\Models\Course::factory(),
        ];
    }
}
