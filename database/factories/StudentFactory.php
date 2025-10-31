<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $grade = $this->faker->randomElement([1, 2, 3]);
        $className = $this->faker->randomElement(['A組', 'B組']);

        return [
            'name' => $this->faker->name('male' | 'female'),
            'kana' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'grade' => $grade,
            'class_name' => $className,
            'permission' => 'write',
        ];
    }
}
