<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'email',
        'password',
        'name',
        'kana',
        'grade',
        'class_id',
        'class_name',
        'permission'
    ];

    // @return \App\Models\Student 作成されたモデルインスタンス
    public static function createStudent(string $email, string $hashedPassword, string $name, string $kana, int $grade, int $classId, string $className, string $permission): Student
    {
        return self::create([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'kana' => $kana,
            'grade' => $grade,
            'class_id' => $classId,
            'class_name' => $className,
            'permission' => $permission
        ]);
    }

    /**
     * この生徒が作成した提出物（Entry）を取得するリレーション。
     */
    public function entries()
    {
        return $this->hasMany(\App\Models\Entry::class);
    }
}
