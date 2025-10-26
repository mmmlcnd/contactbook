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
        'class_name',
        'permission'
    ];

    /**
     * 提出物（Entry）を取得するリレーション。
     */
    public function entries()
    {
        return $this->hasMany(\App\Models\Entry::class);
    }
}
