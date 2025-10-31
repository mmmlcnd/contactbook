<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        $grade = $this->faker->randomElement([1, 2, 3]); // 1～3年生
        $className = $this->faker->randomElement(['A組', 'B組']); // A組 or B組

        return [
            'name' => $this->faker->name('male' | 'female'),
            'kana' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'grade' => $grade,
            'class_name' => $className,
            'permission' => 'read', // SQLデフォルト値に合わせる
        ];
    }
}
