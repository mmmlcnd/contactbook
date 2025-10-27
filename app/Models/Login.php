<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Login extends Model
{
    protected $fillable = [
        'email',
        'password'
    ];

    public function attemptLogin(string $table, ?string $email, ?string $password)
    {

        if (empty($email) || empty($password)) {
            return null;
        }

        // メールアドレスでユーザーレコードを取得
        // 動的にテーブル名を変更する場合はクエリビルダー（DB）使用
        $user = DB::table($table)
            ->where('email', $email) //WHERE email = :emailに相当
            ->first(); //PDO::FETCH_OBJに相当

        // ユーザーが見つからない場合は失敗
        if (!$user) {
            return null;
        }

        // ユーザーが見つかったらパスワードの検証
        if (password_verify($password, $user->password)) {
            return $user; //認証成功（ユーザーオブジェクトを返す）
        } else {
            return false; //パスワード不一致
        }
    }
}
