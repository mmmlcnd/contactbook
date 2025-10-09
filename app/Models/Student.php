<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // 複数代入を許可するカラムを指定
    protected $fillable = [
        'user_id',
        'class_id',
        'student_number',
    ];

    // ユーザーとのリレーション（1対1）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // クラスとのリレーション（多対1）
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    // 連絡帳（entries）とのリレーション（1対多）
    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}
