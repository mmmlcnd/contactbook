<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'name',
        'kana',
        'grade',
        'class_name',
        'permission'
    ];

    // @return \App\Models\Student 作成されたモデルインスタンス
    public static function createStudent(string $email, string $hashedPassword, string $name, string $kana, string $grade, string $className, string $permission): Student
    {
        return self::create([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'kana' => $kana,
            'grade' => $grade,
            'class_name' => $className,
            'permission' => $permission
        ]);
    }

    public function entries()
    {
        // 'foreign_key' は Entry テーブルの student_id カラム
        return $this->hasMany(Entry::class, 'student_id');
    }
}
