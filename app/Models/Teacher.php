<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    // 一括代入を許可するカラム
    protected $fillable = [
        'user_id',
        'class_id',
    ];

    // User（ログイン用アカウント）との関係：1対1
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Classes（担当クラス）との関係：多対1
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
