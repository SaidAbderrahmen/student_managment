<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'score' => $this->faker->numberBetween(0, 20),
            'test_id' => \App\Models\Test::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
