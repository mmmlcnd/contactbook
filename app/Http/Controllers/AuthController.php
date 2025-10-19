<?php

namespace App\Http\Controllers;

use App\Models\Auth;
use App\Models\AuthModel;

class AuthController extends Controller
{
    // ログインフォーム表示
    public function adminLoginForm()
    {
        return view('auth.admin_login');
    }

    public function teacherLoginForm()
    {
        return view('auth.teacher_login');
    }

    public function studentLoginForm()
    {
        return view('auth.student_login');
    }

    // ログイン認証
    public function Login($table)
    {
        // Authモデルのインスタンス化
        $authModel = new Auth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // サーバーへのPOSTリクエストが送られたら
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            // Postされたデータを取得
            $isLoginAttemptSuccessful = $authModel->attemptLogin($table, $email, $password);
            if ($isLoginAttemptSuccessful == true) {
                // ★ リダイレクト先を /{$table}/dashboard に戻す
                return redirect()->route($table . '.dashboard');
                // exit;
            } else {
                $error =  'メールアドレスまたはパスワードが間違っています。';
            }
        }
        if ($table === 'students') {
            return view('auth.student_login', compact('error'));
        } else if ($table === 'teachers') {
            return view('auth.teacher_login', compact('error'));
        } else {
            return view('auth.admin_login', compact('error'));
        }
    }

    // 各ユーザー種別でのログイン
    public function studentLogin()
    {
        return $this->Login('students');
    }

    public function teacherLogin()
    {
        return $this->Login('teachers');
    }

    public function adminLogin()
    {
        return $this->Login('admins');
    }

    // ログアウト処理
    // public function logout(): void
    // {
    //     if (session_status() == PHP_SESSION_NONE) {
    //         session_start();
    //     }
    //     $_SESSION = array();
    //     if (ini_get("session.use_cookies")) {
    //         $params = session_get_cookie_params();
    //         setcookie(
    //             session_name(),
    //             '',
    //             time() - 42000,
    //             $params["path"],
    //             $params["domain"],
    //             $params["secure"],
    //             $params["httponly"]
    //         );
    //     }
    //     session_destroy();
    //     header("Location: /");
    //     exit;
    // }
}
