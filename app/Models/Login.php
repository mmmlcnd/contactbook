<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $fillable = [
        'email',
        'password'
    ];

    public function attemptLogin(string $table, ?string $email, ?string $password)
    {
        global $pdo;

        if (!$pdo) {
            return null;
        }

        if (empty($email) || empty($password)) {
            return null;
        }

        // メールアドレスでユーザーレコードを取得
        $stmt = $pdo->prepare("SELECT * FROM `{$table}` WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_OBJ);

        // ユーザーが見つからない場合は失敗
        if (!$user) {
            return false;
        } else {
            // ユーザーが見つかったらパスワードの検証
            if (password_verify($password, $user->password)) {
                // return $user;

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

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
