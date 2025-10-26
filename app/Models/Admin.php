<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{

    protected $fillable = [
        'name',
        'kana',
        'email',
        'password'
    ];

    /**
     * 管理者データ挿入
     * @param string $email
     * @param string $hashedPassword
     * @param string $name
     * @param string $kana
     * @return static // 作成されたAdminインスタンスを返す
     */
    public function insertAdmin($email, $hashedPassword, $name, $kana): static
    {
        return static::create([ // static::create を使用
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'kana' => $kana
        ]);
    }
}
