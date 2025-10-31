<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
    protected $model = Entry::class;

    public function definition(): array
    {
        return [
            // 'student_id' はシーダー側で関連付け
            'condition_physical' => $this->faker->numberBetween(1, 5),
            'condition_mental' => $this->faker->numberBetween(1, 5),
            'record_date' => $this->faker->dateTimeBetween('-1 week', 'yesterday')->format('Y-m-d'),
            'content' => $this->faker->realText(150),
            'is_read' => $this->faker->boolean(40), // 40%の確率で既読
        ];
    }
}
