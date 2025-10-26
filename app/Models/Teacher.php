<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'email',
        'password',
        'name',
        'kana',
        'grade',
        'class_name',
        'permission'
    ];

    public static function createTeacher(string $email, string $hashedPassword, string $name, string $kana, string $grade, string $className, string $permission)
    {
        return Teacher::create([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'kana' => $kana,
            'grade' => $grade,
            'class_name' => $className,
            'permission' => $permission
        ]);
    }
}
