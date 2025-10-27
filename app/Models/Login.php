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
            return false;
        } else {
            // ユーザーが見つかったらパスワードの検証
            if (password_verify($password, $user->password)) {

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;

                if ($table === 'students') {
                    $_SESSION['user_type'] = 'student';
                } else if ($table === 'teachers') {
                    $_SESSION['user_type'] = 'teacher';
                } else {
                    $_SESSION['user_type'] = 'admin';
                }

                return true;
            } else {
                return false;
            }
        }

        return null; // 認証失敗
    }
}
